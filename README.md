# SmallTime / Fork

Die kleine Zeiterfassung für Privatpersonen und kleine Firmen.

Infos zu Installation und Bedienung: [http://www.small.li/](http://www.small.li/)

* Das Projekt wurde von SmallTime übernommen und angepasst.
* Eine Portable Version (XAMPP + SmallTime + .NET Applikation) kann auf [http://www.petrucci.ch/SmallTime/](http://www.petrucci.ch/SmallTime/) heruntergeladen werden
* Das Projekt eignet sich sehr gut als Offline Version (ohne MySQL und sehr gut aufgebaut)

## Anpassungen 

- Feiertage werden normalerweise pro Firma und nicht pro Benutzer definiert (Beispiel: Thurgauer Mitarbeiter muss die Sankt-Galler Feiertage feiern, falls die Firma in SG ist).
- Absenzen (wie Feiertage) gelten für alle. Kann unter 'Setting' eingestellt werden und nicht mehr pro Benutzer
- Header mit [meta http-equiv="X-UA-Compatible" content="IE=Edge"] erweitert, damit es mit XAMPP und eine .NET Applikation geöffnet werden kann
- PHP7 gibt sehr viele Warnings, diese sollten behoben werden
- XML Anstatt TXT Dateien für Einstellungen

## Neuerungen

- Chipdrive (Bin-)Datei kann importiert werden (Chipdrive-Format)
- Beim Import wird geprüft ob eine Zeit schon vorhanden ist
- Neue Einstellung: Ob die Zeit bei den Reports als Zahl oder Zeitformat dargestellt werden soll

## Changelog
Im Wiki-Bereich befindet sich ein ausführliches Changelog: 
[https://github.com/Adrianotiger/SmallTime/wiki/Changelog](https://github.com/Adrianotiger/SmallTime/wiki/Changelog)



