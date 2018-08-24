<?php 
$data = $this->getDataForView();
$menue = $data['menue']; 
include("popupheader.php");
?>

<div class="container">
	
	
	
    <div class="card">
        <div class="card-content">
		<div class="row">
		  <a href="#" class="mdl-navigation__link orange-text btn-flat action right" onClick="window.close()">Fenster schließen</a>
		 </div>
          <span class="card-title">
            <?php echo $data['header']; ?>
			
                
          </span>
		  
		  <p> <a href="<?php echo $data['borrowedCSV']; ?>">Datei herunterladen</a> </p>
		
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

