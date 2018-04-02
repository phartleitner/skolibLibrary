
<?php
include("popupheader.php"); 
$data = $this->getDataForView(); 
$menue = $data['menue'];
$form=$data['barcode_forms'];
$formJ = json_encode($form);
$printSignature = $data['signature_print'];
/*
$key = array_search(3,array_column($form,'id') );

$formName = $form[$key]['name'];
$cols = $form[$key]['cols'];
$rows = $form[$key]['rows'];
*/

$maxpages = 1; //Einzeletiketten maximal eine Seite

?>
<div class="container">
	<div class="card">
		<div class="card-content">
			<div class="row" >
				<a href="#" class="mdl-navigation__link orange-text btn-flat action right" onClick="window.close()">Fenster schließen</a>
			</div>
			<span class="card-title">
				<?php echo $this->getTitle(); ?>
			</span>
		<div class="row">
			<div class="col l9 m12 s12">
				<form method="POST" action="../">
				<select id="grps" class="browser-default" name="group" onChange="selectForm()"> 
				<option>Bitte Druckformat wählen</option>
				<?php
				
				foreach ($form as $f) { ?>
				<option value="<?php echo $f['id']; ?>"><?php echo $f['name'];?></option>
				
				
				<?php 
				} ?>
				</select>
				</form>
				
			</div>
		</div>
		</div>
	</div>
<div class="card" id="details">
</div>
<div class="card" id="barcodes">

</div>
</div>


</body>

<script>
var cols;
var rows;
var formatId;
function selectForm( data ) {
	var obj = <?php echo $formJ; ?>;
	var signature = <?php echo $printSignature; ?>;
	selection = $('#grps option:selected').val();
	//console.info(obj[selection]['name']);
	this.cols = obj[selection]['cols'];
	this.rows = obj[selection]['rows'];
	anzahlText = "Anzahl der Etiketten";
	if (signature == 1) {
	anzahlText = "Anzahl der Etiketten (Barcode & Signatur): ";	
	} else {
	anzahlText = "Anzahl der Etiketten: ";	
	}
	this.formatId = obj[selection]['id'];
	$('#details').html(
	'<div class="card-content">'+
	anzahlText + (this.cols*this.rows)+
	"<br>Spalten: "+ this.cols+
	"<br>Zeilen: "+ this.rows+
	'</div>'
	);
	inputs = '<div class="card-content">';
	destination = '?type=print&mode=3&format=' + this.formatId;
	inputs += '<form action="'+destination+'" method="POST">';
	inputs += '<button type="submit"  class="btn-flat right waves-effect waves-teal right"  >Drucken</button>' ;
	inputs += '<table>';
	odd = true;
	for (x=0;x<rows;x++) {
		inputs += "<tr>";
		for(y=0;y<cols;y++) {
		if (odd == false && signature == true) { disabled = "disabled";} else { disabled = "";}
		inputs += '<td><input class="text" name="barc[]"' + disabled +'><td>';	
		if (odd == true) {odd = false;} else {odd = true;}
		}
		inputs += '</tr>';
	}
	inputs += '</table></form></div>';
	$('#barcodes').html(inputs);
	
}
</script>
</html>

