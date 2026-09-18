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

        $latestVersion = ltrim((string) ($release['tag_name'] ?? ''), 'v');
        if ($latestVersion === '' || version_compare($latestVersion, $currentVersion, '<=')) {
            return [
                'updateAvailable' => false,
                'downloaded' => false,
                'currentVersion' => $currentVersion,
                'latestVersion' => $latestVersion !== '' ? $latestVersion : null,
            ];
        }

        $assetUrl = null;
        foreach (($release['assets'] ?? []) as $asset) {
            if (($asset['name'] ?? null) === self::ASSET_NAME) {
                $assetUrl = $asset['browser_download_url'] ?? null;
                break;
            }
        }

        if (!\is_string($assetUrl) || $assetUrl === '') {
            throw new \RuntimeException(sprintf('GitHub Release %s enthält %s nicht.', $latestVersion, self::ASSET_NAME));
        }

        $this->installReleaseArchive($assetUrl, $latestVersion, dirname($pluginDir));
        $this->pluginService->refreshPlugins($context, new NullIO());

        return [
            'updateAvailable' => true,
            'downloaded' => true,
            'currentVersion' => $currentVersion,
            'latestVersion' => $latestVersion,
        ];
    }

    private function installReleaseArchive(string $assetUrl, string $expectedVersion, string $pluginRoot): void
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

            file_put_contents($zipPath, $response->getContent());

            $zip = new ZipArchive();
            if ($zip->open($zipPath) !== true) {
                throw new \RuntimeException('Das GitHub-Release-ZIP konnte nicht geöffnet werden.');
            }

            for ($i = 0; $i < $zip->numFiles; ++$i) {
                $name = (string) $zip->getNameIndex($i);
                if (str_contains($name, '../') || str_starts_with($name, '/') || str_contains($name, '\\')) {
                    $zip->close();
                    throw new \RuntimeException('Unsicherer Pfad im Release-ZIP erkannt.');
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
            $this->mirror($source, $target);
        } finally {
            $this->removeDirectory($tmp);
        }
    }

    private function mirror(string $source, string $target): void
    {
        if (!is_dir($target) && !mkdir($target, 0755, true) && !is_dir($target)) {
            throw new \RuntimeException('Plugin-Verzeichnis ist nicht beschreibbar.');
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $destination = $target . '/' . $iterator->getSubPathName();
            if ($item->isDir()) {
                if (!is_dir($destination)) {
                    mkdir($destination, 0755, true);
                }
                continue;
            }
            if (!copy($item->getPathname(), $destination)) {
                throw new \RuntimeException('Update-Datei konnte nicht geschrieben werden: ' . $destination);
            }
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
