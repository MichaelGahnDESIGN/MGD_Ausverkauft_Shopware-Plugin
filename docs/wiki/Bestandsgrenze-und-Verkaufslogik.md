# Bestandsgrenze und Verkaufslogik

Die optionale Bestandsgrenze ermöglicht eine Reserve oberhalb von 0.

Beispiel mit Grenze `2`:

| verfügbarer Bestand | Status | Verkauf |
| ---: | --- | --- |
| 10 | normal | möglich |
| 3 | normal | möglich |
| 2 | ausverkauft | gesperrt |
| 1 | ausverkauft | gesperrt |
| 0 | ausverkauft | gesperrt |

Die Storefront-Prüfung entspricht grundsätzlich `availableStock <= stockThreshold`.

## Warum availableStock?

Shopwares verfügbarer Bestand kann vom einfachen Lagerbestand abweichen. Für die Kaufbarkeit orientiert sich das Plugin deshalb am von Shopware bereitgestellten `availableStock`.

## Serverseitige Reserve

Bei Grenze 2 werden zwei Einheiten als Reserve behandelt. Der maximal verkaufbare Bestand ergibt sich sinngemäß aus `max(0, availableStock - stockThreshold)`.

Ein eigener Cart Validator prüft Produktpositionen serverseitig. Die Grenze ist damit nicht nur ein versteckter Button. Shopwares normale Bestandsvalidierung bleibt zusätzlich aktiv.

## Warum das wichtig ist

Eine rein visuelle Sperre könnte durch alternative Warenkorbwege oder Drittanbieter-Erweiterungen umgangen werden. Die serverseitige Prüfung schützt deshalb die konfigurierte Reserve auch außerhalb des sichtbaren Kaufbuttons.
