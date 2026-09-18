# Varianten und Abverkauf

Shopware kann Bestand und Abverkauf auf Variantenebene verwalten. Deshalb benötigen Varianten einen eigenen Test.

Eine Größe S kann ausverkauft sein, während M und L verfügbar sind. Das Plugin soll nicht allein wegen einer leeren Variante pauschal das gesamte Variantenprodukt sperren.

Bei aktivierter Option **Nur Produkte mit aktiviertem Abverkauf berücksichtigen** muss die im jeweiligen Kontext betrachtete Variante `isCloseout` erfüllen.

Besonders relevant ist die im Listing verwendete Hauptvariante. Individuelle Varianten-Plugins können beeinflussen, welche Produktdaten Shopware an die Produktkarte übergibt.

## Testmatrix

Empfohlen sind Tests mit allen Varianten verfügbar, einer Variante leer, Hauptvariante leer, allen Varianten leer, Bestand exakt auf der Grenze sowie unterschiedlich gesetztem Abverkauf.

Das Plugin verändert die Abverkauf-Eigenschaft der Produkte nicht massenhaft.
