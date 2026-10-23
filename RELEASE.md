# Version 1.0.3

Das Release-ZIP enthält alle benötigten Plugin-Dateien einschließlich fertiger CSS-/JavaScript-Dateien. Upload und GitHub-Update verwenden dasselbe geprüfte Paket. Ein manueller Administration- oder Node-Build entfällt.

Die Release-Pipeline prüft PHP, Paketinhalt, Asset-Verweise und Build-Prüfsummen. Veraltete oder veränderte Build-Dateien werden beim lokalen Verpacken abgewiesen; GitHub baut die Styles bei jedem Release frisch.

Der GitHub-Updater verwendet jetzt den tatsächlichen Plugin-Ordner statt eines fälschlich angehängten zweiten MgdSoldOut-Verzeichnisses. Ältere Versionen deshalb einmal per ZIP aktualisieren.

Nach dem Upload bzw. der automatischen Vorbereitung unter „Erweiterungen → Meine Erweiterungen“ den normalen Shopware-Updatevorgang ausführen. Shopware übernimmt dabei seine Asset- und Cache-Schritte. Für automatische Prüfungen den Schalter in den Plugin-Einstellungen global aktivieren; ScheduledTask-Scheduler und Queue-Worker müssen laufen. Wegen der kurzen Verzeichnis-Umschaltung ein Wartungsfenster verwenden. Eine Shop-Sicherung vor Updates bleibt empfohlen.
