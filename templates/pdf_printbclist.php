<?php
include('./phppdflib/phppdflib.class.php');
include("./class.code39.php");

$data = $this->getDataForView();

//Formatparameter
$top = $data['defaults']['top']; //Beginn oben
$margin_left = $data['defaults']['margin_left'];//linker Rand
$fontsize = $data['defaults']['fontsize'];
$lineheight = $data['defaults']['lineheight']; //Höhe der Textzeile;
$cols = $data['defaults']['cols']; //Anzahl der Etiketten pro Zeile
$rows = $data['defaults']['rows']; //Anzahl der Zeilen pro Seite
$rowheight = $data['defaults']['rowheight']; //Etikettenöhe
$colwidth = $data['defaults']['colwidth']; //Ettikettenbreite
$maxpages = $data['defaults']['maxpages'];
if ($data['mode'] == 4) {$maxpages=1;}
$picspace_v = $data['defaults']['picspace_v'];
$textspace_v = $data['defaults']['textspace_v'];
$showcode = $data['defaults']['showcode'];
$scale = $data['defaults']['ratio']; //Groesse des angezeigten Barcodes im Verhältnis zum Original
$textspace_h = $data['defaults']['textspace_h'];
$picwidth = $data['defaults']['picwidth'];//Dimensionen des Bildes
$picheight = $data['defaults']['picheight'];
$show_signatur = ($data['defaults']['signatur'] == true) ? true : false;
$textposh = $start = 0;

$modeText = null;
switch ($data['mode']) {
	case 1:
		$modeText = "allBC";
		foreach ($data['unprinted_barcodes'] as $barc){
		$libraryItem[]= $barc;
		$barcode[] = $barc->getBarcode();
		$info[] = mb_convert_encoding($barc->getTitle(),'UTF-8');
		$author[] = ($show_signatur==true) ? mb_convert_encoding($barc->getAuthor(),'UTF-8')." (".mb_convert_encoding($barc->getSignature(),'UTF-8').")" : mb_convert_encoding($barc->getAuthor(),'UTF-8');
		}
		$printdate=date('Ymd');
		break;
	case 2:
		$seitentitel = $data['group']; 
		foreach($data['customers'] as $cust){
		$barcode[] = $cust->getBarcode();
		$info[] = $cust->getFullName();
		$author[] = $cust->getForm();
		}
		$modeText = "customerList";
		break;
	case 3:
		//print individually entered barcodes
		$seitentitel="Barcodedruck - einzelne Barcodes";
		$modeText = "singleBC";
		$barcs = $data['entered_barcodes'];
		foreach($barcs as $barc) {
		$barcode[] = ($barc != null) ? $barc->getBarcode() : null;
		$info[] = ($barc != null) ? mb_convert_encoding($barc->getTitle(),'UTF-8') : "";
		if ($barc == null) {
			$author[] = "";	
			} else {
			$author[] = ($show_signatur==true) ? mb_convert_encoding($barc->getAuthor(),'UTF-8')." (".mb_convert_encoding($barc->getSignature(),'UTF-8').")" : mb_convert_encoding($barc->getAuthor(),'UTF-8');
			}
		}
		break;
	case 4:
		$modeText = "Test";
	}

//Ausdruckerzeugen

//PDF Dokument Ausgabe


// Starts a new pdffile object
$pdf = new pdffile;

/* Use the defaults system to turn off page
 * margins
 */
$pdf->set_default('margin', 0);
$page = $pdf->new_page("a4");
$param["fillcolor"] = $pdf->get_color('#000000');

if ($modeText == "Test"){
	//WHY NOT PUTTING THIS INTO CONTROLLER
	//Abspeichern der übergebenen Werte
	if ($_POST['new'])
	{//Neues Formular anlegen
	mysql_query("INSERT INTO $barc_forms_tbl (`bfNr`) VALUES ('')");
	$form=mysql_insert_id();
	}
	//Lesen der Daten per POST
	//Ermitteln der Feldnamen
	$result=mysql_query("SELECT name, top ,margin_left ,fontsize ,lineheight ,
	cols ,rows ,colwidth ,rowheight ,picspace_v ,
	textspace_v ,textspace_h,picwidth,picheight,showcode ,ratio,signatur 
	FROM $barc_forms_tbl 
	WHERE bfNr=1");
	$row=mysql_fetch_array($result);
	for ($a=0;$a<mysql_num_fields($result);$a++)
	{
	$field=mysql_field_name($result,$a);
	$$field=$_POST[$field];
	mysql_query("UPDATE $barc_forms_tbl SET $field=$_POST[$field] where bfNr=$form");
	
	$testbc="1234567891011";
	$testinfo="Testbarcode";
	$testautor="Testbarcode";
	}
	

	mysql_free_result($result);
	}



/*
/* Using the ->draw_text() method:
 * We're going to set up the text parameters,
 * first the height
 */


$end=false;
$x = 0;

//Überschrift drucken
if ($modeText == "customerList"){
	//Nur wenn Klassenliste ausgegeben wird
	$param["font"] = "Helvetica-Bold";
	$param["height"] = 12;
	$pdf->draw_text(200, 820, $seitentitel, $page, $param); 
	}


$item_start_v = $top;
$item_start_h = $item_pos_h = $margin_left;
while(!$end && $x < ($rows * $cols * $maxpages)  ){
	for ($r = 0;$r < $rows; $r++ ){
		$item_pos_v = $item_start_v;	
		for ($c = 0;$c < $cols ; $c++ ){
			if ($data['mode'] == 4){
				$barcode[$x] = $testbc;
				$info[$x] = $testinfo;
				if ($show_signatur==true) {$autor[$x] = $testautor."(Signatur)";} else {$autor[$x]=$testautor."($testbc)";}
				}
			if (isset($barcode[$x]) ) {
			//Barcode Datei erzeugen
			$target = './'.$_SESSION['organisation']['database']."/barcode/".$barcode[$x].".jpg";
			$barcodeObject = new Barcode($barcode[$x],$picheight,$picwidth,1);
			$barcodeBinary = $barcodeObject->getCode39Binary();
			unset($barcodeObject);
			//Erzeugt die jeweilige Barcode Datei
			file_put_contents($target, $barcodeBinary);
			//Bild anzeigen
			$fh = fopen($target, "r");
			$filedata = fread($fh, filesize($target));
			fclose($fh);
			$image = $pdf->jfif_embed($filedata);
			$pdf->image_place($image,$item_pos_v + $picspace_v ,$item_pos_h  , $page, array('scale' => $scale, 'rotation' => 0)); //used to be $item_pos_h - $picspace_v
			//Bilddatei wieder löschen
			unlink($target);
			}
						
			if ($modeText == "allBC"){
				//ANPASSEN der Änderungen
				//mysql_query("UPDATE $bestand_tbl SET print=\"$printdate\" WHERE barcode=\"$is_barcode[$x]\""); //KEIN EINTRAG DES DRUCKDATUMS
				$libraryItem[$x]->markPrintedDate();
				}
			//Informationen zum Barcode
			$param["font"] = "Helvetica-Bold";
			$param["height"] = $fontsize;
			$pdf->draw_text($item_pos_h + $textspace_h, $item_pos_v + $textspace_v, $info[$x], $page, $param); //Buchtitel
			$item_pos_v -= $lineheight;
			$param["font"] = "Helvetica";
			$pdf->draw_text($item_pos_h + $textspace_h, $item_pos_v + $textspace_v,  $author[$x], $page, $param); //Autor/Verlag
			$item_pos_v -= $lineheight;
			//managing column and line break
			//writen line by line, column after column
			if ($modeText != "Test" ){		
				if (isset($info[$x+1]) ) {   //used to be $barcode[$x] but due to singlePrint empty barcodes are allowed
					$x++;
					$item_pos_h += $colwidth;//next column
					$item_pos_v = $item_start_v;
					} else {
					$end = true;
					break; //reaching last barcode
					}
				} else	{
				//Testausgabe
				$x++;
				$item_pos_h += $colwidth;//next column
				$item_pos_v = $item_start_v;
				}
		}
		if ($end) {break;} //finishing printing
		$item_start_v -= $rowheight; //next line
		$item_pos_h = $item_start_h;
		
	}
	if (!$end && $x < ($rows*$cols*$maxpages)){
		//new page
		$page = $pdf->new_page("a4");
		$item_start_v = $top;
		$item_start_h = $item_pos_h = $margin_left;
		} 
	}






/* These headers do a good job of convincing most
 * browsers that they should launch their pdf viewer
 * program
 */
header("Content-Disposition: filename=barcodes.pdf");
header("Content-Type: application/pdf");
$temp = $pdf->generate();
header('Content-Length: ' . strlen($temp));

/* You can now do whatever you want with the PDF file,
 * which is returned from a call to ->generate()
 * This example simply sends it to the browser, but
 * there's nothing to stop you from saving it to disk,
 * emailing it somewhere or doing whatever else you want
 * with it (such as email it somewhere or store it in
 * a database field)
 */
echo $temp;


function print_bc($bc)
{

return $target;
}


?>
