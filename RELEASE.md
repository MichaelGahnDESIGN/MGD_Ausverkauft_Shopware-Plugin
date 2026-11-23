# Version 1.1.1

Räumt auf: Der alte GitHub-Update-Check und sein Schalter sind entfernt. Updates laufen über **Einstellungen → Erweiterungen → MGD Ausverkauft** (Knopf „Jetzt aktualisieren“). Einstellungen und Fachlogik bleiben unverändert.

Nach dem Update auf dem Server: `bin/console cache:clear`, danach `bin/console scheduled-task:register`. Eine Shop-Sicherung vor Updates bleibt empfohlen.
