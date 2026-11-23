# Version 1.1.0

Brückenversion mit dem MGD Plugin-Baukasten. Deine Einstellungen bleiben, die Fachlogik ist unverändert.

- Neue Admin-Seite **Einstellungen → Erweiterungen → MGD Ausverkauft**: Info, Updates mit Knopf „Jetzt aktualisieren“, Weitere Plugins, Installation melden.
- Der alte GitHub-Update-Check ist abgeschaltet. Es gibt kein automatisches Update.
- Beim Deinstallieren wird die Installation bei theme.michael-gahn.de abgemeldet.
- Nach dem Update auf dem Server: `bin/console cache:clear`, danach `bin/console scheduled-task:register`.

Das Release-ZIP enthält alle benötigten Plugin-Dateien einschließlich fertiger CSS-/JavaScript-Dateien. Eine Shop-Sicherung vor Updates bleibt empfohlen.
