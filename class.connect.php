<?php

require_once("MySQLException.php");

/**
 * Database Connection Klasse
 * erstellt die Verbindung zur Datenbank
 */
class Connection {
    
    /**
     * @var string configuration file
     */
    public static $configFile = "skolibsettings.php";
    /**
     * @var string database ip
     */
    private $server;
    /**
     * @var string database user
     */
    private $user;
    /**
     * @var string database password
     */
    private $pass;
    /**
     * @var string filesystem path
     */
    private $basepath;
    /**
     * @var string download folder
     */
    private $download;
    /**
     * @var \mysqli database connection instance
     */

    private $connID;
    /**
     * @var string database name
     */
    private $database;
    
	public function getDatabase(){
		return $this->database;
	}
	
	
	   
    /**
     * Konstruktor der automatisch die Datenbankverbindung
     * bei Instanzierung aufbaut
     */
    public function __construct() {
		 if(isset($_SESSION['organisation'])) {
			 
			$this->setOrganisationDatabase($_SESSION['organisation']['database'],$_SESSION['organisation']['mysql'],$_SESSION['organisation']['pass']);	
			} else{
			$this->getBasicCredentials();
			}
		Debug::writeDebugLog(__METHOD__,"connected with: ".$this->database);
		$this->connect();
        }
	
	/**
	* getting connection ID - used to be able to real_escape_string
	* @return mysqli id
	*/
	public function getId(){
		return $this->connID;
	}
	
	
	/**
	* setting database
	* @param string
	* @param string
	* @param string
	*/
	public function setOrganisationDatabase($db,$user,$pass){
		$this->database = $db;
		$this->user = $user;
		$this->pass = $pass;
		$org = (isset($_SESSION['organisation'])) ? $_SESSION['organisation']['database'] : "not set yet";
		}
	
    
    
    /**
    * Ermittelt die Serververbindung, benutzer und Passwort zur Basisdatenbank
	*/
    public function getBasicCredentials() {
        $this->server = "localhost";
		$this->user = "root";
		$this->pass = "";
		$this->database = "skolib_basis" ;
		//$this->download = trim($larr[1]);
		//this->basepath = trim($larr[1]);
		
    }
    
    /**
     * returns values from ini
     *
     * @return array(string)
     */
    public function getIniParams() {
        return array("download" => $this->download, "basepath" => $this->basepath);  // could be much more versatile
    }
    
    
    /**
     * Verbindet mit der jeweils benutzten Datenbank
     */
    public function connect() {
        
        $reporting = error_reporting(0);
        ChromePhp::info("Connecting to " . $this->user . "@" . $this->server . ":" . $this->database);
        $mysqli = $this->connID = new \mysqli($this->server, $this->user, $this->pass, $this->database);
	Debug::writeDebugLog(__METHOD__,"attempting connection with: ".$this->user . "@" . $this->server . ":" . $this->database." and ".$this->pass);

        error_reporting($reporting);
        
        if ($mysqli->connect_errno) {
            printf("Connection to database failed: %s\n", $mysqli->connect_error);
            ChromePhp::error("Connection to database failed: " . $mysqli->connect_error);
            exit();
        }
        
        //ChromePhp::logSQL("Connection to database " . $this->user . "@" . $this->server . "/" . $this->database . " successful!");
        
        mysqli_set_charset($mysqli, 'utf8');
    }
    
    
    /**
     * universelle Methode zur Rückgabe von Abfrageergebnissen
     *
     * @param string $query SQL Abfrage
     * @return array[][] welches im ersten Index die Zeile, im Zweiten Index die Spalte bezeichnet
     *  zur Abfrage kann also jeweils im zweiten Index auf die einzelnen Ergebnisse in angegebener
     *  Reihenfolge zurückgegriffen werden
     * @throws MySQLException
     */
    public function selectValues($query) {
        ChromePhp::logSQL("Selecting values: \"$query\" from " . $this->getCaller());
        $mysqli = $this->connID;
        $result = $mysqli->query($query);
        
        if ($result === false) {
            throw new MySQLException($mysqli, $query);
        }
        
        $value = null;
        $anz = $result->field_count;
        $valCount = 0;
        
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            for ($x = 0; $x < $anz; $x++) {
                $value[$valCount][$x] = $row[$x];
            }
            $valCount++;
        }
        $result->free();
        
        return $value;
    }
    
    
    /**
     * Gibt assoziative Arrays zurück
     * Indizes mit Feldnamen
     *
     * @param $query String SQL Query
     * @return array[Feldname][] | null
     * @throws MySQLException
     */
    public function selectAssociativeValues($query) {
        ChromePhp::logSQL("Selecting values:  \"$query\" from " . $this->getCaller());
        $mysqli = $this->connID;
        $result = $mysqli->query($query);
        
        if ($result === false) {
            throw new MySQLException($mysqli, $query);
        }
        
        $assocValue = null;
        $fieldName = null;
        $anz = $result->field_count;
        $valCount = 0;
        
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            for ($x = 0; $x < $anz; $x++) {
                $fieldInfo = $result->fetch_field_direct($x);
                $assocValue[$valCount][$fieldInfo->name] = $row[$x];
            }
            $valCount++;
        }
        
        $result->free();
        
        return $assocValue;
    }
    
    /**
     * gibt ein Array mit den Feldnamen zurück
     *
     * @param string $query
     * @return array
     */
    public function selectFieldNames($query) {
        ChromePhp::logSQL("Selecting FieldNames:  \"$query\" from " . $this->getCaller());
        $fieldNames = array();
        $mysqli = $this->connID;
        if ($result = $mysqli->query($query)) {
            $finfo = $result->fetch_fields();
            foreach ($finfo as $f) {
                $fieldNames[] = $f->name;
            }
            
            return $fieldNames;
        } else {
            return null;
        }
    }
    
    
    /**
     * Universelle Methode zum Einfügen von Daten
     *
     * @param string $query SQL Abfrage
     * @return int Insert ID
     * @throws MySQLException
     */
    public function insertValues($query) {
        ChromePhp::logSQL("Inserting:  \"$query\" from " . $this->getCaller());
        $mysqli = $this->connID;
        $result = $mysqli->query($query);
        
        if ($result === false) {
            throw new MySQLException($mysqli, $query);
        }
        
        return $mysqli->insert_id;
    }
    
    
    /**
     * Führt eine SQL Query durch
     *
     * @param $query String SQL Query
     * @throws MySQLException
     */
    function straightQuery($query) {
        ChromePhp::logSQL("Query:  \"$query\" from " . $this->getCaller());
        
        $mysqli = $this->connID;
        $result = $mysqli->query($query);
        
        if ($result === false) {
            throw new MySQLException($mysqli, $query);
        }
    }
    
    /**
     * Führt eine multi SQL Query durch (Statement1 ; Statement2 ; ...)
     *
     * @param $query
     * @throws MySQLException
     */
    function straightMultiQuery($query) {
        $mysqli = $this->connID;
        $result = $mysqli->multi_query($query);
        
        if ($result === false) {
            throw new MySQLException($mysqli, $query);
        }
        
        while ($mysqli->more_results() && $mysqli->next_result()) ;
    }
    
    /**
     * Schließt die Datenbank Verbindung
     */
    public function close() {
        mysqli_close($this->connID);
    }
    
    
    /**
     * Gibt die genutzte mysqli Klasse zurück
     *
     * @return \mysqli
     */
    public function getConnection() {
        return $this->connID;
    }
    
    
    /**
     * Escapes special characters in a string for use in an SQL statement, taking into account the current charset of the connection
     *
     * @param $string
     * @return string
     */
    function escape_string($string) {
        return $this->connID->real_escape_string($string);
    }


    
    /**
     * Escapes special characters in a string for use in an SQL statement, taking into account the current charset of the connection
     * Will change the given String
     *
     * @param $string
     */
    function escape_stringDirect(&$string) {
        $string = $this->escape_string($string);
    }
    
    /**
     * @return string location the caller method was called from
     */
    function getCaller() {
        $info = debug_backtrace();
        $file = $info[1]['file'];
        $line = $info[1]['line'];
        $method = $info[2]['function'];
        
        return "$file:$line / $method()";
        
    }
    
    
}


?>
