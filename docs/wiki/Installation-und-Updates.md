# Installation und Updates

## Voraussetzungen

Shopware 6.7.x, PHP ab 8.2 und für den integrierten Updater `ext-zip`. Der Server benötigt Schreibrechte im Plugin-Verzeichnis und für automatische Updates ausgehenden HTTPS-Zugriff auf GitHub.

Vor produktiven Änderungen sollten Datenbank und `custom/plugins` gesichert werden.

## Installation in der Administration

1. `MgdSoldOut.zip` aus dem GitHub Release laden.
2. **Erweiterungen → Meine Erweiterungen → Erweiterung hochladen** öffnen.
3. ZIP hochladen, installieren und aktivieren.
4. Plugin-Konfiguration prüfen.
5. Cache leeren und Theme kompilieren.
6. Einen verfügbaren und einen ausverkauften Artikel testen.

Releases: https://github.com/MichaelGahnDESIGN/MGD_Ausverkauft_Shopware-Plugin/releases

## CLI

```bash
bin/console plugin:refresh
bin/console plugin:install --activate MgdSoldOut
bin/console cache:clear
bin/console theme:compile
```

## Aktivierung

Bei Aktivierung sichert das Plugin die bisherige Shopware-Einstellung zum Ausblenden leerer Abverkaufsprodukte und deaktiviert sie global sowie für vorhandene Verkaufskanäle. Dadurch werden die Produkte an die Storefront ausgeliefert und können vom Plugin gekennzeichnet werden.

## Deaktivierung und Deinstallation

Die zuvor gesicherten Werte werden wiederhergestellt. Das Plugin soll nach seiner Deaktivierung keine dauerhaft veränderte Ausblendlogik hinterlassen.

## Updates

Neue Versionen werden über GitHub Releases erkannt. Der vollständige Ablauf steht unter [GitHub Update System](GitHub-Update-System).

Nach Updates sollten Detailseite, Kategorie, Suche, Slider, Varianten und Warenkorb getestet werden.
