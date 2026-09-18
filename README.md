<div align="center">

# MGD Ausverkauft für Shopware 6

**Ausverkaufte Produkte sichtbar lassen, eindeutig kennzeichnen und kontrolliert vom Kauf ausschließen.**

[![Shopware 6.7](https://img.shields.io/badge/Shopware-6.7-189EFF.svg)](#voraussetzungen)
[![PHP ab 8.2](https://img.shields.io/badge/PHP-ab%208.2-777BB4.svg)](#voraussetzungen)
[![Lizenz GPL-2.0-or-later](https://img.shields.io/badge/Lizenz-GPL--2.0--or--later-blue.svg)](LICENSE)

Entwicklung: [Michael Gahn DESIGN](https://michael-gahn.de)

</div>

---

MGD Ausverkauft erweitert die Shopware-6-Storefront für Produkte, deren verfügbarer Bestand eine definierte Grenze erreicht. Die Produktdetailseite bleibt erreichbar, während im Shop ein deutliches **„Ausverkauft“** erscheint. In Produktlisten kann wahlweise nur ein Label oder zusätzlich ein roter Rahmen um die Produktkarte angezeigt werden.

Das Plugin ist insbesondere für Shops gedacht, in denen ausverkaufte Produkte aus SEO-, Informations- oder Sortimentsgründen weiterhin erreichbar bleiben sollen.

> [!IMPORTANT]
> Bei Installation und Aktivierung setzt das Plugin Shopwares Core-Einstellung
> `core.listing.hideCloseoutProductsWhenOutOfStock` auf `false`.
> Dadurch werden Abverkaufsprodukte bei Bestand 0 nicht allein aufgrund dieser Shopware-Einstellung aus der Storefront entfernt.

## Funktionen

* Produktdetailseiten ausverkaufter Artikel bleiben erreichbar
* deutlicher Ausverkauft-Hinweis auf der Produktdetailseite
* Kaufbereich kann bei erreichtem Grenzbestand vollständig ausgeblendet werden
* Kennzeichnung in normalen Shopware-Produktkarten
* Darstellungsmodus **Label oben rechts**
* Darstellungsmodus **Roter Rahmen + Label**
* Kennzeichnung in Listings, Suchergebnissen und Slidern, sofern diese Shopwares Standard-Produktkarte verwenden
* optional frei definierbare Bestandsgrenze
* wahlweise nur für Produkte mit aktiviertem Shopware-Abverkauf
* eigener Labeltext und eigener Hinweistext
* Konfiguration über die Shopware-Plugin-Einstellungen
* keine Änderung an Shopware-Core-Dateien
* updatesichere Storefront-Erweiterung über Twig und SCSS
* integrierte GitHub-Release-Prüfung
* neue Releases werden automatisch vorbereitet und anschließend als natives Shopware-Plugin-Update angeboten
* Release-ZIP wird automatisch über GitHub Actions erzeugt

## Beispiel der Bestandsgrenze

Standardmäßig liegt die Grenze bei `0`.

Wird **Bestandsgrenze verwenden** aktiviert und beispielsweise der Wert `2` eingetragen, gilt:

| Verfügbarer Bestand | Status |
| ---: | --- |
| 10 | normal |
| 3 | normal |
| 2 | ausverkauft |
| 1 | ausverkauft |
| 0 | ausverkauft |

Die Prüfung lautet damit grundsätzlich:

`availableStock <= stockThreshold`

Als Grundlage wird bewusst Shopwares **verfügbarer Bestand** verwendet. Dieser kann vom reinen Lagerbestand abweichen, weil Shopware offene Bestellungen in die Verfügbarkeit einbezieht.

## Darstellung

### Produktdetailseite

Erreicht ein Produkt die definierte Grenze, erscheint anstelle des normalen Kaufbereichs beispielsweise:

**AUSVERKAUFT**

Dieser Artikel ist aktuell nicht verfügbar.

Produktname, Bilder, Preis, Beschreibung, Eigenschaften, Bewertungen und die URL der Produktseite bleiben erhalten.

### Produktlisting: Label

Bei der Einstellung **Label oben rechts** erhält die Produktkarte ein rotes Ausverkauft-Label. Die Karte selbst bleibt unverändert und die Produktdetailseite weiterhin anklickbar.

### Produktlisting: Roter Rahmen + Label

Bei **Roter Rahmen + Label** wird zusätzlich die gesamte Produktkarte optisch hervorgehoben.

### Keine Kennzeichnung

Die Listing-Kennzeichnung kann vollständig deaktiviert werden. Die Logik auf der Produktdetailseite bleibt davon unabhängig aktiv.

## Konfiguration

Nach Installation und Aktivierung befindet sich die Konfiguration unter:

**Erweiterungen → Meine Erweiterungen → MGD Ausverkauft → Konfiguration**

### Darstellung

| Einstellung | Bedeutung | Standard |
| --- | --- | --- |
| Kennzeichnung in Listings, Suche und Slidern | Keine Kennzeichnung, Label oder Rahmen + Label | Label |
| Labeltext | Text des sichtbaren Labels | Ausverkauft |
| Hinweis auf der Produktdetailseite | ergänzender Informationstext | Dieser Artikel ist aktuell nicht verfügbar. |

### Bestandsgrenze

| Einstellung | Bedeutung | Standard |
| --- | --- | --- |
| Bestandsgrenze verwenden | aktiviert die frei definierbare Grenze | aus |
| Bestandsgrenze | Produkt gilt bei Bestand ≤ Wert als ausverkauft | 0 |

### Verhalten

| Einstellung | Bedeutung | Standard |
| --- | --- | --- |
| Nur Produkte mit aktiviertem Abverkauf berücksichtigen | bindet die Kennzeichnung an `isCloseout` | an |
| Kaufbereich bei Ausverkauf ausblenden | entfernt Kaufbutton und Mengenwahl auf der Detailseite | an |

## Shopware-Abverkauf

Shopware besitzt pro Produkt beziehungsweise Variante die Option **Abverkauf**. Zusätzlich existiert die globale Einstellung **Produkte nach Abverkaufende ausblenden**.

MGD Ausverkauft setzt beim Installieren und Aktivieren die globale Einstellung zum Ausblenden automatisch auf `false`. Das ist notwendig, damit Shopware ein Abverkaufsprodukt bei Bestand 0 nicht bereits vor der Darstellung aus Listing, Suche oder anderen Storefront-Bereichen entfernt.

Die eigentliche Produkt-Eigenschaft **Abverkauf** wird vom Plugin bewusst nicht massenhaft für sämtliche Produkte verändert. Diese Einstellung gehört zur Warenwirtschaft des jeweiligen Produkts und kann bei Varianten unterschiedlich sein.

Wer die Plugin-Logik unabhängig vom Abverkauf verwenden möchte, kann **Nur Produkte mit aktiviertem Abverkauf berücksichtigen** deaktivieren.

> [!WARNING]
> Das automatische Setzen der globalen Shopware-Einstellung verändert eine Shop-Konfiguration. Vor dem produktiven Einsatz sollte das Verhalten in einer Staging-Umgebung geprüft werden.

## Varianten

Shopware verwaltet Bestände bei Varianten auf Variantenebene. Das Plugin wertet deshalb den `availableStock` des Produkts aus, das Shopware im jeweiligen Storefront-Kontext bereitstellt.

Bei Varianten sollte insbesondere geprüft werden:

* ob Abverkauf auf den gewünschten Varianten aktiv ist
* ob die im Listing verwendete Hauptvariante dem gewünschten Verhalten entspricht
* ob individuelle Themes die Standard-Produktbox ersetzen
* ob eigene Erlebniswelt-Elemente Produktdaten anders aufbereiten

Das Plugin soll nicht pauschal einen Varianten-Hauptartikel als ausverkauft markieren, nur weil eine einzelne Variante keinen Bestand mehr besitzt. Bei komplexen Variantenkonfigurationen ist deshalb ein Test mit dem konkreten Shop notwendig.

## Voraussetzungen

| Anforderung | Stand |
| --- | --- |
| Shopware | 6.7.x |
| PHP | ab 8.2 |
| PHP-Erweiterung | ext-zip |
| Netzwerk | ausgehendes HTTPS zu api.github.com und GitHub Release Assets |
| Storefront | Shopware Storefront / kompatibles Child Theme |
| Installation | Shopware Administration oder CLI |
| Lizenz | GPL-2.0-or-later |

## Updates direkt über GitHub

MGD Ausverkauft besitzt einen eigenen GitHub-Release-Updater. Shopware prüft über einen Scheduled Task regelmäßig das öffentliche GitHub-Repository auf eine neuere Release-Version.

Der Ablauf ist bewusst zweistufig:

1. Das Plugin fragt GitHubs `releases/latest` API ab.
2. Ist die dortige Version neuer als die installierte Version, wird ausschließlich das Release-Asset `MgdSoldOut.zip` heruntergeladen.
3. Das ZIP wird auf sichere Pfade und die erwartete Plugin-Klasse geprüft.
4. Die neuen Plugin-Dateien werden vorbereitet.
5. Shopwares Plugin-Liste wird über den nativen `PluginService` aktualisiert.
6. Shopware erkennt dadurch die höhere Dateiversion und kann das eigentliche Plugin-Update über seinen normalen Update-Mechanismus ausführen.

Damit bleibt die eigentliche Lifecycle-Aktualisierung bei Shopware. Der GitHub-Updater führt nicht eigenmächtig Datenbankmigrationen aus.

Die automatische Prüfung läuft standardmäßig alle sechs Stunden. Voraussetzung ist, dass Shopwares Scheduled Tasks beziehungsweise die Message Queue regulär verarbeitet werden und der Server ausgehende HTTPS-Verbindungen zu GitHub herstellen darf.

> [!IMPORTANT]
> GitHub-Releases müssen ein Asset mit exakt dem Namen `MgdSoldOut.zip` enthalten. Das im Repository enthaltene Release-Workflow erzeugt dieses ZIP automatisch.

> [!NOTE]
> GitHub ist bei diesem Plugin die Updatequelle. Das Plugin benötigt für die öffentliche GitHub-API keinen persönlichen GitHub-Token.

## Automatischer Release-Prozess

Die Workflow-Datei `.github/workflows/release.yml` liest die Version aus `composer.json`. Bei einem Push auf `main` wird geprüft, ob für `vVERSION` bereits ein GitHub-Release existiert.

Existiert noch kein Release, führt GitHub Actions PHP-Syntax- und Composer-Prüfungen aus, baut anschließend ein Shopware-kompatibles ZIP mit dem Root-Ordner `MgdSoldOut` und veröffentlicht es als `MgdSoldOut.zip`.

Für eine neue Version genügt damit im normalen Release-Prozess:

1. Versionsnummer in `composer.json` erhöhen.
2. Änderungen prüfen und nach `main` übernehmen.
3. GitHub Actions baut und veröffentlicht das Release.
4. Installierte Plugin-Instanzen erkennen die neue Release-Version bei ihrer nächsten Updateprüfung.

## Installation über die Shopware Administration

1. Repository beziehungsweise Release als Plugin-ZIP bereitstellen.
2. In Shopware **Erweiterungen → Meine Erweiterungen** öffnen.
3. Plugin hochladen.
4. Plugin installieren.
5. Plugin aktivieren.
6. Konfiguration öffnen.
7. Cache leeren und Theme neu kompilieren.
8. Einen verfügbaren und einen ausverkauften Testartikel kontrollieren.

## Installation per CLI

Den Pluginordner nach `custom/plugins/MgdSoldOut` kopieren und anschließend ausführen:

```bash
bin/console plugin:refresh
bin/console plugin:install --activate MgdSoldOut
bin/console cache:clear
bin/console theme:compile
```

Nach Änderungen an der Storefront sollte das Theme erneut kompiliert werden:

```bash
bin/console cache:clear
bin/console theme:compile
```

## Technischer Aufbau

```text
MgdSoldOut/
├── composer.json
├── LICENSE
├── README.md
└── src/
    ├── MgdSoldOut.php
    └── Resources/
        ├── config/
        │   └── config.xml
        ├── app/
        │   └── storefront/
        │       └── src/
        │           ├── main.js
        │           └── scss/
        │               └── base.scss
        └── views/
            └── storefront/
                ├── component/product/card/
                │   └── box-standard.html.twig
                └── page/product-detail/
                    └── buy-widget.html.twig
```

Die Plugin-Hauptklasse setzt beim Installieren und Aktivieren:

```text
core.listing.hideCloseoutProductsWhenOutOfStock = false
```

Die Storefront entscheidet anhand von `availableStock`, Bestandsgrenze und optional `isCloseout`, ob ein Produkt als ausverkauft dargestellt wird.

## Theme-Kompatibilität

Das Plugin erweitert Shopwares Standard-Twig-Templates. Themes, die dieselben Blöcke erweitern, funktionieren in der Regel weiterhin. Ein Theme, das die Shopware-Produktbox oder den Buy-Widget-Bereich vollständig ersetzt, kann die Plugin-Ausgabe jedoch umgehen.

In diesem Fall sollte die Integration gezielt für das verwendete Theme ergänzt werden, statt Core-Dateien zu verändern.

## Grenzen der Version 1.0.0

Die Bestandsgrenze steuert in dieser ersten Version primär die Storefront-Darstellung und das Ausblenden des Kaufbereichs. Sie verändert Shopwares eigentliche Lagerbestandsberechnung nicht.

Bei einer Bestandsgrenze größer als `0` muss vor einem produktiven Einsatz zusätzlich geprüft werden, ob Warenkorb-, API-, Schnellkauf- oder Drittanbieter-Funktionen einen Artikel unabhängig vom ausgeblendeten Storefront-Kaufbutton hinzufügen können. Für eine harte Reservierung eines Mindestbestands muss die serverseitige Warenkorbvalidierung zusätzlich abgesichert werden.

Diese Einschränkung ist absichtlich dokumentiert: Ein ausgeblendeter Button allein darf nicht mit einer unveränderbaren serverseitigen Verkaufssperre verwechselt werden.

## Entwicklung

Nach Änderungen an Twig oder SCSS:

```bash
bin/console cache:clear
bin/console theme:compile
```

PHP-Syntax der Hauptklasse kann beispielsweise geprüft werden mit:

```bash
php -l src/MgdSoldOut.php
```

Composer-Metadaten:

```bash
composer validate --strict
```

## Geplante Weiterentwicklung

Für die nächsten Ausbaustufen sind insbesondere sinnvoll:

* serverseitige Absicherung der frei definierten Mindestbestandsgrenze
* eigene Behandlung weiterer Product-Box-Typen
* erweiterte Variantenlogik
* optionaler Hinweis „Nur noch wenige verfügbar“
* Verkaufskanal-spezifische Texte und Darstellung
* automatisierte Tests für Shopware 6.7
* reproduzierbares Release-ZIP und GitHub Actions
* Übersetzungen über Shopware-Snippets statt ausschließlich Konfigurationswerten

## Datenschutz

Das Plugin benötigt keine externen Dienste und überträgt keine Produkt-, Kunden- oder Bestandsdaten an Michael Gahn DESIGN oder andere externe Anbieter.

Es verwendet ausschließlich Daten und Konfigurationen innerhalb der bestehenden Shopware-Installation.

## Lizenz und Anbieter

Lizenz: **GPL-2.0-or-later**

Entwicklung und Projekt: **Michael Gahn DESIGN**

[Michael Gahn DESIGN](https://michael-gahn.de) · [GitHub-Profil](https://github.com/MichaelGahnDESIGN)
