# Entwicklerarchitektur

Namespace: `Mgd\SoldOut`

Plugin-Klasse: `Mgd\SoldOut\MgdSoldOut`

Technischer Name: `MgdSoldOut`

## Komponenten

### Plugin Lifecycle

`MgdSoldOut.php` sichert und verändert die Shopware-Einstellung für das Ausblenden leerer Abverkaufsprodukte. Bei Deaktivierung oder Deinstallation werden die vorherigen Werte wiederhergestellt.

### Storefront

Twig-Erweiterungen behandeln die Standard-Produktbox und den `buy_widget_buy_form` Block. SCSS stellt Badge, Rahmen und Detailhinweis dar.

### Cart Validator

`StockThresholdCartValidator` ergänzt Shopwares Bestandsprüfung um die konfigurierte Reserve. Er arbeitet auf Produkt-Line-Items und verwendet die Sales-Channel-Produktdaten.

### GitHub Updater

`GitHubReleaseUpdater` fragt GitHub Releases ab, validiert das Release-Archiv, übernimmt eine höhere Version in das Plugin-Verzeichnis und stößt anschließend ein Refresh der Shopware-Pluginliste an.

### Scheduled Task

`GitHubUpdateCheckTask` und sein Handler führen die Updateprüfung periodisch aus.

## Konfiguration

Die Plugin-Konfiguration liegt in `src/Resources/config/config.xml`. Services werden in `src/Resources/config/services.xml` registriert.

## Storefront Build

`src/Resources/app/storefront/src/main.js` importiert das Plugin-SCSS. Nach Änderungen muss das Theme neu kompiliert werden.

## Designprinzipien

Keine Core-Patches, möglichst kleine Twig-Overrides, serverseitige Durchsetzung geschäftskritischer Bestandsregeln, Wiederherstellung veränderter Shopware-Konfiguration und GitHub Releases als reproduzierbare Updatequelle.
