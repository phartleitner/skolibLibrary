<?php 
$data = $this->getDataForView();
include("popupheader.php");
$params = "";
$hkatData = $data['fields'][array_search('hkat',array_column($data['fields'],'df'))];
$mtypData = $data['fields'][array_search('mtyp',array_column($data['fields'],'df'))];
$ukat1Data = $data['fields'][array_search('ukat1',array_column($data['fields'],'df'))];
$ukat2Data = $data['fields'][array_search('ukat2',array_column($data['fields'],'df'))];
?>
<style>

.red-border{
	border: 2px solid red;
}


</style>
<div class="container">
	
	
	

    <div class="card">
        <div class="card-content">
			<div class="row">
			  <a href="#" class="mdl-navigation__link orange-text btn-flat action right" onClick="window.close()">Fenster schließen</a>
			</div>
			  <span class="card-title">
				<?php echo $this->getTitle(); ?>      
			  </span>
			<form autocomplete="off" onsubmit="sendRequest()" action="javascript:void(0);" class="row"
				  style="margin: 20px;">
			<div class="row"  >
				<div class="input-field col l12 m12 s12" >
					<input type="text" name="titel" id="titel" required>
					<label for="text" class="truncate">Titel</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col l12 m12 s12">
					<input type="text" name="autor" id="autor">
					<label for="autor" class="truncate">Autor/Hrsg.</label>
				</div>
			</div>
			<div class="row">
				<div class="select col l6 m6 s6" style="margin-top: 10px">
					<label for="hkat">Kategorie
					<select class="browser-default" id="hkat" name="hkat" required>
					<option value="">Kategorie wählen</option>
					<?php 
					foreach($data['dropdown'][ $hkatData['dwNr'] ] as $key => $value) {
					?>
					<option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
					<?php
					}
					?>
					</select>
					</label>
				</div>
				<div class="select col l6 m6 s6" style="margin-top: 10px">
					<label for="hkat">Medium
					<select class="browser-default" id="mtyp" name="mtyp" required>
					<option value="">Medium wählen</option>
					<?php 
					foreach($data['dropdown'][ $mtypData['dwNr'] ] as $key => $value) {
					?>
					<option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
					<?php
					}
					?>
					</select>
					</label>
				</div>
			</div>
			<div class="row">
				<div class="select col l6 m6 s6" style="margin-top: 10px">
					<label for="hkat">Unterkategorie1
					<select class="browser-default" id="ukat1" name="ukat1" >
					<option value="">Unterkategorie1 wählen</option>
					<?php 
					foreach($data['dropdown'][ $ukat1Data['dwNr'] ] as $key => $value) {
					?>
					<option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
					<?php
					}
					?>
					</select>
					</label>
				</div>
				<div class="select col l6 m6 s6" style="margin-top: 10px">
					<label for="ukat2">Unterkategorie2
					<select class="browser-default" id="ukat2" name="ukat2" >
					<option value="">Unterkategorie1 wählen</option>
					<?php 
					foreach($data['dropdown'][ $ukat2Data['dwNr'] ] as $key => $value) {
					?>
					<option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
					<?php
					}
					?>
					</select>
					</label>
				</div>
			</div>
			<div class="row">
				<div class="select col l12 m12 s12" style="margin-top: 10px">
					<label for="swort">Schlagworte
					<textarea rows="2" id="swort" class="materialize-textarea" name="swort"></textarea>
					</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col l12 m12 s12">
					<input type="text" name="zusatz" id="zusatz">
					<label for="zusatz" class="truncate">Sonstiges</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col l12 m12 s12">
					
					<input id="anzahl" name="anzahl" type="number" min="1" max="300" step="1" value="1">
					<label for="anzahl">
					Anzahl: </label>
				</div>
			</div>
			<div class="row">
			
					<p align="center" id="message"></p>
				
			<button onClick="" class="btn-flat right waves-effect waves-teal right" id="btn_login" type="submit">
			Hinzufügen</button>
			</div>
			
			</form>
			
		</div>
    </div>


</div>


	




</body>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous">	

</script>	
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
<script type="application/javascript">



	


function sendRequest() {
var xhttp = new XMLHttpRequest();
	
	
var titel = $('#titel').val();
var autor = $('#autor').val();	
var zusatz = $('#zusatz').val();
var swort = $('#swort').val();
var anz = $('#anzahl').val();
var hkat = $('#hkat option:selected').val();
var mtyp = $('#mtyp option:selected').val();
var ukat1 = $('#ukat1 option:selected').val();
var ukat2 = $('#ukat2 option:selected').val();	

xhttp.open("POST", "?type=newitem&titel="+titel+"&autor="+autor+
"&hkat="+hkat+"&mtyp="+mtyp+"&ukat1="+ukat1+"&ukat2="+ukat2+
"&swort="+swort+"&zusatz="+zusatz+"&anz="+anz, true);
xhttp.send();
	
	xhttp.addEventListener('load', function(event) {
   if (this.responseText) {
		var error;
		var msg;
		text = this.responseText;
		console.info(text);
		var result = $.parseJSON(text);
		if (result['status'] == "success" ) {
		error = false;
		msg = "Titel wurde angelegt";
		} else {
			error = true;
		msg = "Benötigte Felder: ";
		for (x = 0;x < result['missing'].length; x++) {
		if (x == result['missing'].length-1) {
			msg += result['missing'][x]['label'];
		} else {
			msg += result['missing'][x]['label']+',';	
			}		
		}
		
		}
	throwMessage(msg,result['missing'],error) ;
	}
});
	
	
	
}




function throwMessage(msg,missing,error){
	if(error) {
	color = "red-text";
	for (x = 0;x < missing.length; x++) {
	markField(missing[x]['field']);	
	}
		} else {
	color = "green-text";}
	
	
	var message = msg;
	Materialize.toast(message,4000);
	//$('#message').html('<b class="'+color+'" >'+message+'</b>');	
	
	if(!error) {
	location.reload();
	
	}
	
}

function markField(field) {
//geht nicht!!
$('[name=' + field + ']').addClass('red-border');	
}


    

</script>
</html>

