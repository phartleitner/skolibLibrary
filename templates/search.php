<?php 

/* Notiz
löse die Suche und deren Anzeige wie bei der Anzeoge der entliehenen Titerl
*/


$data = $this->getDataForView();
$menue = $data['menue'];
include("header.php");
$params = "";
$hkatData = $data['fields'][array_search('hkat',array_column($data['fields'],'df'))];
$mtypData = $data['fields'][array_search('mtyp',array_column($data['fields'],'df'))];
$ukat1Data = $data['fields'][array_search('ukat1',array_column($data['fields'],'df'))];
$ukat2Data = $data['fields'][array_search('ukat2',array_column($data['fields'],'df'))];
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous">	
</script>	
<script src="./js/basic.js"></script>
<script src="./js/search.js"></script>
<script src="./js/iteminfo.js"></script>



<div  class="loader  " id="loading"></div>
<div class="row ">
	
	
	

    <div id="search-container" class=" col s12 m12 l12" >
        
		<div  class="card">
			  <span class="card-title">
				<?php echo $this->getTitle(); ?>      
			  </span>
		<div id="search" class="card-content">
			<form autocomplete="off" onsubmit="sendRequest()" action="javascript:void(0);" class="row"
				  style="margin: 20px;">
			<div class="row"  >
				<div class="input-field col l12 m12 s12" >
					<input type="text" name="titel" id="titel" >
					<label for="text" class="truncate">Titel</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col l12 m12 s12">
					<input type="text" name="autor" id="autor">
					<label for="autor" class="truncate">Autor/Hrsg.</label>
				</div>
			</div>
			<div class="row">
				<div class="select col l6 m6 s6" style="margin-top: 10px">
					<label for="hkat">Kategorie
					<select class="browser-default" id="hkat" name="hkat" >
					<option value="">Kategorie wählen</option>
					<?php 
					foreach($data['dropdown'][ $hkatData['dwNr'] ] as $key => $value) {
					?>
					<option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
					<?php
					}
					?>
					</select>
					</label>
				</div>
				<div class="select col l6 m6 s6" style="margin-top: 10px">
					<label for="hkat">Medium
					<select class="browser-default" id="mtyp" name="mtyp" >
					<option value="">Medium wählen</option>
					<?php 
					foreach($data['dropdown'][ $mtypData['dwNr'] ] as $key => $value) {
					?>
					<option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
					<?php
					}
					?>
					</select>
					</label>
				</div>
			</div>
			<div class="row">
				<div class="select col l6 m6 s6" style="margin-top: 10px">
					<label for="hkat">Unterkategorie1
					<select class="browser-default" id="ukat1" name="ukat1" >
					<option value="">Unterkategorie1 wählen</option>
					<?php 
					foreach($data['dropdown'][ $ukat1Data['dwNr'] ] as $key => $value) {
					?>
					<option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
					<?php
					}
					?>
					</select>
					</label>
				</div>
				<div class="select col l6 m6 s6" style="margin-top: 10px">
					<label for="ukat2">Unterkategorie2
					<select class="browser-default" id="ukat2" name="ukat2" >
					<option value="">Unterkategorie1 wählen</option>
					<?php 
					foreach($data['dropdown'][ $ukat2Data['dwNr'] ] as $key => $value) {
					?>
					<option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
					<?php
					}
					?>
					</select>
					</label>
				</div>
			</div>
			<div class="row">
				<div class="select col l12 m12 s12" style="margin-top: 10px">
					<label for="swort">Schlagworte
					<textarea rows="2" class="materialize-textarea" id="swort" name="swort"></textarea>
					</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col l12 m12 s12">
					<input type="text" name="zusatz" id="zusatz">
					<label for="zusatz" class="truncate">Sonstiges</label>
				</div>
			</div>
			
			<div class="row">
			
					<p align="center" id="message"></p>
				
			<button onClick="" class="btn-flat right waves-effect waves-teal right" id="btn_login" type="submit">
			Suchen</button>
			</div>
			
			</form>
		</div>	
		
		</div>
		
	
		
	
	
	</div>
	<!-- <div id = "search-results" class=" card col s12 m6 l6 white" style="height:500px;overflow:auto;display:none">
		
	</div> -->
	<div class="col s12 m6 l6" id = "search-result-container">
		<div class="card-panel white">
			<div id="search-results" ></div>
		</div>
	</div>
	<div id = "itemInfo" class="col s12 m6 l6" >
	<?php include("iteminfo.php"); ?>
	<?php include("itemedit.php"); ?>
	</div>


</div>
<script type="application/javascript">
$(document).ready(function() {
seriesLibrary = <?php echo json_encode($data['serieslib']); ?>;
})
</script>

</body>




</html>

