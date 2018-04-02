<?php $data = $this->getDataForView(); 
$organisations = (isset($data['organisations']) ) ? $data['organisations'] : null;

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>Skolib-Library-Software</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css">
	
<body class="container orange" style="height: 100vH;background:url(./assets/library.jpg); background-repeat: no-repeat;background-size: 100% 100%;">
<div class="row">
    <div class="col s12 m8 l4 offset-m2 offset-l4" style="margin-top: 100px;">
		<ul class="collapsible white" data-collapsible="accordion">
            <li>
                <div class="collapsible-header active"><i class="material-icons">person</i>Anmelden</div>
                <div class="collapsible-body">
                    <form autocomplete="off" onsubmit="submitLogin()" action="javascript:void(0);" class="row"
                          style="margin: 20px;">
						<div class="input-field col s12">
     							<i class="material-icons">school</i>
								<div class="mdl-selectfield">
								
									<select id="usr_organisation" class="browser-default" required>
									  <option value="" selected>Bitte Schule wählen</option>
									  <?php 
									  foreach($organisations as $org) { ?>
									  <option value="<?php echo $org['value']; ?>"><?php echo $org['name']; ?></option>
									  <?php } ?>
									</select>
								</div>
                        </div> 
                        <div class="input-field col s12">
                            <i class="material-icons prefix">person</i>
                            <input id="usr_login" type="text" class="" required>
                            <label for="usr_login" class="truncate">Name</label>
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">vpn_key</i>
                            <input id="pwd_login" type="password" style="margin-bottom:0px;" required>
                            <label for="pwd_login" class="truncate">Passwort</label>
                            
                        </div>
                        <div class="row" style="margin-bottom: 0;">
                            <button class="btn-flat right waves-effect waves-orange" id="btn_login" type="submit">Submit<i
                                        class="material-icons right">send</i></button>
                        </div>
                    </form>
                </div>
            </li>
            
        </ul>
    </div>
</div>




<script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
<script>

    $('.modal').modal();
    <?php
    if (isset($data['notifications']))
        foreach ($data['notifications'] as $not) {
            echo "Materialize.toast('" . $not['msg'] . "', " . $not['time'] . ");";
            echo "console.info('Toast: " . $not['msg'] . "');";
        }

        if(isset($_GET['s']))
         echo "Materialize.toast('Bitte nutzen sie zur Anmeldung ihre Schul-Zugangsdaten.');";
        
    ?>


    

    function submitLogin() {
        var pwd = $('#pwd_login').val();
        var usr = $('#usr_login').val();
		var org = $('#usr_organisation').val();
		$.post("", {'type': 'login', 'console': '', 'login[password]': pwd, 'login[name]': usr, 'login[organisation]':org}, function (data) {
	        if (data == true) {
	            location.reload();
            } else if (data == false) {
                Materialize.toast("Name oder Passwort falsch", 4000);
                $('#pwd_login').val("");

                $('label[for="pwd_login"]').removeClass("active");
            } else {
                Materialize.toast("Unexpected response: " + data, 4000);
                $('#pwd_login').val("");

                $('label[for="pwd_login"]').removeClass("active");
            }
        });

        return false;
    }

   

</script>
</body>
</html>
