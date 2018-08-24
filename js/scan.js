/**
* script processing the scanned barcodes
* using jquery
*/

/**
* Variables
*/
var input; // scanned barcode, digits only
var itemBuffer = new Array(); // all items scanned during one scanning process
var barcodeBuffer = new Array(); //all scanned Barcode - used to check for doubles
var itemIdArray = new Array(); //array with only item ids
var customer ; // barcode representing customer, only once during one scanning process
var itemInfoStatusColor; //color for infoBox - can change 
var GETStringAdd = ''; //string adding info to finalize scan
var GETCustomActionAdd = ''; //info for extra actions in console mode
var customerPrefix = '';

$(document).ready(function(){
	scanMode = $('#scanmode').val();
	//console.info(scanMode);
	document.getElementById('scan').focus();
	
	});

/**	
* action on pressed Enter key
* i.e. scanner input
*/
$(document).keypress(function(e){
	if(e.which == 13) {
		//console.info(customerPrefix);
		$('#warning').html('');
		$('#favourites').remove();
		$('#stock-details').hide();	
		$('#due-items').hide();	
		var message = new Array();
		//document.getElementById("warning").innerHTML = '';
		if ( /^\d+$/.test($('#scan').val()) ) {
			    input = $('#scan').val();
				$('#scan').val('');
				inputString = String(input);
				if (parseInt(inputString.substring(0,2),10) == customerPrefix  && parseInt(scanMode,10) == 2) {
				message["key"] = "error";
				message["code"] = 405;
				throwWarning(message, true);				
				} else {
				if ($.inArray(input, barcodeBuffer) != -1) {
						message["key"] = "error";
						message["code"] = 409;
						throwWarning(message, true);
						//$('#warning').html('<span class="red-text"><b ></b></span>');	
						}else {
						processScan(scanMode);
						}	
				}	
			}	 else {
				$('#scan').val('');	
				message["key"] = "error";
				message["code"] = 408;
				throwWarning(message);
				//$('#warning').html( '<span class="red-text"><b >Ungültiger Scan - kein Barcode!</b></span>' );	
				if (itemBuffer.length > 0) {createItemList();	}			
			 }
		 }    
});

/**
* controls input scans
* @param string scanMode
*/
function processScan(scanMode) {
document.getElementById('scan').focus();
//console.info(input);
var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
// data will be send from webserver as JSON string
var jsob = this.responseText;
//console.info("Response FROM PHP: "+jsob);
//console.info(typeof(jsob) );
//transform into JS array
var result = $.parseJSON(jsob);
//console.info("JS array:"+result);
//console.info(typeof(result));
if (scanMode == 0) {
	//console.info(barcodeBuffer);
	inputString = String(input);
	if (parseInt(inputString.substring(0,2),10) != customerPrefix ) {
		//avoids customers being put into the barcode buffer list
		barcodeBuffer.push(input);
		}
	}
if (result['return']['order']) {
	//console.info("order"+result['return']['order']);
switch(result['return']['order']){
	case 101:
	//customer scanned, item(s) prior to that
	//borrowing process finished
	throwWarning(result['return']);
	if(result['return']['key'] == "success") {
	//show customer account
	createCustomerAccountList(result['customer'],result['items']);
	//ready for new transaction
	finalizeProcess();
	}
	break;
	case 001:
	//customer scanned, no item(s) prior to that
	customer = result['customer'];
	throwWarning(result['return']);
	//show customer account
	if (scanMode == 1) {
		//do not show customer account list on attempt to scan customer in returning or without prior bookscanb in borrowing mode
		createCustomerAccountList(result['customer'],result['items']);
		addItemsToGetData(customer['id']);
		}
	break;
	case 100:
	//item scanned, no customer prior to that
	throwWarning(result['return']);
	
	if(scanMode == 0 && result['item']){
		//waiting for next item or customer scan
		if (result['item']['status']['statuscode'] == "1"){
		itemBuffer.push(result);
		//console.info("Item Buffer"+itemBuffer);
		itemIdArray.push(result['item']['id']['value']);
		addItemsToGetData(false);
		}
		//show item list
		if (itemBuffer.length > 0 ) {
			createItemList();
			}
		} else if (scanMode == 1) {
		//info scan
		//showItemData
		if(result['item']) {
			createItemInfoView(result);
			}
		}else if (scanMode == 2){
		//returning transaction terminated
		createCustomerAccountList(result['currentBorrower'],result['currentBorrower']['items'],scanMode);
		createItemInfoView(result);
		finalizeProcess();
		} 
	break;
	case 111:
	//item scanned, customer prior to that
	//borrowing process finished
	throwWarning(result['return']);
	if(result['return']['key'] == "success") {
		//show customer account
		createCustomerAccountList(result['customer'],result['items']);
		//ready for new transcation
		finalizeProcess();
		}
	break;
	default:
	break;
	}	
}
}
};
//console.info("Posting - Input: "+input);
//console.info("Posting - GET: "+"?type=scan&mode="+scanMode+"&input="+input+GETStringAdd+GETCustomActionAdd);

xhttp.open("POST", "?type=scan&mode="+scanMode+"&input="+input+GETStringAdd+GETCustomActionAdd, true);
xhttp.send();
GETCustomActionAdd = ''; // must be empty for next scan
}


/**
* throw warning / info
* @param array
* @param bool
*/
function throwWarning(textArr){
	if (textArr['key'] == "error") {
	color = "red-text";
}	else {
	color = "green-text";
}
$('#warning').html('<b class="'+color+'" >'+getStatusText(textArr['code'])+'</b>');		
}

/**
* get status Text (to be shown under scan field)
* @param int code
* @return string
*/
function getStatusText(code){
switch(code) {
	case 100:
		statusText = "Buch oder Benutzer scannen";
		break;
	case 200:
		statusText = "Ausleihvorgang abgeschlossen. Bereit für neuen Vorgang";
		break;
	case 201:
		statusText = "Kontoinformationen nebenstehend";
		break;
	case 202:
		statusText = "Artikelinformationen nebenstehend";
		break;
	case 203:
		statusText = "Artikel zurückgegeben!";
		break;
	case 300:
		statusText = "Buch scannen um Vorgang abzuschließen";
		break;
	case 402:
		statusText = "Beginnen Sie die Ausleihe mit einem Buch!";
		break;
	case 403:
		statusText = "Artikel ist nicht verliehen!";
		break;
	case 404:
		statusText = "Barcode ist keinem Benutzer zugeordnet";
		break;
	case 405:
		statusText = "Barcode ist keinem Buch zugeordnet!";
		break;
	case 406:
		statusText = "Artikel nicht verfügbar! Neues Buch scannen!";
		break;
	case 407:
		statusText = "Dieser Artikel ist zur Zeit verliehen!";
		break;
	case 408:
		statusText = "Ungültiger Barcode!";
		break;
	case 409:
		statusText = "identischer Scan bereits erfolgt!";
		break;
	case 410:
		statusText = "Barcode mehrfach vorhanden! Führen Sie eine Doublettenprüfung durch!";
		break;
	default:
		statusText = "";
		break;
}
return statusText;
}

/**
* remove an item from the list of scanned items
* i.e. remove it from itemBuffer, BarcodeBuffer and itemIdArray
* @param barcode
* @param id
*/
function removeItemFromList(barcode,id){
	barcode = barcode.toString();
	id = id.toString();
	if ($.inArray(barcode, barcodeBuffer) != -1 ){
		barcodeBuffer.splice(barcodeBuffer.indexOf(barcode),1 );
		}
	if ($.inArray(id, this.itemIdArray) != -1 ){
		itemIdArray.splice(itemIdArray.indexOf(id),1 );
		}
	for(var i =0;i< itemBuffer.length;i++) {
		if (itemBuffer[i]['item']['id']['value'] == id ) {
			itemBuffer.splice(i,1);
			}
		}
	this.createItemList();
	addItemsToGetData(false); 
	document.getElementById('scan').focus();
}


/**
* creates HTML code for a list of scanned items
*/
function createItemList(){
var list = '';
if (itemBuffer.length >0) {
	list += '<h5 class="orange-text">Verlauf</h5><table class="striped">';
	for (var i=0;i < itemBuffer.length;i++){
		list += '<tr><td width="10">'+(i+1)+'</td><td>'
		+itemBuffer[i]['item']['titel']['value']+'</td><td>'
		+itemBuffer[i]['item']['autor']['value']+'</td><td>'
		+'<a href="#" onClick="removeItemFromList('+itemBuffer[i]['item']['barcode']['value'] +','+itemBuffer[i]['item']['id']['value'] +')"><i class="material-icons red-text">clear</i></a></tr>'
		}
		list += "</table>";
	}
	else {
	 list = ' ';	
	}
	$('#list').html(list);  
}

/**
* creates list of information of a scanned item
* @param array
*/
function createItemInfoView(dataArr) {
	showItemInfoHTML(dataArr['item'],"aktueller Scan",true);
	$('#showHide').click(function() {showHide()});
}



/**
* creates HTML for a list of customer's account (i.e. borrowed items)
* @param array customer
* @param array items
*/
function createCustomerAccountList(customer,items,scanMode) {
	//console.info("Lib:"+seriesLibrary);
if (scanMode === undefined) {
	scanMode = null;
}
//console.info(scanMode);
var color;
var content = '<div ><h5 class="orange-text">Kontoübersicht für '
+customer['vorname']+' '
+customer['name']+' ('
+customer['klasse']+') :</h5>';
if (items ){
content +='<table class="striped" border="0" cell-padding="0" cell-spacing="0">';
for (var i=0;i<items.length;i++){
		content += '<tr><td>'
		+items[i]['titel']['value'];
		if (items[i]['signatur']['value'] != "") {content += ' ('+items[i]['signatur']['value']+')';}
		content += ' [Barcode: '+ items[i]['barcode']['value']+']';
		if (items[i]['faellig']['value'] != null) {
			if (seriesLibrary == false) {
				//console.info(items[i]);
			if( compareDate(items[i]['faellig']['value']['due']) ) {
				color = "green-text";	
				} else {
				color= "red-text";	
				}
			} else {
			color= "red-text";		
			}
			if (null != items[i]['faellig']['value']['due']) {
			content += '<a class="'+color+'">&nbsp; zurück bis: '+items[i]['faellig']['value']['due']+'</a>';
			}
			if (scanMode != 2) {
				content += '<span><a class="right" href="#" onClick="confirmDeleteAction(' + items[i]['barcode']['value'] + ')" title="löschen">' 
				+'<b><i class="material-icons grey-text">delete</i></a></b>'
				+'<a  class="right" href="#" onClick="performCustomAction('+items[i]['barcode']['value']+',2)" title="zurückgeben"><i class="material-icons grey-text">keyboard_return</i></a>'
				+'</span><div id="' + items[i]['barcode']['value'] + '" class="red-text"></div>';
				}		
			}
		
		content += '</td>';
		//function buttons
		if (scanMode == 1) {
		content += '<td>'
		if  (seriesLibrary == false) {
			content += '<span><a class="right" href="#" onClick="performCustomAction('+items[i]['barcode']['value']+',1)" title="verlängern">'
			+'<b><i class="material-icons grey-text">low_priority</i></a></b></span>';	
		}
		
		content += '&nbsp;<span><a class="right" href="#" onClick="performCustomAction('+items[i]['barcode']['value']+',2)" title="zurückgeben"><i class="material-icons grey-text">keyboard_return</i></a></span>';
		content += '</td>';
		}
		content += '</tr>';
	}
content +'</table>'
}
else {
content += '<h6>keine Ausleihen</h6>';	
}
content +'</div>';
if (null != scanMode) {
$('#returnaccount').html(content);
$('#returnaccount').show();	
} else {
$('#customeraccount').html(content);
$('#customeraccount').show();
}
$('#item-info').hide();
$('#item-history').hide();
//WATCH OUT itemInfo div is now working differently			
}


/**
* confirm deletion in customer account view
* @param int
*/
function confirmDeleteAction(barcode) {
	confText = '<span><b>wirklich löschen?</b> ';
	confText += '<a href="#" onClick="delFire('+barcode+')" class="btn-flat right waves-effect waves-red right">OK</a> '; 
	confText += '<a href="#" onClick="hideConfMess('+barcode+')" class="btn-flat right waves-effect waves-red right">Abbrechen</a></span> ';
	$("#"+barcode).html(confText);
	$("#"+barcode).show();
	//'+performCustomAction(currentItem['barcode']['value'] ,action)+'
	//performCustomAction(currentItem['barcode']['value'] ,1);
}

/**
*hide confirmation message
* @param barcode
*/
function hideConfMess(barcode) {
$("#"+barcode).hide();	
}

/**
* fire delete Action
* @param barcode
*/
function delFire(barcode){
	hideConfMess(barcode);
	performCustomAction(barcode,1,null);
}

/**
* perform a custom action like return or delete
* @param string action
* @param int itemId
*/
function performCustomAction(barcode,action,data ) {
if (null == data) {data = null;}
//myPopup = window.open('', "Zweitfenster", "width=300,height=400,left=100,top=200"); 
//myPopup.document.write('<body class="grey"><div>Artikel mit Barcode '+barcode+' endgültig löschen</div></body'); 
msg = "";
GETCustomActionAdd ='&custom='+barcode+'&do='+action;
if(action == "1") {
	//DELETE
	$('#item-info').hide();
	$('#item-history').hide();
	finalizeProcess();
	msg = "Titel gelöscht";	
	} else if (action == "2") {
	//RETURN
	msg = "Titel zurückgegeben";
	$('#item-info').hide();
	$('#item-history').hide();
	}else if (action == "3") {
	//EDIT SINGLE
	GETCustomActionAdd += '&data='+JSON.stringify(data);
	msg = "Änderungen gespeichert";	
	$('#item-edit-details').hide();
	showItemInfoHTML(items[currentItemIndex],"Details");
	$('#item-info').show();
	} else if (action == "4") {
	//DELETE SERIES
	$('#item-info').hide();
	$('#item-history').hide();
	msg = "Alle Exemplare gelöscht";
	finalizeProcess();	
	} else if (action == 5){
	//EDIT SERIES
	GETCustomActionAdd += '&data='+JSON.stringify(data);
	msg = "Änderungen an Serie gespeichert";	
	}	
	else if (action == 6) {
	$msg = "Titel verlängert";
	} 

throwMessage(msg,4000);	

processScan(scanMode );
msg = "";

//myPopup.focus(); 
}



/**
* comparing returndate and todaydate
* @param string returndate
* @return boolean
*/
function compareDate(returnDate){
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
mm = '0'+mm;
mm = mm.slice(-2);
var yyyy = today.getFullYear();
var todayDate = yyyy+mm+dd;
var returnDateArr = returnDate.split(".");
var returnDateCompareString = returnDateArr[2]+returnDateArr[1]+returnDateArr[0];
if (returnDateCompareString > todayDate) {
	return true;
	} else {
	return false;	
	}

}


/**
* adding scanned item id to GET string
* @param int  customerId
*/
function addItemsToGetData(customerId){
if(customerId){
	GETStringAdd = "&cstm="+customerId;
	//console.info("GET Parameter for customer:"+GETStringAdd);	
	} else {
	GETStringAdd = "&itms="+JSON.stringify(itemIdArray);
	//console.info("Array of items:"+itemIdArray);
	//console.info("GET Parameter for items:"+GETStringAdd);
	}
}

/**
* end of scanning process* emptying all variables
* to start new scan
*/
function finalizeProcess(){
//new transaction will be started
//console.info("Finalizing - all variables null");
itemBuffer = new Array();
itemIdArray = new Array();
barcodeBuffer = new Array();
customer = null;
GETStringAdd = '';
$('#list').html('');
document.getElementById('scan').focus();
//console.info("GET Parameter for items:"+GETStringAdd);
}

