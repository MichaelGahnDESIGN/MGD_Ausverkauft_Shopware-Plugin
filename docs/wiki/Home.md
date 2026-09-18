# MGD Ausverkauft für Shopware 6

Willkommen im Handbuch für **MGD Ausverkauft**. Das Plugin hält ausverkaufte Produkte in Shopware 6 sichtbar, kennzeichnet sie eindeutig und sperrt den Verkauf, sobald der konfigurierte Grenzbestand erreicht ist.

## Dokumentation

* [Installation und Updates](Installation-und-Updates)
* [Grundkonfiguration](Grundkonfiguration)
* [Bestandsgrenze und Verkaufslogik](Bestandsgrenze-und-Verkaufslogik)
* [Darstellung in der Storefront](Darstellung-in-der-Storefront)
* [Varianten und Abverkauf](Varianten-und-Abverkauf)
* [GitHub Update System](GitHub-Update-System)
* [Themes, Listings und Erlebniswelten](Themes-Listings-und-Erlebniswelten)
* [Fehlerbehebung](Fehlerbehebung)
* [Entwicklerarchitektur](Entwicklerarchitektur)
* [Sicherheit, Datenschutz und Betrieb](Sicherheit-Datenschutz-und-Betrieb)
* [Releaseprozess](Releaseprozess)

## Ziel

Statt ein Abverkaufsprodukt bei Bestand 0 aus der Storefront verschwinden zu lassen, bleibt seine URL erreichbar. Preis, Bilder, Beschreibung, Eigenschaften und weitere Informationen bleiben sichtbar. Die Kaufmöglichkeit wird durch einen Ausverkauft-Hinweis ersetzt. In Produktkarten kann zusätzlich ein Label oder ein roter Rahmen mit Label erscheinen.

Eine optionale Bestandsgrenze schützt Restbestand als Reserve. Diese Grenze wird nicht nur optisch, sondern zusätzlich serverseitig im Warenkorb abgesichert.

## Unterstützter Stand

Version 1.0.0 ist für Shopware 6.7.x und PHP ab 8.2 vorgesehen. Für den GitHub-Updater wird `ext-zip` benötigt.

Repository: https://github.com/MichaelGahnDESIGN/MGD_Ausverkauft_Shopware-Plugin

Entwicklung: Michael Gahn DESIGN

Lizenz: GPL-2.0-or-later
