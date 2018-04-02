<?php 
$data = $this->getDataForView(); 
$menue = $data['menue'];
$hkatData = $data['fields'][array_search('hkat',array_column($data['fields'],'df'))];
$mtypData = $data['fields'][array_search('mtyp',array_column($data['fields'],'df'))];
$ukat1Data = $data['fields'][array_search('ukat1',array_column($data['fields'],'df'))];
$ukat2Data = $data['fields'][array_search('ukat2',array_column($data['fields'],'df'))];
//var_dump($data['serieslib']);die;
?>



<?php include("header.php");?>
	

<div class="row">
	<div class="col s12 m6 l6" id = "search-result-container">
		<div class="card-panel white">
			<div class="row">
				<h4><?php echo $data['header']; ?></h4>
			</div>
			<div id="search-results" ></div>
		</div>
	</div>
	<div class="col s12 m6 l6" id="itemInfo">
	<?php include("iteminfo.php"); ?>
	<?php include("itemedit.php"); ?>
	</div>
</div>

	
<script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous">	
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
<script src="./js/basic.js"></script>
<script src="./js/iteminfo.js"></script> 
<script src="./js/borrowed.js"></script> 
<script type="application/javascript">

$(document).ready(function() {

var data = <?php echo $data['borrowedItems']; ?>;
seriesLibrary = <?php echo json_encode($data['serieslib']); ?>;

items = data['items'];

if(data['status'] != "success") {
	$('#search-results').html("keine Titel entliehen");
	$('#search-result-container').show();
	}
else {
showList();
}

});



</script>


</body>

</html>