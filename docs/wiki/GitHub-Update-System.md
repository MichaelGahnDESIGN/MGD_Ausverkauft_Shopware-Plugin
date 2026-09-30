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

Der Updater prüft die feste Release-Asset-URL, Größe und SHA-256, die erwartete ZIP-Struktur, verdächtige Archivpfade/Symlinks, die Plugin-Klasse `Mgd\SoldOut\MgdSoldOut` sowie die Übereinstimmung zwischen Release-Tag und Plugin-Version. Vor dem Austausch wird auf demselben Dateisystem eine neue Version vorbereitet; das Original bleibt als private Sicherung bestehen. Ein fehlgeschlagener Plugin-Refresh löst die Wiederherstellung aus. Das ist keine Shopware-Datenbankmigration.

## Intervall

Die Prüfung ist standardmäßig stündlich vorgesehen. Shopwares Scheduled Tasks und Message Queue müssen dafür regulär verarbeitet werden; die Umstellung bestehender Task-Einträge ist in einer echten Installation zu prüfen.

## Voraussetzungen

Der Server muss `api.github.com` und GitHub Release Assets per HTTPS erreichen können, `ext-zip` bereitstellen und das Plugin-Verzeichnis beschreiben dürfen.

Fehlgeschlagene Prüfungen werden protokolliert und sollen den normalen Shopbetrieb nicht blockieren.

## Vor dem ersten produktiven Update

`1.0.1` härtet den bisher direkten Dateiaustausch ab. Zuerst auf einer Shopware-Staging-Instanz mit installiertem `1.0.0` prüfen: Release-Erkennung, vorbereitete Dateiversion, native Update-Schaltfläche, Storefront, Konfiguration und Rückfall. Vor Produktion Dateien und Datenbank sichern. CI und der isolierte Rückfalltest ersetzen diesen Test nicht. Bei inkompatiblen Composer-Abhängigkeiten stoppt die automatische Vorbereitung absichtlich.
