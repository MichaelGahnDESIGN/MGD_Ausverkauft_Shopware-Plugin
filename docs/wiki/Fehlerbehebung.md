# Fehlerbehebung

## Produkt verschwindet weiterhin

Prüfen, ob das Plugin aktiviert ist, Cache und Theme neu gebaut wurden, das Produkt aktiv und dem Verkaufskanal zugewiesen ist und ob Drittanbieter eigene Listing-Filter einsetzen.

## Label fehlt

Bestandsgrenze, `availableStock`, Abverkauf-Einstellung und den Listing-Modus prüfen. **Keine Kennzeichnung** blendet das Label absichtlich aus.

## Kaufen Button bleibt sichtbar

Die Option **Kaufbereich bei Ausverkauf ausblenden** prüfen. Bei individuellen Themes kontrollieren, ob Shopwares `buy_widget_buy_form` Block noch verwendet wird.

## Preis fehlt

Das Plugin ersetzt nur den Buy-Form-Block. Fehlt der Preis, ist ein Theme- oder Drittanbieter-Override wahrscheinlich.

## Update wird nicht erkannt

Prüfen, ob ein neueres echtes GitHub Release existiert, das Asset exakt `MgdSoldOut.zip` heißt, Tag und Composer-Version übereinstimmen, Scheduled Tasks laufen, GitHub erreichbar ist, `ext-zip` installiert und das Plugin-Verzeichnis beschreibbar ist.

## CLI-Diagnose

```bash
bin/console plugin:refresh
bin/console cache:clear
bin/console theme:compile
bin/console scheduled-task:list
```

Bei Updateproblemen zusätzlich Shopware-/Symfony-Logs prüfen.
