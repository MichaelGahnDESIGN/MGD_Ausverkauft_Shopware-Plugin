<?php declare(strict_types=1);

namespace Mgd\SoldOut\Service;

/**
 * Tauscht nur fertig entpackte Plugin-Versionen aus. Das Original bleibt nach
 * Erfolg als private Sicherung erhalten; Shopwares Update-Lifecycle wird nicht
 * hier ausgeführt, sondern erst über den Pluginmanager angestoßen.
 */
final class AtomicInstaller
{
    public function prepare(string $source, string $target, callable $refresh): void
    {
        $installed = realpath($target);
        if ($installed === false || is_link($target) || basename($installed) !== 'MgdSoldOut'
            || basename(dirname($installed)) !== 'plugins' || basename(dirname($installed, 2)) !== 'custom') {
            throw new \RuntimeException('Update nur im lokalen custom/plugins/MgdSoldOut zulässig.');
        }
        $project = dirname($installed, 3);
        $private = $project . '/var/mgd-soldout-updates';
        if (is_link($project . '/var') || is_link($private)) {
            throw new \RuntimeException('Update-Verzeichnis darf kein Symlink sein.');
        }
        if (!is_dir($private) && !mkdir($private, 0700, true)) {
            throw new \RuntimeException('Privates Update-Verzeichnis kann nicht erstellt werden.');
        }
        $privateStat = stat($private);
        $pluginStat = stat(dirname($installed));
        if (!is_writable($private) || $privateStat === false || $pluginStat === false
            || $privateStat['dev'] !== $pluginStat['dev']) {
            throw new \RuntimeException('Staging muss auf demselben beschreibbaren Dateisystem liegen.');
        }
        chmod($private, 0700);

        $lockPath = $private . '/update.lock';
        if (is_link($lockPath)) {
            throw new \RuntimeException('Ungültige Update-Sperre.');
        }
        $lock = fopen($lockPath, 'c');
        if ($lock === false) {
            throw new \RuntimeException('Update-Sperre konnte nicht erstellt werden.');
        }
        chmod($lockPath, 0600);
        if (!flock($lock, LOCK_EX | LOCK_NB)) {
            fclose($lock);
            throw new \RuntimeException('Ein anderes Plugin-Update läuft bereits.');
        }

        $stage = $private . '/stage-' . bin2hex(random_bytes(12));
        $backup = $private . '/backup-' . gmdate('Ymd-His') . '-' . bin2hex(random_bytes(6));
        $backedUp = false;
        $swapped = false;
        try {
            $this->copyTree($source, $stage);
            if (!rename($installed, $backup)) {
                throw new \RuntimeException('Installierte Version konnte nicht gesichert werden.');
            }
            $backedUp = true;
            if (!rename($stage, $installed)) {
                throw new \RuntimeException('Vorbereitete Version konnte nicht eingesetzt werden.');
            }
            $swapped = true;
            $refresh();
            if (function_exists('opcache_reset')) {
                opcache_reset();
            }
        } catch (\Throwable $error) {
            if ($swapped && !rename($installed, $stage)) {
                throw new \RuntimeException('Rückfall blockiert; Sicherung liegt unter ' . $backup, 0, $error);
            }
            if ($backedUp) {
                if (!rename($backup, $installed)) {
                    throw new \RuntimeException('Rückfall blockiert; Original liegt unter ' . $backup, 0, $error);
                }
                try { $refresh(); } catch (\Throwable) { /* Originaldateien sind zurück; primären Fehler erhalten. */ }
            }
            throw $error;
        } finally {
            if (is_dir($stage)) {
                $this->removeTree($stage);
            }
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }

    private function copyTree(string $source, string $destination): void
    {
        if (!mkdir($destination, 0755)) {
            throw new \RuntimeException('Staging-Verzeichnis konnte nicht erstellt werden.');
        }
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $item) {
            if ($item->isLink()) {
                throw new \RuntimeException('Symlink im entpackten Paket nicht erlaubt.');
            }
            $path = $destination . '/' . $iterator->getSubPathName();
            if ($item->isDir()) {
                if (!mkdir($path, 0755)) {
                    throw new \RuntimeException('Staging-Unterordner konnte nicht erstellt werden.');
                }
            } elseif (!copy($item->getPathname(), $path)) {
                throw new \RuntimeException('Staging-Datei konnte nicht geschrieben werden.');
            }
        }
    }

    /** Entfernt ausschließlich das selbst erzeugte, zufällige Staging. */
    private function removeTree(string $directory): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $item) {
            $item->isDir() && !$item->isLink() ? rmdir($item->getPathname()) : unlink($item->getPathname());
        }
        rmdir($directory);
    }
}
