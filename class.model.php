<?php

class Model {
	
	/**
     * @var Connection
     */
    private $connection;
    /**
     * @var Model
     */
    protected static $model;
    
	/**
     *Konstruktor
     */
    protected function __construct() {
        if ($this->connection == null) {
            $this->connection = new Connection();
			}
		if(!isset($_SESSION['organisation'])) {
		$this->connection->connect();				
		}
		else {
		$this->connection->setOrganisationDatabase($_SESSION['organisation']['database'],$_SESSION['organisation']['mysql'],$_SESSION['organisation']['pass']);
		$this->connection->connect();
		}
        
    }
    /**
	* creating instance of model
	* when exist do nothing
	*/
    static function getInstance() {
		if(self::$model == null ) {
			//Debug::writeDebugLog(__METHOD__,"create first Model");
			self::$model = new Model();
		} 
		
		return self::$model;
		
	}
	
	/**
	* change Connection
	*/
	public function changeConnection(){
		if(isset($_SESSION['organisation'])) {
			$this->connection->setOrganisationDatabase($_SESSION['organisation']['database'],$_SESSION['organisation']['mysql'],$_SESSION['organisation']['pass']);
			Debug::writeDebugLog(__METHOD__,"...connection renewed - database = ".$this->connection->getDatabase());			
			
			$this->connection->close();
			$this->connection->connect();
			
			}
		}
	
	
	/**
	* change connection to Organisation
	* @param string database
	* @param string user
	* @param string pass
	*/
	public function changeToOrganisationDB($db, $user, $pass){
	$this->connection->setOrganisationDatabase($db,$user,$pass);	
	}
	
	
	/**
	* get organisation
	* @return array
	*/
	public function getOrganisationsFromBaseDB(){
		$organisations=array();
		$data = $this->connection->selectAssociativeValues("SELECT * FROM organisations");
		foreach($data as $d){
		$org['value'] = $d['bNr'];
		$org['kurz'] = $d['kurz'];
		$org['name'] = $d['Name'];
		array_push($organisations,$org);
		unset($org);
		}
		return $organisations;
	}
	
	/**
	* get organisation credentials
	* @param int id
	* @return array()
	*/
	public function getOrganisationCredentials($organisationId) {
	Debug::writeDebugLog(__Method__,"getting credentials for organisation ".$organisationId);
	$credentials = array();	
	$data = $this->connection->selectAssociativeValues("SELECT * FROM organisations WHERE bNr=$organisationId");
	if($data) {
		$credentials = array("database"=> $data[0]['kurz'], "name"=>$data[0]['Name'], "ort"=>$data[0]['Ort'],"mysql"=>$data[0]['mysql'],"pass"=>$data[0]['pass']);
	}
	return $credentials;
	
	}
	
	

	/**
	* get User By Login
	* @param string name
	*/
	public function getUserByLogin($name){
		$userObj = null;
		$data = $this->connection->selectValues("SELECT id FROM skolib_user WHERE login='$name' ");
		if ($data) {
			$userObj = new User($data[0][0]);
			}
		return $userObj;
	}
	
	/**
	* get User By Barcode
	* @param string barcode
	*/
	public function getUserByBarcode($barcode){
		$userObj = null;
		$data = $this->connection->selectValues("SELECT id FROM skolib_user WHERE barcode='$barcode' ");
		if ($data) {
			$userObj = new User($data[0][0]);
		}
		return $userObj;
	}
	
	/**
	* check AccessRight - should prevent get-parameter fraud with inferior rights
	* @param string type
	* @param int right
	*/
	public function checkAccessRight($type,$right) {
		//the following array contains all types which the navigation table does not contain
		$freeAccess = array("newitem","uschoose","usstart","editprofile","print","scan","reminder");
		$access = false;
		if (in_array($type,$freeAccess) ) {
			$access = true;
		} else {
			$data = $this->connection->selectValues('SELECT property FROM skolib_navigation WHERE type="'.$type.'"');
			if ($data) {
				$access = ($data[0][0] <= $right) ? true : false;
			} else {
				$access = false;
			}	
		}
		
		return $access;
	}
	
	/**
	* get Customer By Barcode
	* @param string barcode
	*/
	public function getCustomerByBarcode($barcode){
		$userObj = null;
		$data = $this->connection->selectValues("SELECT SNr FROM skolib_customer WHERE SBarcode='$barcode' ");
		if ($data) {
			$userObj = new Customer($data[0][0]);
		}
		return $userObj;
	}
	
	/**
	* enter Customer Data into DB
	* @param  Customer
	* @return int id
	*/
	public function enterCustomerDataIntoDB($customer) {
		$surname = $customer->getSurname();
		$name = $customer->getName();
		$form = $customer->getForm();
		$asvId = $customer->getAsvId();
		$otherId = $customer->getOtherId();
	return $this->connection->insertValues("INSERT into skolib_customer (`SName`,`SRufname`,`KName`,`ASVID`,`SvNr`) 
	VALUES ('$surname','$name','$form','$asvId','$otherId') ");
	
	}
	
	/**
	* enter Customer Barcodeinto DB
	* @param  int id
	* @param string barcode
	* @return int id
	*/
	public function enterCustomerBarcode($id,$barcode) {
	return $this->connection->straightQuery('UPDATE skolib_customer set SBarcode = "'.$barcode.'" WHERE SNr = '.$id);
	
	}
	
	
	/**
	* get User Details
	* @param int id
	* @return array
	*/
	public function getUserData($id){
		Debug::writeDebugLog(__METHOD__,"...fetching user data using SELECT * FROM skolib_user WHERE id=$id");
		$data = $this->connection->selectAssociativeValues("SELECT * FROM skolib_user WHERE id=$id");
		return array("name"=>$data[0]['Name'],"vorname"=>$data[0]['Vorname'],"id"=>$id,"login" => $data[0]['login']);
	}
	
	/**
	* set user LoginToken
	* @param userID
	*/
	public function setLoginToken($id){
		$limit = 1200;
		//delete old entry
		if($this->getLoginToken($id))  {
			$this->connection->straightQuery("DELETE FROM skolib_login_token WHERE userId = $id");	
			}
		//enter new token
		$timeToken = date('Y-m-d H:i:s',time()+$limit);
		$this->connection->straightQuery("INSERT INTO skolib_login_token (`userId`,`validity`) VALUES ('$id','$timeToken')");
		}
	
	/**
	* get user LoginToken
	* @param userID
	*/
	public function getLoginToken($id) {
		$data = $this->connection->selectValues("SELECT validity FROM skolib_login_token WHERE userId = $id");
		if(count($data) >0) {
			return $data[0][0];	
			}
		
		return false;
		}
	
	/**
	* get user LoginToken
	* @param userID
	*/
	public function validateLoginToken($id) {
		if($validityTime = $this->getLoginToken($id) ) {
			Debug::writeDebugLog(__METHOD__,"...login valid until".$validityTime);
			if (date('Y-m-d H:i:s') < $validityTime) {
			return true;	
			}
			else {
				Debug::writeDebugLog(__METHOD__,"...login timed out".$validityTime);
			return false;
			}
		}
		return true;
		}
		
	/**
	* delete user LoginToken
	* @param userID
	*/
	public function deleteLoginToken($id) {
		$data = $this->connection->straightQuery("DELETE FROM skolib_login_token WHERE userId = $id");
		}
	/**
	* get menue data
	* @param int right 
	* @return array();
	*/
	function getMenueData($right) {
	/*$menue = array();*/
	$data = $this->connection->selectAssociativeValues("SELECT mNr,mentry,type,icon,collapsible,nav_area,popup FROM skolib_navigation 
	WHERE property <= ".$right['rvalue'] .
	" ORDER BY nav_area,ordinal");
	foreach($data as $d){
		$menue[] = array("id" => $d['mNr'],"value"=>$d['mentry'], "type"=>$d['type'],"icon"=>$d['icon'],
		"collapsible" => $d['collapsible'],"navarea"=>$d["nav_area"],"popup"=>$d["popup"]);
		}
	Debug::writeDebugLog(__METHOD__,'selectAssociativeValues("SELECT mentry,type,icon FROM skolib_navigation 
	WHERE property <= "'.$right['rvalue'] .	'" ORDER BY ordinal"');
	return $menue;
	}
	
	
	/**
	* get Customer Details
	* @param int id
	* @return array
	*/
	public function getCustomerData($id){
		$data = $this->connection->selectAssociativeValues("SELECT SBarcode,SName,SRufname,KName 
		FROM skolib_customer WHERE SNr = $id");
		return array("barcode"=>$data[0]['SBarcode'],"name"=>$data[0]['SName'],"vorname"=>$data[0]['SRufname'],"klasse"=>$data[0]['KName']);
	}
	
	/**
	* get Customer Group
	* @return array
	*/
	public function getCustomerGroups(){
		$groups = array();
		$data = $this->connection->selectValues("SELECT DISTINCT KName FROM skolib_customer ORDER BY KName ASC");
		if($data) {
			foreach($data as $d) {
				$groups[] = $d[0];
			}
		}
		return $groups;
	}
	
	/**
	* get Customers by Group
	* @param string groups
	* @return array(Customer)
	*/
	public function getCustomerByGroup($group){
		$customers = array();
		$data = $this->connection->selectValues('SELECT SNr FROM skolib_customer WHERE KName = "'.$group.'"');
		if ($data) {
				foreach ($data as $d) {
				$customer = new Customer($d[0]);
				$customer->setCustomerData();
				$customers[] = 	$customer;
				}
		}
		return $customers;
	}
	
	
	/**
	* get available users
	*/
	public function getLibrarians(){
	$data = $this->connection->selectValues("SELECT id FROM skolib_user
	WHERE noshow=0 "); //Superadmin has noshow == 1
	$users = array();
	if ($data) {
		foreach ($data as $d) {
			$user = new User($d[0]);
			$user->setUserData();
			array_push($users,$user);		
			}
		}
	
	return $users;
	
	}
	
	
	/**
	* get possible rights
	* @return array();
	*/
	public function getPossibleRights(){
	return null;	
	}
	
	
	/**
	* get user rights
	* @param int $id
	* @return array()
	*/
	public function getUserRights($id,$library = 1){
		Debug::writeDebugLog(__METHOD__,"...fetching user Rights ");
		$data = $this->connection->selectValues("SELECT skolib_user_rights.user_right,RWert,ausgabe,description,grant_right
		FROM skolib_user_rights,skolib_rights WHERE skolib_rights.RNr=skolib_user_rights.user_right AND LNr=$id");
		
		return array("rvalue"=>$data[0][1],"rname"=>$data[0][2],"rdescription"=>$data[0][3],"rgrant"=>$data[0][4],"rightId"=>$data[0][0]);
	}
	
	
	/**
	* change user rights
	* @param int userId
	* @param string mode
	* @return string right
	*/
	public function changeUserRight($id,$mode){
		$maxRight = 3; //highest possible right to assign
		$currentRight = $this->getUserRights($id)['rvalue'];
		$newRight = null;
		//get current user rights
		switch ($mode) {
			case "up":
				if ($currentRight  < $maxRight) {
					//get next highest Right
					$nextRight = $currentRight + 1;
					$data = $this->connection->selectValues("SELECT RNr,ausgabe FROM skolib_rights WHERE RWert = $nextRight ");
					//assign Right
					$rightToAssign = $data[0][0];
					$this->connection->straightQuery("UPDATE skolib_user_rights SET user_right = $rightToAssign  WHERE LNr = $id");
					return $data[0][1];
					}
				break;
			case "down":
				if ($currentRight  > 0) {
					//get next highest Right
					$nextRight = $currentRight - 1;
					Debug::writeDebugLog(__METHOD__,"mode is up to ".$nextRight);
					$data = $this->connection->selectValues("SELECT RNr,ausgabe FROM skolib_rights WHERE RWert = $nextRight ");
					//assign Right
					$rightToAssign = $data[0][0];
					$this->connection->straightQuery("UPDATE skolib_user_rights SET user_right = $rightToAssign  WHERE LNr = $id");
					return $data[0][1];
					}
				break;
			}
		return false;
		}
		
	/**
	* delete user
	* @param int id
	*/
	public function deleteUser($id){
		$this->connection->straightQuery("DELETE FROM skolib_user_rights WHERE LNr = $id");
		$this->connection->straightQuery("DELETE FROM skolib_user WHERE id = $id");
		}
	
	/**
	*  add user
	* @param string login
	* @param string name
	* @param string vorname
	* @param string password
	*/
	public function addUser($login,$name,$vorname,$pass){
	$standardRight = 4; //HardCoded Bibliothekshelfer - NOT NICE!
	$password = password_hash($pass,PASSWORD_DEFAULT);
	$id = $this->connection->insertValues("INSERT INTO skolib_user (`id`,`Name`,`Vorname`,`login`,`password`) 
	VALUES ('','$name','$vorname','$login','$password')");
	$this->connection->insertValues("INSERT INTO skolib_user_rights (`LNr`,`user_right`) 
	VALUES ('$id','$standardRight')");
	
	}
	
	/**
	* update user data
	* @param User
	* @param string pass
	*/
	public function updateUserData($user,$passw = null) {
	$this->connection->straightQuery('UPDATE skolib_user SET Name ="'.$user->getSurname().'",Vorname ="'.$user->getName().
	'", Login ="'.$user->getLogin().'" WHERE id ='.$user->getId() );
	if (isset($passw) ) {
		$password = password_hash($passw,PASSWORD_DEFAULT);
		$this->connection->straightQuery('UPDATE skolib_user SET password ="'.$password.'" WHERE id = '.$user->getId() );
	}
	
	}
	
	/**
	 * Password validate
     * @param $name
     * @param $password
     * @return bool user exists in database and password is equal with the one in the database
     */
    public function passwordValidate($name, $password) {
        $login = $this->connection->escape_string($name);
        $data = $this->connection->selectAssociativeValues("SELECT password from skolib_user WHERE login='$login'");
		if ($data == null)
            return false;
        $data = $data[0];
		$pwd_hash = $data['password'];
        Debug::writeDebugLog(__METHOD__,"...validating password in database ".$this->connection->getDatabase()." with result: ".password_verify($password, $pwd_hash));
		return password_verify($password, $pwd_hash);
    }
	
	
	/**
	* get Library Item Data fields
	* @return array()
	*/
	public function getLibraryItemDataFields() {
		$fields = array();
		$data = $this->connection->selectAssociativeValues("SELECT * FROM skolib_data_fields ORDER BY ordinal");
		if($data) {
			foreach ($data as $d) {
				$fields[] = array("id" => $d['dfNr'], "df" => $d['df'], "label" => $d['dfout'], "labeladd" => $d['dftext'],
				"required" => $d['pflicht'], "size" => $d['length'], "type" => $d['input_type'], "dwNr" => $d['dwNr']);
			}
		}
		return $fields;
	}
	
	/**
	* get all Library Items
	* @return array(LibraryItem)
	*/
	public function getAllLibraryItems(){
		$items = array();
		$data  = $this->connection->selectValues("SELECT tNr 
		FROM skolib_titel ORDER BY hkat,autor" );
		if ($data) {
			foreach ($data as $d) {
				$item = new LibraryItem();
				$item->constructFromId($d[0]);
				$items[] = $item;
			}
		}
		return $items;
	}
	
	/**
	* get library item id from barcode
	* @param string barcode
	* @return int
	*/
	public function getLibraryItemIdByBarcode($barcode){
	$data = $this->connection->selectValues('SELECT tNr 
	FROM skolib_titel
	WHERE barcode  ="'.$barcode.'"'); 
	if ($data) {return $data[0][0];} else {return null;}
	}
		
	/**
	* get library item details
	* @param int id (tNr)
	* @return array()
	*/
	public function getLibraryItemDetails($id){
	$details = array();
	$stringValue = array();
	$data = $this->connection->selectValues('SELECT titel,autor,hkat,ukat1,ukat2,mtyp,swort,zusatz,barcode,signatur,erfasst
	FROM skolib_titel WHERE tNr ='.$id);
	if ($data){
		//get String Values for numeric values
		$stringData = $this->connection->selectValues("SELECT ddnr,wert FROM skolib_drop_down");
		foreach($stringData as $s) {
		$stringValue[ $s[0] ] = $s[1];
		}
		$signatur = (!isset($data[0][9]) || $data[0][9] == "") ? "--" : $data[0][9];
		$details = array(
		"id"=>$id,
		"titel"=>$data[0][0],
		"autor"=>$data[0][1],
		"signatur"=>$signatur,
		"barcode"=>$data[0][8],
		"erfasst"=>$this->makeProperDate($data[0][10]),
		"hkat"=>array("key"=>$data[0][2],"value"=>($data[0][2] > 0) ? $stringValue[$data[0][2]] : null, "id" => ($data[0][2] > 0) ? $data[0][2] : null),
		"ukat1"=>array("key"=>$data[0][3],"value"=>($data[0][3] > 0) ? $stringValue[$data[0][3]] : null, "id" => ($data[0][3] > 0) ? $data[0][3] : null),
		"ukat2"=>array("key"=>$data[0][4],"value"=>($data[0][4] > 0) ? $stringValue[$data[0][4]] : null, "id" => ($data[0][4] > 0) ? $data[0][4] : null),
		"mtyp"=>array("key"=>$data[0][5],"value"=>($data[0][5] > 0) ? $stringValue[$data[0][5]] : null, "id" => ($data[0][5] > 0) ? $data[0][5] : null),
		"zusatz"=>$data[0][7],
		"schlagwort"=>$data[0][6]
		);
		
		
		}
	return $details;


	return $details;
	}
	
	/**
	* get item borrowing history
	* @param int id (tNr)
	*/
	public function getItemBorrowingHistory($id){
	$history = array();
	$data = $this->connection->selectValues("SELECT sNr,rueck FROM skolib_ausleihe WHERE tNr=$id  AND rueck>0 ORDER BY rueck DESC");
	if ($data) {
		foreach ($data as $d) {
			array_push($history,array("userid"=>$d[0],"returndate"=>$this->makeProperDate($d[1])) );		
			}
		}
	return $history;
	}
	
	/**
	* get library item status (loaned or available)
	* @param int id
	* @return array
	*/
	public function getLibraryItemStatus($id) {
	$data = $this->connection->selectValues("SELECT skolib_ausleihe.frist,SNr
		FROM skolib_titel,skolib_ausleihe
		WHERE skolib_ausleihe.tNr = $id
		AND skolib_titel.tNr=skolib_ausleihe.tNr
		AND skolib_ausleihe.rueck = 0");
	
	if ($data) {
		return array("status"=>"out","customer"=>$data[0][1],"duedate"=>$this->makeProperDate($data[0][0]));
		} else {
		return null;
		}	
	
	}
	
	
	/**
	* get borrowing defaults
	* @return int
	*/
	public function getBorrowingDefaults(){
		$defaults = null;
		$data = $this->connection->selectValues('SELECT value FROM skolib_library_defaults WHERE category = "length"');
		if($data) {
			$defaults = $data[0][0];	
			}
		return $defaults;
	}
	
	/**
	* get extension defaults
	* @return int
	*/
	public function getExtensionDefaults(){
		$defaults = null;
		$data = $this->connection->selectValues('SELECT value FROM skolib_library_defaults WHERE category = "extension"');
		if($data) {
			$defaults = $data[0][0];	
			}
		return $defaults;
	}
	
	/**
	* enter borrowing into DB
	* @param int titel id
	* @param customer id
	*/
	public function borrowItem($tnr,$snr){
	$borrowingLength = $this->getBorrowingDefaults();
	$outDate = date('Ymd');
	if(isset($borrowingLength)) {
		$returnDate = date('Ymd', strtotime("+".$borrowingLength." days"));
		} else {
		$returnDate = 0;
		}
	$this->connection->straightQuery("INSERT INTO skolib_ausleihe(`aNr`,`tNr`,`user`,`sNr`,`aus`,`frist`)
	VALUES ('','$tnr','1','$snr','$outDate','$returnDate') ");	
	}
	
	/**
	* mark Print Date of Libary Item
	* @param int id
	*/
	public function enterItemPrintDate($id){
		$printedAt = date('Ymd H:i:s');
		$this->connection->straightQuery('UPDATE skolib_titel SET print = "'.$printedAt.'" WHERE tNr = '.$id);
	}
	
	
	/**
	* get Account Data - all currently borrowed items
	* @param int id
	* @return array(int)
	*/
	public function getBorrowersAccountData($id){
	$borrowed = array();
	$data = $this->connection->selectValues("SELECT tNr FROM skolib_ausleihe WHERE sNr=$id AND rueck=0 ORDER BY frist");
	if($data) {
		foreach($data as $d){
		array_push($borrowed,$d[0]);
		}
		return $borrowed;
	}
	else {
		return false;
	}
	}
	
	/**
	* return item
	* @param int id (tNr)
	*/
	public function returnItem($id){
	$this->connection->straightQuery('UPDATE skolib_ausleihe SET rueck="'.date('Ymd').'" 
	WHERE rueck = 0 
	AND tNr='.$id);
	}
	
	/**
	* extend item
	* @param int id (tNr)
	*/
	public function extendItem($id){
	//get extension standard length
	$extension = $this->getExtensionDefaults();
	if ($extension) {
		//get return date
		$data = $this->connection->selectValues("SELECT frist FROM skolib_ausleihe WHERE tNr = $id AND rueck = 0");
		$oldReturnDate = $data[0][0]; 
		//extended due date
		$year = $oldReturnDate[0].$oldReturnDate[1].$oldReturnDate[2].$oldReturnDate[3];
		$month = $oldReturnDate[4].$oldReturnDate[5];
		$day = $oldReturnDate[6].$oldReturnDate[7];
		$newDate = date("Ymd",mktime(0, 0, 0, date($month)  , date($day)+$extension, date($year)));
		$this->connection->straightQuery('UPDATE skolib_ausleihe SET frist= "'.$newDate.'", extend = "'.date('Ymd').'" 
		WHERE rueck = 0 
		AND tNr='.$id);
		} 
	
	}
	
	/**
	* delete item
	* @param int id (tNr)
	*/
	public function deleteItem($id){
	$this->connection->straightMultiQuery("DELETE FROM skolib_ausleihe WHERE tNr=$id;
	DELETE FROM skolib_titel WHERE tNr = $id");
	}
	
	
	/**
	* delete a series of items
	* @param int 
	*/
	public function deleteItemSeries($id){
	//get all elements of series (serie = titel, author and main category are alike)
	$items = $this->getItemsOfSerie($id);
	foreach ($items as $itemId) {
		$this->deleteItem($itemId);
		}
	}
	
	/**
	* change and update item in db
	* @param array()
	*/
	public function updateItemData($data) {
	$this->connection->straightQuery('UPDATE skolib_titel SET
	titel ="'.$data['titel'].'",
	autor ="'.$data['autor'].'",
	hkat ="'.$data['hkat'].'",
	ukat1 ="'.$data['ukat1'].'",
	ukat2 ="'.$data['ukat2'].'",
	mtyp ="'.$data['mtyp'].'",
	swort ="'.$data['swort'].'",
	zusatz ="'.$data['zusatz'].'" 
	WHERE tNr = '.$data['id']);	
	}
	
	
	/**
	* change and update a series of items
	* @param int 
	* @param array()
	*/
	public function updateItemSeries($id,$data) {
	//get all elements of series (serie = titel, author and main category are alike)
	$items = $this->getItemsOfSerie($id);
	foreach ($items as $itemId) {
		$data['id'] = $itemId;
		$this->updateItemData($data);
		}
	}
	
	
	/**
	* get ids of series elements
	* @param int
	* @return array()
	*/
	public function getItemsOfSerie($id){
	$seriesItems = array();
	$seriesDetails = $this->getSeriesDetails($id);
	$data = $this->connection->selectValues('SELECT tNr
	FROM  skolib_titel
	WHERE titel="'.$seriesDetails['titel'].'"
	AND autor = "'.$seriesDetails['autor'].'"
	AND hkat = '.$seriesDetails['hkat'].'
	AND mtyp = '.$seriesDetails['mtyp']);	
	if($data) {
		foreach ($data as $d) {
			$seriesItems[] = $d[0];	
		}
	}
	return $seriesItems;
	}
	
	/**
	* get details of an element to identifiy series
	* @param int 
	* @return array()
	*/
	private function getSeriesDetails($id){
	$data = $this->connection->selectValues('SELECT titel,autor,hkat,mtyp 
	FROM skolib_titel
	WHERE tNr ='. $id);
	if ($data) {
		return array("titel" => $data[0][0],"autor" => $data[0][1],"hkat" => $data[0][2],"mtyp" => $data[0][3])	;
		} else {
			return null;
		}
	}
	
	/**
	* get Library Id
	* @return int
	*/
	public function getLibraryId(){
	$data = $this->connection->selectValues('SELECT value FROM skolib_library_defaults WHERE category = "libraryprefix" ');
		if($data) {
			return	$data[0][0];
			}else {
		return false;
		}
	}
	
	/**
	* get library type
	* @return boolean
	*/
	public function getLibraryType(){
	$data = $this->connection->selectValues('SELECT value FROM skolib_library_defaults WHERE category = "serieslib" ');
		if($data) {
			return  ($data[0][0] == "1") ? true : false;
			}
		return false;
	}
	
	/**
	* get Barcode Length
	* @return int
	*/
	public function getBarcodeLength() {
		$data = $this->connection->selectValues('SELECT value FROM skolib_library_defaults WHERE category = "barclength" ');
		if($data) {
			return	$data[0][0];
			}
		return false;
	}

	/**
	* get Customer Prefix for Barcode
	* @return int
	*/
	public function getCustomerPrefix() {
		$data = $this->connection->selectValues('SELECT value FROM skolib_library_defaults WHERE category = "customerprefix" ');
		if($data) {
			return	$data[0][0];
			}
		return false;
	}
	
	/**
	* get Signature Separator
	* @return string
	*/
	public function getSignatureSeparator(){
	$data = $this->connection->selectValues('SELECT value FROM skolib_library_defaults WHERE category = "signatureseparator"');
	if($data) {
			return	$data[0][0];
			}else {
		return false;
		}
	}
	
	/**
	* get Signature PrintState
	* @return boolean
	*/
	public function getSignaturePrintState(){
	//changed the return value - false or null wouldn't be passed on to js correctly !?
	$data = $this->connection->selectValues('SELECT value FROM skolib_library_defaults WHERE category = "signatureprint"');
	if($data) {
			return  ($data[0][0] == "1") ? 1 : 0;
			}
		return 0;
	}
	
		
	/**
	* getSignatureElements
	* @return array
	*/
	public function getSignatureElements(){
	$elements = array();
	if ($this->getSignatureSeparator()) {
		$data = $this->connection->selectAssociativeValues('SELECT  id,query,field
		FROM skolib_signature_rules
		WHERE typ = "element" ORDER BY id'); 
		if ($data) {
			foreach($data as $d) {
				$elements[ $d['id'] ] = array("id" => $d['id'],"query" => $d['query'],"field" => $d['field']);
			}	
		}
		return $elements;
		} else {
		return false;
		}
	
	}
	
	/**
	* get signature Settings
	* @return array()
	*/
	public function getSignatureSettings(){
	$settings = null;
		$data = $this->connection->selectAssociativeValues("SELECT id,ruleId,hkatId,length,ordinal,addNr 
		FROM skolib_signature_settings
		ORDER BY ruleId,ordinal");
		if ($data) {
			foreach ($data as $d) {
				$settings[ $d['id'] ] =  array("id"	 => $d['id'], "ruleId" =>$d['ruleId'], "hkatId" => $d['hkatId'],
				"length" => $d['length'], "ordinal" => $d['ordinal'],"addNr" => $d['addNr'] );
				}
			}
	return $settings;	
	}
	
	/*
	* write signature rules to DB
	* @param array updates
	* @param array new
	*/
	public function writeSignatureRules($updates,$new){
		//Update entries
		foreach($updates as $upd) {
		$this->connection->straightQuery("UPDATE skolib_signature_rules 
		SET field = '".$upd['field']."', query='".$upd['query']."' WHERE id=".$upd['id']) ;
		$this->connection->straightQuery("UPDATE skolib_signature_settings 
		SET hkatId = ".$upd['hkatId'].", length=".$upd['length'].", ordinal=".$upd['ordinal'].", addNr =".$upd['addNr']." WHERE id=".$upd['settingId']) ;
		}
		
		
		if (isset($new) ){
			//Insert new rule
			$hint = $new['field'];
			$query = $new['query'];
			$hkatId = $new['hkatId'];
			$length = $new['length'];
			$ordinal = $new['ordinal'];
			$ruleId = $this->connection->insertValues("INSERT INTO skolib_signature_rules (`typ`,`field`,`query`)
			VALUES ('element','$hint','$query')");
			$this->connection->insertValues("INSERT INTO skolib_signature_settings(`ruleId`,`hkatId`,`length`,`ordinal`)
			VALUES ('$ruleId','$hkatId','$length','$ordinal')" ) ;
		
			}
		
	}
	
	
	/**
	* getSignatureContent  -- NEEDS OVERHAUL ACCORDING TO NEW SYSTEM
	* @param LibaryItem
	* @return array(String)
	*/
	public function getSignatureContent($item){
	$contents = array();
	$elements = $this->getSignatureElements();
	$settings = $this->getSignatureSettings();
	$signatureSettingToUse = array();
	$defaultSignatureSetting = array();
	$searchCategory = (array_search($item->getCategory()['key'],array_column($settings,'hkatId') )) ? $item->getCategory()['key'] : 0;
	$settingsKeys = array_keys($settings);
	$contents = array();
	$addNr = false;
	foreach ($settingsKeys as $key) {
		if ($settings[$key]['hkatId'] == $searchCategory) {
			//check for additional Number
			if($settings[$key]['addNr'] == 1) {$addNr = true;}
			//get Rule for this key
			$query= $elements[$settings[$key]['ruleId'] ]['query']; 
			//adapt query to relevant Item
			$qArr = explode("%wert%",$query);
			$part2 = (isset($qArr[1]) ) ? $qArr[1] : "";
			$query = $qArr[0] . $item->getId() . $part2;
			//get Data
			$data = $this->connection->selectValues($query);
			//cut data to particular length
			
			//Formatting ISSUE
			//Result of the query doesn't return UTF-8 encoded values (For whatever reasons??)
			$contents[] = mb_substr($data[0][0],0,$settings[$key]['length'],'UTF-8');
			}
		}
	return array("contents" => $contents,"addNr" => $addNr);
	}
	
	/**
	* check Signatures if number is required
	* @param string signature
	* @return int number
	*/
	public function checkSignatureForNumbers($signature){
			$number = false;
			$noNumberYet = true;
			$data = $this->connection->selectValues('SELECT signatur FROM skolib_titel WHERE signatur LIKE "'.$signature.'%"');
			$sigNrList = array();
			if ($data) {
				foreach($data as $d) {
					$sigArr = explode("--",$d[0]);
					if ( isset($sigArr[1])  ) {
					$noNumberYet = false;
					$sigNrList[] = $sigArr[1];
					}
				}
			//check for highest number	
			if (count($sigNrList) > 0) {
				rsort($sigNrList);
				$number = $sigNrList[0]+1;
				} elseif ($noNumberYet) {
					
				$number = 1;	
				}
			}
		return $number;
	}
	
	/**
	* get signature fields - in order to make those uneditable
	* @return array
	*/
	public function getIneditableSignatureFields(){
		$ineditables= array();
		$data = $this->connection->selectValues("SELECT df,hkatId 
		FROM skolib_data_fields,skolib_signature_rules,skolib_signature_settings 
		WHERE skolib_data_fields.dfNr = skolib_signature_rules.field 
		AND skolib_signature_settings.ruleId=skolib_signature_rules.id ");
		if($data) {
			foreach($data as $d) {
				$ineditables[ $d[1] ][] = $d[0];
				
			}
		}
		return $ineditables;
		
	}
		
	
	
	
	/**
	* get main Categories
	* @return array()
	*/
	public function getMainLibraryCategories(){
	$categories = array();
	$data = $this->connection->selectValues("SELECT ddNr,wert FROM skolib_drop_down WHERE dwNr = 1 ORDER BY ordinal");
	if ($data) {
		foreach($data as $d) {
		$categories[] = array("id" => $d[0], "value" => $d[1]);	
		}
	}
	return $categories;
	}

	/**
	* get all Categories by their ID
	* @param int category dwNr[optional]
	* @return array
	*/
	public function getAllCategoriesById($cat = null){
	$add = (isset($cat)) ? " WHERE dwNr = $cat " : "";
	$categories = array();
	$data = $this->connection->selectValues("SELECT ddNr,wert FROM skolib_drop_down $add ORDER BY dwNr,ordinal");
	if ($data) {
		foreach($data as $d) {
		$categories[ $d[0] ] = $d[1];	
		}
	}
	return $categories;	
	}
	
	/**
	* get Barcode Forms and Details
	* @return array()
	*/
	public function getBarcodeForms(){
	$forms = array();
	$data = $this->connection->selectAssociativeValues("SELECT * FROM skolib_barc_forms");
	if ($data) {
		foreach ($data as $d){
			$forms[$d['bfNr']] = array('id' => $d['bfNr'], 'public' => $d['public'],
			'name' => $d['name'],'margin_left' => $d['margin_left'], 
			'top' => $d['top'], 'fontsize' => $d['fontsize'],
			'lineheight' => $d['lineheight'], 'cols' => $d['cols'], 
			'rows' => $d['rows'],'colwidth' => $d['colwidth'], 
			'rowheight' => $d['rowheight'], 'picspace_v' => $d['picspace_v'],
			'textspace_v' => $d['textspace_v'],'textspace_h' => $d['textspace_h'],
			'showcode' => $d['showcode'],'maxpages' => $d['maxpages'], 
			'picwidth' => $d['picwidth'],'picheight' => $d['picheight'],
			'ratio' => $d['ratio'],'signatur' => $d['signatur']);
		}
	
		}
	return $forms;
	}
	
	
	/**
	* get all details of a Barcode Forms -- REDUNDANT???
	* @param int bNr
	* @return array()
	*/
	/*
	public function getBarcodeFormDetails($id) {
	$formdetails = array();
	$data = $this->connection->selectValues("SELECT * FROM skolib_barc_forms_tbl WHERE public=1 AND bNr=".$id);
	if ($data) {
		foreach ($data as $d) {
			//action
			}
		}
	}
	*/
	
	/**
	* create Barcode Form
	* @param array
	*/
	public function createBarcodeForm($formData) {
		$this->connection->insertValues("INSERT into skolib_barc_forms (`public`,`name`,`margin_left`,
		`top`,`fontsize`,`lineheight`,`cols`,`rows`,`colwidth`,`rowheight`,
		`picspace_v`,`textspace_v`,`showcode`,`maxpages`,`picwidth`,
		`picheight`,`ratio`,`signatur`) 
		VALUES ('".$formData['public']."','".$formData['name']."',
		'".$formData['margin_left']."','".$formData['top']."',
		'".$formData['fontsize']."','".$formData['lineheight']."',
		'".$formData['cols']."','".$formData['rows']."','".$formData['colwidth']."',
		'".$formData['rowheight']."','".$formData['picspace_v']."',
		'".$formData['textspace_v']."','".$formData['showcode']."',
		'".$formData['maxpages']."','".$formData['picwidth']."',
		'".$formData['picheight']."','".$formData['ratio']."',
		'".$formData['signatur']."')");
	}
	
	
	/**
	* update Barcode Form
	* @param array
	*/
	public function updateBarcodeForm($formData) {
		$this->connection->straightQuery('UPDATE skolib_barc_forms SET public="'.$formData['public'].'",name="'.$formData['name'].'",margin_left="'.$formData['margin_left'].'",
		top="'.$formData['top'].'",fontsize="'.$formData['fontsize'].'",lineheight="'.$formData['lineheight'].'",
		cols="'.$formData['cols'].'",rows="'.$formData['rows'].'",colwidth="'.$formData['colwidth'].'",
		rowheight="'.$formData['rowheight'].'",picspace_v="'.$formData['picspace_v'].'",textspace_v="'.$formData['textspace_v'].'",
		showcode="'.$formData['showcode'].'",maxpages="'.$formData['maxpages'].'",picwidth="'.$formData['picwidth'].'",
		picheight="'.$formData['picheight'].'",ratio="'.$formData['ratio'].'",signatur="'.$formData['signatur'].'" 
		WHERE bfNr = '.$formData['id']);
		}
	
	/**
	* get all unprinted barcodes
	* @param boolean noDetails 
	* @return array(LibraryItems) int when noDetails == false
	*/
	public function getUnprintedBarcodes($noDetails){
		$unprinted = array();
		$data = $this->connection->selectValues("SELECT tNr FROM skolib_titel WHERE print is null");
		if ($data) {
			if ($noDetails) {return count($data);}
			foreach($data as $d) {
				$id = $d[0];
				$libraryItem = new LibraryItem();
				$libraryItem->constructFromId($id);
				$libraryItem->getItemDetails();
				$unprinted[] = $libraryItem;
			}
		} else {
				if ($noDetails) {return 0;}
		}
		return $unprinted;

	}
	
	/**
	* get Barcodes by Entry
	* @param array
	* @return array(LibraryItem)
	*/
	public function getBarcodesByEntries($barcs) {
		$toPrint = array();
		foreach($barcs as $barc) {
			
				$data = $this->connection->selectValues('SELECT tNr FROM skolib_titel WHERE barcode ="'. $barc.'"');
				if ($data) {
						$id = $data[0][0];
						$libraryItem = new LibraryItem();
						$libraryItem->constructFromId($id);
						$libraryItem->getItemDetails();
						$toPrint[] = $libraryItem;
					}
				else {
						$toPrint[] = null;
				}
				}
			
		return $toPrint;
	}

	/**
	* get inventory
	* all existing Library Items
	* @return array
	*/
	public function getInventory($seriesLibrary = false){
		$inventory = array();
		$groupArgument = ($seriesLibrary) ? " GROUP BY titel " : "";
		$countArgument = ($seriesLibrary) ? ",COUNT(titel) AS anzahl " : "";
		$barcArgument = ($seriesLibrary) ? "" : ",barcode,signatur";
				
		$data = $this->connection->selectValues("SELECT titel".$countArgument.",wert".$barcArgument." 
			FROM  skolib_titel, skolib_drop_down
			WHERE skolib_titel.hkat=skolib_drop_down.ddNr ".$groupArgument.
			" ORDER BY titel");
			
		if ($data) {
			foreach($data as $d) {
				$inventory[] = array("title" => $d[0],"category" => ($seriesLibrary) ? $d[2] : $d[1] , "amount" =>($seriesLibrary) ? $d[1] : null,
				"borrowed" => $this->getBorrowedItemsByTitle($d[0],$seriesLibrary),"barcode" => ($seriesLibrary) ? null : $d[2],
				"signature"=>($seriesLibrary) ? null : $d[3] 	) ;		
				}
		}
		return $inventory;
	}
	
	
	/**
	* get amount of books
	* @return int
	*/
	public function getStockAmount(){
		$stock = 0;
		$data = $this->connection->selectValues("SELECT  COUNT( tNr ) AS anzahl FROM  skolib_titel");
		if ($data) {
			$stock = $data[0][0];
		}
		return $stock;
	}
	
	/**
	* get title amount of one specific title
	* @param int
	* @return array
	*/
	function getTitleAmount($id) {
		$seriesDetails = $this->getSeriesDetails($id);
		$data = $this->connection->selectValues('SELECT count(tNr ) as amount
		FROM  skolib_titel
		WHERE titel="'.$seriesDetails['titel'].'"
		AND autor = "'.$seriesDetails['autor'].'"
		AND hkat = '.$seriesDetails['hkat'].'
		AND mtyp = '.$seriesDetails['mtyp']);
		if ($data)  {
			return $data[0][0];
			} else {
				return false;
			}

		}
	
	
	
	/**
	* detect amount of borrowed items by title
	* @param string $title
	*/
	public function getBorrowedItemsByTitle($title, $seriesLibrary = false){
	$borrowed = 0;
	if (preg_match('`^`', $title, $m)) {
		$title = str_replace('"','\"',$title);
		//$title = mysqli_real_escape_string($this->connection->getId(),$title);
	}
	//if ($seriesLibrary) {
		//get a non Object Oriented response - takes too long when library has a decent size
		$data = $this->connection->selectValues('SELECT count(skolib_titel.tNr) AS anzahl FROM skolib_titel,skolib_ausleihe 
		WHERE titel="'.$title.'" 
		AND skolib_titel.tNr=skolib_ausleihe.tnr
		AND rueck = 0');
		if ($data[0][0]) {
			$borrowed = $data[0][0];
			}
	//the following is not used any longer, see above
	//	} else {
	/*	$items = $this->getItemsOfSerieByTitle($title);
		foreach ($items as $i) {
			if($i->getItemStatus()) {
				//item is borrowed
				$borrowed++;
				}
			}
		}*/
	return $borrowed;
	}
	
	/**
	* detect all borrowed items
	* in use is actually only the basic version, better performance
	* @return array(LibraryItems)
	*/
	public function getAllBorrowedItems(){
	$borrowedItems = array();
	$data = $this->connection->selectValues("SELECT tNr FROM skolib_ausleihe WHERE rueck = 0");
	if($data) {
		foreach ($data as $d) {
		$item = new LibraryItem();
		$item->constructFromId( $d[0] );
		$borrowedItems[] = $item->getDetailArrayForJson();
		}
	}
	return $borrowedItems;	
	}
	
	/**
	* detect all borrowed items basic way (only small amount of data)
	* @return array(LibraryItems)
	*/
	public function getAllBorrowedItemsBasic(){
	$borrowedItems = array();
	$data = $this->connection->selectValues("SELECT skolib_ausleihe.tNr,titel,barcode,frist
	FROM skolib_ausleihe,skolib_titel 
	WHERE rueck = 0
	AND skolib_ausleihe.tNr = skolib_titel.tNr
	ORDER BY titel");
	if($data) {
		foreach ($data as $d) {
		$borrowedItems[] = array(
		"id"=>array("key"=>"Titelnummer","value"=>$d[0]),
		"titel"=>array("key"=>"Titel","value"=>$d[1]),
		"barcode"=>array("key"=>"Barcode","value"=>$d[2]),
		"faellig"=>array("key"=>"faellig","value"=>array("due"=>$this->makeProperDate($d[3])))
		);
		
		}
	}
	return $borrowedItems;	
	}
	
	/**
	* detect borrowed items and borrowing customer to create csv File
	* @return array
	*/
	public function getBorrowedItemsForCSV(){
	$borrowedItems = array();
	$data = $this->connection->selectValues("SELECT titel,barcode,frist,SName,SRufname,KName
	FROM skolib_ausleihe,skolib_titel,skolib_customer 
	WHERE rueck = 0
	AND skolib_ausleihe.tNr = skolib_titel.tNr
	AND skolib_customer.SNr = skolib_ausleihe.SNr
	ORDER BY KName,SName,SRufname");
	if($data) {
		foreach ($data as $d) {
		$borrowedItems[] = array(
		"form"=>$d[5],
		"sn"=>$d[3],
		"gn"=>$d[4],
		"title"=>$d[0],
		"barcode"=>$d[1],
		"faellig"=>$this->makeProperDate($d[2]));
		
		}
	}
	return $borrowedItems;		
	}
	
	/**
	* borrowed items amount
	* @return int
	*/
	public function getBorrowedItemsAmount(){
		$amount = 0;
		$data = $this->connection->selectValues("SELECT  COUNT( tNr ) AS anzahl 
		FROM  skolib_ausleihe
		WHERE rueck = 0");
		if ($data) {
			$amount = $data[0][0];
		}
		return $amount;	
	}
	
	/**
	* get due date of title
	* @param int id
	* return array
	*/
	public function getBorrowedItemDueDetails($id){
	$data = $this->connection->selectValues("SELECT aus,frist,mahn,extend
	FROM skolib_ausleihe
	WHERE tNr = $id
	AND rueck = 0");
		if($data) {
			return array("due" => $data[0][1],"out" => $data[0][0], "mahn" => $data[0][2],"extend" => $data[0][3]);
		} else {
			return null;
		}
	}
	
	
	/**
	* detect all customer and items to be reminded of
	* @return array()
	*/
	public function getReminderData(){
		$reminderData = array();
		$today = date('Ymd');
		$data = $this->connection->selectValues("SELECT DISTINCT SNr FROM skolib_ausleihe 
		WHERE frist <= ".$today." 
		AND rueck = 0
		AND mahn = 0 ORDER BY SNr");
		if($data) {
			foreach($data as $d) {
				$customer = new Customer($d[0]);
				$customer->setCustomerData();
				$reminderData[] = array("customer" => $customer, "items" => $customer->getTitlesToRemind() );
			}
		}
		return $reminderData;
	}
	
	/**
	* get titles to be reminded of per customer
	* @return array
	*/
	public function getTitlesToRemind($customerId, $repeat=false){
		$titles = array();
		$today = date('Ymd');
		$mahnArgument = ($repeat) ? " AND mahn > 0 " : " AND mahn = 0 ";
		$data = $this->connection->selectValues("SELECT aNr,tNr FROM skolib_ausleihe 
			WHERE rueck <= $today 
			AND rueck = 0
			$mahnArgument
			AND SNr = $customerId ORDER BY rueck");
		if($data) {
			foreach($data as $d) {
				$item= new LibraryItem();
				$item->constructFromId($d[1]);
				$item->getItemDetails();
				$titles[] = array("aNr" => $d[0], "item" => $item);
				$this->markReminderDate($d[0]);
			}
		}
		return $titles;	
	}
	
	
		
	
	/**
	* enter date of reminder notice into DB
	* @param int aNr
	*/
	private function markReminderDate($anr) {
		$this->connection->straightQuery("UPDATE skolib_ausleihe set mahn = ".date('Ymd')." WHERE aNr = $anr");
	}
	
	/**
	* get favourite items sorted by popularity
	* return array
	*/
	public function getFavouriteItems(){
		$favourites = array();
		$maxanz = 5;
		$data = $this->connection->selectValues("SELECT tNr,count(tNr) as anzahl 
		FROM `skolib_ausleihe` group by tNr order by anzahl desc LIMIT 0,".$maxanz);
		
		
		if($data) {
			if (count($data) < $maxanz) {$maxanz = count($data);}
			for ($x = 0;$x < $maxanz;$x++) {
				$item = new LibraryItem();
				$item->constructFromId($data[$x][0]);
				$favourites[] = array("item" => $item,"count" => $data[$x][1] );
			}
		}
		return $favourites;
	}
	
	/**
	* get Dashboard Elements
	* return array()
	*/
	public function getDashboardSettings(){
	$settings = array();
	$data = $this->connection->selectValues('SELECT type FROM skolib_library_defaults WHERE category = "dashboard" and active = 1');
	if($data) {
	foreach ($data as $d) {
		switch ($d[0]) {
			case "borrowedItemsAmount":
				$settings['borrowedItemsAmount'] = $this->getBorrowedItemsAmount();
				break;
			case "inventoryAmount":
				$settings['inventoryAmount'] = $this->getStockAmount();
				break;
			case "dueItems":
				$settings['dueItems'] = $this->getDueItems();
				break;
			case "warnedItems":
				$settings['warnedItems'] = $this->getWarnedItems();
				break;
			case "favourites":
				$settings['favourites'] = $this->getFavouriteItems();
				break;
			default:
				break;
			}

		}	
	}
	return $settings;
		
	}
	
	
	/**
	* get all due items 
	* @return array()
	*/
	public function getDueItems(){
		$dueItems = array();
		$today = date('Ymd');
		$data = $this->connection->selectValues('SELECT tNr FROM skolib_ausleihe 
		WHERE rueck = 0 
		AND frist <"'.$today.'"
		AND mahn = 0');
		if ($data) {
			foreach ($data as $d){
			$item = new LibraryItem();
			$item->constructFromId( $d[0] ); 
			$dueItems[] = $item;			
			}
		}
	return $dueItems;	
	}
	
	/**
	* get all warned items 
	* @return array()
	*/
	public function getWarnedItems(){
		$warnedItems = array();
		$today = date('Ymd');
		$data = $this->connection->selectValues('SELECT tNr FROM skolib_ausleihe 
		WHERE rueck = 0 
		AND mahn > 0');
		if ($data) {
			foreach ($data as $d){
			$item = new LibraryItem();
			$item->constructFromId( $d[0] ); 
			$warnedItems[] = $item;			
			}
		}
	return $warnedItems;	
	}
	
	/**
	* get all items of a serie 
	* by its title
	* @param string $title
	* @return array(LibraryItems)
	*/
	public function getItemsOfSerieByTitle($title) {
		
	if (preg_match('`^`', $title, $m)) {
		$title = str_replace('"','\"',$title);
		//$title = mysqli_real_escape_string($this->connection->getId(),$title);
	}
	
	$items = array();
	$data = $this->connection->selectValues("SELECT skolib_titel.tNr 
	FROM skolib_titel 
	WHERE skolib_titel.titel=\"$title\" ");
	if ($data){
		foreach ($data as $d) {
			$item = new LibraryItem();
			$item->constructFromId($d[0]);	
			$items[]= $item;
			}
		}
	return $items;
	}
	
	
	/**
	* enter new LibraryItem into table skolib_title
	* creatinmg barcode and signature afterwards
	* @param LibraryItem
	* @return int id
	*/
	public function enterItemIntoTableTitle($item){
		$author = $item->getAuthor();
		$title = $item->getTitle();
		$category = $item->getCategory()['key'];
		$subCategory1 = $item->getSubCategory1()['key'];
		$subCategory2 = $item->getSubCategory2()['key'];
		$keyWords = $item->getKeyWords();
		$mediaType = $item->getMediaType();
		$various = $item->getVarious();
		$signature = $item->getSignature();
		$query = "INSERT INTO skolib_titel (`titel`,`autor`,`hkat`,`ukat1`,`ukat2`,`swort`,`mtyp`,`zusatz`) 
		VALUES ('$title','$author','$category','$subCategory1','$subCategory2','$keyWords','$mediaType','$various') ";
		$id = $this->connection->insertValues($query);
		$item->setId($id);
		$library = new Library();
		if ($library->getLibraryType() == false ) {
		$item->makeSignature();
		}
		$item->makeBarcode();
		//update entry
		$today = date('Ymd');
			Debug::writeDebugLog(__METHOD__,'UPDATE skolib_titel SET barcode = "'.$item->getBarcode().'",signatur ="'.
		$item->getSignature().'", erfasst = "'.$today.'" WHERE tNr = '.$id);
		$this->connection->straightQuery('UPDATE skolib_titel SET barcode = "'.$item->getBarcode().'",signatur ="'.
		$item->getSignature().'", erfasst = "'.$today.'" WHERE tNr = '.$id);
	
	}
	
	
	/**
	* get Search Field Name
	* @param string search field
	* @return string search field 
	*/
	public function getSearchFieldName($searchField) {
		$data = $this->connection->selectValues('SELECT dfout FROM skolib_data_fields WHERE df = "'.$searchField.'"');
		if($data) {
		return $data[0][0];	
		}
		return false;
	}
	
	/** 
	* get dropDown nname to numeric value
	* @int ddNr
	* @return string wert
	*/
	public function getDropDownName($id) {
		$data = $this->connection->selectValues("SELECT wert FROM skolib_drop_down WHERE ddNr = ".$id);
		if($data) {
		return $data[0][0];	
		}
		return false;
	}
	
	/**
	* get result from full text item search
	* in use is actually only the basic version, better performance
	* @param string query
	* @return array LibraryItems
	*/
	public function searchItems($query){
		$query = "SELECT tNr FROM skolib_titel ".$query;
		$result = array();
		$data = $this->connection->selectValues($query);
		if ($data) {
			foreach($data as $d) {
			$item = new LibraryItem();
			$item->constructFromId($d[0]);			
			
			$result[] = $item->getDetailArrayForJson();
			}
		return $result;	
		} else {
			
		}return null;
		
	}
	
	/**
	* get result from full text item search - basic way
	* @param string query
	* @return array LibraryItems
	*/
	public function searchItemsBasic($query, $seriesLibrary){
		$query = "SELECT tNr,titel,barcode FROM skolib_titel ".$query." ORDER BY titel";
		$result = array();
		
		$data = $this->connection->selectValues($query);
		if ($data) {
			foreach($data as $d) {
				//get Item Status
				$status = $this->getLibraryItemStatus($d[0]);
				$due = ($status) ? $status['duedate']: null;
				if ($seriesLibrary)
						$due = ($status) ? true: null;
				$dueArr = ($due) ? array("key"=>"faellig","value"=>array("due"=>$due)) : null;
				$result[] = array(
				"id"=>array("key"=>"Titelnummer","value"=>$d[0]),
				"titel"=>array("key"=>"Titel","value"=>$d[1]),
				"barcode"=>array("key"=>"Barcode","value"=>$d[2]),
				"faellig"=>$dueArr);
				}
			return $result;	
		} else {
			return null;
		}
		
	}
	
	/**
     *read datafields from database
     * @return array datafield names
     */
    public function readDBFields() {
        $data = $this->connection->selectFieldNames("SELECT * FROM skolib_customer");
        //Remove Primary Key and Update Flag
		$remKey1 = array_search('SNr', $data); 
		$remKey2 = array_search('upd', $data); 
		unset($data[$remKey1],$data[$remKey2]);
		return $data;
        
    }
	
	
	/**
     *check if data exist in database - used for customer data update
     * @param int  $id
     * @param string key field
     *
     * @return bool existence
     */
    public function checkDBData($id, $keyField) {
		$data = $this->connection->selectValues("SELECT SNr FROM skolib_customer where $keyField='$id' ");
        if (count($data) > 0) {
            return true;
        } else {
            return false;
        }
    }
	
	 /**
     *update data
     * @param string id / field content used for update
	 * @param string field name used for update
     * @param array
	 */
    public function updateData($id, $keyField, $line) {
        $string = null;
        foreach ($line as $key => $value) {
            $key = $this->connection->escape_string($key);
            $value = $this->connection->escape_string($value);
            $key = trim($key);
            $value = trim($value);
            $value = addslashes($value);
			if ($key != $keyField) {
				if (isset($string)) {
					$string = $string . ",$key='$value' ";
				} else {
					$string = "$key='$value' ";
				}
			}
        }
        $string = $string . ",upd=1 WHERE $keyField='$id' ";
		$string = "UPDATE skolib_customer SET " . $string;
        $this->connection->straightQuery($string);
    }
    
    /**
     * insert data 
     * @param bool
     * @param array
     */
    public function insertData($line) {
        $fieldstring = null;
        $valuestring = null;
        foreach ($line as $key => $value) {
            $key = $this->connection->escape_string($key);
            $value = $this->connection->escape_string($value);
            $key = trim($key);
            $value = trim($value);
            $value = addslashes($value);
            if (isset($fieldstring)) {
                $fieldstring = $fieldstring . ",`$key`";
            } else {
                $fieldstring = "`$key`";
            }
            if (isset($valuestring)) {
                $valuestring = $valuestring . ",'$value'";
            } else {
                $valuestring = "'$value'";
            }
        }
        $fieldstring = $fieldstring . ",`upd`";
        $valuestring = $valuestring . ",'1'";
        $string = "INSERT INTO skolib_customer (" . $fieldstring . ") VALUES (" . $valuestring . ")";
        $id = $this->connection->insertValues($string);
		//make barcode
		$customer = new Customer($id);
		$customer->makeBarcode();
		$this->connection->straightQuery('UPDATE skolib_customer SET SBarcode="'.$customer->getBarcode().'" WHERE SNr ='.$id);
        unset($customer);
    }
    
    /**
     * delete unused data from DB - NOT ADAPTED YET
     * @return int amount of deletions
     */
    public function deleteDataFromDB() {
        $toDelete = 0;
        $data = $this->connection->selectValues("SELECT SNr FROM skolib_customer WHERE upd=0");
        $toDelete = count($data);
        $this->connection->straightQuery("DELETE FROM skolib_customer WHERE upd=0");
        return $toDelete;
    }
    
    /**
     *set update status to zero
    */
    public function setUpdateStatusZero() {
        $this->connection->straightQuery("UPDATE skolib_customer SET upd=0");
    }
		
	/**
	* turn datestring from DB into proper date Format
	* @param string 
	* return string
	*/
	public function makeProperDate($datestring) {
	if ($datestring) {
		$date = DateTime::createFromFormat('Ymd', $datestring);
		return $date->format('d.m.Y');
		}else {
		return null;	
		}
	}
	
	
	
	/**
	* get setup data
	* @return array()
	*/
	public function getLibraryDefaultSetups() {
	$setupData = array();
	//Get Setup Categories first
	$categories = array();
	$data = $this->connection->selectValues("SELECT DISTINCT setup_category FROM skolib_library_defaults");
	if ($data) {
		foreach ($data as $d) {
			$categories[]= $d[0];
		}
	}
	
	foreach ($categories as $category) {
	$setups = array();
	$data = $this->connection->selectValues('SELECT id,value,active,setup_category,setup_name,setup_field,setup_update,setup_comment
	FROM skolib_library_defaults 
	WHERE setup_category="'.$category.'" ORDER BY id');
	if($data) {
		foreach ($data as $d) {
		$setups[] = array("id"=>$d[0],"value"=> ($d[6] == "value") ? $d[1] : $d[2],
			"name"=>$d[4],"feld"=>$d[5],"comment"=>$d[7]);
			}	
	
		}
	$setupData[] = array("category" => $category,"settings"=>$setups);	
	unset($setups);
	}
	return $setupData;
	
	}
	
	/**
	* get field details for setup update
	* @return array
	*/
	public function getUpdateFields() {
	$updateFields = array();
	$data = $this->connection->selectValues('SELECT id,setup_field,setup_update
	FROM skolib_library_defaults ');
	if($data) {
		foreach($data as $d) {
			$updateFields[] = array("id"=>$d[0],"name"=>$d[1],"update"=>$d[2]);
			}
		}
	return $updateFields;
	}
	
	/** 
	* update setup field values
	* @param int id
	* @param mixed new value
	* @param string fieldname
	*/
	public function updateSetupField($id,$value,$field) {
		$this->connection->straightQuery('UPDATE skolib_library_defaults SET '.$field.'="'.$value.'" WHERE id='.$id);
	}
	
	/**
	* check database for double barcodes
	* @return array
	*/
	public function checkDatabaseForDoubles(){
		$doubles = null;
		$data = $this->connection->selectValues("SELECT barcode, COUNT(barcode) AS NumOccurrences
		FROM skolib_titel
		GROUP BY barcode
		HAVING ( COUNT(barcode) > 1 )");
		if ($data) {
			foreach ($data as $d) {
				$doubleData = $this->connection->selectValues('SELECT tNr FROM skolib_titel 
				WHERE barcode ="'. $d[0].'"');
				if ($doubleData) {
					$doubleArray = array();
					foreach($doubleData as $dd) {
						$item = new LibraryItem();
						$item->constructFromId( $dd[0] );
						$borrowedItems[] = $item->getDetailArrayForJson();
						$doubleArray[] = array("item"=>$item->getDetailArrayForJson());
						}				
					}
					$doubles[] = array("barcode"=>$d[0],"doubles"=>$doubleArray);	
				}
		}
		return $doubles;
	}
	
	/**
	* check if a barcode has duplicate entry
	* @param string
	* @return bool
	*/
	public function checkBarcodeForDoubles($barcode){
		$data = $this->connection->selectValues('SELECT tNr FROM skolib_titel WHERE barcode = "'.$barcode.'"'); 
		if ($data) {
			if (count($data) > 1) {
				return true;
				} else {
				return false;
				}
			} else {
			return false;
			}
	}
	
	
	
	
	}

?>