<?php 
$data = $this->getDataForView(); 
$menue = $data['menue'];
/*
var_dump($data['doubleitems']);die;
$hkatData = $data['fields'][array_search('hkat',array_column($data['fields'],'df'))];
$mtypData = $data['fields'][array_search('mtyp',array_column($data['fields'],'df'))];
$ukat1Data = $data['fields'][array_search('ukat1',array_column($data['fields'],'df'))];
$ukat2Data = $data['fields'][array_search('ukat2',array_column($data['fields'],'df'))];
*/

?>



<?php include("header.php");?>
	
<div class="row">
<div class="col s12 m12 l12" id = "doubles-result-container">
		<div class="card white">
		
			<div class="card-title">
				<h4><?php echo $data['header']; ?></h4>
			</div>
			<div id="doubles-results" class="card white" ></div>
		</div>
	</div>
</div>

	
<script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous">	
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
<script src="./js/doubles.js"></script>


<script type="application/javascript">

$(document).ready(function() {
var data = <?php echo  $data['doubleitems']; ?>;
seriesLibrary = <?php echo json_encode($data['serieslib']); ?>;
items = data['items'];

if(data['status'] != "success") {
	$('#doubles-results').html("keine Doubletten vorhanden!");
	$('#doubles-result-container').show();
	}
else {
showDoublesList();
}

});



</script>


</body>

</html>