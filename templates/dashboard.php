

<?php 
$data = $this->getDataForView(); 
$menue = $data['menue'];
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <title>Skolib-Library-Software</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css">
	</head>
<body class="grey lighten-2" id="body" style="height: 80vh;">
<?php include("header.php");?>
<div class="row">
	
	
	
</div>		

<div class="row">
	<div class="col s12 m6 l6">
		<div class="card-panel white">
		<h2>Ausleihe:</h2>
		  <input id="scan" type="text" class="" autofocus required />
		  <p id="warning"><br></p>
		  <div id="list"> </div>
		  </div>
	</div>
	<div class="col s12 m6 l6" id="itemInfo">
	</div>
</div>

	
<script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous">	
		
</script>

<script>
<?php include "scan.js" ?>
</script>


</body>

</html>