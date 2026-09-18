# Sicherheit, Datenschutz und Betrieb

## Datenschutz

Das Plugin benötigt keine Kundenkonten, Trackingdienste oder externe Analysedienste. Produkt- und Kundendaten werden nicht an Michael Gahn DESIGN übertragen.

Die einzige externe Kommunikation des Plugins ist die Updateprüfung gegen das öffentliche GitHub-Repository und der Download eines Release-Assets, wenn eine höhere Version vorhanden ist.

## Release-Sicherheit

Der Updater akzeptiert nicht beliebige Downloadquellen. Repository und erwarteter Assetname sind im Plugin festgelegt. Das Archiv wird vor der Übernahme auf Struktur und Pluginidentität geprüft.

## Schreibrechte

Für automatische Updates benötigt PHP Schreibzugriff auf das Plugin-Verzeichnis. Diese Rechte sollten nicht großzügiger vergeben werden als für den Shopware-Betrieb erforderlich.

## Backups

Vor produktiven Plugin- und Shopware-Updates sollten Datenbank und Plugin-Dateien gesichert werden. Updates zuerst in Staging zu testen bleibt auch mit automatischer Release-Erkennung sinnvoll.

## Scheduled Tasks

Die Updateprüfung hängt von Shopwares Scheduled-Task-Infrastruktur ab. Betreiber müssen sicherstellen, dass Message Queue und Scheduled Tasks entsprechend ihrer Shopware-Installation verarbeitet werden.

## Verfügbarkeit

Ein GitHub-Ausfall darf nicht den Shopbetrieb verhindern. Fehler der Updateprüfung werden abgefangen und protokolliert.

## Verantwortung

Das Plugin ergänzt die Storefront- und Bestandslogik. Betreiber bleiben für Serverhärtung, TLS, Backups, Shopware-Updates, Rollen und Berechtigungen sowie die Kompatibilität weiterer Erweiterungen verantwortlich.
