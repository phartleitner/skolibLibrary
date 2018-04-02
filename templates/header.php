<!DOCTYPE html>
<html lang="de">
<head>
    <title>Skolib-Library-Software</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css">
	<link rel="shortcut icon" type="image/ico" href="./assets/favicon.ico" />
	<link rel="stylesheet" href="./css/skolib.css">


<script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous">	
</script>
<script type="application/javascript">
var show = false;
function addItems(){
	if(!this.show) {
		this.show = true;
	$('#add').addClass('showdiv');
	$('#add').html('<a href="?type=editprofile"><i class="material-icons left">edit</i>Profil bearbeiten</a><a href="?type=logout" ><i class="material-icons left">power_settings_new</i>Logout</a>');
	} else {
		this.show = false;
	$('#add').html('');	
	}
}
</script>	
</head>
<body class="grey lighten-2" id="body" style="height: 100vH;background:url(./assets/library.jpg); background-repeat: no-repeat;background-size: 100% 100%;">



<div class="navbar-fixed">
		<nav>
			<div class="nav-wrapper orange" >
				<a href="?" class="brand-logo">
					
					<div class="chip">
						<img src="./assets/skolib.png" width="50vh" height="50vh" align="center">
					
					<span class="black-text hide-on-med-and-down bold-text" ><?php echo $_SESSION['organisation']['name']; ?></span></div>	
				</a>
				<ul class="right bold-text hide-on-med-and-down">
				<?php foreach($menue as $m) {?>
				<?php if ($m['navarea'] == 0) {?> 
					<li><a href="?type=<?php echo $m['type']; ?>" >
				
				<i class="material-icons left"><?php echo $m['icon']; ?></i>
				<?php echo $m['value']; ?></a></li>	
				<?php } ?>
				<?php } ?>
				<!--
				<li><a href="?type=logout"><i class="material-icons">power_settings_new</i></a></li>
				-->
					<li>
						<div >
						<span  onClick="addItems();">
						<i class="material-icons left">account_circle</i>
						<?php echo $data['user']->getFullName(); ?>
						</span>
						<div class="orange" id="add" style="width:25vh">
							
						</div>
						</div>
					<li>
				</ul>
			</div>
			
		</nav>
	
	</div>
	
