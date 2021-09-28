<?php
/** --------------------------------------------------------------
    $Id: exportintralex.php 2021/09/28 - Patrick Kastner
   --------------------------------------------------------------
    based on:
      exportintralex.php,v 2.3e FREE (Date: 2011/03/19) - Martin Haeupler - http://www.der-schub-laden.eu
    
    Released under the GNU General Public License
   --------------------------------------------------------------*/



$max_bestellanzeige = 100;  //Anzahl der dargestellten Datensaetze
$Deb_Konto_Ist_Null = 'Debitorenkonto';
$action_lexware ='Lexware'; // Event fuer Lexware-Export
$action_intraship_5_0 ='Intraship_5_Adressdaten'; // Event fuer Intraship-Export
$action_intraship_4_0 ='Intraship_4_Adressdaten'; // Event fuer Intraship-Export
$action_intraship_auftragsexport = 'Intraship_Sendungsdatenexport'; // Event fuer Intraship-Export
$reset_lexware ='Lexware Reset'; //noch nicht verwendet
$L_Sep = ";"; // Separator fuer die Exportdatei zu Lexware
$I_Sep = "|"; // Separator fuer die Exportdatei zu intraship
$mehr_oID = "Bestellung hinzuf�gen";
$mehr_oID_progr = "Bestellung_zusaetzlich";

/* Nachfolgender Kontenplan wird in function Set_Debitorenkonto() verwendet */
$Deb_Kontenplan = "\nDer Anfangsbuchstabe des Kundennamens bildet folgendes Debitorenkonto in Lexware:\n\"A\"=10100 \"B\"=10200 \"C\"=10300 \"D\"=10400\"E\"=10500 \"F\"=10600 \"G\"=10700 \"H\"=10800\n\"I\"=10900 \"J\"=11000 \"K\"=11100 \"L\"=11200 \"M\"=11300 \"N\"=11400 \"O\"=11500 \"P\"=11600\n\"Q\"=11700 \"R\"=11800 \"S\"=11900 \"Sch\"=12000 \"St\"=12100 \"T\"=12200 \"U\"=12300\n\"V\"=12400 \"W\"=12500 \"X\"=12600 \"Y\"=12700 \"Z\"=12800\n";
$Deb_No_Kontenplan ="\nDebitorenkonten werden nicht vorbelegt,\nKontennummern werden von Lexware selbst gesteuert.\n";
$SchnellZuLexwareExport = false; //Gibt an, ob ein Popup erscheint, welches infos zu Lexwareexport anzeigt / true=schnell=keine Anzeige  / false=nicht schnell=mit Anzeige


/* weitere Definitionen*/
define ('UEBERSCHRIFT1', 'Export der Adressdaten von Bestellungen nach Intraship und Lexware');
define ('CONTENT1', 'W&auml;hlen sie die Bestellungen von Kunden aus, deren Adressdaten Sie exportieren m&ouml;chten!<br />Wenn Sie mehrere Eintr&auml;ge ausw&auml;hlen wollen, verwenden Sie die Strg-Taste.');
define ('CONTENT2', '&nbsp; In dieser Liste werden die letzten ' . $max_bestellanzeige . ' Bestellungen angezeigt. Exportierte Bestellungen erhalten ein &#x2714;');
define ('TABLE_INTRASHIP_CONFIG','intraship_config');
define ('LEERZEILE2','<tr><td>&nbsp;</td><td>&nbsp;</td></tr>');

require('includes/application_top.php');
require_once(DIR_WS_FUNCTIONS . 'export_functions.php');




function ersetzeUmlaute($string)
{
	$string = str_replace ("�", "ae", $string);
	$string = str_replace ("�", "Ae", $string);
	$string = str_replace ("�", "oe", $string);
	$string = str_replace ("�", "Ue", $string);
	$string = str_replace ("�", "ue", $string);
	$string = str_replace ("�", "Ue", $string);
	$string = str_replace ("�", "ss", $string);
	return ($string);
}

function Set_Debitorenkonto($Debitoren_name) { // Ein Beispielset zum Export nach Lexware: Debitorensammelkonto nach Anfangsbuchstabe
if ($SchnellZuLexwareExport) {return "";}; //keine Zuweisung von Kontonummern
if (isset($_GET['Debitorenkonto']) && ($_GET['Debitorenkonto'])=='Debitorenkonto') {
$Debitoren_name = ersetzeUmlaute($Debitoren_name);
$Erster_Buchstabe = strtolower(substr ($Debitoren_name,0,1));
//return $Debitoren_name."+".$Erster_Buchstabe." "; break;
switch ($Erster_Buchstabe) {
case "a": return "10100"; break;
case "b": return "10200"; break;
case "c": return "10300"; break;
case "d": return "10400"; break;
case "e": return "10500"; break;
case "f": return "10600"; break;
case "g": return "10700"; break;
case "h": return "10800"; break;
case "i": return "10900"; break;
case "j": return "11000"; break;
case "k": return "11100"; break;
case "l": return "11200"; break;
case "m": return "11300"; break;
case "n": return "11400"; break;
case "o": return "11500"; break;
case "p": return "11600"; break;
case "q": return "11700"; break;
case "r": return "11800"; break;
//case "r": return $Debitoren_name."11800"; break;
case "s":
  if (strtolower(substr ($Debitoren_name,0,3))=="sch") {return "12000"; break;}
  if (strtolower(substr ($Debitoren_name,0,2))=="st") {return "12100"; break;}
  return "11900"; break;
case "t": return "12200"; break;
case "u": return "12300"; break;
case "v": return "12400"; break;
case "w": return "12500"; break;
case "x": return "12600"; break;
case "y": return "12700"; break;
case "z": return "12800"; break;

}
}
return "";
}

define('URHEBER', 'Martin Gottlieb H&auml;upler<br />Thann 6<br />D-92681 Erbendorf<br /><i>Dateiversion: exportintralex.php,v 2.3e FREE (Date: 2011/03/19)</i><br />Interesse an der Professional Version? Infos unter <a href="http://www.der-schub-laden.eu/ShopSystem/media/content/exportintralex.php" target="_blank">http://www.der-schub-laden.eu/ShopSystem/media/content/exportintralex.php</a>');

function utf8_str_word_count($string,$format=0,$charlist='') {
    $array = preg_split("/[^'\-A-Za-z".$charlist."]+/u",$string,-1,PREG_SPLIT_NO_EMPTY);
    switch ($format) {
    case 0:
        return(count($array));
    case 1:
        return($array);
    case 2:
        $pos = 0;
        foreach ($array as $value) {
        $pos = utf8_strpos($string,$value,$pos);
        $posarray[$pos] = $value;
        $pos += utf8_strlen($value);
        }
        return($posarray);
    }
}

function Schreibe_Uebergabedatei($ausgabe, $filename) {
        header("Content-Disposition: attachment; filename=".$filename);
				header("Content-Length: ".strlen($ausgabe));
				header("Content-type: text/plain; name=".$filename);
				header("Connection: close");
				echo $ausgabe;
}

function ersetze_pipe($i){  // hier wird das Pipe-Zeichen "|" durch ein Schraegstrichzeichen "/" ersetzt
  $_POST["nachname".$i] = utf8_decode(str_replace("|","/",$_POST["nachname".$i]));
  $_POST["vorname".$i] = utf8_decode(str_replace("|","/",$_POST["vorname".$i]));
  $_POST["Firma".$i] = utf8_decode(str_replace("|","/",$_POST["Firma".$i]));
  $_POST["street".$i] = utf8_decode(str_replace("|","/",$_POST["street".$i]));
  $_POST["telefon".$i] = utf8_decode(str_replace("|","/",$_POST["telefon".$i]));
  $_POST["hausnr".$i] = utf8_decode(str_replace("|","/",$_POST["hausnr".$i]));
  $_POST["city".$i] = utf8_decode(str_replace("|","/",$_POST["city".$i]));
}

function Display_Doctype(){
?><!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?> FREE - Datenexport nach intraship und lexware</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css"><?php
}


// Auswertung von $_POST und $_GET
  switch ($_POST['action']) {

//-----------------------------------------------------------------------------------------------------
  case 'lexware_Progress':
  case 'Export starten':

        $filename = 'Lieferadr_Intraship_'.date("j_n_Y").'.csv';

        $ausgabe  = "# ***************************************************************************************************************\r\n";
        $ausgabe .= "# * Erstellt von: Export-Generator f�r *XT:Commerce 3.04 SP2.1* von Martin H�upler - Service@der-schub-laden.eu *\r\n";
        $ausgabe .= "# * Dateiformat f�r DHL ".$_POST["version"]."                                                                     *\r\n";
        $ausgabe .= "# ***************************************************************************************************************\r\n";
        $ausgabe .= "#\r\n";


  for ($i = 1; $i < (intval($_POST['datensaetze'])+1); $i++) {

  ersetze_pipe($i);  // pipe "|" ersetzen
  $ausgabe .= $I_Sep.$_POST["Nummer".$i].$I_Sep."U".$I_Sep.$I_Sep;
  if ($_POST["Firma".$i]==''){
  $ausgabe .= $_POST["nachname".$i]." ".$_POST["vorname".$i].$I_Sep.$I_Sep.$I_Sep.$I_Sep;
  } else {
  $ausgabe .= $_POST["Firma".$i].$I_Sep.$I_Sep.$I_Sep.$_POST["nachname".$i]." ".$_POST["vorname".$i].$I_Sep;
  }
  $ausgabe .= $_POST["street".$i].$I_Sep.$_POST["hausnr".$i].$I_Sep.$I_Sep.$I_Sep.$_POST["plz".$i].$I_Sep.$_POST["city".$i].$I_Sep.$_POST["country_code".$i].$I_Sep;
  $ausgabe .= $I_Sep;
  if ($_POST["telefon_ok"]=='telefon_ok') {$ausgabe .= $_POST["telefon".$i];};


  $ausgabe .= $I_Sep.$I_Sep;
  if ($_POST["email_ok"]=='email_ok') {$ausgabe .= $_POST["Email".$i];};

  $ausgabe .= str_repeat($I_Sep,12);


/*  Unterschied von Format 4.0 und 5.0
|1|U||Testfirma|||Kontakt|Rheinstrasse|19|||77815|B�hl|DE||07223||||||||||||||       //version 4.0
|1|U||Testfirma|||Kontakt|Rheinstrasse|19|||77815|B�hl|DE||07223|||||||||||||||||||  //version 5.0*/
  if ($_POST["version"]==$action_intraship_5_0) {$ausgabe .= str_repeat($I_Sep,6);} else {$ausgabe .= str_repeat($I_Sep,1);};

  $ausgabe .= "\r\n";
  };


  Schreibe_Uebergabedatei($ausgabe, $filename);
  xtc_db_query("update ".TABLE_ORDERS." set intraship_export_success = 1 ".$_POST["suchstring"]);

  exit;
  break;


}


  switch ($_GET['action']) {
    case $action_lexware:
      $Auswahlliste = array($_REQUEST['eoID']);
      if ((count($Auswahlliste, COUNT_RECURSIVE)==2) && ($Auswahlliste[0][0]=='') ) {
        unset ($_GET['action']);
        xtc_redirect(xtc_href_link(FILENAME_EXPORTINTRALEX, ''));
        break;
      }
$Suchliste = array();
$WoS = ' or o.orders_id =';
$Suchstring = ' where (o.orders_id =';
foreach($Auswahlliste as $v1) {
    foreach ($v1 as $v2) {
        if ($v2 != '') {
        $Suchliste[] = $v2;
        $Suchstring .= $v2.$WoS;
        }
    }
}
$Suchstring = rtrim($Suchstring, $WoS).')';
$customer = array();

    	                    $orders_export_query = xtc_db_query("SELECT
    	                                                              o.billing_firstname,
    	                                                              o.billing_lastname,
                                                                    o.customers_email_address,
                                                                    o.billing_company,
                                                                    o.billing_street_address,
                                                                    o.billing_city,
                                                                    o.billing_postcode,
                                                                    o.billing_state,
                                                                    o.billing_country,
                                                                    o.delivery_firstname,
                                                                    o.delivery_lastname,
                                                                    o.delivery_company,
                                                                    o.delivery_country,
                                                                    o.delivery_postcode,
                                                                    o.delivery_city,
                                                                    o.delivery_street_address,
                                                                    o.customers_telephone,
                                                                    o.customers_vat_id
                                                                    FROM ".TABLE_ORDERS." o ".$Suchstring);





//       $filename = 'Onlineshop_Kunden_Neu'.'.txt';
        $filename = 'Kundenliste_Lexware_'.date("j_n_Y").'.txt';
        $ausgabe = "Vorname".$L_Sep."Name".$L_Sep."EMAIL".$L_Sep.
                   "Firma".$L_Sep."Stra�e".$L_Sep."Ort".$L_Sep."Postleitzahl".$L_Sep.
                   "Bundesland".$L_Sep."Land".$L_Sep."Telefon".$L_Sep."Anrede".$L_Sep;

       $ausgabe .= "Sammelkonto".$L_Sep."Debitorenkonto".$L_Sep."Matchcode".$L_Sep;

       $ausgabe .= "Liefer. Zusatz".$L_Sep."Liefer.Ansprechpartner".$L_Sep."Liefer. Strasse".$L_Sep."Liefer. PLZ".$L_Sep."Liefer. Ort".$L_Sep."Liefer.Land";
       $ausgabe .= $L_Sep."EG ID".$L_Sep."steuerbare Ums�tze";

       $ausgabe .= "\r\n";

while ($customer = xtc_db_fetch_array($orders_export_query)) {

// Semikolon ersetzen durch Komma (in Lexware wird das Semikolon als Feldtrenner verwendet)
foreach($customer as $key => $val) {
  $customer[$key] = str_replace(";",",",$val);
  }
//
     /*if ($customer['billing_company']=="") {
        if ($customer['customers_gender']=="f") {$customer['customers_gender']="Frau";}
        if ($customer['customers_gender']=="m") {$customer['customers_gender']="Herr";}
        } else {$customer['customers_gender']="";};*/
        $customer['customers_gender']="";
//        $customer['billing_firstname']=ucwords($customer['billing_firstname']);
//        $customer['billing_lastname']=ucwords($customer['billing_lastname']);
        $customer['billing_street_address']=ucwords($customer['billing_street_address']);
        $customer['billing_city']=ucwords($customer['billing_city']);

//        foreach($customer as $werte_a1){
//        }

      $ausgabe .= ucwords($customer['billing_firstname']).$L_Sep.ucwords($customer['billing_lastname']).$L_Sep;
      $ausgabe .= $customer['customers_email_address'].$L_Sep.$customer['billing_company'].$L_Sep;
      $ausgabe .= $customer['billing_street_address'].$L_Sep.$customer['billing_city'].$L_Sep;
      $ausgabe .= $customer['billing_postcode'].$L_Sep.$customer['billing_state'].$L_Sep;
      if ($customer['billing_country']=='Germany') {
          $ausgabe .= $L_Sep;
        } else {
          $ausgabe .= $customer['billing_country'].$L_Sep;
      }


      $ausgabe .= $customer['customers_telephone'].$L_Sep.$customer['customers_gender'].$L_Sep;
      if (isset($_GET['Debitorenkonto']) && ($_GET['Debitorenkonto'])=='Debitorenkonto') {$ausgabe .= "J";} else {$ausgabe .= "N";};
      $ausgabe .= $L_Sep;

      $ausgabe .= Set_Debitorenkonto($customer['billing_lastname']).$L_Sep;
//      $ausgabe .= strtolower($customer['billing_lastname']).strtolower($customer['billing_firstname']).ucwords($customer['billing_postcode']).$L_Sep;
      $ausgabe .= ucwords($customer['billing_lastname'])."/".ucwords($customer['billing_firstname'])."/".$customer['billing_city'].$L_Sep;



/*      Ueberpruefen, ob Rechnungs- und Lieferadresse identisch sind */
        // Wenn Firma abweicht, wird Firma �betragen. Wenn Namen abweichen, werden Namen �bertragen
      if ($customer['delivery_company'] == $customer['billing_company']) {
            $ausgabe .= $L_Sep;
      } else {
            $ausgabe .= "c/o ".$customer['delivery_company'].$L_Sep;
      };
      if (($customer['delivery_firstname'] == $customer['billing_firstname'])
       && ($customer['delivery_lastname'] == $customer['billing_lastname'])) {
                   $ausgabe .= $L_Sep;
        }  else {
            $ausgabe .= "c/o ".$customer['delivery_firstname']." ".$customer['delivery_lastname'].$L_Sep;
      };
      /*Ueberpruefung Rechnungsnamen/Liefernamen ENDE*/

      $ausgabe .= $customer['delivery_street_address'].$L_Sep.$customer['delivery_postcode'].$L_Sep.$customer['delivery_city'].$L_Sep;

          if ($customer['delivery_country']=='Germany') {
          $ausgabe .= $L_Sep;
          } else {
          $ausgabe .= $customer['delivery_country'].$L_Sep;
          }

      // Umsatsteuer ID
      $ausgabe .= strtoupper($customer['customers_vat_id']).$L_Sep;
      if ($customer['customers_vat_id'] != "") {
        if (strpos(strtoupper($customer['customers_vat_id']), "DE") !== false) // wenn ust-id nicht DE, dann umsatzsteuerfreie innergemeinschaftiche Lieferung
           { $ausgabe .= "J";} else { $ausgabe .= "N";}
      } else {
        $ausgabe .= "J"; //wenn keine ust-id, dann immer steuerbare umsaetze
      }

      $ausgabe .= $L_Sep;

      $ausgabe .= "\r\n";

}

  Schreibe_Uebergabedatei($ausgabe, $filename);

$Suchliste = array();
$WoS = ' or orders_id =';
$Suchstring = ' where orders_id =';
foreach($Auswahlliste as $v1) {
    foreach ($v1 as $v2) {
//        echo "$v2\n";
        if ($v2 != '') {
        $Suchliste[] = $v2;
        $Suchstring .= $v2.$WoS;
        }
    }
}
$Suchstring = rtrim($Suchstring, $WoS);

    xtc_db_query("update ".TABLE_ORDERS." set lexware_export_success = 1 ".$Suchstring);



    break;



// -----------------------------------------------------------------------------------------//


 case $action_intraship_5_0:
 case $action_intraship_4_0:
       $Auswahlliste = array($_REQUEST['eoID']);
      if ((count($Auswahlliste, COUNT_RECURSIVE)==2) && ($Auswahlliste[0][0]=='') ) {
        $messageStack->add('Fehler: Keine Bestellung ausgew&auml;hlt!', 'error');
        unset ($_GET['action']);
        xtc_redirect(xtc_href_link(FILENAME_EXPORTINTRALEX, ''));
        break;
      }

  Display_Doctype();
 ?>
</head>
<body>

</head>

<?php
require (DIR_WS_INCLUDES.'head.php');
require (DIR_WS_INCLUDES . 'header.php');
?>

<!-- header //-->


<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <!-- body_text //-->
    <td width="100%" valign="top"><table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <noscript><tr><td class="smallText">Hinweis: Voller Funktionsumfang nur bei aktiviertem Javascript !!</td></tr></noscript>
              <tr>
                <td class="pageHeading"><?php echo 'Export von Lieferadressen nach '.$_GET['action'].'.TXT'; ?></td>
              </tr><tr>
                <td class="smallText"><?php echo 'Bitte &uuml;berpr&uuml;fen Sie die Adressen, insbesondere die Auftrennung von <b>Stra&szlig;e und Hausnummer</b>'; ?></td>
              </tr>
            </table></td>
        </tr>
        <!-- first ends // -->
        <tr>
          <td><table border="0" style="font-family:tahoma;font-size:11px;" width="100%" cellspacing="5" cellpadding="5">
              <tr>
                <td>

                 <form action="<?php echo $_SERVER['PHP_SELF']; ?>" name="EDIT_DELIVERY" method="POST" enctype="multipart/form-data">
                 <input type="hidden" name="version" value="<?php echo $_GET['action']; ?>">
<?php

      $Auswahlliste = array($_REQUEST['eoID']);
      if ((count($Auswahlliste, COUNT_RECURSIVE)==2) && ($Auswahlliste[0][0]=='') ) {
        unset ($_GET['action']);
        xtc_redirect(xtc_href_link(FILENAME_EXPORTINTRALEX, ''));
        break;
      }
$Suchliste = array();
$WoS = ' or orders_id =';
$Suchstring = ' where orders_id =';
foreach($Auswahlliste as $v1) {
    foreach ($v1 as $v2) {
        if ($v2 != '') {
        $Suchliste[] = $v2;
        $Suchstring .= $v2.$WoS;
        }
    }
}
$Suchstring = rtrim($Suchstring, $WoS);
$delivery = array();

    	                    $orders_deliver_query = xtc_db_query("SELECT orders_id,
                                                                    customers_firstname,
                                                                    customers_lastname,
                                                                    customers_company,
                                                                    customers_postcode,
                                                                    customers_city,
                                                                    customers_street_address,
                                                                    customers_telephone,

                                                                    delivery_firstname,
                                                                    delivery_lastname,
                                                                    delivery_company,
                                                                    delivery_country_iso_code_2,
                                                                    delivery_postcode,
                                                                    delivery_city,
                                                                    delivery_street_address,
                                                                    customers_email_address
                                                                    FROM ".TABLE_ORDERS.$Suchstring." order by orders_id DESC");
$x=0; $y=0;


while ($delivery = xtc_db_fetch_array($orders_deliver_query)) {
  $x++;

echo '<tr bgcolor="';
if($x%2==0){ echo '#ffffb4'; } else {echo '#c3e4fd';};
echo '"><td> ';
?>

<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<tr>
		<td>
		<?php echo 'Kunde: '.$delivery['customers_firstname'].' '.$delivery['customers_lastname'].', '.$delivery['customers_street_address'].', '.$delivery['customers_postcode'].' '.$delivery['customers_city']; ?>
    </td></tr></table><table><tr>

    <td>

			<table border="0" width="100%" cellspacing="2" cellpadding="2">
				<tr>
					<td align="right">LfdNr:</td>
					<td align="left">
						<?php echo '<input name="Nummer'.$x.'" type="text" size="5" maxlength="5" value="'.$x.'">'; ?>
					</td>
				</tr>
				<tr>
					<td align="right">Firma:</td>
					<td align="left">
						<?php echo '<input name="Firma'.$x.'" type="text" size="30" maxlength="30" value="'.$delivery['delivery_company'].'">'; ?>
					</td>
				</tr>
				<tr>
					<td align="right">Vorname:</td>
					<td align="left">
						<?php echo '<input name="vorname'.$x.'" type="text" size="30" maxlength="30" value="'.ucwords($delivery['delivery_firstname']).'">'; ?>
					</td>
				</tr>
				<tr>
					<td align="right">Nachname:</td>
					<td align="left">
						<?php echo '<input name="nachname'.$x.'" type="text" size="30" maxlength="30" value="'.ucwords($delivery['delivery_lastname']).'">'; ?>
					</td>
				</tr>
				<tr>
					<td align="right">E-Mail:</td>
					<td align="left">
						<?php echo '<input name="Email'.$x.'" type="text" size="30" maxlength="30" value="'.$delivery['customers_email_address'].'">'; ?>
					</td>
				</tr>
				<tr>
					<td align="right">Telefon:</td>
					<td align="left">
						<?php echo '<input name="telefon'.$x.'" type="text" size="30" maxlength="30" value="'.$delivery['customers_telephone'].'">'; ?>
					</td>
				</tr>


			</table>
		</td>
		<td>&emsp;</td>
		<td>
			<table border="0" width="100%" cellspacing="2" cellpadding="2">

<?php //Strasse und Hausnummer trennen ANFANG
$token = str_replace ('.', '. ', $delivery['delivery_street_address']);
$token = addslashes($token);
$token = str_replace ('strasse', 'str.', $token);
$token = str_replace ('stra�e', 'str.', $token);
$token = str_replace ('Stra�e', 'Str.', $token);
$token = str_replace ('Strasse', 'Str.', $token);
$token = utf8_str_word_count(htmlentities ($token), 1, '0123456789.&auml;&ouml;&uuml;&Auml;&Ouml;&Uuml;\\/');
$Strasse=''; $Hausnr=''; $HausnrModus=false;
foreach($token as $v1) {
  if (strspn ($v1, '0123456789')!= 0) {$HausnrModus = true;};
  if ($HausnrModus==true) {$Hausnr .= $v1.' ';} else {$Strasse .= $v1.' ';};
};

$Hausnr = trim($Hausnr);
$token = trim($Hausnr, '0123456789');
if ((strlen($token))>2) {$Achtung = true;} else {$Achtung = false;};
$Strasse = trim(html_entity_decode($Strasse));
// Strasse und Hausnummer trennen ENDE
?>


        <?php if ($Achtung) {echo '<tr bgcolor="red">';} else {echo '<tr>';};
         ?>
					<td align="right">Stra&szlig;e:</td>
					<td align="left">
						<?php echo '<input name="street'.$x.'" type="text" size="40" maxlength="40" value="'.ucwords($Strasse).'">'; ?>
					</td>
				</tr>
				<?php if ($Achtung) {echo '<tr bgcolor="red">';} else {echo '<tr>';}; ?>
					<td align="right">Hausnummer:</td>
					<td align="left">
						<?php echo '<input name="hausnr'.$x.'" type="text" size="40" maxlength="10" value="'.$Hausnr.'">'; ?>
					</td>
				</tr>
				<tr>
					<td align="right">Postleitzahl:</td>
					<td align="left">
						<?php echo '<input name="plz'.$x.'" type="text" size="12" maxlength="12" value="'.$delivery['delivery_postcode'].'">'; ?>
					</td>
				</tr>
				<tr>
					<td align="right">Ort:</td>
					<td align="left">
						<?php echo '<input name="city'.$x.'" type="text" size="50" maxlength="50" value="'.ucwords($delivery['delivery_city']).'">'; ?>
					</td>
				</tr>
				<tr>
					<td align="right">L&auml;ndercode:</td>
					<td align="left">
						<?php echo ' <input name="country_code'.$x.'" type="text" size="2" maxlength="2" value="'.$delivery['delivery_country_iso_code_2'].'">'; ?>
					</td>
				</tr>
				<tr>
					<td align="right">&nbsp;</td>
					<td align="left">
						<?php echo '<input name="oID'.$x.'" type="hidden" size="20" maxlength="20" value="Bestellnr: '.$delivery['orders_id'].'">'; ?>
					</td>
				</tr>
			</table>
		</tr>
	</tr>
</table>




<?php

}

?>
<input type="hidden" name="datensaetze" value="<?php echo $x; ?>">
                    <table border="0" style="font-size:11px;" cellpadding="3">
                      <tr>

                        <td>

                        <?php /* echo xtc_draw_input_field('ups_tracking_id', $order->info['ups_tracking_id'] ); */ ?>

                        </td>

                        </tr>
                    </table>
                      <tr><td><?php
                        echo '<input type="checkbox" name="email_ok" value="email_ok">E-Mail Adresse(n) auch exportieren<br />';
                        echo '<input type="checkbox" name="telefon_ok" value="telefon_ok">Telefonnummer(n) exportieren';
                      //<BUTTON name="action" value="lexware_Progress" type="submit" onClick="return confirm('Haben Sie auch alle Daten &uuml;berpr&uuml;ft ?')" >Export starten</BUTTON>
                      ?>
                      <BUTTON name="action" value="lexware_Progress" type="submit" >Export starten</BUTTON>
                      <input type="hidden" name="suchstring" value="<?php echo $Suchstring; ?>">
                      </td></tr>

                  </form></td>

              </tr>
            </table><div style="font-size:11px;"><?php echo 'Autor:<br />'.URHEBER; ?></div></td>
        </tr>
      </table></td>
  </tr>
</table>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');



  break;


 default:
  Display_Doctype();
?>
<style type="text/css">
.columnLeft[disabled] {
    background-color: #ffffb4;
    border-bottom-color: #000000;
    border-bottom-style: solid;
    border-bottom-width: 1px;
    color: #000000;
}
</style>

<body>
<!-- header //-->
<script type="text/javascript"><!--

	function select_all(n){
	  document.getElementById("eoID").style.display = "none";
    for (var i=0; i < document.getElementById("eoID").options.length;i++){document.getElementById("eoID").options[i].selected = n;}
    document.getElementById("eoID").style.display = "block";
    if (n==0) {document.getElementById("eoID").options[0].selected = 1;};
    if (n==1) {document.getElementById("eoID").options[0].selected = 0;};
  }
	function mehr_Zeilen(n){if (n==0) {
	                        document.getElementById("eoID").size = document.getElementById("eoID").size+10;} else {document.getElementById("eoID").size = document.getElementById("eoID").size-10;}
                          if (document.getElementById("eoID").size < 30) {document.getElementById("eoID").size = 20;}
                         }

  function Pruefe_Intra_Auswahl() {
    var y=0;
    for (var i=0;  i < document.getElementById("eoID").length; i++) {if(document.getElementById("eoID")[i].selected == 1) {y++};};
    if ((document.getElementById("eoID")[0].selected == 1) && (y==1)) { alert("Sie haben keine Bestellung ausgew"+unescape("%E4")+"hlt !!\n� Martin H�upler"); return false;}
  };
  function Pruefe_LEX_Auswahl() { var y=0; LexPlan=unescape("<?php echo rawurlencode($Deb_Kontenplan."\n� Martin H�upler");?>");
  DKonto=unescape("<?php echo rawurlencode($Deb_No_Kontenplan."\n� Martin H�upler");?>");

    if (document.getElementsByName("Debitorenkonto")[0].checked == true)  {DKonto = LexPlan};
    for (var i=0;  i < document.getElementById("eoID").length; i++) {if(document.getElementById("eoID")[i].selected == 1) {y++};};
    if ((document.getElementById("eoID")[0].selected == 1) && (y==1)) { alert("Sie haben nichts ausgew"+unescape("%E4")+"hlt !!\n� Martin H�upler"); return false;}
    else {if(document.getElementById("eoID")[0].selected == 1) {y=y-1}; return confirm(y + " x Adressdaten nach Lexware exportieren ?\n"+DKonto)}
  }
  function select_lex_Export(n) {
    var lvar = 0;
    document.getElementById("eoID").style.display = 'none';
    document.getElementById("eoID").options[0].selected = 0;
    for (var i=1; i < document.getElementById("eoID").options.length;i++) {
      if (document.getElementsByName("lexpsuc"+i)[0].value == 0) {document.getElementById("eoID").options[i].selected = 1; lvar++;}
      else {document.getElementById("eoID").options[i].selected = 0;};
      }
    document.getElementById("eoID").style.display = 'block';
    alert(lvar+" Bestellung(en) zum Export nach Lexware ausgew"+unescape("%E4")+"hlt. ");
//    lvar = document.getElementById("eoID").style.display;
//    alert(lvar);
  }
  function select_intra_Export(n) {
    var lvar = 0;
    document.getElementById("eoID").style.display = 'none';
    document.getElementById("eoID").options[0].selected = 0;
    for (var i=1; i < document.getElementById("eoID").options.length;i++) {
      if (document.getElementsByName("iexpsuc"+i)[0].value == 0) {document.getElementById("eoID").options[i].selected = 1; lvar++;}
      else {document.getElementById("eoID").options[i].selected = 0;};
      }
    document.getElementById("eoID").style.display = 'block';
    alert(lvar+" Bestellung(en) zum Export nach intraship ausgew"+unescape("%E4")+"hlt.");
  }
//                         }
//--></script>

<?php
require (DIR_WS_INCLUDES.'head.php');
require (DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<!-- body //-->


<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>

    <!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <noscript><tr><td class="smallText">Hinweis: Voller Funktionsumfang nur bei aktiviertem Javascript !!</td></tr></noscript>
              <tr>
                <td class="pageHeading"><?php echo UEBERSCHRIFT1; ?></td>
              </tr><tr>
                <td class="pageContent"><?php echo CONTENT1; ?></td>
              </tr>
            </table></td>
        </tr>
        <!-- first ends // -->
        <tr>
          <td><table border="0" style="font-family:tahoma;font-size:11px;" width="100%" cellspacing="2" cellpadding="2">
              <tr>
                <td>

                 <form action="<?php echo $_SERVER['PHP_SELF']; ?>" name="SELECT_PRODUCT" method="GET" >
                    <table border="0" style="font-size:11px;" cellpadding="3">
                      <tr>

                        <td><input type="button" onClick="select_all(1);" value="Alle Bestellungen ausw&auml;hlen" />
                            <input type="button" onClick="select_all(0);" value="Alle Bestellungen abw&auml;hlen" />
                            <?php echo CONTENT2 ?></td>

                        </tr>
                      <tr>

                        <td>

<?php
  if (!isset($lang)) $lang=$_SESSION['languages_id'];
  $Bestellstatus = '<select name="status" size="1" onChange="top.location.href=\'exportintralex.php?status=\'+this.form.status.options[this.form.status.selectedIndex].value"><option value="">Alle Bestellungen anzeigen</option>';
  $orders_status_query = xtc_db_query("select orders_status_id, orders_status_name from ".TABLE_ORDERS_STATUS." where language_id = '".$lang."'");
  while ($orders_status = xtc_db_fetch_array($orders_status_query)) {
  $Bestellstatus .= '<option value="'.$orders_status['orders_status_id'].'" ';
    if ($_GET['status'] == $orders_status['orders_status_id']) { $Bestellstatus .= 'selected';}
    $Bestellstatus .= '>'.$orders_status['orders_status_name'].'</option>';
  }
  $Bestellstatus .= '<option value="lex0" ';
    if ($_GET['status'] == 'lex0') { $Bestellstatus .= 'selected';}
    $Bestellstatus .= '>offene Lexware Exporte</option>';
  $Bestellstatus .= '<option value="intra0" ';
    if ($_GET['status'] == 'intra0') { $Bestellstatus .= 'selected';}
      $Bestellstatus .= '>offene Intraship Exporte</option>';

  $AnzeigenAbfrage = ($_GET['status'] ? ' where orders_status='.$_GET['status'] : '');
  if ($_GET['status'] == 'lex0') {$AnzeigenAbfrage = ' where lexware_export_success = 0';}
  if ($_GET['status'] == 'intra0') {$AnzeigenAbfrage = ' where intraship_export_success = 0';}

  echo $Bestellstatus.'</select>&nbsp;';
?>


                            <input type="button" onClick="select_lex_Export(1);" value="offene Lexware Exporte anw&auml;hlen" />
                            <input type="button" onClick="select_intra_Export(1);" value="offene IntraShip Exporte anw&auml;hlen" />
                            angew&auml;hlte Bestellungen werden farblich unterlegt.
                        </tr>
                        <tr><td>
                          <?php
    	                    $orders_list_query = xtc_db_query("SELECT orders_id,
                                                                    date_purchased,
                                                                    lexware_export_success,
                                                                    intraship_export_success,
                                                                    customers_firstname,
                                                                    customers_lastname,
                                                                    customers_company,
                                                                    customers_postcode,
                                                                    customers_city,
                                                                    customers_street_address,
                                                                    customers_email_address
                                                                    FROM ".TABLE_ORDERS.$AnzeigenAbfrage." ORDER BY orders_id DESC LIMIT ".$max_bestellanzeige);

   							$orders_list_array = array();
							$orders_list_array[] = array('id' => '" class="columnLeft" disabled="disabled', 'text' => 'Bestellnr&nbsp;&nbsp;&nbsp;Datum &nbsp;&nbsp;&#x2502;Lexw Intr&#x2502; Adresse');
							  $lex_list_array = array();
							  $intra_list_array = array();
   						     $zaehler = 0;
                /*   ? >
<select name="eoID[]" multiple="multiple" id="eoID" size="20" style="font-size:12px;font-family:Courier New" title="">Auswahlliste">
<option value="" class="columnLeft" disabled="disabled">Bestellnr&#x2502; &nbsp;Datum &nbsp;&nbsp;&#x2502;Lexw&#x2502;Intr&#x2502; Adresse</option>
                   < ?php  */
                   while ($orders_list = xtc_db_fetch_array($orders_list_query)) {



  						      $Lexw = '&nbsp;&nbsp;&nbsp;';
   						      $Intra = '&nbsp;&nbsp;&nbsp;';

                   $email_list_query = xtc_db_query("SELECT count(customers_email_address) as Anzahl, count(distinct lexware_export_success) as Anzahl2 FROM orders WHERE customers_email_address = '".$orders_list['customers_email_address']."'");
                   $email_list = xtc_db_fetch_array($email_list_query);
                   if ($email_list['Anzahl'] > 1) {$Lexw = '&nbsp;&#133;&nbsp;';}
                   if ($email_list['Anzahl2'] > 1) {$Lexw = '&nbsp;&harr;&nbsp;';}
/*
    	                    $email_list_query = xtc_db_query("SELECT count(*) as Anzahl from ".TABLE_ORDERS." where customers_email_address = '".$orders_list['customers_email_address']."'");
                          $email_list = xtc_db_fetch_array($email_list_query);
                    if ($email_list['Anzahl'] > 1) {$Lexw = '&nbsp;&#133;&nbsp;';}
    	                    $email_list_query = xtc_db_query("SELECT count(*) as Anzahl from ".TABLE_ORDERS." where customers_email_address = '".$orders_list['customers_email_address']."' and lexware_export_success = 1");
                          $email_list = xtc_db_fetch_array($email_list_query);
                    if ($email_list['Anzahl'] > 0) {$Lexw = '&nbsp;&harr;&nbsp;';}
//                    echo '&harr;&#133;';
*/
   						      if ($orders_list['lexware_export_success'] == '1') {$Lexw = '&nbsp;&radic;&nbsp;';}
                    if ($orders_list['intraship_export_success'] == '1') {$Intra = '&nbsp;&#8730;&nbsp;';}
                       $zaehler++;
                       $lex_list_array[] = '<input type="hidden" name="lexpsuc'.$zaehler.'" value="'.$orders_list['lexware_export_success'].'" disabled>';
                       $intra_list_array[] = '<input type="hidden" name="iexpsuc'.$zaehler.'" value="'.$orders_list['intraship_export_success'].'" disabled>';
/*                       echo '<input type="hidden" name="lexpsuc'.$zaehler.'" value="'.$orders_list['lexware_export_success'].'">
                       ';

                       echo '<input type="hidden" name="iexpsuc'.$zaehler.'" value="'.$orders_list['intraship_export_success'].'">
                       ';
*/
 //                   $Bestellnr = $orders_list['orders_id'];
                    $Bestellnr = sprintf("%'?-8s\n",   $orders_list['orders_id']);
                    $Bestellnr = str_replace('?', '&nbsp;',$Bestellnr);
                    $Markierung='';
//                    $Markierung='" style="color:blue;';
//                     if (($orders_list['lexware_export_success'] == '1')&&($orders_list['intraship_export_success'] == '1')){$Markierung='" style="color:grey;';}
                    if ($zaehler==1){$Markierung ='" selected="selected';}

//                     $Markierung='" selected="selected';
   					        $orders_list_array[] = array('id' => $orders_list['orders_id'].$Markierung,

                                                 'text' => $Bestellnr." |".
                                                 xtc_date_short($orders_list['date_purchased']). "&#x2502;&nbsp;".
                                                 $Lexw.'&#x2502; '.$Intra.'&#x2502; '.


                                                 $orders_list['customers_firstname']. " ".
                                                 $orders_list['customers_lastname']. ", ".
                                                 $orders_list['customers_company']. ", ".
                                                 $orders_list['customers_street_address']. ", ".
                                                 $orders_list['customers_postcode']. " ".
                                                 $orders_list['customers_city']


                                                 );



							}

              echo xtc_draw_pull_down_menu('eoID[]', $orders_list_array, '', 'multiple="multiple" id="eoID" size="20" style="font-size:12px;font-family:Courier New"', false, false) . '&nbsp;';
?>

			  			</td>
             </tr>
            </table>
            <table>

<tr>
                        <td>
                        <BUTTON name="action" value="<?php echo $action_lexware;?>" <?php if ($SchnellZuLexwareExport==false){echo 'onclick="return Pruefe_LEX_Auswahl()"';} ?> type="submit" ><?php echo $action_lexware;?></BUTTON>

                        </td>
                        <td>
                         <BUTTON name="action" value="<?php echo $action_intraship_4_0;?>" onclick="return Pruefe_Intra_Auswahl()" type="submit" ><?php echo $action_intraship_4_0;?></BUTTON>
                        </td>
                        <td>
                         &nbsp;
                        </td>



            <td><input type="button" onClick="mehr_Zeilen(0);" value="Ausschnitt vergr&ouml;ssern" /></td>



                      </tr>
                      <tr>
						<td style="font-family:tahoma;font-size:11px;">
            <?php if ($SchnellZuLexwareExport==false){
            echo '<input type="checkbox" name="'.$Deb_Konto_Ist_Null.'" value="'.$Deb_Konto_Ist_Null.'" title="Debitorenkonto beim Export nach Lexware vorbelegen oder nicht." checked="checked">Debitorenkonto';
            }

           ?>
           </td>
                         <td><BUTTON name="action" value="<?php echo $action_intraship_5_0;?>" onclick="return Pruefe_Intra_Auswahl()" type="submit" ><?php echo $action_intraship_5_0;?></BUTTON></td>
                        <td> </td>
            <td><input type="button" onClick="mehr_Zeilen(1);" value="Ausschnitt verkleinern " /></td>

                      </tr>
                      </table>
                    </table>


                  <?php
                  foreach ($lex_list_array as $II) echo $II;
                  foreach ($intra_list_array as $II) echo $II;
                    //echo 'Martin'.$lex_list_array[0];
                  ?>
                  </form></td>

              </tr>
            </table><div style="font-size:11px;"><?php echo 'Autor:<br />'.URHEBER; ?></div>
        </tr>
      </table></td>
  </tr>
</table>
<script type="text/javascript"><!--
document.getElementsByName("eoID[]")[0].focus();
//--></script>

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');


} // Ende der Auswertung von $_GET(['action'])
 ?>
