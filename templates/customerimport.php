<?php
/****************************************
***this import routine requires**********
***a file setup to the required order,*** 
***there's no errorcathing***************
****************************************/ 


//Book file to database
$fh = fopen("./import/SusoEdvDaten.csv","r");

$header = fgets($fh);
$headerArr = explode(";",$header);
//create a libraryObject - needed for category values
$library = new Library();

while (!feof($fh)) {
$line = fgets($fh);
$lineArr = explode(";",$line);

//create a LibraryItem
$customer = new Customer(null);

echo $lineArr[0].','.$lineArr[1].','.$lineArr[2].','.$lineArr[3].'<br>';
$customer->constructFromData($lineArr[0],$lineArr[1],$lineArr[2],$lineArr[3]);




	
}
fclose($fh);






?>