<!-- MGD-HEADER -->
<p align="center"><a href="https://Michael-Gahn.de"><img src="assets/mgd-logo.png" alt="Michael Gahn DESIGN" width="48"></a></p>

<p align="center"><img src="assets/banner.svg" alt="MGD Ausverkauft Shopware Plugin" width="100%"></p>

<p align="center">
  <img alt="Lizenz" src="https://img.shields.io/github/license/MichaelGahnDESIGN/MGD_Ausverkauft_Shopware-Plugin?label=Lizenz">
  <a href="https://github.com/MichaelGahnDESIGN/MGD_Ausverkauft_Shopware-Plugin/releases/latest"><img alt="Release" src="https://img.shields.io/github/v/release/MichaelGahnDESIGN/MGD_Ausverkauft_Shopware-Plugin?label=Release"></a>
  <img alt="Sprache" src="https://img.shields.io/badge/Sprache-PHP-2f6fed">
  <a href="https://Michael-Gahn.de"><img alt="by Michael Gahn DESIGN" src="https://img.shields.io/badge/by-Michael%20Gahn%20DESIGN-cd1616"></a>
</p>
<!-- /MGD-HEADER -->

<div align="center">

# MGD Ausverkauft für Shopware 6

**Ausverkaufte Produkte sichtbar lassen, eindeutig kennzeichnen und kontrolliert vom Kauf ausschließen.**

[![Shopware 6.7](https://img.shields.io/badge/Shopware-6.7-189EFF.svg)](#voraussetzungen)
[![PHP ab 8.2](https://img.shields.io/badge/PHP-ab%208.2-777BB4.svg)](#voraussetzungen)
[![Lizenz GPL-2.0-or-later](https://img.shields.io/badge/Lizenz-GPL--2.0--or--later-blue.svg)](LICENSE)

Entwicklung: [Michael Gahn DESIGN](https://michael-gahn.de)

</div>

---

## Dokumentation

Das ausführliche Handbuch liegt unter [`docs/wiki/`](docs/wiki/Home.md).

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
* Admin-Seite **MGD Ausverkauft** (ab 1.1.0) mit Info, Updates (Knopf „Jetzt aktualisieren“), weiteren Plugins und dem Schalter „Installation melden“
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

## Updates (ab 1.1.0)

1. Öffne **Einstellungen → Erweiterungen → MGD Ausverkauft**.
2. Steht dort „Version X ist verfügbar“, klicke **Jetzt aktualisieren**.
3. Das Plugin lädt die ZIP-Datei aus dem GitHub-Release. Es prüft Größe, Prüfsumme (SHA-256) und den Inhalt der ZIP. Nur wenn alles stimmt, spielt Shopware sie ein.
4. Danach steht dort „Fertig“. Lade die Seite neu.
5. Klappt es nicht (zum Beispiel fehlen Schreibrechte), zeigt die Seite einen Link zur ZIP-Datei. Lade sie dann selbst unter **Erweiterungen → Meine Erweiterungen → Erweiterung hochladen** hoch und klicke beim Plugin auf **Aktualisieren**.

Den Knopf sehen nur Admins mit den Rechten „Erweiterungen hochladen“ und „Erweiterungen verwalten“. Es gibt kein automatisches Update. Vor einem Update gehören Sicherung und Wartungsfenster dazu.

Nach dem Update auf dem Server einmal `bin/console cache:clear` und danach `bin/console scheduled-task:register` ausführen.

Der alte GitHub-Update-Check mit dem Schalter „GitHub-Updates“ ist seit 1.1.1 entfernt.

## Weitere Plugins (ab 1.1.0)

Die Liste steht auf der Seite **Einstellungen → Erweiterungen → MGD Ausverkauft**. Sie kommt von theme.michael-gahn.de und wird 15 Minuten zwischengespeichert. Es wird nichts automatisch installiert. Im Shop für Besucher gibt es keine Anfrage an theme.michael-gahn.de.

## Installation melden (ab 1.1.0)

Das Plugin kann sich bei theme.michael-gahn.de anmelden (Plugin, Version, Shopsystem, Domain und eine zufällige Installations-ID). Der Schalter steht auf der Seite **MGD Ausverkauft**. Aus = der Eintrag wird gelöscht. Deinstallierst du das Plugin, wird der Eintrag ebenfalls gelöscht. Das Plugin funktioniert immer voll, auch ohne Meldung.

Technik: gemeinsamer Plugin-Baukasten aus dem Pro-Repo (`php bin/build-plugin-kit.php … --platform=shopware6 --namespace='Mgd\SoldOut\Kit' --product=mgd-ausverkauft-shopware --technical-name=MgdSoldOut --name='MGD Ausverkauft' --catalog-slug=plugin-ausverkauft-shopware`), hier unter `src/Kit`. Diesen Ordner nicht von Hand ändern, er wird neu erzeugt.

## Automatischer Release-Prozess

Die Workflow-Datei `.github/workflows/release.yml` liest die Version aus `composer.json`. Bei einem Push auf `main` wird geprüft, ob für `vVERSION` bereits ein GitHub-Release existiert.

Existiert noch kein Release, führt GitHub Actions PHP-Syntax- und Composer-Prüfungen aus, baut anschließend ein Shopware-kompatibles ZIP mit dem Root-Ordner `MgdSoldOut` und veröffentlicht es als `MgdSoldOut.zip`.

Für eine neue Version genügt damit im normalen Release-Prozess:

1. Versionsnummer in `composer.json` erhöhen.
2. Änderungen prüfen und nach `main` übernehmen.
3. GitHub Actions baut und veröffentlicht das Release.
4. Installierte Plugin-Instanzen sehen die neue Version auf der Seite **MGD Ausverkauft** (Update-Datei auf theme.michael-gahn.de).

## Installation über die Shopware Administration

1. Fertiges `MgdSoldOut.zip` aus dem GitHub-Release herunterladen.
2. In Shopware **Erweiterungen → Meine Erweiterungen** öffnen.
3. Plugin hochladen.
4. Plugin installieren.
5. Plugin aktivieren.
6. Konfiguration öffnen.
7. Den normalen Shopware-Vorgang abschließen; fertige Styles sind enthalten.
8. Einen verfügbaren und einen ausverkauften Testartikel kontrollieren.

## Installation per CLI

Den Pluginordner nach `custom/plugins/MgdSoldOut` kopieren und anschließend ausführen:

```bash
bin/console plugin:refresh
bin/console plugin:install --activate MgdSoldOut
bin/console cache:clear
bin/console theme:compile
```

Nur bei eigener Entwicklung an zusätzlichen Theme-Quellen kann eine Theme-Kompilierung erforderlich sein. Für das fertige Release-ZIP entfällt dieser manuelle Schritt:

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

## Serverseitige Absicherung der Bestandsgrenze

Die Bestandsgrenze ist nicht nur eine optische Storefront-Funktion. Ein eigener Shopware-Cart-Validator prüft Produktpositionen serverseitig.

Bei einer Grenze von beispielsweise `2` werden diese zwei Stück als Reserve behandelt. Der maximal verkaufbare Bestand ergibt sich aus `availableStock - 2`. Dadurch reicht es nicht aus, den ausgeblendeten Kaufen-Button über einen alternativen Warenkorb-, API- oder Drittanbieter-Weg zu umgehen.

Shopwares normale Bestandsvalidierung bleibt zusätzlich aktiv. Das Plugin ersetzt sie nicht, sondern ergänzt sie um die konfigurierte Reserve.

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

<!-- MGD-LEGAL -->
---

## Lizenz

Dieses Projekt steht unter der [GNU GPL v2 oder neuer](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html). Den vollständigen Text enthält die Datei [LICENSE](LICENSE).

## Impressum

**Angaben gemäß § 5 DDG (Digitale-Dienste-Gesetz)**

Michael Gahn DESIGN  
Michael Gahn  
Dr.-Theodor-Brugsch Str. 12  
08529 Plauen  
Sachsen  
Deutschland

Tel.: +49 (0) 151 59156639  
E-Mail: Anfrage@Michael-Gahn.de

Umsatzsteuer-Identifikationsnummer gemäß § 27 a Umsatzsteuergesetz:  
Steuernummer: 223/222/02451  
Ust-ID: DE288143343

Wir sind zur Teilnahme an einem Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle weder verpflichtet noch bereit.

**Redaktionell verantwortlich:**

Michael Gahn DESIGN  
Michael Gahn  
Dr.-Theodor-Brugsch Str. 12  
08529 Plauen  
Sachsen  
Deutschland

Tel.: +49 (0) 151 59156639  
E-Mail: Anfrage@Michael-Gahn.de
<!-- /MGD-LEGAL -->

## Plugin-Icon – Version 1.0.2

Eigenes lokales Icon in der Shopware-Erweiterungsverwaltung. Die Grafik ist im Release-Paket enthalten; keine externen Bilddienste und keine neuen gespeicherten Daten. Vorhandene Funktionen und Einstellungen bleiben unverändert.

## Fertige Release-Pakete und Updates

Das Release-ZIP enthält alle benötigten Plugin-Dateien einschließlich fertiger CSS-/JavaScript-Dateien. Upload und Knopf „Jetzt aktualisieren“ verwenden dasselbe geprüfte Paket. Ein manueller Administration- oder Node-Build entfällt.

Die Release-Pipeline prüft PHP, Paketinhalt, Asset-Verweise und Build-Prüfsummen. Veraltete oder veränderte Build-Dateien werden beim lokalen Verpacken abgewiesen; GitHub baut die Styles bei jedem Release frisch.

Nach dem Upload unter „Erweiterungen → Meine Erweiterungen“ den normalen Shopware-Updatevorgang ausführen. Shopware übernimmt dabei seine Asset- und Cache-Schritte. Eine Shop-Sicherung vor Updates bleibt empfohlen.

Entwicklung: `python3 scripts/build-assets.py` baut die Dateien. `python3 scripts/build-release.py` prüft den Stand und erzeugt das ZIP unter `dist/`. Ein Push auf `main` erstellt nach erfolgreichen Prüfungen das Release der in `composer.json` angegebenen Version. Bereits veröffentlichte Versionen werden niemals überschrieben.
