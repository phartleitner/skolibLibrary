<?php

/**
 * class handles input and other data
 */
class Controller {
    
    /**
     * @var Model instance of model to be used in this class
     */
    protected $model = null;
	 /**
     * @var array combined POST & GET data received from client
     */
	protected $input;
     /**
     * @var User
     */
    protected static $user;
     /**
     * @var logfile
     */
    protected $logfile = "skolib.log";
    /**
	*@var infoToView
	*/
	protected $infoToView = array();
    /**
     * @return User
     */
    public static function getUser() {
        return self::$user;
    }
    
    /**
     * Controller constructor.
     *
     * @param $input
     */
    public function __construct($input) {
		Debug::writeDebugLog(__METHOD__,"initialising Controller");
		if ($this->model == null){
			$this->model = Model::getInstance();
			}
		if (isset($_SESSION['user'])) {
			Debug::writeDebugLog(__METHOD__,"Already logged in: SESSION['user'] is set to ".$_SESSION['user']['id']);
			//could the user be set here?
			$this->createUserObject();
			}
				
		$this->input = $input;
		$this->handleLogic();
		
		
    }
    
    protected function handleLogic() {
		if (isset($this->input['console']))
            header('Content-Type: text/json');
        // handles login verification and creation of user object
		
        if (!isset(self::$user) && !isset($_SESSION['user']['id'])){
			Debug::writeDebugLog(__METHOD__,"...SESSION['user'] is NOT set: ");	
			if(!isset($this->input['login']['name']) ) {$this->input['type'] = null;}
			//WORK here to prevent errors when timed out
			}
		else {
			if (isset(self::$user) ) {
				Debug::writeDebugLog(__METHOD__,"...User Object exists: ".self::$user->getFullName() );
				} 
			else if (isset($_SESSION['user']['id'])) {
				Debug::writeDebugLog(__METHOD__,"...SESSION['user'] has already been set: ".$_SESSION['user']['id']);
				}	
			//create user object
			
			
			}
		if (!isset($this->input['type'])) {
            $this->input['type'] = null;
			
			}
			$this->display($this->handleType());

/*			else {
			if (!Model::getInstance()->checkAccessRight($this->input['type'],self::$user->getId() )  && $this->input['type'] != "logout") {
				$this->notify("Insufficient rights!");
				}else {
				$this->display($this->handleType());
				}	
			}
 */       
		
    }
    
    protected function getEmptyIfNotExistent($array, $key) {
        return (isset($array[$key])) ? $array[$key] : "";
    }
    
    /**
     * @return string
     */
    protected function handleType() {
		
		
		
		//check access rights - prevents GET parameter manipulation
		if ($this->input['type'] != null && self::$user != null) {
		if (!Model::getInstance()->checkAccessRight($this->input['type'],self::$user->getRight()['rvalue'] )  && $this->input['type'] != "logout" && $this->input['type'] != "login") {
				$this->input['type'] = null;
				}	
		}
		$add_log = (isset($this->input['do'])) ? "Custom Action do: ".$this->input['do']." on ".$this->input['custom'] : "no custom action";
		Debug::writeDebugLog(__METHOD__,"...checking input: ".$this->input['type'].$add_log);
		$template = "login";
		//dropdown for organization selection
		if (!isset($_SESSION['organisation'])) {$this->infoToView['organisations'] = $this->model->getOrganisationsFromBaseDB();}
        //Custom Actions triggered by javascript function
		if(isset($this->input['custom'])) {
		switch($this->input['do']) {
			case "1":
				//delete item
				$libraryItem = new LibraryItem();
				$libraryItem->constructFromBarcode($this->input['custom']);
				$libraryItem->deleteItem();
				break;
			case "2":
				//return item
				$libraryItem = new LibraryItem();
				$libraryItem->constructFromBarcode($this->input['custom']);
				$libraryItem->returnItem();
				break;
			case "3":
				//edit item
				$libraryItem = new LibraryItem();
				$libraryItem->constructFromBarcode($this->input['custom']);
				$libraryItem->changeItem(json_decode( $this->input['data'],true ));
				die;
				//GO on here
				break;
			case "4":
				//delete series
				$libraryItem = new LibraryItem();
				$libraryItem->constructFromBarcode($this->input['custom']);
				$libraryItem->deleteItemSeries();
				break;
			case "5":
				//edit series
				$libraryItem = new LibraryItem();
				$libraryItem->constructFromBarcode($this->input['custom']);
				$libraryItem->changeItemSeries(json_decode( $this->input['data'],true ));
				break;
			case "6":
				//extend item
				$libraryItem = new LibraryItem();
				$libraryItem->constructFromBarcode($this->input['custom']);
				$libraryItem->extendItem(); 
				break;
			case "7":
				//getDetails
				$libraryItem = new LibraryItem();
				$libraryItem->constructFromBarcode($this->input['custom']);
				//$libraryItem->getDetailArrayForJSON();
				//Debug::writeDebugLog(__METHOD__,"getting details for :".$this->input['custom']);
				echo  json_encode(array("status" => "success", "items" => $libraryItem->getDetailArrayForJSON()));
				die;
				break;
			
			default:
				break;		
			}
		}
		
		
		switch ($this->input['type']) {
            case "login":
				$template = $this->login();
                break;
            case "logout":
                $this->logout();
                break;
			case "start":
				//dashboard showing missing titles
				break;
			case "setup":
				if(isset($this->input['update']) ) {
					//get the fields to be updated
					$updateFields = Model::getInstance()->getUpdateFields();
					foreach ($updateFields as $field) {
						Model::getInstance()->updateSetupField($field['id'],$this->input[$field['name']],$field['update']);
						}
					$this->infoToView['message'] = "Setup geändert!";
					}
				$library = new Library();
				$this->infoToView['setups'] = $library->getSetupData();
				$template = "setup";
				break;
			case "search":
				$library = new Library();
				$seriesLibraryType = $library->getLibraryType();
				$this->infoToView['serieslib'] = ($seriesLibraryType) ? true : false;
				if (isset($this->input['searchfor']) ) {
				$result = $this->handleSearch();
				echo json_encode($result); 
				die;
				}
				$this->infoToView['title'] = "Buch / Medium suchen";
				$this->setFieldValues();				
				$template = "search";
				break;
			case "stock":
				//create an inventory list
				$library = new Library();
				$seriesLibraryType = $library->getLibraryType();
				$this->infoToView['serieslib'] = ($seriesLibraryType) ? true : false;
				$this->infoToView['title'] = "Inventarliste";
				//write inventory list to file
				include("class.filehandler.php");
				$file = $_SESSION['organisation']['database']."/dwnld/inventar.csv";
				$this->infoToView['dwnld'] = $file;
				$fileHandler = new Filehandler($file);
				if ($seriesLibraryType) {
					
					//series type library, i.e. lots of items with the same title
					$this->infoToView['inventory'] = $library->getBasicInventory();
					$data = array();
					array_push($data,"Titel;Kategorie;Bestand;verliehen\r\n");
					foreach($this->infoToView['inventory'] as $inventory) {
					$line = $inventory['title'].";".$inventory['category'].";".$inventory['amount'].";".$inventory['borrowed']."\r\n";
					array_push($data,$line );
					}
					
					} else {
					//singleItem type library
					$this->infoToView['inventory'] = $library->getInventory();
					$data = array();
					array_push($data,"Titel;Kategorie;Barcode;Signatur\r\n");
					foreach($this->infoToView['inventory'] as $inventory) {
					$line = $inventory['title'].";".$inventory['category'].";".$inventory['barcode'].";".$inventory['signature']."\r\n";
					array_push($data,$line );
					}
					}
					$fileHandler->createCSV($data);
					$template = "inventory";
				break;
			
			case "editprofile":
				//edit userprofile
				$this->editUserProfile();
				$template = "editprofile";
				break;
			case "addItem":
				//create a new library item
				$this->infoToView['title'] = "Buch / Medium hinzufügen";
				$library = new Library();
				//Get Datafields to enter
				$libraryFields = $library->getLibraryItemDataFields();
				$this->infoToView['fields'] = $libraryFields;
				$this->infoToView['dropdown'] = $library->getDropdownValues($libraryFields);
				$template = "itemadd";
				break;
			case "newitem":
				//enter a new item into DB after checking the entries - via js
				$result = $this->handleNewItemEntry($this->input);
				echo json_encode($result);
				die;
				break;
			case "users":
				//handle users
				$this->handleUsers();
				$template = "usermgt";
				break;
			case "barc":
				echo "deleted function";die;
				/*$this->infoToView['title'] = "Barcodes drucken";
				$this->infoToView['navarea'] = "barcodes";
				$template = "simple_menue";	*/
				break;
			case "bookbarc":
				//barcode print
				$library = new Library();
				$this->infoToView['title'] = "Bücherbarcodes - Format wählen";
				$this->infoToView['barcode_forms'] = $library->getBarcodeForms();
				$this->infoToView['amount_unprinted_barcodes'] = $library->getUnprintedBarcodes(true); //true -> get only number (int)
				$this->infoToView['selection'] = "forms";
				$template = "barcodeprint";	
				break;
			case "singlebarc":
				$library = new Library();
				$this->infoToView['title'] = "Bücherbarcodes - Einzeldruck";
				$this->infoToView['barcode_forms'] = $library->getBarcodeForms();
				$this->infoToView['signature_print'] = $library->getSignaturePrintState();
				$template = "single_barcode_print_enter";					
				break; 
			case "makesig":
				//Can be removed later
				$this->infoToView['items'] = Model::getInstance()->getAllLibraryItems();
				$template = "makesig";
				break;
			case "managesig":
				$this->handleSignatureManagement();
				$template = "managesig";
				break;
			case "managebarcform":
				$this->handleBarcodeFormManagement();
				$template = "managebarcform";
				break;
			case "bookimport":
				$this->infoToView['title'] = "Import";
				$template = "bookstodb";
				break;
			case "customer":
				$this->infoToView['groups'] = Model::getInstance()->getCustomerGroups();
				$this->infoToView['title'] = "Entleiherbarcodes - Klasse/Gruppe wählen";
				$this->infoToView['selection'] = "groups";
				$template = "barcodeprint";
				break;
			case "customerimport":
				$template = "customerimport";
				break;
			case "update":
				$this->infoToView['header'] = "Benutzerdatenabgleich - Quelldatei wählen";
				$this->infoToView['actiontype'] = "uschoose";
				$template = "update";
				break;
			//Update student data
            case "uschoose":
				include("class.filehandler.php");
                $upload = $this->fileUpload();
                $success = $upload['success'];
                $written = $success ? "true" : "false";
                                
                if ($success) {
                    $_SESSION['file'] = $upload['location'];
                }
                
                if (isset($input['console'])) {
                    $error = (isset($upload['error']) ? $upload['error'] : "");
                    
                    die("<script type='text/javascript'>window.top.window.uploadComplete($written, '$error');</script>");
                }
				if ($success) {
                    
                    echo "<script> alert(".$upload['location'].");   </script>  ";
                    $this->infoToView['header'] = "Benutzerdatenabgleich - Updateparameter wählen".$upload['location'];
                    $this->prepareDataUpdate();
                    $this->infoToView['actiontype'] = "usstart";
					$template = "update1";
                    
                } else {
					$this->infoToView['header'] = "Benutzerdatenabgleich - Quelldatei wählen";
                    $template = "update";
                }
				
                break;
			//Customer Update start
            case "usstart":
				include("class.filehandler.php");
				$this->infoToView['header'] = "Daten aktualisiert";
				$this->performDataUpdate( $this->input);
                $template = "update2";
                break;
			case "print":
				$printSignature = false;
				$this->infoToView['useForm'] = $this->input['format'];
				$this->infoToView['mode'] = $this->input['mode'];
				switch($this->input['mode']){
				case 1://barcode list of all unprinted items
					$this->infoToView['unprinted'] = 1;
					$library = new Library();
					$printSignature = $library->getSignaturePrintState();
					if ($printSignature) {$this->infoToView['separator'] =  $library->getSignatureSeparator();}
					$this->infoToView['defaults'] =  $library->getBarcodeForms()[$this->infoToView['useForm']];
					$this->infoToView['unprinted_barcodes'] = $library->getUnprintedBarcodes(false);
					break;
				case 2://print list of a form (pupils)
					$library = new Library();
					$this->infoToView['defaults'] =  $library->getBarcodeForms()[4]; //Hardcoded form - not good
					$this->infoToView['group'] = $this->input['group'];
					$this->infoToView['customers'] = Model::getInstance()->getCustomerByGroup($this->input['group']);
					break;
				case 3://print individually entered barcode
					$library = new Library();
					//must be active when file is ready
					$printSignature = $library->getSignaturePrintState();
					if ($printSignature) {$this->infoToView['separator'] =  $library->getSignatureSeparator();}
					$this->infoToView['defaults'] =  $library->getBarcodeForms()[$this->infoToView['useForm']];
					$this->infoToView['entered_barcodes'] = (isset($this->input['barc'])) ? $library->getBarcodesByEntries($this->input['barc'] ) : null;
					break;
				case 4://print to test
				
					break;
				}
				
				$template = ($printSignature) ? "pdf_printbclist_signatures" : "pdf_printbclist";
				break;
			case "reminder":
				if (isset($this->input['rpt']) ) {
				//print individual reminder again
				$customer = new Customer($this->input['rpt']);
				$customer->setCustomerData();
				$this->infoToView['repeatedReminder'] = true;
				$this->infoToView['toremind'] = array(array("customer" => $customer, "items" => $customer->getTitlesToRemind(true) ));
				} else {				
				//print all reminder notices
				$this->infoToView['toremind'] = Model::getInstance()->getReminderData();
				}
				$template = "pdf_reminder";
				break;
			case "admin":
				//administration interface menue
				$this->infoToView['title'] = "Verwaltung";
				$this->infoToView['navarea'] = 101;
				$template = "collapsible_menue";
				break;
			case "borrowed":
				//detect all borrowed items
				$this->setFieldValues();
				$library = new Library();
				$seriesLibraryType = $library->getLibraryType();
				$this->infoToView['serieslib'] = ($seriesLibraryType) ? true : false;
				
				if(isset($this->input['do']) ) {
					$borrowedItems = Model::getInstance()->getAllBorrowedItemsBasic();
					$status = ($borrowedItems == true) ? "success" : "keine Titel entliehen";
					echo json_encode(array("status" => $status, "items" => $borrowedItems));
					die;
					} else { 
					$borrowedItems = Model::getInstance()->getAllBorrowedItemsBasic();
					$status = ($borrowedItems == true) ? "success" : "keine Titel entliehen";
					$this->infoToView['borrowedItems'] = json_encode(array("status" => $status, "items" => $borrowedItems));
					}
				
				$this->infoToView['header'] = "entliehene Titel";
				$template = "borrowedtitles";	
				break;
			case "csv":
				$library = new Library();
				$this->infoToView['borrowedCSV'] = $library->createBorrowedItemsCSV();
				$this->infoToView['header'] = "entliehene Titel - CSV Download";
				$template = "csvdwnld";
				break;
			case "out":
				$this->infoToView['scantype']['key'] = 0;
				$this->infoToView['scantype']['value'] = "Ausleihe";
				$this->setFieldValues();
				$library = new Library();
				$this->infoToView['customerPrefix'] = $library->getCustomerPrefix();
				$seriesLibraryType = $library->getLibraryType();
				$this->infoToView['serieslib'] = ($seriesLibraryType) ? true : false;				
				$template = "scan";
				break;
			case "info":
				$this->infoToView['scantype']['key'] = 1;
				$this->infoToView['scantype']['value'] = "InfoScan";
				$this->setFieldValues();	
				$library = new Library();
				$this->infoToView['customerPrefix'] = $library->getCustomerPrefix();
				$seriesLibraryType = $library->getLibraryType();
				$this->infoToView['serieslib'] = ($seriesLibraryType) ? true : false;		
				$template = "scan";
				break;
			case "return":
				$this->infoToView['scantype']['key'] = 2;
				$this->infoToView['scantype']['value'] = "Rückgabe";
				$this->setFieldValues();
				$library = new Library();
				$this->infoToView['customerPrefix'] = $library->getCustomerPrefix();
				$seriesLibraryType = $library->getLibraryType();
				$this->infoToView['serieslib'] = ($seriesLibraryType) ? true : false;		
				$template = "scan";
				break;
				
			case "dblchk":
				$this->setFieldValues();
				$this->infoToView['header'] = "Doublettenprüfung der Datenbank";
				$library = new Library();
				$seriesLibraryType = $library->getLibraryType();
				$this->infoToView['serieslib'] = ($seriesLibraryType) ? true : false;
				$doubles = $library->checkDatabaseForDoubles();
				$status = (isset($doubles)) ? "success" : "keine Doubletten vorhanden";
				$this->infoToView['doubleitems'] =	json_encode(array("status" => $status, "items" => $doubles));
				/*header("Content-type: application/json; charset=utf-8");
				echo json_encode(array("status" => $status, "items" => $doubles),JSON_PRETTY_PRINT); die;
				echo json_encode($this->infoToView['doubleitems'],JSON_PRETTY_PRINT);die;*/
				$template = "doubleitems";
				break;
			case "scan":
				//action is called through javascript function and will return JSON object
				$library = new Library();
				$customerPrefix = $library->getCustomerPrefix();
				$seriesLibraryType = $library->getLibraryType();
				$this->infoToView['serieslib'] = ($seriesLibraryType) ? true : false;
				$scanMode = $this->input['mode'];
				$result = array();
				$jsonReturn = array();
				$barcode_init = substr($this->input['input'],0,2);
				if($barcode_init == $customerPrefix ){
					//Customer scanned
					if ($scanMode == 2) {
					//returning mode
					//customer scan not supported in returning mode
					$jsonReturn = array("return"=>array("key"=>"error","value"=>"Kein Benutzerscan möglich"),"customer"=>"no Customer");		
					} else {
					$jsonReturn = $this->handleCustomerScan($this->input,$scanMode);
					}
				}else{
					//item scanned
					$jsonReturn = $this->handleItemScan($this->input,$scanMode);
					}
				echo json_encode($jsonReturn);
				die;
				break;
			default:
                if (self::$user == null) { // not logged in
						Debug::writeDebugLog(__METHOD__,"..nobody loggged in yet, waiting for Login");
						if (isset($_SESSION['logout'])) { 
							// if just logged out display toast
							$notifyText = isset($_SESSION['logout']['timeout']) ? "Anmeldung abgelaufen" : "Erfolreich abgemeldet";
						    $this->notify($notifyText);
						}
                    return "login";
					} else {
					Debug::writeDebugLog(__METHOD__,"..already logged in as ".self::$user->getId());
					}
         
                return $this->getDashBoardName();
                break;
        }
		
        return $template;
    }
    
     /**
     * Login logic
     * @return string returns template to be displayed
     */
    protected function login() {
        if (isset($this->input['console'])) // used to only get raw login state -> can be used in js
			{
				Debug::writeDebugLog(__METHOD__,"...input['type'] = console -- javaScript: ".$this->input['login']['organisation']." - ". $this->input['login']['name']. " - ".$this->input['login']['password']);
				
				if (!isset($_SESSION['organisation']))  { 
					$_SESSION['organisation'] = $this->model->getOrganisationCredentials($this->input['login']['organisation']);
					Debug::writeDebugLog(__METHOD__,"SESSION['organisation'] :".$_SESSION['organisation']['database']);
					$this->model->changeConnection();
					}
				
				die($this->checkLogin($this->input['login']['name'], $this->input['login']['password']) ? "true" : "false");
			}
			else {
				Debug::writeDebugLog(__METHOD__,"... starte login Routine (no JS) mit Name: ".$this->input['login']['name']);
				if (!isset($this->input['login']['name']) || !isset($this->input['login']['password']) || !isset($this->input['login']['organisation']) ) {
					$this->notify('Kein Loginname, Passwort oder Schule angegeben');
					Debug::writeDebugLog(__METHOD__,"... kein name, Passwort oder Schule");
					return "login";
				} else {
				Debug::writeDebugLog(__METHOD__,"...login data sent again :".$this->input['login']['name']);	
				
				}
				if (isset($_SESSION['user']['id'])) {
					Debug::writeDebugLog(__METHOD__,"...SESSION['user'] already set :".$_SESSION['user']['id']);
					if (self::$user != null)
					{
					Debug::writeDebugLog(__METHOD__,"...user object exists:".self::$user->getId());	
					}
					$this->createUserObject();
					Debug::writeDebugLog(__METHOD__,"...login finished");	
					return "scan";
					}
			}
		
		
		
		
        if ($this->checkLogin($this->input['login']['name'], $this->input['login']['password'])) {
			Debug::writeDebugLog(__METHOD__,"Login from anywhere-why?");
			return $this->getDashBoardName();
        } else {
            ChromePhp::info("Invalid login data");
            $this->notify('Email-Addresse oder Passwort falsch');
            
            return "login";
        }
    }
	
	
	
	
    /**
	 * check Login
     * @param $name string user name
     * @param $pwd string user pwd
     * @return bool success of login
     */
    protected function checkLogin($name, $pwd) {
		Debug::writeDebugLog(__METHOD__,"...just validating password");
		$uid = null;
        $type = null;
		$success = false;
			
		if($this->model->passwordValidate($name, $pwd) ) {
				
				$userObj = $this->model->getUserByLogin($name);
                if ($userObj != null) {
					$type = $userObj->getType();
					Debug::writeDebugLog(__METHOD__,"Password correct -> user Object instantiated :".$userObj->getId());	
					$uid = $_SESSION['user']['id'] = $userObj->getId();
                    $time = $_SESSION['user']['logintime'] = time();
                    $success = true;
					//Setze das LoginToken
					$this->model->setLoginToken($userObj->getId());
					}
				} 
        Debug::writeDebugLog(__METHOD__,"return :".$success);	
		 Debug::writeDebugLog(__METHOD__,"SESSION['organisation'] :".$_SESSION['organisation']['database'] );	
        if (!$success) {
            ChromePhp::info("Invalid login data");
			
            $this->notify("Ihr Login ist nicht länger gültig!");
			Debug::writeDebugLog(__METHOD__,"Login failed! ");
			} 
        
        return $success;
    }
	
	
	 /**
     * Creates userobject of logged in user and saves it to Controller:$user
     * @param User $usr specify if object already created
     * @return User the current userobject
     */
    protected function createUserObject($usr = null) {
        if(isset($_SESSION['user']['id'])) {	
		//Prüfe Login Token
		if (!$this->model->validateLoginToken($_SESSION['user']['id']) ){
			self::$user = null;
			Debug::writeDebugLog(__METHOD__,"Token Validation timed out!! SESSION killed");
			$this->logout(true);
			$this->input['type'] = "logout";
			} else {
			Debug::writeDebugLog(__METHOD__,"Token Validation OK");
			$this->model->setLoginToken($_SESSION['user']['id']);
			if (self::$user == null){
				self::$user = new User($_SESSION['user']['id']);
				Debug::writeDebugLog(__METHOD__,"...Creating User Object with id:". $_SESSION['user']['id']);
				self::$user->setUserData();
				$this->infoToView['user'] = self::$user;
				$this->infoToView['menue'] = $this->model->getMenueData(self::$user->getRight() );
				Debug::writeDebugLog(__METHOD__,"... menue data set, number of elements: ".count($this->infoToView['menue']) );
				}
			
			}
		}
		return self::getUser();
    }
	
	
	/**
	* edit user profile
	*/
	private function editUserProfile(){
	$this->infoToView['title'] = "Profil bearbeiten";
	if(isset($this->input['changed']) ) {
		//check Password
		if($this->model->passwordValidate(self::$user->getLogin(), $this->input['passold']) ) {
		if ( isset($this->input['passnew']) )  {
				self::$user->updateUserData($this->input['surname'],$this->input['name'],$this->input['login'],$this->input['passnew']);	
			} else {
				self::$user->updateUserData($this->input['surname'],$this->input['name'],$this->input['login'],null);	
			}
			$result = array("status" => "success","message" => "Daten erfolgreich aktualisiert!");
			} else {
			$result = array("status" => "error","message" => "Passwort ist nicht korrekt!",
			"pass" => $this->input['passold'],"login" => self::$user->getLogin());
			}
		echo json_encode($result);
		die;	
		}
	$this->infoToView['user'] = self::$user;	
	}
	
	/**
	* handle users
	*/
	private function handleUsers(){
	if(isset($this->input['id'])) {
		if(isset($this->input['mode'])) {
			switch($this->input['mode']){
				case "up":
					Debug::writeDebugLog(__METHOD__,"increasing right");
					echo $this->model->changeUserRight($this->input['id'],"up") ;die;
					break;
				case "down":
					echo  $this->model->changeUserRight($this->input['id'],"down") ;die;
					break;
				case "delete":
					$this->model->deleteUser($this->input['id']);
					echo "reload";die;
					break;
			}
		}
		} elseif (isset($this->input['add'])) {
			$this->model->addUser($this->input['login'],$this->input['name'],$this->input['vorname'],$this->input['pass']);
			echo "added";die;
		}
	$this->infoToView['title'] = "Benutzerverwaltung";
	$this->infoToView['librarians'] = $this->model->getLibrarians();
	}
	
	/**
	* handle customer scan
	* @param array()
	* @param int scanMode
	* @return array()
	* transaction order codes:
	* 001  - customer no book before
	* 101  - customer book before
	* 100  - book no customer before
	* 111	- book customer before
	*/
	private function handleCustomerScan($data,$scanMode) {
	$customer = null;
	$customer = Model::getInstance()->getCustomerByBarcode($data['input']);
	if($customer) {
			$customer->setCustomerData();
			} else {
				$returnStatus = array("key"=>"error","value"=> "Barcode ist keinem Benutzer zugeordnet","code"=>"404","order"=>"101");
				$returnData = $this->getReturnData("error",404,101);
				//$returnData = array("return"=>$returnStatus,"customer"=>"no Customer");
				$returnData["customer"] = "no customer";
				return $returnData;
			}
	if ($scanMode == 1) {
		//on info scan provide all items customer has borrowed since
		// maybe a lot of data
		$returnData = $this->getReturnData("success",201,101);
		//$returnData["return"] = array("key"=>"success","value"=> "Kontoinformationen nebenstehend.","code"=>"201","order"=>"101");
		} else {
		
		if(isset($data['itms']) && $scanMode == 0 ){
			//Customer Scan WITH items scanned prior to that
			//get customer data
			if($customer) {
				//borrowing process
				$borrowingItems = array();
				foreach (json_decode($data['itms'],true) as $itm) {
					$borrowedItem = new LibraryItem();
					$borrowedItem->constructFromId($itm);
					array_push($borrowingItems,$borrowedItem);
					}
				$this->handleBorrowingItems($borrowingItems,$customer);
				$returnData = $this->getReturnData("success",200,101);
				//$returnData["return"] = array("key"=>"success","value"=> "Ausleihvorgang abgeschlossen. Bereit für neuen Vorgang!","code"=>"200","order"=>"101");
				} else {
				//customer doesn't exist
				$returnStatus = array("key"=>"error","value"=> "Barcode ist keinem Benutzer zugeordnet","code"=>"404","order"=>"101");
				$returnData = $this->getReturnData("error",404,101);
				//$returnData = array("return"=>$returnStatus,"customer"=>"no Customer");
				$returnData["customer"] = "no customer";
				}
			} else {
			//customer scan WITHOUT items scanned prior to that
			if ($customer){
				$returnData = $this->getReturnData("error",402,001);
				//$returnData["return"] = array("key"=>"success","value"=> "Buch scannen um Vorgang abzuschließen","code"=>"300","order"=>"001");
				} else {
				$returnData = $this->getReturnData("error",402,001);
				//$returnData["return"] = array("key"=>"error","value"=> "Barcode ist keinem Benutzer zugeordnet","code"=>"402","order"=>"001");
				}
			}	
		}
	if ($customer) {
		//create customer account data
		$returnData = $this->createCustomerAccountArray($customer,$returnData);
		}
	return $returnData;
	}
	
	/**
	* handle item scan
	* @param array()
	* @param int scanMode
	* @return array()
	* transaction order codes:
	* 001  - customer no book before
	* 101  - customer book before
	* 100  - book no customer before
	* 111  - book customer before
	*/
	private function handleItemScan($data,$scanMode){
	$statusCode = "1";
	$statusString = "verfügbar";
	$libraryItemOut = false;
	$libraryItem = new LibraryItem();
	$libraryItemExists = ($libraryItem->constructFromBarcode($data['input'])) ? true : false;
	if ($libraryItemExists) {
		$libraryItemOut = $libraryItem->getItemStatus();
		$libraryItemDouble = $libraryItem->checkForDoubles();
		if ($libraryItemDouble) {return $this->getReturnData("error",410,111);}
		}
	if ($scanMode == 0){
		//borrowing mode
		if( isset($this->input['cstm']) ) {
			//customer scanned prior to item code 111
			if ($libraryItemExists) {
				if (!$libraryItemOut) {
					//item available - borrow one item 
					$customer = new Customer($this->input['cstm']);
					$customer->setCustomerData();
					$customer->borrowItem($libraryItem);
					$returnData = $this->getReturnData("success","200","111");
					//$returnStatus = array("key"=>"success","value"=> "Ausleihvorgang abgeschlossen. Bereit für neuen Vorgang!","code"=>"200","order"=>"111");
					//$returnData["return"] = $returnStatus;
					$returnData = $this->createCustomerAccountArray($customer,$returnData);
					return $returnData;
					} else {
					//item not available
					$borrowingCustomer = $libraryItemOut['customer'];
					$borrowingCustomer->setCustomerData();
					$statusString = "verliehen an ".$borrowingCustomer->getFullName().' ('.$borrowingCustomer->getForm().')';
					$statusCode = "0";					
					$returnData = $this->getReturnData("error",406,111);
					//$returnStatus = array("key"=>"error","value"=> "Artikel nicht verfügbar! Neues Buch scannen!","code"=>"406","order"=>"111");
					//$returnData["return"] = $returnStatus;						
					}
				}  else 	{
				//item doesn't exist
				$returnData = $this->getReturnData("error",405,111);
				//$returnData["return"] = array("key"=>"error","value"=>"Barcode ist keinem Buch zugeordnet!","code"=>"405","order"=>"111");
				}
			} else {
			//no customer scanned prior to item	code 100
			if ($libraryItemExists) {
			//Item exists
			if (!$libraryItemOut) {
				$returnData = $this->getReturnData("success",100,100);
				//$returnData["return"] = array("key"=>"success","value"=> "Buch oder Benutzer scannen","code"=>"100","order"=>"100");
				} else {
				//item not available
				$borrowingCustomer = $libraryItemOut['customer'];
				$borrowingCustomer->setCustomerData();
				$statusString = "verliehen an ".$borrowingCustomer->getFullName().' ('.$borrowingCustomer->getForm().')'; 
				$statusCode = "0";
				$returnData = $this->getReturnData("error",407,100);
				//$returnData['return'] = array("key"=>"error","value"=> "Dieser Artikel ist zur Zeit verliehen!","code"=>"407","order"=>"100");		
				}
			$returnData["item"] =  $libraryItem->getDetailArrayForJSON();
			$returnData["item"]["status"] = array("key"=>"Status","value"=>$statusString,"statuscode"=>$statusCode);
			} else {
			//item doesn't exist
			$returnData = $this->getReturnData("error",405,100);
			//$returnData["return"] = array("key"=>"error","value"=>"Barcode ist keinem Buch zugeordnet!","code"=>"405","order"=>"100");	
			}
			}
		} else if ($scanMode == 1) {
		//info mode
		if ($libraryItemExists) {
			
			if ($libraryItemOut) {
				$borrowingCustomer = $libraryItemOut['customer'];
			    $borrowingCustomer->setCustomerData();
				$statusString = "verliehen an ".$borrowingCustomer->getFullName().' ('.$borrowingCustomer->getForm().')'; 
				$statusCode = "0";	
				} else {
				$statusString = "verfügbar"; 
				$statusCode = "1";	
				}
			$returnData = $this->getReturnData("success",202,100);
			//$returnData['return'] = array("key"=>"success","value"=> "Artikelinformationen nebenstehend","code"=>"202","order"=>"100");
			$returnData["item"] =  $libraryItem->getDetailArrayForJSON();
			$returnData["item"]["status"] = array("key"=>"Status","value"=>$statusString,"statuscode"=>$statusCode);
			} else {
			//item doesn't exist
			$returnData = $this->getReturnData("error",405,100);
			//$returnData["return"] = array("key"=>"error","value"=>"Barcode ist keinem Buch zugeordnet!","code"=>"405","order"=>"100");	
			}			
		}
		elseif ($scanMode == 2){
		//returning mode
		//create item and return
		if ($libraryItemExists) {
			if (!$libraryItemOut) {
			//item is not borrowed
			$returnData = $this->getReturnData("value",403,100);
			//$returnData['return'] = array("key"=>"value","value"=> "Artikel ist nicht verliehen!","code"=>"403","order"=>"100");	
			} else {
			$libraryItem->returnItem();	
			$borrowingCustomer = $libraryItemOut['customer'];
			$borrowingCustomer->setCustomerData();
			$returnData = $this->getReturnData("success",203,100);
			//new
			
			if ($borrowingCustomer->getBorrowersAccountDataForJSON()) {
			//customer's account not empty;
			$items = $borrowingCustomer->getBorrowersAccountDataForJSON();
			} else {
			//customer's account IS empty
			$items = null;
			}
			$returnData["currentBorrower"] =array(
			"id"=>$borrowingCustomer->getId(),
			"barcode"=>$borrowingCustomer->getBarcode(),
			"name"=>$borrowingCustomer->getSurname(),
			"vorname"=>$borrowingCustomer->getName(),
			"klasse"=>$borrowingCustomer->getForm(),
			"items"=>$items);
			// Irgendwie kommen die Daten des gescannten Buchs nicht sauber rüber -> faellig wird nicht gefunden!!!
			//new End
			//$returnData['return'] = array("key"=>"success","value"=> "Artikel zurückgegeben!","code"=>"203","order"=>"100");
			
			}
			$returnData["item"] =  $libraryItem->getDetailArrayForJSON();
			$returnData["item"]["status"] = array("key"=>"Status","value"=>"verfügbar","statuscode"=>"1");
			} else {
			//item doesn't exist
			$returnData = $this->getReturnData("error",405,100);
			//$returnData["return"] = array("key"=>"error","value"=>"Barcode ist keinem Buch zugeordnet!","code"=>"405","order"=>"100");	
			}
		} 
	unset($libraryItem);
	return $returnData;
	}
	
	/**
	* create Return Data array
	* @param int code for statusText
	* @param int order (scanning order)
	* @return array
	*/
	private function getReturnData($errorState,$code,$order){
	$returnData = array();
	$returnData['return'] = array("key"=>$errorState,"code"=>$code,"order"=>$order);	
	return $returnData;
	}
	
	/**
	* handle customers borrowing several items
	* @param array($LibraryItem Objects)
	* @param Customer Object
	*/
	private function handleBorrowingItems($borrowedItems,$customer) {
		foreach($borrowedItems as $item) {
			$customer->borrowItem($item);
			}
		}
	
	/**
	* create Customer's borrowing account array
	* @param Customer Object
	* @param array()
	* @return array
	*/
	private function createCustomerAccountArray($customer,$returnArray){
		$returnArray["customer"] =array(
			"id"=>$customer->getId(),
			"barcode"=>$customer->getBarcode(),
			"name"=>$customer->getSurname(),
			"vorname"=>$customer->getName(),
			"klasse"=>$customer->getForm());
		if ($customer->getBorrowersAccountDataForJSON()) {
			//customer's account not empty;
			$returnArray["items"] = $customer->getBorrowersAccountDataForJSON();
			} else {
			//customer's account IS empty
			$returnArray["items"] = null;
			}
		return $returnArray;
	}
	
	/**
	* handle new Item Entry
	* @param input data
	* @return arry();
	*/
	private function handleNewItemEntry($data){
	//check if all relevant entries are made
	//this largely depends on the entered main Category and the thus resulting required fields for signature
	$hkat = $data['hkat'];
	
	$library = new Library();
	$signatureSettings = $library->readSignatureSettings();
	if($signatureSettings ) {
		//Signature is required
		$signatureRules = $library->readSignatureRules();
		//exisiting datafields
		$dataFields = $library->getLibraryItemDataFields();
		//short field names by index
		foreach($dataFields as $field) {
				$fieldName[$field['id']] = $field['df'];
				$fieldLabel[$field['id']] = $field['label'];
			}
		$settingsKeys = array_keys($signatureSettings);
		$useRuleIds = array();
		foreach ($settingsKeys as $key) {
			if ($signatureSettings[$key]['hkatId'] == $hkat) {
				$useRuleIds[] = $signatureSettings[$key]['ruleId'];
				}		
			}
		if (count($useRuleIds) == 0) {
		//Default Values are required
		foreach ($settingsKeys as $key) {
			if ($signatureSettings[$key]['hkatId'] == 0) {
				$useRuleIds[] = $signatureSettings[$key]['ruleId'];
				}		
			}
		}
		$requiredFields = array();
		foreach ($useRuleIds as $id) {
				$requiredFields[] = $signatureRules[$id]['field'];
			}
		$missing = false;
		$status = "success";
		foreach ($requiredFields as $field) {
			$requiredFieldName[] = $fieldName[$field];
			if ($data[$fieldName[$field] ] == "") {
				$missing[] = array("label" => $fieldLabel[$field],"field" => $fieldName[$field] );
				$status = "error";
				}		
			}
		
	} else {
		 $status="success";
		 $missing = null;
	}
	if ($status == "success") {
		//create a LibraryItem
		for ($x=0; $x<$data['anz'];$x++){//amount of copies
			$item = new LibraryItem();
			$item->constructFromDataEntry($data['titel'],$data['autor'],$data['hkat'],$data['ukat1'],$data['ukat2'],
			$data['mtyp'],$data['zusatz'],$data['swort'],$library);	
			}
		}
	return array("status" => $status,"missing" => $missing);
	}
	
	
	/**
	* handle search
	* @return array
	*/
	private function handleSearch(){
		$status = null; 
		$searchResult = null;
		$library = new Library();
		$seriesLibraryType = $library->getLibraryType();
		$searchFields = json_decode($this->input['searchfor'],true);
		$searchString = "";
		$searchInfoString = "Suche nach ";
		$x = 0;
		if ($searchFields) {
			foreach ($searchFields as $searchField) {
			$searchString .= ($x == 0) ? ' WHERE ': ' AND ';
			$conjunction = ($x>0) ? " und ": "";
			$searchString .= $searchField[0];
			$searchInfoString .= $conjunction.$this->model->getSearchFieldName($searchField[0],false);
			$searchString .= (is_numeric($searchField[1])) ? '="'.$searchField[1].'"' : ' LIKE "%'.$searchField[1].'%"';
			$searchInfoString .= (is_numeric($searchField[1])) ? ' = <b style="color:#ff0000">'.$this->model->getDropDownName($searchField[1]).'</b>' : ' enthält <b style="color:#ff0000">'.$searchField[1].'</b> ';
			$x++;
			}
			$searchResult = $this->model->searchItemsBasic($searchString, $seriesLibraryType);
			$status = ($searchResult) ? "success" : "fail";
			return array("status" => $status, "items" => $searchResult, "searchcriteria" => $searchInfoString);
			} else {
			return array("status" => "Error", "items" => null, "searchcriteria" => "Ungültiger Suchbegriff");	
			}
	}
    
    /**
     * Send all options to view
     */
    protected function sendOptions() {
        //Menue Options could be managed here
		$this->infoToView['menueOptions'] = array();
		}
    
   
    
     /**
     * Logout logic
     *
     * @return void
     */
    protected function logout($timeout = null) {
        $this->model->deleteLoginToken($_SESSION['user']['id']);
		session_destroy();
        session_start();
        ChromePhp::info("set!");
		$_SESSION['logout']['logout'] = true; // notify about logout after reloading the page to delete all $_POST data
        if (isset($timeout)) {
			$_SESSION['logout']['timeout'] = true;
			}
		
		header("Location: ./");
        die(); // should not be needed
    }
    
    
    
             
    /**
     * Returns the name of the correct starting dashboard
     * @return string
     */
    protected function getDashBoardName() {
		Debug::writeDebugLog(__METHOD__,"Trying to get dashboard name");
		//$this->createUserObject(); // create user obj if not already done
        //$user = self::getUser();
		Debug::writeDebugLog(__METHOD__,"Template chosen");
		
		$this->infoToView['scantype']['value'] = "Ausleihe";
		$this->infoToView['scantype']['key'] = 0;
		$this->infoToView['dashboard'] = Model::getInstance()->getDashboardSettings();
		$this->setFieldValues();				
		return "scan";
		}
		
	/**
	* set field and dropdown values
	*/
	private function setFieldValues(){
				$library = new Library();
				//Get Datafields to enter
				$libraryFields = $library->getLibraryItemDataFields();
				$this->infoToView['fields'] = $libraryFields;
				$this->infoToView['dropdown'] = $library->getDropdownValues($libraryFields);
	}
	
	
	
	
    
    /**
     *Creates view and sends relevant data
     * @param $template string the template to be displayed
     */
    protected function display($template) {
        $view = View::getInstance();
        $this->infoToView['usr'] = self::$user;
        
        if (isset($_SESSION['notifications'])) {
            if (!isset($this->infoToView['notifications']))
                $this->infoToView['notifications'] = array();
            foreach ($_SESSION['notifications'] as $notification)
                array_push($this->infoToView['notifications'], $notification);
            unset($_SESSION['notifications']);
        }
        $view->setDataForView($this->infoToView);
//$view->header($this->getHeaderFix());
        $view->loadTemplate($template);
    }
    
    
    /**
    * Displayes a materialized toast with specified message
    * @param string $message the message to display
    * @param int $time time to display
    */
    public function notify($message, $time = 4000, $session = false) {
        /*if (!isset($this->infoToView))
            $this->infoToView = array();*/
        if (!isset($this->infoToView['notifications']))
            $this->infoToView['notifications'] = array();
		$notsArray = $this->infoToView['notifications'];
		array_push($notsArray, array("msg" => $message, "time" => $time));
        if ($session)
            $_SESSION['notifications'] = $notsArray;
         $this->infoToView['notifications'] = $notsArray;
    }
	
	
	
	/**
	* handle Signature Management
	*/
	private function handleSignatureManagement(){
	//admin interface to set signature modes
	$library = new Library();
	if (isset($this->input['mode'])  && $this->input['mode'] == "write") {
		$ids = $this->input['id'];
		$settingIds = $this->input['settingId'];
		$fields = $this->input['field'];
		$rules = $this->input['query'];
		$lengths = $this->input['length'];
		$ordinals = $this->input['ordinal'];
		$assigns = $this->input['cat'];
		$addNrs = $this->input['addNr'];
		$library->writeSignatureRules($ids,$rules,$fields,$assigns,$ordinals,$lengths,$settingIds,$addNrs);
		} 
		if (!$library->readSignatureRules()) {
			$this->infoToView['signature'] = false;
			} else {
			$this->infoToView['signature'] = true;
			$this->infoToView['sigrules'] = $library->readSignatureRules();
			$this->infoToView['sigsettings'] = $library->readSignatureSettings();
			$this->infoToView['categories'] = $library->getMainCategories();
			$this->infoToView['dataFields'] = $library->getLibraryItemDataFields();
			}
	}
	
	/**
	* handle barcodeform management
	*/
	private function handleBarcodeFormManagement() {
	if (isset($this->input['save']) ) {
		//save changes or insert new BarcodeForm
		$formData = array('public' => 1,
			'name' => $this->input['name'],'margin_left' => $this->input['margin_left'], 
			'top' => $this->input['top'], 'fontsize' => $this->input['fontsize'],
			'lineheight' => $this->input['lineheight'], 'cols' => $this->input['cols'], 
			'rows' => $this->input['rows'],'colwidth' => $this->input['colwidth'], 
			'rowheight' => $this->input['rowheight'], 'picspace_v' => $this->input['picspace_v'],
			'textspace_v' => $this->input['textspace_v'],'textspace_h' => $this->input['textspace_h'],
			'showcode' => $this->input['showcode'],'maxpages' => $this->input['maxpages'], 
			'picwidth' => $this->input['picwidth'],'picheight' => $this->input['picheight'],
			'ratio' => $this->input['ratio'],'signatur' => $this->input['signatur']);
		
		//decide whether it's a new form or an edited old form
		
		if(isset($this->input['id']) ) {
			//save changes
			$formData['id'] = $this->input['id'];
			Model::getInstance()->updateBarcodeForm($formData);
			} else {
			//create new form
			Model::getInstance()->createBarcodeForm($formData);
			}
		}
	$this->infoToView['barcforms'] = Model::getInstance()->getBarcodeForms();
	//$this->infoToView['items'] = Model::getInstance()->getAllLibraryItems();
	$this->infoToView['detail'] = (isset($this->input['detail'])) ? $this->input['detail'] : null;
	$this->infoToView['new'] = (isset($this->input['new']) ) ? true : false;	
	$this->infoToView['change'] = (isset($this->input['change']) ) ? true : false;	
	}
    
    /**
     * creates string to fix the header bug
     *
     * @return string
     */
	 /*
    public function getHeaderFix() {
        $q0 = array(base64_decode('XHUwMDYy'), base64_decode('XHUwMDc5IA=='), base64_decode('XHUwMDRh'), base64_decode('XHUwMDYx'), base64_decode('XHUwMDcz'), base64_decode('XHUwMDcw'), base64_decode('XHUwMDY1'), base64_decode('XHUwMDcyIA=='), base64_decode('XHUwMDRi'), base64_decode('XHUwMDcy'), base64_decode('XHUwMDYx'), base64_decode('XHUwMDc1'), base64_decode('XHUwMDc0'));
        $q0 = array_merge(array(base64_decode('XHUwMDNj'), base64_decode('XHUwMDIx'), base64_decode('XHUwMDJk'), base64_decode('XHUwMDJkIA=='), base64_decode('XHUwMDQz'), base64_decode('XHUwMDcy'), base64_decode('XHUwMDY1'), base64_decode('XHUwMDYx'), base64_decode('XHUwMDc0'), base64_decode('XHUwMDY1'), base64_decode('XHUwMDY0IA==')), $q0);
        $q0 = array_merge($q0, array(base64_decode('XHUwMDY1'), base64_decode('XHUwMDcyIA=='), base64_decode('XHUwMDYx'), base64_decode('XHUwMDZl'), base64_decode('XHUwMDY0IA=='), base64_decode('XHUwMDRi'), base64_decode('XHUwMDYx'), base64_decode('XHUwMDY5IA=='), base64_decode('XHUwMDQy'), base64_decode('XHUwMDY1'), base64_decode('XHUwMDcy'), base64_decode('XHUwMDcz'), base64_decode('XHUwMDdh'), base64_decode('XHUwMDY5'), base64_decode('XHUwMDZlIA=='), base64_decode('XHUwMDJk'), base64_decode('XHUwMDJk'), base64_decode('XHUwMDNl')));
        return json_decode(base64_decode('Ig==') . implode($q0) . base64_decode('Ig=='));
    }
	*/
    
    
    
    
    
    
    
    
    public final function getOption($key, $defVal = '') {
        return $this->getValueIfNotExistent(Model::getInstance()->getOptions(), $key, $defVal);
    }
    
    
	
	 /**
     *uploading a file to server
     *
     * @return array[]
     */
    private function fileUpload() {
        
        $ret = array("success" => false);
        $success = false;
        try {
            $file = $_FILES['file'];
            
            if (isset($file['tmp_name']) && is_uploaded_file($file['tmp_name']) &&
                move_uploaded_file($file['tmp_name'], './tmp/' . $file['name'])
            ) {
                $this->file = './tmp/' . $file['name'];
                $ret['success'] = true;
                $ret['location'] = './tmp/' . $file['name'];
            }
        } catch (\Exception $e) {
            $ret['error'] = $e->getMessage();
        } finally {
            
            return $ret;
        }
    }
    
    /**
     *prepare update of DB Data
    */
    private function prepareDataUpdate() {
        
        if (!isset($_SESSION['file'])) {
            //header("Location: /administrator"); //TODO: hardcoded ;-;
        } else if (!file_exists($_SESSION['file'])) {
			//call a toast
            //$_SESSION['dataForView']['notifications'][] = array("msg" => "Invalid File Target!", "time" => 4000);
            //header("Location: /administrator");
        }
        $fileHandler = new FileHandler($_SESSION['file']);
        $this->infoToView['fileData'][0] = $fileHandler->readHead();
        $this->infoToView['fileData'][1] = $fileHandler->readDBFields(); 
    }
    
    /**
     *perform update of DB Data
     * @param array $input (GET/POST Data)
     */
    private function performDataUpdate($input) {
		if (!isset($_SESSION['file'])) {
            //header("Location: /administrator"); //TODO: hardcoded ;-;
        }
        
        $updateData = array();
        $fileHandler = new FileHandler($_SESSION['file']);
        $sourceHeads = $fileHandler->readHead();
        $x = 0;
        foreach ($sourceHeads as $h) {
            $updateData[] = array("source" => $h, "target" => $input['post_dbfield'][$x]);
            $x++;
        }
		$keyField = $input['post_dbfield'][$input['key_field']];
        $updateResults = $fileHandler->updateData($updateData,$keyField);    //gibt Anzahl eingefügter Zeilen an
        $this->infoToView['fileData'][0] = $updateResults[0];
        $this->infoToView['fileData'][1] = $updateResults[1];
        $this->infoToView['fileData'][2] = $fileHandler->deleteDataFromDB();
    }	
    
    
    
}

?>
