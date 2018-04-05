/**
* create the HTML for the detail view of ONE item
* @param array data for this item 
* @param scanmode if applicable
* @param String header
*/

var header;
var editOn = false;
var serie = false;
var currentItem = [];

/**
* show details of an item
*/
function showItemInfoHTML(data ,headline,isScan) {
if (data === undefined) {data = null;}	
if (headline === undefined) {headline = null;}
if (isScan === undefined) {isScan = false;}
serie = false;
	$('#customeraccount').hide();
	$('#itemInfo').show();
	if (null != data) {currentItem = data;}
	if (null != headline) {header = headline;}
	if (currentItem['faellig']) {
			itemInfoStatusColor = "red";
			itemInfoStatusColorInverse = "green";				
		}else {
			itemInfoStatusColor = "green";
		itemInfoStatusColorInverse = "red";				
		}
	$('#item-edit-details').hide();	
	$('#header').html(header);
	customAction = "";
	if (scanMode == "0") {} else {
	customAction = '<a href="#" onClick="confirmAction(1)" title="löschen"><i class="material-icons black-text">delete</i></a>';
	if (seriesLibrary ==  true) {
		customAction += '<a href="#" onClick="confirmAction(4)" title="Serie löschen"><i class="material-icons grey-text">delete_sweep</i></a>';
		}
	customAction +=	'<a href="#" onClick="showEditView();" title="bearbeiten"><i class="material-icons black-text">edit</i></a>';
	if (seriesLibrary ==  true) {
		customAction += '<a href="#" onClick="showEditView(true)" title="Serie bearbeiten"><i class="material-icons grey-text">border_color</i></a>';
		}
	
	$('#custom-action').html(customAction);
	}
	
	//add dteails of current selection
	$('#titel-key').html(currentItem['titel']['key']);
	$('#titel-value').html(currentItem['titel']['value']);
	$('#autor-key').html(currentItem['autor']['key']);
	$('#autor-value').html(currentItem['autor']['value']);
	$('#hkat-key').html(currentItem['hkat']['key']);
	$('#hkat-value').html(currentItem['hkat']['value']);
	if (currentItem['ukat1'] ) {
		if (currentItem['ukat1']['value'] != null) {
		$('#ukat1-key').html(currentItem['ukat1']['key']);
		$('#ukat1-value').html(currentItem['ukat1']['value']);
		$('#ukat1').show();
		} else {
		$('#ukat1').hide();
		}
	}
	if (currentItem['ukat2'] ) {
		if (currentItem['ukat2']['value'] != null) {
		$('#ukat2-key').html(currentItem['ukat2']['key']);
		$('#ukat2-value').html(currentItem['ukat2']['value']);
		$('#ukat2').show();
		}else {
		$('#ukat2').hide();	
		}
	}
	$('#mtyp-key').html(currentItem['mtyp']['key']);
	$('#mtyp-value').html(currentItem['mtyp']['value']);
	if (currentItem['signatur'] ) {
		signaturKey = currentItem['signatur']['key']+' / '+currentItem['barcode']['key'];
		signaturValue = currentItem['signatur']['value']+' / '+currentItem['barcode']['value'];
	} else {
		signaturKey = currentItem['barcode']['key'];
		signaturValue = currentItem['barcode']['value'];
	}
	$('#signatur-key').html(signaturKey);
	$('#signatur-value').html(signaturValue);
	$('#erfasst-key').html(currentItem['erfasst']['key']);
	$('#erfasst-value').html(currentItem['erfasst']['value']);
	statusContent = "verfügbar";
	if(currentItem['faellig'] ) {
		statusContent = "";
		if(seriesLibrary == false) {
		statusContent = '<span><a class="right" href="#" onClick="performCustomAction('+currentItem['barcode']['value']+',6)" title="verlängern">'
		+'<b><i class="material-icons grey-text" >low_priority</i></a></b></span>';	
		}
		if(seriesLibrary == false) {
			dueText = 'fällig:'+currentItem['faellig']['value']['due'];
			} else {
			dueText = 'verliehen';
			}
		statusContent += '<span ><b>'+dueText +' </b>[ '+currentItem['faellig']['value']['customer'] + ' ]'+
		'&nbsp;<a class="right" href="#" onClick="performCustomAction('+currentItem['barcode']['value']+',2)" title="zurückgeben">'
		+'<b><i class="material-icons grey-text" >keyboard_return</i></a></span></b>';
		
		
		
	}else if (currentItem['status']) {
		statusContent = "";
		if (currentItem['status']['statuscode'] == 1) {
		statusContent = "verfügbar";	
		} else if (currentItem['status']['statuscode'] == 0 && scanMode == 1) {
		if(seriesLibrary == false) {
			statusContent += '<span><a class="right" href="#" onClick="performCustomAction('+currentItem['barcode']['value']+',6)" title="verlängern">'
			+'<b><i class="material-icons grey-text">low_priority</i></a></b></span>';	
			}
		statusContent += '<span><a class="right" href="#" onClick="performCustomAction('+currentItem['barcode']['value']+',2)" title="zurückgeben">'
		+'<i class="material-icons grey-text">keyboard_return</i></a></span>';
		}	
	}
	
	$('#status-content').html(statusContent);
	$('#status-content').addClass(itemInfoStatusColor).removeClass(itemInfoStatusColorInverse);
	$('#item-info').show();
	if (historyArr = currentItem['history']['value']) {
	historyContent = 'davor '+historyArr.length+' mal verliehen';
		if (historyArr.length >0 ) {
			historyContent += '<button class="btn-floating btn-small waves-effect waves-light grey lighten-2 right" id = "showHide" ><i class="material-icons right ">keyboard_arrow_down</i></button>';
			historyContent += '<div id="history" style="display:none;left:5px;">'+makeItemBorrowingHistory(historyArr)+'</div>';
			
			}
	$('#history-content').html(historyContent);
	$('#item-history').show();
	}	
	
}

/**
* confirm an action like delete
* @param int
*/
function confirmAction(action, serie) {
	if (serie === undefined) {serie = false;}
	if (action == 1) {
	//DELETE
	confText = '<span><b>Titel entfernen?</b> ';
	} else if (action == 4) {
	//DELETE SERIE
	confText = '<span><b>Serie entfernen? ('+currentItem['series']+' Titel)</b> ';	
	}
	confText += '<a href="#" onClick="confFire('+action+');" class="btn-flat right waves-effect waves-teal right">OK</a> ';
	confText += '<a href="#" onClick="confFire(0)" class="btn-flat right waves-effect waves-teal right">Abbrechen</a></span> ';
	$('#confirmation').html(confText);
	$('#confirmation').show();
	//'+performCustomAction(currentItem['barcode']['value'] ,action)+'
	//performCustomAction(currentItem['barcode']['value'] ,1);
}

/**
* trigger action after confirmation
*/
function confFire(action){
	$('#confirmation').hide();
	if(action == 1) {
		performCustomAction(currentItem['barcode']['value'] ,action);
		} else if (action == 4) {
		performCustomAction(currentItem['barcode']['value'] ,action, currentItem['series']);	
		}
}


/**
* display the edit Window
* @param currentItem
*/
function showEditView(serie) {
	if(serie === undefined) {serie = false;}
	this.serie = serie;
	//Enable all fields 
	$('input').prop('disabled', false);
	$('select').prop('disabled', false);
	//Disable fields relevant for signature
	if (null != this.currentItem['ineditable']) {
	currentItem['ineditable'].forEach(function(element) {
	$('[id*="'+element+'"]').prop('disabled', true);
	});
	}
	if (this.serie == true) {
		msg = "Serie bearbeiten!";
		} else {
		msg = "Titel bearbeiten";
		}
	throwMessage(msg);
	content = "";
	if (currentItem['faellig']) {
			itemInfoStatusColor = "red";	
		}else {
			itemInfoStatusColor = "green";	
		}
	$('#header').html(header);
	if (this.serie == true) {
		$('#serie').html(currentItem['series']+" Titel betroffen!");
		} else {
		$('#serie').html('');	
		}
	$('#titel').val(currentItem['titel']['value']);
	$('#autor').val(currentItem['autor']['value']);
	$('#hkat').val( currentItem['hkat']['id'] );
	$('#ukata').val( currentItem['ukat1']['id'] );
	$('#ukatb').val( currentItem['ukat2']['id'] );
	$('#mtyp').val( currentItem['mtyp']['id'] );
	$('#swort').val(currentItem['swort']['value']);
	$('#zusatz').val(currentItem['zusatz']['value']);
	
	
	$('#item-info').hide();
	$('#item-history').hide();
	$('#item-edit-details').show();
}



/**
* save changes
* calling performCustomAction Method
*/
function saveChanges(){
	currentItem['titel']['value'] = $('#titel').val();
	if (null == scanMode) {items[currentItemIndex]['titel']['value'] = $('#titel').val();}
	if ($('#autor').val()) {
	currentItem['autor']['value'] = $('#autor').val();
	}
	if ($('#hkat').val()) {
		currentItem['hkat']['id'] = $('#hkat').val();
		currentItem['hkat']['value'] = $("#hkat option[value="+$('#hkat').val()+"]").text();
		}
	if ($('#ukata').val()) {
	currentItem['ukat1']['id'] = $('#ukata').val();
	currentItem['ukat1']['value'] = $("#ukata option[value="+$('#ukata').val()+"]").text();
	}
	if($('#ukatb').val()) {
		currentItem['ukat2']['id'] = $('#ukatb').val();
		currentItem['ukat2']['value'] = $("#ukatb option[value="+$('#ukatb').val()+"]").text();	
		}
	if ($('#mtyp').val()) {
	currentItem['mtyp']['id'] = $('#mtyp').val();
	currentItem['mtyp']['value'] = $("#mtyp option[value="+$('#mtyp').val()+"]").text();
	}
	currentItem['swort']['value'] = $('#swort').val();
	currentItem['zusatz']['value'] = $('#zusatz').val();
	if (this.serie == false) {editType = 3;} else {editType = 5;}
	showList();
	performCustomAction(currentItem['barcode']['value'],editType,currentItem);
}


/**
* create expanding list of borrowing events
* @param array
*/
function makeItemBorrowingHistory(historyArr){
	var borrowingHistory =  '';
		for (var i = 0;i<historyArr.length;i++) {
			borrowingHistory += '<p style="font-size:10px;"><b>'+historyArr[i]['customer']+'</b> bis: '+historyArr[i]['returndate']+'</p>';
			}	                                                 
		return borrowingHistory;	
}

/**
* show and hide borrowing history element
*/
function showHide(){
	if (document.getElementById('history').style.display == 'none'){
	document.getElementById('history').style.display = 'block';
	document.getElementById('showHide').innerHTML = '<i class="material-icons right ">keyboard_arrow_up</i>';
	} else {
	document.getElementById('history').style.display = 'none';
	document.getElementById('showHide').innerHTML = '<i class="material-icons right ">keyboard_arrow_down</i>';	
	}
	
}