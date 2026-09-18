# GitHub Update System

MGD Ausverkauft erkennt neue Versionen über die Releases des offiziellen Repositories:

https://github.com/MichaelGahnDESIGN/MGD_Ausverkauft_Shopware-Plugin

## Ablauf

1. Ein Scheduled Task fragt GitHubs öffentliche `releases/latest` API ab.
2. Die Release-Version wird mit der Version in `composer.json` verglichen.
3. Bei einer höheren Version wird das Asset `MgdSoldOut.zip` geladen.
4. Das Archiv wird geprüft und entpackt.
5. Die Plugin-Dateien werden vorbereitet.
6. Shopwares Plugin-Liste wird über den nativen PluginService aktualisiert.
7. Shopware kann anschließend seinen normalen Plugin-Update-Lifecycle ausführen.

## Sicherheitsprüfungen

Der Updater prüft die erwartete ZIP-Struktur, verdächtige Archivpfade, die Plugin-Klasse `Mgd\SoldOut\MgdSoldOut` sowie die Übereinstimmung zwischen Release-Tag und Plugin-Version.

## Intervall

Die Prüfung ist standardmäßig alle sechs Stunden vorgesehen. Shopwares Scheduled Tasks und Message Queue müssen dafür regulär verarbeitet werden.

## Voraussetzungen

Der Server muss `api.github.com` und GitHub Release Assets per HTTPS erreichen können, `ext-zip` bereitstellen und das Plugin-Verzeichnis beschreiben dürfen.

Fehlgeschlagene Prüfungen werden protokolliert und sollen den normalen Shopbetrieb nicht blockieren.
