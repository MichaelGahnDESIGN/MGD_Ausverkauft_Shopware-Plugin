# Releaseprozess

Releases werden über GitHub Actions erzeugt.

## Versionsquelle

Die Plugin-Version steht in `composer.json`.

Beispiel:

`"version": "1.0.1"`

## Automatischer Workflow

Bei einem Push auf `main` startet `.github/workflows/release.yml`.

Der Workflow:

1. liest die Version aus `composer.json`
2. prüft die PHP-Syntax
3. validiert `composer.json`
4. prüft, ob `vVERSION` bereits existiert
5. baut `MgdSoldOut.zip`
6. legt darin den Root-Ordner `MgdSoldOut` an
7. veröffentlicht ein GitHub Release mit passendem Tag

Ein vorhandenes Release derselben Version wird nicht erneut erzeugt.

## Neue Version veröffentlichen

Vor dem Merge einer neuen Version sollten Code, Twig, Konfiguration, Cart Validator und Updater geprüft werden. Danach Versionsnummer erhöhen und nach `main` übernehmen.

Die installierten Plugin-Instanzen können das neue Release anschließend über den integrierten Updater erkennen.

## Release-ZIP

Das ZIP muss mindestens `composer.json`, `src/`, `README.md` und `LICENSE` im Ordner `MgdSoldOut/` enthalten.

Der Assetname muss exakt `MgdSoldOut.zip` lauten, weil der Updater gezielt dieses Asset erwartet.

## Qualität

Für Pull Requests und Pushes auf `main` existiert zusätzlich ein Quality Workflow für PHP-Syntax und Composer-Metadaten.
