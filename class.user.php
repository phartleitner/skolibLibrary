<?php

/**
 * User class used to get user related data easily
 */
class User {
    /**
     * @var int 0 -> SuperAdmin, 1 -> Admin, 2 -> LibraryHelper
     */
    protected $type;
    /**
     * @var int userId
     */
    protected $id;
    /**
     * @var $surname string Surname name of the user
     */
    protected $surname;
    /**
     * @var $surname string Name name of the user
     */
    protected $name;
	/**
	* @var string organisation
	*/
	protected $organisation;
	/**
	* @var array
	*/
	protected $right;
	
	/**
	* @var string
	*/
	protected $login;

	
    
    /**
     *Construct method of User class
     *
     * @param int $id userId
     * @param int $type
     * @param string $email
     * @param string $name
     * @param string $surname
     */
    public function __construct($id, $type = null, $name =null, $surname = null ) {
        $this->id = $id;
        $this->type = $type;
        $this->name = $name;
        $this->surname = $surname;
		
    }
	
	/*
	* set User Data
	* @return array
	*/
	public function setUserData(){
	Debug::writeDebugLog(__METHOD__,"...fetching user data");
	$data = Model::getInstance()->getUserData($this->id);
	$this->surname = $data['name'];
	$this->name = $data['vorname'];
	$this->login = $data['login'];
	$this->right = Model::getInstance()->getUserRights($this->id); //Library Value can be added
	}
	
    
    /**
     *Returns user ID
     *
     * @return int id
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     *Returns user type (0 for admin, 1 for parent, 2 for teacher, 3 for StudentUser)
     *
     * @return int type
     */
    public function getType() {
        return $this->type;
    }
    
    /**
	* get Fullname
	* @return string
	*/
    public function getFullname() {
        return $this->name . ' ' . $this->surname;
    }
    /**
	* get name
	* @return string
	*/
    public function getName() {
        return $this->name;
    }
    /**
	* get Surname
	* @return string
	*/
    public function getSurname() {
        return $this->surname;
    }
    /**
	* get right
	* @return array
	*/
    public function getRight() {
		return $this->right;
	}
	/**
	* get login
	* @return string
	*/
	public function getLogin() {
		return $this->login;
	}
	
	
    /**
     * @return array[String => Data] used for creating __toString and jsonSerialize
     */
    public function getData() {
        return array("userid" => $this->id, "type" => $this->type, "name" => $this->name, "surname" => $this->surname);
    }
	
	/**
	* update UserData
	* @param string surname
	* @param string name
	* @param string login
	* @param string pass
	*/
	public function updateUserData($surname, $name, $login, $pass) {
	$this->surname = $surname;
	$this->name = $name;
	$this->login = $login;
	
	Model::getInstance()->updateUserData($this, $pass);
	
	}
	
    
    
}
/**
 * Customer as subclass of User 
 */
class Customer extends User{

/**
* @var string Klasse
*/
private $form;

/**
* @var string Barcode
*/
private $barcode;

/**
* @var string ASV Id
*/
private $asvId;

/**
* @var string otherId
*/
private $otherId;

/**
* Constructor
* @param id
*/
public function __construct($id) {
	parent::__construct($id);
	
}

/**
* Construct from data entry
* e.g. via import through a file
* @param string name
* @param string vorname
* @param string Klasse
* @param string ASV ID
* @param string anyother Id
*/
public function constructFromData($name,$vorname,$klasse,$asvId = null, $otherId = null){
	$this->surname = str_replace("'","\\'",$name);
	$this->name = str_replace("'","\\'",$vorname);
	$this->form = $klasse;
	if(isset($asvId)) {$this->asvId = $asvId; }
	if(isset($otherId)) {$this->otherId = $otherId; }
	
	$this->id = Model::getInstance()->enterCustomerDataIntoDB($this);
	$this->makeBarcode();
	Model::getInstance()->enterCustomerBarcode($this->id,$this->barcode);
	}
	
/**
* make Barcode
* consists of "10"+padding+id to reach given number of digits
*/
public function makeBarcode() {
$library = new Library();
$minLength = $library->getBarcodeLength();
//$barcode1 = $library->getCustomerPrefix(); - method is prepared, though not in use!
$barcode1 = "10"; //HARDCODED DO NOT ALTER
$barcode2 = $this->id;
$padding = $minLength - strlen($barcode2);
$this->barcode = $padding > 0 ? str_pad($barcode1,$padding,"0",STR_PAD_RIGHT).$barcode2 : $barcode1.$barcode2;
}

/*
* set User Data
* @return array
*/
public function setCustomerData(){
$data = Model::getInstance()->getCustomerData($this->id);
if($data) {
$this->surname = $data['name'];
$this->name = $data['vorname'];
$this->form = $data['klasse'];
$this->barcode = $data['barcode'];
return true;
} else {
	return false;
}
}


/**
*get Customer's form class
* @return string
*/
public function getForm() {
	return $this->form;
	}
/**
*get Customer's Barcode
* @return string
*/
public function getBarcode() {
	return $this->barcode;
	}

/**
*get Customer's ASV Id
* @return string
*/
public function getAsvId() {
	return $this->asvId;
	}
	
/**
*get Customer's other Id
* @return string
*/
public function getOtherId() {
	return $this->otherId;
	}



/**
* customer borrows item
* @param LibrayItem Object
*/
public function borrowItem($item){
	Model::getInstance()->borrowItem($item->getId(),$this->id);
}

/**
* get Account Data - all currently borrowed items
* @return array(LibraryItems)
*/
public function getBorrowersAccountData(){
	$data = Model::getInstance()->getBorrowersAccountData($this->id);
    if (!$data) {
	return false;
	} else {
			$itemList = array();
			foreach ($data as $d) {
				$item = new LibraryItem();
				$item->constructFromId($d);	
				array_push($itemList,$item);
				unset($item);			
				}
	return $itemList;
	}
}


/**
* get account data as JSON usable array
* @return array
*/
public function getBorrowersAccountDataForJSON(){
	$dataArray = array();
	if ($itemList = $this->getBorrowersAccountData()) {
		foreach($itemList as $item) {
			$itemArray = $item->getDetailArrayForJSON();
			array_push($dataArray,$itemArray);
			}
	}
	else {
	$dataArray  = null;	
	}
	return $dataArray;
}

/**
* get Titles to be reminded of
* @return array
*/
public function getTitlesToRemind(){
	return Model::getInstance()->getTitlesToRemind($this->id);
}

}

?>