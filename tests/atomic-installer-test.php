<?php declare(strict_types=1);

/** Netzfreier Test: erfolgreicher Austausch und Rückfall nach Refresh-Fehler. */
require __DIR__ . '/../src/Service/AtomicInstaller.php';

use Mgd\SoldOut\Service\AtomicInstaller;

function assertSameContent(string $expected, string $path): void
{
    if (!is_file($path) || file_get_contents($path) !== $expected) {
        throw new RuntimeException('Unerwarteter Dateiinhalt: ' . $path);
    }
}

function removeFixture(string $directory): void
{
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($iterator as $item) {
        $item->isDir() && !$item->isLink() ? rmdir($item->getPathname()) : unlink($item->getPathname());
    }
    rmdir($directory);
}

foreach (array(false, true) as $failRefresh) {
    $root = sys_get_temp_dir() . '/mgd-soldout-test-' . bin2hex(random_bytes(8));
    $target = $root . '/custom/plugins/MgdSoldOut';
    $source = $root . '/source';
    mkdir($target, 0755, true);
    mkdir($source, 0755, true);
    file_put_contents($target . '/version.txt', 'alt');
    file_put_contents($source . '/version.txt', 'neu');
    try {
        $refreshes = 0;
        try {
            (new AtomicInstaller())->prepare($source, $target, static function () use ($failRefresh, &$refreshes): void {
                ++$refreshes;
                if ($failRefresh && $refreshes === 1) {
                    throw new RuntimeException('Simulierter Shopware-Refresh-Fehler');
                }
            });
            if ($failRefresh) {
                throw new RuntimeException('Fehlender erwarteter Refresh-Fehler');
            }
        } catch (RuntimeException $error) {
            if (!$failRefresh || $error->getMessage() !== 'Simulierter Shopware-Refresh-Fehler') {
                throw $error;
            }
        }
        assertSameContent($failRefresh ? 'alt' : 'neu', $target . '/version.txt');
        if ($refreshes !== ($failRefresh ? 2 : 1)) {
            throw new RuntimeException('Unerwartete Anzahl Plugin-Refreshs.');
        }
        if (!$failRefresh && count(glob($root . '/var/mgd-soldout-updates/backup-*')) !== 1) {
            throw new RuntimeException('Original wurde nicht gesichert.');
        }
    } finally {
        removeFixture($root);
    }
}

echo "Atomarer Austausch und Rückfall: 2 Prüfungen erfolgreich.\n";
