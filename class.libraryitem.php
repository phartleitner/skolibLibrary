<?php
/**
* class represents all items like books etc
*/
class LibraryItem {

/**
* int ID (tNr)
*/
private  $id;
/**
* string Title	
*/
private $title;
/**
* string author
*/
private $author;
/**
* array category
*/
private $category;
/**
* array subCategory1
*/
private $subCategory1;
/**
* array category
*/
private $subCategory2;
/**
* string barcode
*/
private $barcode;
/**
* string signature
*/
private $signature = null;
/**
* int mediaType
*/
private $mediaType;
/**
* string various
*/
private $various;
/**
* string keyword
*/
private $keywords;

/********************
***Getter & Setter***
********************/
/**
*Set ID
* @param int $id
*/
public function setId($id) {
	$this->id = $id;
	//check requirement
}
/**
* Set Barcode	
* @param string Barcode
*/
public function setBarcode($barcode) {
		$this->barcode = $barcode;
		//check requirement
}
/**
* Set Signature	
* @param string Signature
*/
private function setSignature($signature) {
		$this->signature = $signature;
}
/**
* Set author	
* @param string author
*/
private function setAuthor($author) {
		$this->author = $author;
}
/**
* Set title	
* @param string title
*/
private function setTitle($title) {
		$this->title = $title;
}
/**
* Set category
* @param string category
*/
private function setCategory($category) {
		$this->category = $category;
}
/**
* Set subcategory1
* @param string subcategory1
*/
private function setSubCategory1($subcategory1) {
		$this->subcategory1 = $subcategory1;
}
/**
* Set subcategory2
* @param string subcategory2
*/
private function setSubCategory2($subcategory2) {
		$this->subcategory2 = $subcategory2;
}
/**
* Set mediaType
* @param string mediaType
*/
private function setMediaType($mediaType) {
		$this->mediaType = $mediaType;
}
/**
* Set various
* @param string various
*/
private function setVarious($various) {
		$this->various = $various;
}
/**
* Set various
* @param string various
*/
private function setKeywords($keywords) {
		$this->keywords = $keywords;
}
/**
* get ID
* @return int
*/
public function getId(){
	return $this->id;
}
/**
* get Barcode
* @return string
*/
public function getBarcode(){
	return $this->barcode;
}
/**
* get Signature
* @return string
*/
public function getSignature(){
	return $this->signature;
}
/**
* get category (Main category)
* @return string
*/
public function getCategory(){
	return $this->category;
}
/**
* get Subcategory1
* @return string
*/
public function getSubCategory1(){
	return $this->subCategory1;
}
/**
* get Subcategory2
* @return string
*/
public function getSubCategory2(){
	return $this->subCategory2;
}
/**
* get Mediatype
* @return string
*/
public function getMediaType(){
	return $this->mediaType;
}
/**
* get various
* @return string
*/
public function getVarious(){
	return $this->various;
}
/**
* get keywords
* @return string
*/
public function getKeywords(){
	return $this->keywords;
}
/**
* get Title
* @return string
*/
public function getTitle(){
	return mb_convert_encoding($this->title,'UTF-8');
}
/**
* get Author
* @return string
*/
public function getAuthor(){
	return mb_convert_encoding($this->author,'UTF-8');
}

/**
* Constructor 
* @param string Barcode
*/
public function constructFromBarcode($barcode) {
	$this->barcode = $barcode;
	if ($this->id = Model::getInstance()->getLibraryItemIdByBarcode($barcode)) {
		return true;
	} else {
		return false;
	}
}

/**
* Constructor
* @param int ID
*/
public function constructFromId($id) {
	$this->id = $id;
	}


	
/**
* Constructor
* @param string title
* @param string author
* @param int category
* @param int subcategory1
* @param int subcategory2
* @param int mediaType
* @param string various
* @param string keywords
* @param Library Object library
*/
public function constructFromDataEntry($title, $author, $category, $subCategory1 = null, 
$subCategory2 = null, $mediaType, $various = null, $keyWords = null,$library){
$this->title = str_replace("'","\\'",$title );
$this->author = str_replace("'","\\'",$author );
$this->category = array("key" => $category,"value" => $library->getAllCategoriesById()[$category]) ;
if (isset($subCategory1) && $subCategory1 <> 0 ) {$this->subCategory1 = array("key" => $subCategory1,"value" => $library->getAllCategoriesById()[$subCategory1]);}
if (isset($subCategory2) && $subCategory2 <> 0) {echo $subCategory2;$this->subCategory2 = array("key" => $subCategory2,"value" => $library->getAllCategoriesById()[$subCategory2]);}
$this->mediaType = $mediaType;
$this->various = $various;
$this->keyWords = $keyWords;
//enter into DB
Model::getInstance()->enterItemIntoTableTitle($this);

}


/**
* get all item data and set values of object
*/
public function getItemDetails(){
$itemDetails = Model::getInstance()->getLibraryItemDetails($this->id);
$this->setTitle(str_replace("'","\\'",mb_convert_encoding($itemDetails['titel'],'UTF-8')));
$this->setAuthor(str_replace("'","\\'",mb_convert_encoding($itemDetails['autor'],'UTF-8')));
$this->setSignature($itemDetails['signatur']);
$this->setBarcode($itemDetails['barcode']);
$this->setCategory($itemDetails['hkat']);
$this->setSubCategory1($itemDetails['ukat1']);
$this->setSubCategory2($itemDetails['ukat2']);
$this->setMediaType($itemDetails['mtyp']);
return $itemDetails;
}


/**
* get all item data and set values of object
*/
public function getItemBasics(){
$itemDetails = Model::getInstance()->getLibraryItemDetails($this->id);
$this->setTitle(str_replace("'","\\'",mb_convert_encoding($itemDetails['titel'],'UTF-8')));
$this->setBarcode($itemDetails['barcode']);
return $itemDetails;
}

/**
* get all item details as array to be used as JSON
* @return array()
*/
public function getDetailArrayForJSON(){
$detailArray = $this->getItemDetails();
$historyArray = $this->getItemBorrowingHistory();
$seriesLib = Model::getInstance()->getLibraryType();
$return =  array(
"id"=>array("key"=>"Titelnummer","value"=>$this->id),
"titel"=>array("key"=>"Titel","value"=>$detailArray['titel']),
"autor"=>array("key"=>"Autor od. Herausgeber","value"=>$detailArray['autor']),
"hkat"=>array("key"=>"Kategorie","value"=>$detailArray['hkat']['value'],"id" => $detailArray['hkat']['id'] ),
"ukat1"=>array("key"=>"Unterkategorie1","value"=>$detailArray['ukat1']['value'],"id" => $detailArray['ukat1']['id']),
"ukat2"=>array("key"=>"Unterkategorie2","value"=>$detailArray['ukat2']['value'], "id" => $detailArray['ukat2']['id']),
"mtyp"=>array("key"=>"Medientyp","value"=>$detailArray['mtyp']['value'],"id" => $detailArray['mtyp']['id']),
"erfasst"=>array("key"=>"im Bestand seit","value"=>$detailArray['erfasst']),
"barcode"=>array("key"=>"Barcode","value"=>$detailArray['barcode']),
"signatur"=>array("key"=>"Signatur","value"=>$detailArray['signatur']),
"swort"=>array("key"=>"Schlagwort","value"=>$detailArray['schlagwort']),
"zusatz"=>array("key"=>"Sonstiges","value"=>$detailArray['zusatz']),
"history"=>array("key"=>"bisher verliehen","value"=>$historyArray)
);
if ($this->getItemStatus()) {
//Item is borrowed
$customer = $this->getItemStatus()['customer'];
$customer->setCustomerData();
$customerJSON = $customer->getFullName().'('.$customer->getForm().')';
$return["faellig"] = array("key"=>"faellig",
"value"=>array("due"=>$this->getItemStatus()['duedate'],
"customer"=>$customerJSON));
}
if ($seriesLib) {
	$return["ineditable"] = null;
	$return["series"] = Model::getInstance()->getTitleAmount($this->id);
	} else {
	//detect ineditable Fields that makeup the signature an thus cannot be changed
	$ineditables = Model::getInstance()->getIneditableSignatureFields();
	if (isset($ineditables[$detailArray['hkat']['id']]) ) {
		$return["ineditable"] = $ineditables[$detailArray['hkat']['id']] ;	
		} else {
		// default signatureValues	
		$return["ineditable"] = $ineditables[0]; 
		}
	}
return $return;
}

/**
* get BASIC item details as array to be used as JSON
* @return array()
*/
/*
public function getBasicArrayForJSON(){
$detailArray = $this->getItemBasics();
$seriesLib = Model::getInstance()->getLibraryType();
$return =  array(
"id"=>array("key"=>"Titelnummer","value"=>$this->id),
"titel"=>array("key"=>"Titel","value"=>$detailArray['titel']),
);
if ($this->getItemStatus()) {
//Item is borrowed
$customer = $this->getItemStatus()['customer'];
$customer->setCustomerData();
$customerJSON = $customer->getFullName().'('.$customer->getForm().')';
$return["faellig"] = array("key"=>"faellig",
"value"=>array("due"=>$this->getItemStatus()['duedate'],
"customer"=>$customerJSON));
}
return $return;
}		

*/

/**
* get status of Item
* @return array
*/
public function getItemStatus(){
if (Model::getInstance()->getLibraryItemStatus($this->id ) ){
	//item is loaned
	$status = Model::getInstance()->getLibraryItemStatus(($this->id)); 
	$customer = new Customer($status['customer']);
	return array("customer"=>$customer,"duedate"=>$status['duedate']);

	} else{
	//item is available
	return null;
	}	
}


/**
* get borrowing history of item
* @return array
*/
public function getItemBorrowingHistory(){
$borrowingHistoryArray = array();
if ($historyArray = Model::getInstance()->getItemBorrowingHistory($this->id)) {
	foreach($historyArray as $borrowedByAndAt) {
		$customer = new Customer($borrowedByAndAt['userid']);
		$customer->setCustomerData();
		array_push($borrowingHistoryArray,array(
		"customer"=>$customer->getFullName().'('.$customer->getForm().')',
		"returndate"=>$borrowedByAndAt['returndate']) 
		);
		}
	} 
return $borrowingHistoryArray;  
}

/**
* get due Details when item is borrowed
* @return array();
*/
public function getDueDetails(){
return Model::getInstance()->getBorrowedItemDueDetails($this->id);	
}

/**
* borrow item
*/
public function borrowItem(){
//not used - done in customerObject	
}

/**
* return item
*/
public function returnItem(){
Model::getInstance()->returnItem($this->id);	
}

/**
* extend item
*/
public function extendItem(){
Model::getInstance()->extendItem($this->id);	
}

/**
* delete item
*/
public function deleteItem() {
Model::getInstance()->deleteItem($this->id);		
}

/**
* delete item series
*/
public function deleteItemSeries(){
Model::getInstance()->deleteItemSeries($this->id);	
}

/**
* mark printed Date
*/
public function  markPrintedDate(){
Model::getInstance()->enterItemPrintDate($this->id);
}

/**
* change item data
* @param array
*/
public function changeItem($data) {
$changeArray= array();
$changeArray['id']	= $data['id']['value'];
$changeArray['titel'] = $data['titel']['value'];
$changeArray['autor'] = ($data['autor']['value']) ? $data['autor']['value'] : "";
$changeArray['hkat'] = ($data['hkat']['id']) ? $data['hkat']['id'] : 0;
$changeArray['ukat1'] = ($data['ukat1']['id']) ? $data['ukat1']['id'] : 0;
$changeArray['ukat2'] = ($data['ukat2']['id']) ? $data['ukat2']['id'] : 0;
$changeArray['mtyp'] = ($data['mtyp']['id']) ? $data['mtyp']['id'] : 0;
$changeArray['swort'] = ($data['swort']['value']) ? $data['swort']['value'] : "";
$changeArray['zusatz'] = ($data['zusatz']['value']) ? $data['zusatz']['value'] : "";
Model::getInstance()->updateItemData($changeArray);	

/*

Debug::writeDebugLog(__METHOD__,$data['id']['value']);


*/
}


/**
* change a series of items
* @param array
*/
public function changeItemSeries($data){
$changeArray= array();
$id	= $data['id']['value'];
$changeArray['titel'] = $data['titel']['value'];
$changeArray['autor'] = ($data['autor']['value']) ? $data['autor']['value'] : "";
$changeArray['hkat'] = ($data['hkat']['id']) ? $data['hkat']['id'] : 0;
$changeArray['ukat1'] = ($data['ukat1']['id']) ? $data['ukat1']['id'] : 0;
$changeArray['ukat2'] = ($data['ukat2']['id']) ? $data['ukat2']['id'] : 0;
$changeArray['mtyp'] = ($data['mtyp']['id']) ? $data['mtyp']['id'] : 0;
$changeArray['swort'] = ($data['swort']['value']) ? $data['swort']['value'] : "";
$changeArray['zusatz'] = ($data['zusatz']['value']) ? $data['zusatz']['value'] : "";
Model::getInstance()->updateItemSeries($id,$changeArray);		
}

/**
* make Signature
*/
public function makeSignature(){
	//getSignature defaults
	$signature = null;
	if ($separator = Model::getInstance()->getSignatureSeparator() ) {
		$signatureContentArray = Model::getInstance()->getSignatureContent($this);
		$signatureContents = $signatureContentArray['contents'];
		$x = 0;
		foreach ($signatureContents as $se) {
			if ($x == 0) { $signature = $se.$separator;}
			elseif (isset($signatureContents[$x+1]) && $signatureContents[$x+1] ) { $signature .= $se.$separator;}
			else { $signature .= $se;}
			$x++;
			}
		if(	$signatureContentArray['addNr']) {
			//check if Signature already exists, if so add a number
			//number is always added with a doubledash '--' HARDCODED DEFAULT
			if ($nr = Model::getInstance()->checkSignatureForNumbers($signature) ) {
				$signature .= "--".$nr;
			}
			
			}
		}
	$this->signature = str_replace("'","\\'", $signature);
	}

/**
* create Barcode
*/
public function makeBarcode() {
		$library = new Library();
		$minLength = $library->getBarcodeLength();
		$barcode1 = $library->getLibraryId();
		$barcode2 = $this->id;
		$padding = $minLength - strlen($barcode2);
		$this->barcode = $padding > 0 ? str_pad($barcode1,$padding,"0",STR_PAD_RIGHT).$barcode2 : $barcode1.$barcode2;
		}	
	

/**
* check if a barcode is doubledash
* @return bool
*/
public function checkForDoubles() {
	return Model::getInstance()->checkBarcodeForDoubles($this->barcode);
}

	
}

?>