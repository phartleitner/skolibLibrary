<?php

$fh = fopen("import.csv","r");
$fi = fopen("importkorr.csv","w");
$title = fgets($fh);
$tArr = explode(";",$title);
$newTitle = "";
for ($x = 0; $x<count($tArr); $x++) {
	$newTitle .= $tArr[$x];
	if ($x == 1) {
	$newTitle .= ";Autor korrigiert";	
	}
	$newTitle .= ";";
	}
echo $newTitle;
echo '<br>';
//$newTitle .= "\n";
fputs($fi,$newTitle);
while (!feof($fh) ) {
$line = fgets($fh);
$newLine = "";
$lArr = explode(";",$line);
for ($x = 0; $x < count($lArr); $x++) {
if ($x == 1) {
	//Autor
	$newLine .= $lArr[$x].";";
	$autorArr = explode(' ',$lArr[$x]);
	for ($y = count($autorArr)-1 ;$y >=0 ; $y--) {
		$newLine .= $autorArr[$y];
		if ($y == count($autorArr)-1) {
			$newLine .= ', ';
			} else {
			$newLine .= ' ';	
			}
		}
	} else {
	$newLine .= $lArr[$x];	
	}
$newLine .= ';';
}
echo $newLine;
echo '<br>';
//$newLine .= "\n";
fputs($fi,$newLine);
}

fclose ($fh);
fclose($fi);

?>