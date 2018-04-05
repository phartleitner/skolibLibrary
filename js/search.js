




var titel ;
var autor ;	
var zusatz;
var swort;
var hkat ;
var mtyp ;
var ukat1 ;
var ukat2;
var searchFor = []; //search criteria 


/**
* send search criteria to webserver
* working with responseText
*/
function sendRequest() {

	
	
titel = $('#titel').val();
autor = $('#autor').val();	
zusatz = $('#zusatz').val();
swort = $('#swort').val();
hkat = $('#hkat option:selected').val();
mtyp = $('#mtyp option:selected').val();
ukat1 = $('#ukat1 option:selected').val();
ukat2 = $('#ukat2 option:selected').val();



if(!titel && !autor && !hkat && !ukat1 && !ukat2 && !swort  && !mtyp) {
	throwMessage("Bitte mindestens ein Suchkriterium angeben");
	
}else {
$('#loading').show();
if (titel) {
searchFor.push(["titel",titel]);
}
if (autor) {
searchFor.push(["autor",autor]);
}
if (hkat) {
searchFor.push(["hkat",hkat]);
}	
if (ukat1) {
searchFor.push(["ukat1",ukat1]);
}
if (ukat2) {
searchFor.push(["ukat1",ukat2]);
}
if (mtyp) {
searchFor.push(["mtyp",mtyp]);
}
if (zusatz) {
searchFor.push(["zusatz",zusatz]);
}
if (swort) {
searchFor.push(["swort",swort]);
}
searchFor = JSON.stringify(searchFor);
//console.info(searchFor);
destination = "?type=search&searchfor="+searchFor;
fire(destination);

//sending the data	
xhttp.addEventListener('load', function(event) {
   if (this.responseText) {
		var error;
		var msg;
		var result = $.parseJSON(this.responseText);
		//console.info(result);
		if (callDetail == false) {
			items = result['items'];
			if (items ) {$('#loading').hide(); treffer = items.length;} else {treffer = 0;}
			$('#search').html(result['searchcriteria'] + 
			'<br><b>'+
			treffer	+ ' Treffer</b>' +
			'<button onClick="location.reload()" class="btn-flat right waves-effect waves-teal right" id="btn_login" type="submit">Neue Suche</button>');
			
			}
		
		var resultText;
		if (null != items ) {
			/*resultText = printResults(items);
			$('#search-results').html(resultText);
			$('#search-result-container').show();*/
			if (callDetail == false) {showList();} else {callDetail = false; showSentDetails(result);}
		} else {
			msg = "Keine Ergebnisse";
		}
		
	$('#loading').hide();	
	throwMessage(msg) ;
	}
});
	
}	
	
}





/*
function showDetails(x) {
	showItemInfoHTML(items[x],"Details");
	$('#showHide').click(function() {showHide()});
}
*/

/*
* perform a custom Action like delete, return or edit
* send data to webserver
* @param barcode
* @param action
* @param data (used only in editing mode - containing the data to be updated)
*/
function performCustomAction(barcode,action,data) {
if(data === undefined) {data = null;}
if (action == 1) {
	//DELETE
	$('#itemInfo').hide();
	$('#item-history').hide();
	items.splice(currentItemIndex,1);
	showList();
	GETCustomActionAdd ='&custom='+barcode+'&do='+action;
	//destination = "?type=search&searchfor="+searchFor+GETCustomActionAdd;
	destination ='?custom='+barcode+'&do='+action;
	fire(destination);
	console.info(GETCustomActionAdd);
	throwMessage("Titel gelöscht");
	} else if (action == 2){
	//RETURN
	$('#itemInfo').hide();
	$('#history').hide();
	items[currentItemIndex]['faellig'] = null;
	showList();
	GETCustomActionAdd ='&custom='+barcode+'&do='+action;
	//destination = "?type=search&searchfor="+searchFor+GETCustomActionAdd;
	destination = '?custom='+barcode+'&do='+action;
	fire(destination);
	throwMessage("Titel zurückgegeben");
	} else if (action == 3) {
	//EDIT SINGLE
	GETCustomActionAdd = '&custom='+barcode+
	'&do='+action+
	'&data='+JSON.stringify(data);
	//destination = "?type=search&searchfor="+searchFor+GETCustomActionAdd;
	destination = '?custom='+barcode+'&do='+action+
	'&data='+JSON.stringify(data);
	fire(destination);
	throwMessage("Änderungen gespeichert");
	showItemInfoHTML();
	} else if (action == 4) {
	//DELETE SERIES
	$('#itemInfo').hide();
	$('#history').hide();
	GETCustomActionAdd = '&custom='+barcode+
	'&do='+action;
	destination = "?type=search&searchfor="+searchFor+GETCustomActionAdd;
	fire(destination);
	throwMessage("Alle Exemplare gelöscht");
	} else if (action == 5) {
	//EDIT SERIES
	GETCustomActionAdd = '&custom='+barcode+
	'&do='+action+
	'&data='+JSON.stringify(data);
	destination = "?type=search&searchfor="+searchFor+GETCustomActionAdd;
	fire(destination);
	throwMessage(data['series']+" Exemplare geändert");
	showItemInfoHTML();
	} else if (action == 6) {
	//EXTEND TITLES DUE DATE
	GETCustomActionAdd = '&custom='+barcode+
	'&do='+action;
	destination = "?type=search&searchfor="+searchFor+GETCustomActionAdd;
	fire(destination);
	throwMessage("Titel verlängert");
	$('#itemInfo').hide();
	$('#history').hide();
	} 
}

