<?php

declare(strict_types=1);

// Brückenversion: Baukasten drin, alter GitHub-Update-Check abgemeldet, Abmelden beim Deinstallieren
$root = dirname(__DIR__);
$fail = 0;
$check = static function (bool $ok, string $name) use (&$fail): void {
    echo ($ok ? 'OK   ' : 'FAIL ') . $name . "\n";
    $fail += $ok ? 0 : 1;
};
$read = static fn (string $f): string => (string) file_get_contents($root . '/' . $f);
$services = $read('src/Resources/config/services.xml');
$routes = $read('src/Resources/config/routes.xml');
$kitServices = $read('src/Kit/Platform/Shopware/Resources/config/services.xml');
$plugin = $read('src/MgdSoldOut.php');
$adminJs = $read('src/Resources/public/administration/js/mgd-sold-out.js');

$check(is_file($root . '/src/Kit/Platform/Shopware/ShopwareKit.php') && is_file($root . '/src/Kit/Platform/Shopware/administration/mgd-kit.js'), 'Baukasten liegt unter src/Kit');
$check(str_contains($services, '<import resource="../../Kit/Platform/Shopware/Resources/config/services.xml"/>'), 'Kit-Dienste importiert');
$check(str_contains($routes, '<import resource="../../Kit/Platform/Shopware/Resources/config/routes.xml"/>'), 'Kit-Routen importiert');
$check(!str_contains($services, 'GitHubReleaseUpdater') && !str_contains($services, 'GitHubUpdateCheckTask'), 'alter GitHub-Update-Check nicht mehr angemeldet');
$check(!is_dir($root . '/src/Service') && !is_dir($root . '/src/ScheduledTask') && !str_contains($read('src/Resources/config/config.xml'), 'automaticUpdates'), 'alter Updater und Schalter raus');
$check(str_contains($services, 'StockThresholdCartValidator'), 'Warenkorb-Prüfung bleibt angemeldet');
$check(str_contains($kitServices, '<argument>mgd-ausverkauft-shopware</argument>') && str_contains($kitServices, '<argument>MgdSoldOut</argument>') && str_contains($kitServices, '<argument>plugin-ausverkauft-shopware</argument>'), 'Produkt, technischer Name, Katalog-Kennung');
$check((bool) preg_match('~<argument>false</argument>\s*</service>~', $kitServices), 'Lizenz aus');
$check(str_contains($plugin, "ShopwareKit::forUninstall(\$uninstallContext, \$this->container->get(SystemConfigService::class), 'mgd-ausverkauft-shopware')?->uninstall(\$uninstallContext)"), 'Abmelden beim Deinstallieren');
$check($adminJs === $read('src/Kit/Platform/Shopware/administration/mgd-kit.js'), 'Admin-JS = Kit-Seite');
$placeholders = 0;
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/src/Kit', FilesystemIterator::SKIP_DOTS)) as $f) {
    $placeholders += str_contains((string) file_get_contents((string) $f), '{{KIT_') ? 1 : 0;
}
$check($placeholders === 0, 'keine Platzhalter im Baukasten');
exit($fail === 0 ? 0 : 1);
