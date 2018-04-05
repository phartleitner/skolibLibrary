var xhttp = new XMLHttpRequest();

xhttp.addEventListener('load', function(event) {
	if (this.responseText) {
		var data = $.parseJSON(this.responseText);
		if (this.callDetail == false) {items = data['items'];}
		if(data['status'] != "success") { 
			$('#search-results').html("keine Titel entliehen");
			$('#search-result-container').show();
			}
		else {
			if (callDetail == false) {showList();} else {callDetail = false; showSentDetails(data);}
			}
	}
} );




/*
function showDetails(x) {
	currentItemIndex = x;
	showItemInfoHTML(items[x],"Details");
	$('#showHide').click(function() {showHide()});
	}
*/
/*
function createItemInfoView(dataArr) {
	showItemInfoHTML(dataArr,"Details");
	//$('#itemInfo').html(content);
	$('#showHide').click(function() {showHide()});
}
*/


function performCustomAction(barcode,action,data) {
if (data === undefined) {data = null;}
if (action == 1) {
	//DELETE
	$('#itemInfo').hide();
	$('#item-history').hide();
	items.splice(currentItemIndex,1);
	showList();
	GETCustomActionAdd ='&custom='+barcode+'&do='+action;
	//destination = "?type=borrowed"+GETCustomActionAdd;
	destination = '?custom='+barcode+'&do='+action;
	fire(destination);
	throwMessage("Buch gelöscht");
} else if (action == 2){
	//RETURN
	$('#itemInfo').hide();
	$('#history').hide();
	items.splice(currentItemIndex,1);
	showList();
	GETCustomActionAdd ='&custom='+barcode+'&do='+action;
	//destination = "?type=borrowed"+GETCustomActionAdd;
	destination = '?custom='+barcode+'&do='+action;
	fire(destination);
	throwMessage("Buch zurückgegeben");
} else if (action == 3) {
	//EDIT SINGLE
	GETCustomActionAdd ='&custom='+barcode+'&do='+action+
	'&data='+JSON.stringify(data);
	//destination = "?type=borrowed"+GETCustomActionAdd;
	destination = '?custom='+barcode+'&do='+action+
	'&data='+JSON.stringify(data);
	fire(destination);
	throwMessage("Änderungen gespeichert");
	showItemInfoHTML();
} else if (action == 4) {
	//DELETE SERIES
	$('#itemInfo').hide();
	$('#history').hide();
	$('#search-results').hide();
	GETCustomActionAdd ='&custom='+barcode+'&do='+action;
	destination = "?type=borrowed"+GETCustomActionAdd;
	fire(destination);
	throwMessage("Alle Exemplare gelöscht");
	} else if (action == 5) {
	//EDIT SERIES
	GETCustomActionAdd ='&custom='+barcode+'&do='+action+
	'&data='+JSON.stringify(data);
	destination = "?type=borrowed"+GETCustomActionAdd;
	fire(destination);
	throwMessage(data['series'].length+" Exemplare geändert",false);
	showItemInfoHTML();
	}  else if (action == 6) {
	//EXTEND TITLE'S DUE DATE
	GETCustomActionAdd = '&custom='+barcode+
	'&do='+action;
	destination = "?type=borrowed"+GETCustomActionAdd;
	fire(destination);
	throwMessage("Titel verlängert");
	$('#itemInfo').hide();
	$('#history').hide();
	} 


}