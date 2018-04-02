<?php 
$data = $this->getDataForView();
include("popupheader.php");
$params = "";
$hkatData = $data['fields'][array_search('hkat',array_column($data['fields'],'df'))];
?>
<style>

#error{
position: absolute;
margin-left: 20px;
display: none;
bottom: 10%;
right: 30%;
width: 40%;
z-index: 10;
font-size: 20px;
font-weight: bold;	
}


</style>
<div class="container">
	
	
	

    <div class="card">
		<span class="card-title">
            <?php echo $this->getTitle(); ?>      
          </span>
        <div class="card-content">
		<div class="row">
		  <a href="#" class="mdl-navigation__link orange-text btn-flat action right" onClick="window.close()">Fenster schließen</a>
		</div>
          
		<div class="row">jkkjjk</div>
					<form autocomplete="off" onsubmit="submitAddItem(<?php echo $fields; ?>)" action="javascript:void(0);" class="row"
                          style="margin: 20px;">
					<div class="input-field col l12 m12 s12">
						<input type="text" name="titel" id="titel" required>
						<label for="text" class="truncate">Titel</label>
					</div>
					<div class="input-field col l12 m12 s12">
						<input type="autor" name="titel" id="autor" required>
						<label for="autor" class="truncate">Autor</label>
					</div>
					<div class="select col l6 m6 s6" style="margin-top: 10px">
						<select class="browser-default" id="hkat" name="hkat">
						<option>Bitte wählen</option>
						<?php 
						foreach($data['dropdown'][ $hkatData['dwNr'] ] as $key => $value) {
						?>
						<option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
						<?php
						}
						?>
						</select>
					</div>
					
					
					<button onClick="" class="btn-flat right waves-effect waves-teal right" id="btn_login" type="submit">
					Hinzufügen</button>
					
					</form>
				<div id ="error">
					<p align="center" id="error_text" class="red-text" ></p>
				</div>		

		</div>
    </div>


</div>


	




</body>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous">	

</script>	
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
<script type="application/javascript">
var xhttp = new XMLHttpRequest();
/*
xhttp.onreadystatechange = function() {
	console.info(typeof(this.responseText));
	console.info(this.responseText);
	}
*/	
function rightUp(id){
	sendRequest(id,"up");
}

function rightDown(id){
	sendRequest(id,"down");
}

function deleteUser(id){
	sendRequest(id,"delete");
}

function sendRequest(id,mode,name = null, vorname = null, login = null, pass = null) {
	console.info("id: "+id+" -- mode: "+mode);
	if(mode == "add") {
			 	xhttp.open("POST", "?type=users&add=true&name="+name+"&vorname="
				+vorname+"&login="+login+"&pass="+pass, true);
						
		} else {
			xhttp.open("POST", "?type=users&id="+id+"&mode="+mode, true);	
		}
	xhttp.send();
	xhttp.onreadystatechange = function() {
	if (text = this.responseText) {
		if (text == "reload" ||text == "added") {
		 location.reload();
		}else {
		loc = '#r'+id;
		$(loc).html(text);
		}
		
	}
	}
	
	
}

function showAddUserWindow(show){
	if(show) {
		$('#adduser').show();
	} else {
		$('#adduser').hide();
	}	
}



function submitAddItem(fields){
	var name;
for ( var x = 1; x <= fields; x++) {
	//name = 'n'+x;
	
	alert( $('#autor').val() );
	
}

var error = null;

//sendRequest(null,"add",$('#name').val(),vorname,$('#login').val(),$('#pass1').val());
}

function throwError(error){
	$('#error_text').html(error);
	$('#error').fadeIn(400).delay(3000).fadeOut(400);
}


    

</script>
</html>

