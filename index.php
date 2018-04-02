<?php

$fh = fopen("chk.txt","w");
fwrite($fh,"calling index.php time to live:".session_cache_limiter());
fclose($fh);

$input = array_merge($_GET, $_POST);
$DEBUG = true ;//|| isset($input['debug']);
$SQL_DEBUG = true || isset($input['sqldebug']);

/* Debug Classes */
require "ChromePhp.php"; // debugging
/* Utility Classes */
require "class.user.php";
/* Functional Classes */
require "class.connect.php";
require "class.model.php";
require "class.controller.php";
require "class.view.php";
require "class.libraryitem.php";
require "class.debug.php";
require "class.library.php";

if (isset($input['type']) ) {Debug::writeDebugLog(null,"index.php - value of input['type']:" .$input['type']);}
else {Debug::writeDebugLog("index.php","Input is empty");}


if (isset($_SESSION['count'])) {
$_SESSION['count'] = $_SESSION['count'] +1;	
}
else{
$_SESSION['count'] = 1;
}
Debug::writeDebugLog("index.php","Aufruf Nr: ".$_SESSION['count']);

/* Settings */

\ChromePhp::setEnabled($DEBUG);

if ($DEBUG) {
    ini_set("display_errors", true);
    View::$DEBUG = false;
    enableCustomErrorHandler();
}

date_default_timezone_set('Europe/Berlin'); // if not corretly set in php.ini

/* Let's go! */
session_start();
//session_destroy();
//$_SESSION = array();

if (isset($input['destroy'])) {
    session_destroy();
    header("Location: /");
}


$control = new Controller($input);

/**
 * This function will throw Exceptions instead of warnings (better to debug)
 */
function enableCustomErrorHandler() {
    set_error_handler(function ($errno, $errstr, $errfile, $errline) {
        // error was suppressed with the @-operator
        if (0 === error_reporting()) {
            return false;
        }
        
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    });
}

?>