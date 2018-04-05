<?php 
$data = $this->getDataForView(); 
$menue = $data['menue'];
$hkatData = $data['fields'][array_search('hkat',array_column($data['fields'],'df'))];
$mtypData = $data['fields'][array_search('mtyp',array_column($data['fields'],'df'))];
$ukat1Data = $data['fields'][array_search('ukat1',array_column($data['fields'],'df'))];
$ukat2Data = $data['fields'][array_search('ukat2',array_column($data['fields'],'df'))];

?>



<?php include("header.php");?>

<script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous">	
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
<script src="./js/basic.js"></script>
<script src="./js/iteminfo.js"></script>
<script src="./js/scan.js"></script>

<input type="hidden" id="scanmode" value="<?php echo $data['scantype']['key']; ?>"/>
	

<div class="row">
	<div class="col s12 m6 l6">
		<div class="card-panel white">
		<h2><?php echo $data['scantype']['value']?>:</h2>
		  <input id="scan" type="text" class="" autofocus required />
		  <p id="warning"><br></p>
		  <div id="list"> </div>
		  </div>
	
	<?php if (isset($data['dashboard']['favourites']) ) {include("favourites.php");}?>
	
	</div>
	<div class="col s12 m6 l6 " id="itemInfo">
	<?php if (isset($data['dashboard']['inventoryAmount']) ) include("stock.php"); ?>
	<?php include("iteminfo.php"); ?>
	<?php include("itemedit.php"); ?>
	<?php include("customeraccount.php"); ?>
	</div>
</div>

	
<script type="application/javascript">
$(document).ready(function() {
seriesLibrary = <?php if (isset($data['serieslib'])) {echo json_encode($data['serieslib']);} else {echo "null";} ?>;
});
</script>


</body>

</html>