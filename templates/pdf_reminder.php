<?php
/****************************************************************
**********Mahnzettel bei Fristüberschreitung*********************
****************************************************************/
$data = $this->getDataForView(); 
$toRemind = $data['toremind'];
$hinweistext = mb_convert_encoding("Laut Kartei hast du das folgende Buch / die folgende CD ausgeliehen:",'UTF-8');
$aufforderung1 = mb_convert_encoding("Offenbar hast du  vergessen, es rechtzeitig  zurückzugeben oder zu verlängern.",'UTF-8');
$aufforderung2 = mb_convert_encoding("Bitte hol dies in den nächsten Tagen nach! Danke!",'UTF-8');
$repeatedText = (isset($data['repeatedReminder'])) ? "wiederholte Erinnerung!": null;

//Mahnung ist gedruckt markieren


/*
foreach ($toRemind as $reminder){
	echo '<br>'.$reminder['customer']->getFullName();
		foreach($reminder['items'] as $item) {
		echo '<br>'.$item['item']->getTitle()." -- ".$item['item']->getItemStatus()['duedate'];
		}
	}
die;
*/

//PDF Dokument Ausgabe
include('./phppdflib/phppdflib.class.php');

// Starts a new pdffile object
$pdf = new pdffile;

/* Use the defaults system to turn off page
 * margins
 */
$pdf->set_default('margin', 0);



$page = $pdf->new_page("a4");

$param["fillcolor"] = $pdf->get_color('#000000');

$start=35;
$umbruch=70;
$textstart=530;
$lineheight=14;




$seitenwechsel=false;

// Versionsanzeige
$param["rotation"]="90";
$textposv=35;
$textposh=15;
$param["height"] = 6;
$param["font"] = "Helvetica";
//$pdf->draw_text($textposh, $textposv, $version, $page, $param);
$param["rotation"]="0";
//Ende Versionsanzeige

/* Using the ->draw_text() method:
  */

$textposv=780;

foreach ($toRemind as $reminder){
       
	if ($seitenwechsel) {$page = $pdf->new_page("a4");$seitenwechsel=false; $textposv=780;    }
	//Kopf
	$param["font"] = "Helvetica-Bold";
	$param["height"] = 14;
	$textposh=100;
	//Novexnet-Logo
	/*$fh = fopen("../images/novexnet.jpg", "r");
	$filedata = fread($fh, filesize("../images/novexnet.jpg"));
	fclose($fh);
	$image = $pdf->jfif_embed($filedata);
	//$placement = $pdf->image_place($image, 536, 290, $page);
	//$pdf->image_place($image, 200, 300, $page);
	$scale=0.8;$v=$textposv-25;$h=$start;
	$pdf->image_place($image,$v ,$h , $page, array('scale' => $scale, 'rotation' => 0));
	*/
	//Headline School
	$pdf->draw_text($textposh, $textposv, $_SESSION['organisation']['name'] , $page, $param);
	$param["font"] = "Helvetica";

	$textposh=470;
	$pdf->draw_text($textposh, $textposv, date('d.m.Y'), $page, $param);
	//Linie
	$param["width"] = 2; // PDF units
	$x[0] = $start;
	$y[0] = $textposv-10;
	$x[1] = 550;
	$y[1] = $textposv-10;
	$pdf->draw_line($x, $y, $page, $param);
	//Kopfende
	$textposv=$textposv-(2*$lineheight);
	$textposh=$start;
	$param["height"] = 14;
	$pdf->draw_text($textposh, $textposv,"Leihfristhinweis für: ".$reminder['customer']->getFullName()." (".$reminder['customer']->getForm().")" , $page, $param);
	$textposv=$textposv-(2*$lineheight);
	$param["height"] = 13;
	$pdf->draw_text($textposh, $textposv, $hinweistext , $page, $param);
	$textposv=$textposv-(2*$lineheight);
	$param["font"] = "Helvetica-Bold";

	foreach($reminder['items'] as $item) {
		//Ausgabe der zu mahnenden Titel für einen Entleiher
		$param["height"] = 10;
		$pdf->draw_text($textposh, $textposv, $item['item']->getTitle().'('.$item['item']->getBarcode().')' , $page, $param);
		$param["height"] = 9;
		 $pdf->draw_text($textposh+410, $textposv, "fällig: ".$item['item']->getItemStatus()['duedate'] , $page, $param);
		 $textposv=$textposv-$lineheight;
        }
	if (isset($repeatedText) ) {
	$param["font"] = "Helvetica-Bold";	
	$param["height"] = 14;
	$textposv=$textposv-$lineheight;
	$pdf->draw_text($textposh, $textposv,$repeatedText , $page, $param);
	}
	$param["font"] = "Helvetica";
	$textposv=$textposv-(3*$lineheight);
	$param["height"] = 14;
	$pdf->draw_text($textposh, $textposv,$aufforderung1 , $page, $param);
	$textposv=$textposv-$lineheight;
	$pdf->draw_text($textposh, $textposv,$aufforderung2 , $page, $param);

	//Linie
	$param["width"] = 2; // PDF units
	$x[0] = $start;
	$y[0] = $textposv-10;
	$x[1] = 550;
	$y[1] = $textposv-10;
	$pdf->draw_line($x, $y, $page, $param);
	$textposv=$textposv-(5*$lineheight);

	if ($textposv<$umbruch) {$seitenwechsel = true;}
		
}


/* These headers do a good job of convincing most
 * browsers that they should launch their pdf viewer
 * program
 */
header("Content-Disposition: filename=example.pdf");
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




?>
