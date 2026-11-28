# Änderungen

## [2.2.0] - 2026-11-28

- Baukasten 1.17.0: Anmeldung auch bei Update und Aktivieren, Farbwähler-Verbesserungen.

## [2.1.1] - 2026-11-25

- Aktueller Baukasten, einheitliche Version.

## 1.1.1 – 2026-11-24

- Der alte GitHub-Update-Check ist ganz entfernt, auch der Schalter „GitHub-Updates“ in den Einstellungen. Updates laufen über die Seite **Einstellungen → Erweiterungen → MGD Ausverkauft**.
- Nach dem Update `bin/console cache:clear`, danach `bin/console scheduled-task:register`.

## 1.1.0 – 2026-11-24

Diese Version ist eine Brückenversion. Deine Einstellungen bleiben genau so, wie sie sind.

- **Neue Seite „MGD Ausverkauft“** unter **Einstellungen → Erweiterungen**. Dort siehst du Version, Update-Stand, weitere Plugins und den Schalter „Installation melden“.
- **Updates über theme.michael-gahn.de:** Gibt es eine neue Version, zeigt die Seite den Knopf **„Jetzt aktualisieren“**. Vorher werden Herkunft (GitHub), Größe und Prüfsumme der Datei geprüft. Es gibt kein automatisches Update.
- Den Knopf sehen nur Admins mit den Rechten „Erweiterungen hochladen“ und „Erweiterungen verwalten“. Klappt das Update nicht, zeigt die Seite einen Download-Link und 3 einfache Schritte.
- **Weitere Plugins** stehen jetzt auf dieser Seite.
- Der alte GitHub-Update-Check (Scheduled Task und Schalter „GitHub-Updates“) ist abgeschaltet.
- **Löschen:** Deinstallierst du das Plugin, wird die Installation bei theme.michael-gahn.de abgemeldet.
- Technik: gemeinsamer Plugin-Baukasten aus dem Pro-Repo unter `src/Kit`. Nach dem Update einmal `bin/console cache:clear`, danach `bin/console scheduled-task:register`.

## 1.0.2 – 2026-10-22

- Plugin-Icon lokal eingebunden und im Release-Paket enthalten.
- Darstellung in der Erweiterungsverwaltung ergänzt beziehungsweise verbessert.
- Keine Änderung an Fachlogik, Einstellungen oder Datenbank.
