<?php
/****************************************
***this import routine requires**********
***a file setup to the required order,*** 
***there's no errorcathing***************
****************************************/ 


//Book file to database
$fh = fopen("./import/importkorrU8.csv","r");

$header = fgets($fh);
$headerArr = explode(";",$header);
//create a libraryObject - needed for category values
$library = new Library();

while (!feof($fh)) {
$line = fgets($fh);
$lineArr = explode(";",$line);

//create a LibraryItem
$item = new LibraryItem();
$item->constructFromDataEntry($lineArr[0],$lineArr[2],$lineArr[3],$lineArr[4],$lineArr[5],'29',null,null,$library);




	
}
fclose($fh);






?>