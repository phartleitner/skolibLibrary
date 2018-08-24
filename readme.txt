Erfüllung Kundenwünsche:

20180824:
Im Infoscan beim Kundenkonto direktes zurückgeben und löschen ermöglicht.
Lösche erfordert Rückfrage, das ist auch so beabsichtigt!
Erforderliche Optionen wurden in der Customer Account Ansicht realisiert, alle Funktionalitäten in der scan.js.
Bei der Rückgabe eines Titels und der darauf folgenden Kundenkonto Anzeige sidn diese Funktionalitäten deaktiviert. Grund: Seite kann nicht mit den aktuellen Kundenkontodaten aktualisiert werden, weil 
das zurückgegebene oder gelöschte Buch nicht mehr im Rückgabe Scan erfasst wird und sich die Anzeige nicht ändern würde.

Entliehene Titel können als CSV Datei erstellt werden und heruntergeladen werden.




------alte Kommentare-----


Was kommt als nächstes:



Überprüfe die Timeout Problematik

Lösche die Funktionen die nicht mehr gebraucht werden.
ACHTUNG: Beim Import aus einer Liste könnte auch der LibraryItem Konstruktor über die Dateneingabe genutzt werden. 
Dort könnte die Qeury erstellt werden und  dann der DB Eintrag erfolgen, mit anschließender Brechnung von Signatur und Barcode.



Änderungen in der DB:

Bücher werden nur noch in einer Tabelle geführt: skolib_titel



Ziel:
Verbesserung der Login-Überprüfung:

Nach erfolgreichem Login (Überprüfung von Passwort) wird eine Session Variable mit Benutzernamen angelegt.



Aufruf von Controller
Wenn Session Variable User gesetzt ist
	Prüfe Logintoken
		Wenn Logintoken aktuell ist
			rufe gewünschte Seite auf
			erstelle UserObjekt
			aktualisiere Logintoken
		Sonst 
			Anmeldung nicht aktuell
			Zeige Login Bildschirm	
			Setze Session Variable auf null
			Lösche Logintoken
Sonst
	Wenn Logidaten eingegeben wurden
		Überprüfe eingegebene Daten
		Wenn Loginname und Passwort stimmen
			setze Session Variable mit user Name
			erstelle UserObjekt
			erstelle Logintoken
		Sonst
			zeige Login Bildschirm
	Sonst
		zeige Login Bildschirm
		

