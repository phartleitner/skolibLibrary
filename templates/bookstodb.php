<?php
/****************************************
***this import routine requires**********
***a file setup to the required order,*** 
***there's no errorcathing***************
****************************************/ 


//Book file to database
$fh = fopen("./import/importhistorybooksUTF8.csv","r");

$header = fgets($fh);
$headerArr = explode(";",$header);
//create a libraryObject - needed for category values
$library = new Library();

while (!feof($fh)) {
$line = fgets($fh);
$lineArr = explode(";",$line);

//create a LibraryItem
$item = new LibraryItem();
$item->constructFromDataEntry($lineArr[3],$lineArr[4],$lineArr[0],$lineArr[1],$lineArr[2],'29',$lineArr[6],$lineArr[7],$library);




	
}
fclose($fh);






?>