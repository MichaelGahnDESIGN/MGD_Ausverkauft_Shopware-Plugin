<?php declare(strict_types=1);

/** Verhindert einen versehentlich aktiven Dateiaustausch nach Installation. */
$config = simplexml_load_file(__DIR__ . '/../src/Resources/config/config.xml');
if ($config === false) {
    throw new RuntimeException('Shopware-Konfiguration ist kein gültiges XML.');
}

$fields = $config->xpath("//input-field[name='automaticUpdates']");
if (!is_array($fields) || count($fields) !== 1 || (string) $fields[0]->defaultValue !== 'false') {
    throw new RuntimeException('Automatische Update-Vorbereitung muss standardmäßig aus sein.');
}

$handler = file_get_contents(__DIR__ . '/../src/ScheduledTask/GitHubUpdateCheckTaskHandler.php');
if (!is_string($handler)
    || !str_contains($handler, "getBool('MgdSoldOut.config.automaticUpdates')")
    || !str_contains($handler, '$this->updater->checkAndPrepare(')) {
    throw new RuntimeException('Scheduled Task muss die globale Opt-in-Konfiguration prüfen.');
}

echo "Update-Vorbereitung ist global und standardmäßig deaktiviert.\n";
