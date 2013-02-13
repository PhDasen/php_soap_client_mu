# plentymarkets SOAP API
Vielen Dank für Ihr Interesse an der plentymarkets SOAP API!
Weitere Informationen finden Sie in unserem [Handbuch](http://man.plentymarkets.eu/soap-api/).
Außerdem haben Sie die Möglichkeit, in [diesem Video](https://vimeo.com/58852181) eine detailliert Einführung in unseren **PHP SOAP Client** zu erhalten.

## SOAP
Bevor Sie starten können, passen Sie die Parameter in der Datei `config/soap.inc.php` an.
Sie müssen zuerst alle SOAP Objekte durch den Code-Generator erstellen lassen.

Starten Sie den Code Generator per Shell:

    shell> php cli/PlentymarketsSoapGenerator.cli.php

## Datenbank
Viele Beispiele benötigen eine Datenbank. Erstellen Sie daher eine MySQL-Datenbank.
Tragen Sie die Logindaten in die Datei `config/db.inc.php` ein.
Legen Sie zur Ausführung der Beispiele alle Tabellen in der Datei `config/example_db/example.sql` an.

## SOAP Call Test
Nun können Sie einen API-Test-Aufruf starten:

    shell> php cli/PlentymarketsSoapExampleLoader.cli.php [ExampleName]
    
## SOAP Daemon
Die wohl beste Möglichkeit, um Daten zwischen zwei System synchron zu halten, ist ein Daemon-Prozess. Also ein permanent laufender Prozess, welcher unterschiedliche Aktionen in einem fest definierten Intervall ausführt.
Weiterhin wäre dabei auch eine weitere Anforderung spielend einfach möglich: keine API Calls werden mehr parallel ausgeführt!

Wir haben für Sie einen einfach zu pflegenden Daemon-Prozess entwickelt - starten:

    shell> php cli/PlentySoap.daemon.php

##Testdatengenerator
Das Standard-Problem bei der Entwicklung einer Integration ist meist die Nichtexistenz von sinnvollen Testdaten. 
Auch hierfür wurde eine Lösung geschaffen, welches gleichzeitig als komplexeres Beispiel zur Erstellung von 
Artikelstammdatensätzen dient. Die dabei erstellten Artikel erhalten sinnvolle Namen, eine Artikelbeschreibung, 
eine Kategorie, ein Preis-Set, Artikelbilder und einige Dinge mehr. Der Testdatengenerator ist ebenfalls so modular 
aufgebaut, damit hier sehr einfach eigene Generatoren erstellt werden können.

Durch diesen Befehlt werden 50 Artikelstammdaten angelegt:

    shell> php cli/PlentyTestdataGenerator.cli.php type:item quantity:50 
