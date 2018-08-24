<?php 
$data = $this->getDataForView();
$menue = $data['menue']; 
include("popupheader.php");
?>
<style>
#adduser{
position: absolute;
display: none;
width: 95%;
height: 200%;
overflow: auto;
z-index: 5;
	
}

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
	
	
	<div id="adduser" class="modal">
			<div class="modal-content">
				<h4>Benutzer hinzufügen</h4>
					<form autocomplete="off" onsubmit="submitAddUser()" action="javascript:void(0);" class="row"
                          style="margin: 20px;">
					
							<div class="input-field col l4 m4 s4">
								<input id="login"  type="text" class="validate" required>
								<label for="login" class="truncate">Login</label>
							</div>
							<div class="input-field col l4 m4 s4">
								<input id="gname"  type="text" class="validate">
								<label for="gname">Vorname</label>
							</div>
							<div class="input-field col l4 m4 s4">
								<input id="name" type="text"  required>
								<label for="name" class="truncate">Name</label>
							</div>
						<div class="input-field col l6 m6 s6">
							<input id="pass1"  type="password"  required>
							<label for="pass1" class="truncate">Passwort</label>
						</div>
						<div class="input-field col l6 m6 s6">
							<input id="pass2"  type="password" required>
							<label for="pass2" class="truncate">Passwort wiederholen</label>
						</div>
					
					<button onClick="" class="btn-flat right waves-effect waves-teal" id="btn_login" type="submit">
					Hinzufügen</button>
					<button class="btn-flat right waves-effect waves-teal" onClick="showAddUserWindow(false)" id="buttonClose" >
					Abbrechen</button>
					</form>
				<div id ="error">
					<p align="center" id="error_text" class="red-text" ></p>
				</div>		
			</div>
	</div>

    <div class="card">
        <div class="card-content">
		<div class="row">
		  <a href="#" class="mdl-navigation__link orange-text btn-flat action right" onClick="window.close()">Fenster schließen</a>
		 </div>
          <span class="card-title">
            <?php echo $this->getTitle(); ?>
			
                
          </span>
		  
		  
            <p style="margin-top: 20px;">
                <?php
				if (isset($data['librarians'])) {
                foreach($data['librarians'] as $l) {?>
					
            <div class="row" id=" <?php echo "user".$l->getId(); ?>">
            <?php echo $l->getFullName()." (".$l->getLogin().")"; ?>
			<p class="right" ><span id="<?php echo "r".$l->getId(); ?>" ><?php echo $l->getRight()['rname']; ?></span>
			<a href="#" onClick="rightUp('<?php echo $l->getId(); ?>')"  class="mdl-navigation__link orange-text " title="Recht erhöhen"><i class="material-icons">keyboard_arrow_up</i></a>
			<a href="#" onClick="rightDown('<?php echo $l->getId(); ?>')"class="mdl-navigation__link orange-text " title="Recht reduzieren"><i class="material-icons">keyboard_arrow_down</i></a>
			<a href="#" onClick="deleteUser('<?php echo $l->getId(); ?>')"class="mdl-navigation__link orange-text " title="Benutzer löschen"><i class="material-icons">delete</i></a>
			</p>
            </div>

            <?php
				
               } 
			}?>

            </p>
		<div class="row" id="add" >
			<a class='right btn-floating  orange' href='#' onClick="showAddUserWindow(true)" id="buttonAddUser" ><i class="material-icons" >add</i></a>
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

function sendRequest(id,mode,name, vorname, login, pass) {
	if (name === undefined) {name = null;}
	if (vorname === undefined) { vorname = null;}
	if (login === undefined) {login = null;}
	if (pass === undefined) {pass = null;} 
	//console.info("id: "+id+" -- mode: "+mode);
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
	if(show == true) {
		$('#adduser').show();
	} else {
		$('#adduser').hide();
	}
	
}



function submitAddUser(){
var error = null;
var vorname = null;
if ($('#gname').val() ){
	vorname = $('#gname').val();
	}
if ($('#pass1').val() != $('#pass2').val() ) {
	error = "Passwörter stimmen nicht überein";	
	throwError(error);		 
	}
else if ($('#pass1').val().length <6 ) {
	error = "Passwort muss mindestens 6 Zeichen haben!";	
	throwError(error);		
	}
else {
	sendRequest(null,"add",$('#name').val(),vorname,$('#login').val(),$('#pass1').val());
	}
}

function throwError(error){
	Materialize.toast(error,4000);
	//$('#error_text').html(error);
	//$('#error').fadeIn(400).delay(3000).fadeOut(400);
}


    

</script>
</html>

