Erf�llung Kundenw�nsche:

20180824:
Im Infoscan beim Kundenkonto direktes zur�ckgeben und l�schen erm�glicht.
L�sche erfordert R�ckfrage, das ist auch so beabsichtigt!
Erforderliche Optionen wurden in der Customer Account Ansicht realisiert, alle Funktionalit�ten in der scan.js.
Bei der R�ckgabe eines Titels und der darauf folgenden Kundenkonto Anzeige sidn diese Funktionalit�ten deaktiviert. Grund: Seite kann nicht mit den aktuellen Kundenkontodaten aktualisiert werden, weil 
das zur�ckgegebene oder gel�schte Buch nicht mehr im R�ckgabe Scan erfasst wird und sich die Anzeige nicht �ndern w�rde.

Entliehene Titel k�nnen als CSV Datei erstellt werden und heruntergeladen werden.




------alte Kommentare-----


Was kommt als n�chstes:



�berpr�fe die Timeout Problematik

L�sche die Funktionen die nicht mehr gebraucht werden.
ACHTUNG: Beim Import aus einer Liste k�nnte auch der LibraryItem Konstruktor �ber die Dateneingabe genutzt werden. 
Dort k�nnte die Qeury erstellt werden und  dann der DB Eintrag erfolgen, mit anschlie�ender Brechnung von Signatur und Barcode.



�nderungen in der DB:

B�cher werden nur noch in einer Tabelle gef�hrt: skolib_titel



Ziel:
Verbesserung der Login-�berpr�fung:

Nach erfolgreichem Login (�berpr�fung von Passwort) wird eine Session Variable mit Benutzernamen angelegt.



Aufruf von Controller
Wenn Session Variable User gesetzt ist
	Pr�fe Logintoken
		Wenn Logintoken aktuell ist
			rufe gew�nschte Seite auf
			erstelle UserObjekt
			aktualisiere Logintoken
		Sonst 
			Anmeldung nicht aktuell
			Zeige Login Bildschirm	
			Setze Session Variable auf null
			L�sche Logintoken
Sonst
	Wenn Logidaten eingegeben wurden
		�berpr�fe eingegebene Daten
		Wenn Loginname und Passwort stimmen
			setze Session Variable mit user Name
			erstelle UserObjekt
			erstelle Logintoken
		Sonst
			zeige Login Bildschirm
	Sonst
		zeige Login Bildschirm
		

