<?php

class Library{

/**
*String Name
*/
private $name;


/**
*Array ReturnTimes and Extension
*/
private $returnStuff = array();

/**
*Array BarcodeForms
*/
private $barcodeForms = array();

/**
* int library id
*/
private $id;


/**
* getLibrary Id
* @return int
*/
public function getLibraryId(){
	$this->setLibraryId();
	return $this->id;
}


/**
* @param Model 
* Konstruktor
*/
public function __construct(){
		
}


/**
* get borrowing defaults
* @user_error
*/
public function getBorrowingDefaults() {
	$defaults = Model::getBorrowingDefaults();
	
}

	
/**
* get BarcodeForms
* @return Array
*/
public function getBarcodeForms($model = null){
		return $this->barcodeForms = Model::getInstance()->getBarcodeForms();
}

/**
* get unprinted Barcodes
* @param boolean nodetail
* @return array(LibraryItem) OR int (when nodetail == false);
*/
public function getUnprintedBarcodes($nodetail){
		return Model::getInstance()->getUnprintedBarcodes($nodetail);
}

/**
* get Barcodes by Entry
* @param array
* @return array(LibraryItem)
*/
public function getBarcodesByEntries($barcs) {
	return Model::getInstance()->getBarcodesByEntries($barcs);
}

/**
* get Inventory
* @return array
*/
public function getInventory(){
		return Model::getInstance()->getInventory(); // this could be different from the basic version (not showing amount but every title)
}

/**
* get Inventory for libraries with many items of the same title 
* less object oriented - focus on collection the data
* @return array
*/
public function getBasicInventory(){
		return Model::getInstance()->getInventory(true);
}

/**
* read signature rules
* @return array()
*/
public function readSignatureRules(){
		return Model::getInstance()->getSignatureElements();
}

/**
* get Signature Print Status
* @return boolean
*/
public function getSignaturePrintState(){
		 return Model::getInstance()->getSignaturePrintState();
}


/**
* get Signature Separator
* @return string
*/
public function getSignatureSeparator(){
		 return Model::getInstance()->getSignatureSeparator();
}

/**
* get library type ( i.e. single items or item series) 
* @return boolean true = series library , false = single items library
*/
public function getLibraryType() {
		return Model::getInstance()->getLibraryType();
}

/**
* get Barcode Length
* @return int
*/
public function getBarcodeLength() {
		return Model::getInstance()->getBarcodeLength();
}

/**
* get Customer Prefix for Barcode
* Must be the same throughout, because it's the basis for scan detection
* @return int
*/
public function getCustomerPrefix() {
		return Model::getInstance()->getCustomerPrefix();
}

/**
* write signature rules
* @param array ids
* @param array queries
* @param array hints
* @param array hkats
* @param array ordinals
* @param array lengths
* @param array settingIds
* @param array addNrs
*/
public function writeSignatureRules($ids,$queries,$fields,$hkats,$ordinals,$lengths,$settingIds,$addNrs) {
		$updates = array();
		$new = null;
		for($x = 0; $x < count($ids); $x++) {
			$updates[]= array("id" => $ids[$x],"query" => $queries[$x], "field" => $fields[$x],
			"hkatId" => $hkats[$x], "ordinal" => $ordinals[$x], "length" => $lengths[$x],
			"settingId" => $settingIds[$x], "addNr" => $addNrs[$x] );	
			
			}
		if ($queries[count($ids)] != "" && $fields[count($ids)] != "") {
			//new Rule entered
			$new =  array("query" => $queries[ count($ids) ], "field" => $fields[ count($ids) ],
			"hkatId" => $hkats[ count($ids) ], "ordinal" => $ordinals[ count($ids) ], "length" => $lengths[ count($ids) ]);	
			}
			
		Model::getInstance()->writeSignatureRules($updates,$new);
}

/**
* read signature settings
* @return array()
*/
public function readSignatureSettings(){
		return Model::getInstance()->getSignatureSettings();
}

/**
* read Library ID - required for Barcode creation
*/
public function setLibraryId() {
	$this->id = Model::getInstance()->getLibraryId();
}



/**
* get Main categories
* @return array()
*/
public function getMainCategories(){
		return Model::getInstance()->getMainLibraryCategories();
}

/**
* getAllCategories indexed by Id
* @param int category dwNr [optional]
* @return array()
*/
public function getAllCategoriesById($cat = null){
	return Model::getInstance()->getAllCategoriesById($cat);
}


/**
* get datafields for new ItemEntry
* @return array()
*/
public function getLibraryItemDataFields(){
	return Model::getInstance()->getLibraryItemDataFields();
}

/**
* get dropdown values for nuemric database Values
* @param array()
* return array()
*/
public function getDropdownValues($libraryFields) {
	$dropdown = array();
	foreach ($libraryFields as $fields) {
		if ($fields['dwNr']) {
			$dropdown[ $fields['dwNr'] ] = $this->getAllCategoriesById($fields['dwNr']);
			}
		}
	return $dropdown;
}


/**
* get setupData
* @return array
*/
public function getSetupData(){
return Model::getInstance()->getLibraryDefaultSetups();	
}

/**
* check database for double barcodes
* @return array
*/
public function checkDatabaseForDoubles(){
	return Model::getInstance()->checkDatabaseForDoubles();
}



/**
* create a csv file with borrowed titles
* @return string
*/
public function createBorrowedItemsCSV(){
	$borrowedItems = Model::getInstance()->getBorrowedItemsForCSV();
	
	$fileName = $_SESSION['organisation']['database'].'/dwnld/borroweditems.csv';
	
	$fh = fopen($fileName,"w");
	fwrite($fh,pack("CCC",0xef,0xbb,0xbf)); // ensures UTF-8 encoding
	fwrite($fh,mb_convert_encoding("Klasse;Name;Vorname;Titel;Barcode;Fälligkeitsdatum\r\n",'UTF-8') );
	foreach($borrowedItems as $b) {
	$line = $b['form'] . ';' . $b['sn'] . ';' . $b['gn'] . ';' . $b['title'] . ';' . 
	$b['barcode'] . ';' . $b['faellig'];
	$line .= "\r\n";
	fwrite($fh,mb_convert_encoding($line,'UTF-8') );
	}
	
	
	fclose($fh);
	return $fileName;
	}	
}

?>