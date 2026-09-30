<?php declare(strict_types=1);

namespace Mgd\SoldOut\Service;

use Composer\IO\NullIO;
use Mgd\SoldOut\MgdSoldOut;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Plugin\PluginService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use ZipArchive;

final class GitHubReleaseUpdater
{
    private const RELEASE_API = 'https://api.github.com/repos/MichaelGahnDESIGN/MGD_Ausverkauft_Shopware-Plugin/releases/latest';
    private const ASSET_NAME = 'MgdSoldOut.zip';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly PluginService $pluginService,
    ) {
    }

    /**
     * Downloads a newer GitHub release into the plugin directory and refreshes
     * Shopware's plugin list. Shopware then exposes its native update action.
     *
     * @return array{updateAvailable: bool, downloaded: bool, currentVersion: string, latestVersion: string|null}
     */
    public function checkAndPrepare(Context $context): array
    {
        $pluginDir = dirname((new \ReflectionClass(MgdSoldOut::class))->getFileName());
        $composerFile = $pluginDir . '/../composer.json';
        $composer = json_decode((string) file_get_contents($composerFile), true, 512, JSON_THROW_ON_ERROR);
        $currentVersion = (string) ($composer['version'] ?? '0.0.0');

        $release = $this->httpClient->request('GET', self::RELEASE_API, [
            'headers' => [
                'Accept' => 'application/vnd.github+json',
                'User-Agent' => 'MGD-Ausverkauft-Shopware-Updater',
                'X-GitHub-Api-Version' => '2022-11-28',
            ],
            'timeout' => 15,
        ])->toArray();

        $tag = (string) ($release['tag_name'] ?? '');
        if (!preg_match('/^v([0-9]+\.[0-9]+\.[0-9]+)$/D', $tag, $matches)
            || ($release['draft'] ?? false) || ($release['prerelease'] ?? false)) {
            throw new \RuntimeException('GitHub liefert kein stabiles Release mit gültigem Versionstag.');
        }
        $latestVersion = $matches[1];
        if (version_compare($latestVersion, $currentVersion, '<=')) {
            return [
                'updateAvailable' => false,
                'downloaded' => false,
                'currentVersion' => $currentVersion,
                'latestVersion' => $latestVersion !== '' ? $latestVersion : null,
            ];
        }

        $asset = null;
        foreach (($release['assets'] ?? []) as $asset) {
            if (($asset['name'] ?? null) === self::ASSET_NAME) {
                break;
            }
        }

        if (!\is_array($asset) || ($asset['name'] ?? null) !== self::ASSET_NAME) {
            throw new \RuntimeException(sprintf('GitHub Release %s enthält %s nicht.', $latestVersion, self::ASSET_NAME));
        }
        $assetUrl = $asset['browser_download_url'] ?? null;
        $digest = $asset['digest'] ?? null;
        $size = $asset['size'] ?? null;
        if (!\is_string($assetUrl) || !str_starts_with($assetUrl, 'https://github.com/MichaelGahnDESIGN/MGD_Ausverkauft_Shopware-Plugin/releases/download/')
            || !\is_string($digest) || !preg_match('/^sha256:[a-f0-9]{64}$/iD', $digest)
            || !\is_int($size) || $size < 100 || $size > 40_000_000) {
            throw new \RuntimeException('Release-Asset besitzt keine sichere URL, Größe oder SHA-256-Prüfsumme.');
        }

        $this->installReleaseArchive($assetUrl, $digest, $size, $latestVersion, dirname($pluginDir), $context);

        return [
            'updateAvailable' => true,
            'downloaded' => true,
            'currentVersion' => $currentVersion,
            'latestVersion' => $latestVersion,
        ];
    }

    private function installReleaseArchive(string $assetUrl, string $digest, int $size, string $expectedVersion, string $pluginRoot, Context $context): void
    {
        $tmp = sys_get_temp_dir() . '/mgd-soldout-' . bin2hex(random_bytes(8));
        if (!mkdir($tmp, 0700, true) && !is_dir($tmp)) {
            throw new \RuntimeException('Temporäres Update-Verzeichnis konnte nicht erstellt werden.');
        }

        $zipPath = $tmp . '/release.zip';
        try {
            $response = $this->httpClient->request('GET', $assetUrl, [
                'headers' => ['User-Agent' => 'MGD-Ausverkauft-Shopware-Updater'],
                'timeout' => 60,
            ]);

            $content = $response->getContent();
            if (strlen($content) !== $size || strlen($content) > 40_000_000
                || !hash_equals(substr($digest, 7), hash('sha256', $content))) {
                throw new \RuntimeException('Release-Download ist unvollständig oder SHA-256 stimmt nicht.');
            }
            if (file_put_contents($zipPath, $content, LOCK_EX) !== strlen($content)) {
                throw new \RuntimeException('Release-ZIP konnte nicht vollständig gespeichert werden.');
            }

            $zip = new ZipArchive();
            if ($zip->open($zipPath) !== true) {
                throw new \RuntimeException('Das GitHub-Release-ZIP konnte nicht geöffnet werden.');
            }

            if ($zip->numFiles < 2 || $zip->numFiles > 500) {
                $zip->close();
                throw new \RuntimeException('Release-ZIP enthält zu viele oder zu wenige Einträge.');
            }
            $uncompressed = 0;
            for ($i = 0; $i < $zip->numFiles; ++$i) {
                $name = (string) $zip->getNameIndex($i);
                $stat = $zip->statIndex($i);
                $parts = explode('/', rtrim($name, '/'));
                $unsafe = !str_starts_with($name, 'MgdSoldOut/') || str_contains($name, '\\')
                    || str_contains($name, "\0") || str_contains($name, '//')
                    || \in_array('..', $parts, true) || \in_array('.', $parts, true);
                if ($unsafe || $stat === false || $stat['size'] > 10_000_000) {
                    $zip->close();
                    throw new \RuntimeException('Unsicherer Pfad oder übergroße Datei im Release-ZIP erkannt.');
                }
                $uncompressed += $stat['size'];
                if ($uncompressed > 60_000_000 || !$zip->getExternalAttributesIndex($i, $opsys, $attributes)
                    || ($opsys === ZipArchive::OPSYS_UNIX && !\in_array(($attributes >> 16) & 0o170000, [0, 0o100000, 0o040000], true))) {
                    $zip->close();
                    throw new \RuntimeException('ZIP enthält zu große Inhalte oder eine Spezialdatei.');
                }
            }

            $extractDir = $tmp . '/extract';
            mkdir($extractDir, 0700, true);
            if (!$zip->extractTo($extractDir)) {
                $zip->close();
                throw new \RuntimeException('Das GitHub-Release-ZIP konnte nicht entpackt werden.');
            }
            $zip->close();

            $source = $extractDir . '/MgdSoldOut';
            $newComposer = $source . '/composer.json';
            if (!is_file($newComposer)) {
                throw new \RuntimeException('Das Release besitzt nicht die erwartete Plugin-Struktur.');
            }

            $metadata = json_decode((string) file_get_contents($newComposer), true, 512, JSON_THROW_ON_ERROR);
            if (($metadata['extra']['shopware-plugin-class'] ?? null) !== 'Mgd\\SoldOut\\MgdSoldOut') {
                throw new \RuntimeException('Das Release gehört nicht zu MGD Ausverkauft.');
            }
            if (($metadata['version'] ?? null) !== $expectedVersion) {
                throw new \RuntimeException('Release-Tag und Plugin-Version stimmen nicht überein.');
            }

            $target = $pluginRoot . '/MgdSoldOut';
            $installed = json_decode((string) file_get_contents($target . '/composer.json'), true, 512, JSON_THROW_ON_ERROR);
            if (!\is_array($installed) || ($metadata['require'] ?? null) !== ($installed['require'] ?? null)) {
                throw new \RuntimeException('Geänderte Shopware-Abhängigkeiten benötigen einen manuell geprüften Updateweg.');
            }
            (new AtomicInstaller())->prepare($source, $target, function () use ($context): void {
                $this->pluginService->refreshPlugins($context, new NullIO());
            });
        } finally {
            $this->removeDirectory($tmp);
        }
    }

    private function removeDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $item) {
            $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
        }
        rmdir($directory);
    }
}
