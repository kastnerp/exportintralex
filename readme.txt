Daten von Bestellungen nach CSV exportieren
Installationsanleitung
� Martin H�upler
www.der-schub-laden.eu


Hinweise :
Beachten Sie bitte dass alle �nderungen an Ihrem Shopsystem auf eigene Verantwortung
geschehen. Legen Sie vor allen �nderungen Sicherungskopien Ihrer Dateien und der
Datenbank an, um bei evt. auftretenden Fehlern die Ursprungsdateien wieder verwenden zu
k�nnen.



Beschreibung :
Mit diesem kleinen Tool k�nnen Sie die Adressdaten von Bestellungen aus XT:Commerce 3.04/SP2.1 ausw�hlen, 
�berpr�fen und in eine CSV/TXT Datei exportieren, welche dann von intraship bzw. von lexware eingelesen werden k�nnen.
Eine Demo finden Sie unter:
http://www.der-schub-laden.eu/ShopSystem/media/content/exportintralex.php


Anleitung :
Nach der Installation steht Ihnen im AdminBereich ein neuer Eintrag - "Intraship + Lexware" - im linken Men� zur Verf�gung. 
Nach Aufruf erhalten Sie eine Seite auf der Sie ausw�hlen k�nnen von und bis zu welcher Bestellung Sie exportieren m�chten.


Installation :
1.) Kopieren Sie die Datei "exportintralex.php" in das Verzeichnis Ihres Shops: /IhrShopverzeichnis/admin/
2.) F�hren Sie folgende DatenbankabFrage aus (phpMyAdmin):

ALTER TABLE `admin_access` ADD `exportintralex` INT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE `orders` ADD `lexware_export_success` INT( 1 ) NOT NULL DEFAULT '0',
ADD `intraship_export_success` INT( 1 ) NOT NULL DEFAULT '0'

3.) �ndern Sie nun folgende Dateien :
Datei: /IhrShopverzeichnis/admin/includes/application_top.php
das suchen :
// define the filenames used in the project
darunter das einf�gen :

// CSVuTXT-Export von Adressdaten der Bestellungen
define('FILENAME_EXPORTINTRALEX', 'exportintralex.php');


Datei: /IhrShopverzeichnis/admin/includes/column_left.php
das suchen :
echo ('<div class="dataTableHeadingContent"><b>'.BOX_HEADING_TOOLS.'</b></div>');

darunter das einf�gen :
if (($_SESSION['customers_status']['customers_status_id'] == '0') && ($admin_access['exportintralex'] == '1')) echo '<a href="' .xtc_href_Link (FILENAME_EXPORTINTRALEX, '', 'NONSSL') . '" class="menuBoxContentLink"> -' .BOX_EXPORTINTRALEX . '</a><br />';

Beachten Sie bitte, dass der gesamte Text in einer Zeile ohne Umbruch steht !


Datei: /IhrShopverzeichnis/lang/german/admin/german.php
ganz unten vor ?> das hinzuf�gen :
define('BOX_EXPORTINTRALEX','Intraship + Lexware');


Das war es schon.
