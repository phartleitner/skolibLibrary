<?php 
$data = $this->getDataForView();
$menue = $data['menue'];
$user = $data['user'];
include("header.php");
?>
<style>

.red-border{
	border: 2px solid red;
}


</style>
<div class="container">

    <div class="card">
        <div class="card-content">
			
			  <span class="card-title">
				<?php echo $this->getTitle(); ?>      
			  </span>
			<form  onsubmit="sendRequest()" action="javascript:void(0);" class="row"
				  style="margin: 20px;">
			<div class="row"  >
				<div class="input-field col l4 m4 s4">
					<input  type="text" name="login" id="login" required value="<?php echo $user->getLogin(); ?>">
					<label for="login" class="truncate">Login</label>
				</div>
				<div class="input-field col l4 m4 s4" >
					<input type="text" name="surname" id="surname" required value="<?php echo $user->getSurname(); ?>">
					<label for="surname" class="truncate">Nachname</label>
				</div>
				<div class="input-field col l4 m4 s4">
					<input type="text" name="name" id="name" required value="<?php echo $user->getName(); ?>">
					<label for="name" class="truncate">Name</label>
				</div>
			</div>
			
			<div class="row">
				<div class="input-field col l12 m12 s12">
					<input autocomplete="false"  type="password" name="passold" id="passold" required>
					<label for="passold" class="truncate">altes Passwort</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col l6 m6 s6">
					<input  autocomplete="false" type="password" name="pass1" id="pass1">
					<label for="pass1" class="truncate">neues Passwort</label>
				</div>
				<div class="input-field col l6 m6 s6">
					<input autocomplete="false" type="password" name="pass2" id="pass2">
					<label for="pass2" class="truncate">neues Passwort (wiederholen)</label>
				</div>
			</div>
						
			<div class="row">
				<p align="center" id="message"></p>
				<button onClick="" class="btn-flat right waves-effect waves-teal right" id="btn_login" type="submit">
				Speichern</button>
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



	
var xhttp;
var error = false;
var msg;
function sendRequest() {
xhttp = new XMLHttpRequest();
var surname = $('#surname').val();
var name = $('#name').val();	
var login = $('#login').val();
var pass1 = $('#pass1').val();
var pass2 = $('#pass2').val();
var passold = $('#passold').val();
var passnew = null;
var text = "";


$('#pass1').val('');
$('#pass2').val('');

if (pass1 != "" || pass2 != "") {
	if (pass1 != pass2) {
		this.error = true;
		msg = "Passwörter stimmen nicht überein";
	} else if (pass1.length < 6) {
		this.error = true;
		msg = "Passwort muss mindestens 6 Zeichen lang sein";
	} else {
			this.error = false;
			passnew = pass1;
	}
}

if (passnew) {
text = "?type=editprofile&changed=true&surname="+surname+"&name="+name+"&login="+login+
"&passold="+passold+"&passnew="+passnew;	
} else {
text = "?type=editprofile&changed=true&surname="+surname+"&name="+name+"&login="+login+
"&passold="+passold;		
}


if (!this.error) {
xhttp.open("POST", text, true);
xhttp.send();
} else {
throwMessage(this.msg) ;	
}



xhttp.addEventListener('load', function(event) {
   if (this.responseText) {
		txt = this.responseText;
		console.info(txt);
		result = $.parseJSON(txt);
	if (result['status'] == "success") {
		$('#passold').val('');	
		}	
	throwMessage(result['message']) ;
	
	}
});

	

	
}





function throwMessage(msg){
	Materialize.toast(msg,4000);
	//empty password fields
	
	
	}




    

</script>
</html>

