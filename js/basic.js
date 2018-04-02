/**
* basic functions needed in a number of scripts
*/
var xhttp = new XMLHttpRequest();
var items = []; //the array of items created from responseText - globally used
var scanMode = null; //string representing kind of scanning procedure - defined here and referred to in iteminfo.js
var seriesLibrary = false; //boolean - shows if the library has lots of same titles
var currentItemIndex = null;
var callDetail = false;

function throwMessage(msg){
	Materialize.toast(msg,2000);
}

function fire(destination) {
xhttp.open("POST", destination, true);
xhttp.send();
}

function showList(){
$('#search-results').html(printResults(items));
$('#search-result-container').show();	
}

/**
*shows the details of one item  sent by webserver
* @param array 
*/
function showSentDetails(details){
showItemInfoHTML(details['items'],"Details");
$('#showHide').click(function() {showHide()});	
}

/**
* calls the webserver for details of one item
* @param int
*/
function callDetails(x) {
callDetail = true;
currentItemIndex = x;
barcode = items[x]['barcode']['value'];
showList();
destination ='?custom='+barcode+'&do=7';
fire(destination);	
}


function printResults(results) {
text = '<table class="striped">';

for(x = 0;x < results.length ;x++) {
	param = results[x];
	if (results[x]['faellig']) {
		statusColor = "red-text";
		if(seriesLibrary == true){
			statusValue = "verliehen";			
			} else {
			statusValue = 	'fällig: ' + results[x]['faellig']['value']['due'];
			}
		} else {
		statusColor = "green-text";
		statusValue = 	"verfügbar";	
		}
	funcText = 'callDetails('+x+')';
	text += 
	'<tr>'+
		'<td >' +
			results[x]['titel']['value'] +
		'</td>' +
		'<td >(' +
			results[x]['barcode']['value'] +
		')</td>' +
		'<td class="'+statusColor+'">'+
			statusValue +
		'</td>'+
		'<td>'+
			'<a class="mdl-navigation__link orange-text btn-flat"  onClick="'+funcText+'" href="#">'+
				'<i class="material-icons">chevron_right</i>'+
			'</a>' +
		'</td>'+
	'</tr>';
	//was showDetails('+x+')
	
	}
text += '</table>';	
return text;
}

