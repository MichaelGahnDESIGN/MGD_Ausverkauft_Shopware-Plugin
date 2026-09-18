# Themes, Listings und Erlebniswelten

Das Plugin verwendet Twig-Vererbung und verändert keine Shopware-Core-Dateien.

## Standard-Produktbox

Listings, Suche und Slider werden automatisch erfasst, wenn sie Shopwares Standard-Produktbox verwenden.

## Individuelle Themes

Child Themes, die Shopwares Blöcke sauber erweitern, sind grundsätzlich gut integrierbar. Ein Theme, das die betreffenden Templates oder Blöcke vollständig ersetzt, kann die Plugin-Ausgabe umgehen.

## Erlebniswelten und Drittanbieter

Normale Shopware-Produktslider können die Kennzeichnung übernehmen. Eigene CMS-Elemente, Drittanbieter-Slider oder externe Suchlösungen müssen separat geprüft werden.

## Diagnose

Fehlt ein Label nur in einem bestimmten Bereich, zuerst den tatsächlich gerenderten Twig-Pfad und das HTML prüfen. Erst wenn feststeht, dass das Plugin-Markup vorhanden ist, sollte CSS als Ursache untersucht werden.
