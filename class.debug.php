<?php

class Debug{

public static function writeDebugLog($methode,$message){
$fh = fopen("chk.txt","a");
fwrite($fh,"\r\n---------------------------------------------------------------------------");
fwrite($fh,"\r\nmessage sent from ".$methode);
fwrite($fh,"\r\n".$message);

fclose($fh);
}


}