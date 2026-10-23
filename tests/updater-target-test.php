<?php declare(strict_types=1);

namespace Composer\IO { class NullIO {} }
namespace Shopware\Core\Framework { class Context {} }
namespace Shopware\Core\Framework\Plugin {
    class PluginService {
        public int $refreshes = 0;
        public function refreshPlugins(mixed $context, mixed $io): array { ++$this->refreshes; return []; }
    }
}
namespace Symfony\Contracts\HttpClient { interface HttpClientInterface { public function request(string $method, string $url, array $options = []): mixed; } }
namespace {
    // Isolierte Plugin-Installation: Der echte Updater muss genau dieses Verzeichnis tauschen.
    $project = sys_get_temp_dir() . '/soldout-target-' . bin2hex(random_bytes(8));
    $plugin = $project . '/custom/plugins/MgdSoldOut';
    mkdir($plugin . '/src', 0755, true);
    $manifest = json_decode(file_get_contents(__DIR__ . '/../composer.json'), true, 512, JSON_THROW_ON_ERROR);
    $manifest['version'] = '1.0.2';
    file_put_contents($plugin . '/composer.json', json_encode($manifest));
    file_put_contents($plugin . '/src/MgdSoldOut.php', '<?php namespace Mgd\SoldOut; class MgdSoldOut {}');
    require $plugin . '/src/MgdSoldOut.php';
    require __DIR__ . '/../src/Service/AtomicInstaller.php';
    require __DIR__ . '/../src/Service/GitHubReleaseUpdater.php';
    $archive = $project . '/release.zip';
    $zip = new \ZipArchive(); $zip->open($archive, \ZipArchive::CREATE);
    $manifest['version'] = '1.0.3';
    $zip->addFromString('MgdSoldOut/composer.json', json_encode($manifest));
    $zip->addFromString('MgdSoldOut/src/MgdSoldOut.php', '<?php namespace Mgd\SoldOut; class MgdSoldOut {}');
    $zip->addFromString('MgdSoldOut/src/Resources/public/storefront/plugin.css', '.ready{display:block}');
    $zip->close();
    $client = new class(file_get_contents($archive)) implements \Symfony\Contracts\HttpClient\HttpClientInterface {
        public function __construct(private string $zip) {}
        public function request(string $method, string $url, array $options = []): mixed {
            $body = $this->zip;
            return new class($body) {
                public function __construct(private string $body) {}
                public function toArray(): array { return ['tag_name' => 'v1.0.3', 'draft' => false, 'prerelease' => false, 'assets' => [[
                    'name' => 'MgdSoldOut.zip', 'size' => strlen($this->body), 'digest' => 'sha256:' . hash('sha256', $this->body),
                    'browser_download_url' => 'https://github.com/MichaelGahnDESIGN/MGD_Ausverkauft_Shopware-Plugin/releases/download/v1.0.3/MgdSoldOut.zip'
                ]]]; }
                public function getContent(): string { return $this->body; }
            };
        }
    };
    try {
        $plugins = new \Shopware\Core\Framework\Plugin\PluginService();
        $updater = new \Mgd\SoldOut\Service\GitHubReleaseUpdater($client, $plugins);
        $result = $updater->checkAndPrepare(new \Shopware\Core\Framework\Context());
        if (!$result['downloaded'] || $plugins->refreshes !== 1 || !is_file($plugin . '/src/Resources/public/storefront/plugin.css') || is_dir($plugin . '/MgdSoldOut')) {
            throw new \RuntimeException('Update erreicht nicht den echten Plugin-Pfad mit fertigen Assets.');
        }
        if (count(glob($project . '/var/mgd-soldout-updates/backup-*')) !== 1) { throw new \RuntimeException('Sicherung fehlt.'); }
        echo "OK: echter Updater ersetzt den richtigen Plugin-Pfad samt fertigen Assets.\n";
    } finally {
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($project, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) { $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname()); }
        rmdir($project);
    }
}
