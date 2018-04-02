<?php
include("popupheader.php"); 
$data = $this->getDataForView();
if ($data['selection'] == "forms") {
$barcforms = $data['barcode_forms'];	
} elseif ($data['selection'] == "groups") {
$groups = $data['groups'];
}



?>

<div class="container">
	
    <div class="card">
        <div class="card-content">
		<div class="row" >
		<a href="#" class="mdl-navigation__link orange-text btn-flat action right" onClick="window.close()">Fenster schließen</a>
		</div>
          <span class="card-title">
            <?php echo $this->getTitle(); ?>
          </span>
		  
		 
            <?php if ($data['selection'] == "forms") { ?>
			<p style="margin-top: 20px;">
			<ul class="collection">
				<?php 
				if ($data['selection'] == "forms") { ?>
				<li class="collection-item red-text">
					<b>Ungedruckte Barcodes:&nbsp;<?php echo $data['amount_unprinted_barcodes']; ?></b>
				</li>
				<?php }?>
            <?php
				if ($data['amount_unprinted_barcodes'] > 0 ){
					foreach ($barcforms as $b) {
						if($b["public"] == 1) {	 ?>
							<li class="collection-item orange-text">
							<a href="#" class="mdl-navigation__link orange-text btn-flat action" onClick="MM_openBrWindow('?type=print&mode=1&format=<?php echo $b['id']; ?>','','width=600,height=600');location.reload();">
							<?php echo $b["name"]."( maximal:".$b["maxpages"]." Seiten! )"; ?>
							</a>
							</li>
							<?php
							}				
						}
					}
			?>
            
			</ul>
            </p>
			<?php
			} elseif ($data['selection'] == "groups") { ?>
			<p style="margin-top: 20px;">
			<div class="row">
				<div class="col l9 m12 s12">
					<form method="POST" action="../">
					<select id="grps" class="browser-default" name="group" onChange="MM_openBrWindow('?type=print&mode=2&format=4','','width=600,height=600',true);location.reload();">
					<option>Bitte wählen</option>
					<?php
					
					foreach ($groups as $g) { ?>
					<option value="<?php echo $g; ?>"><?php echo $g;?></option>
					
					
					<?php 
					} ?>
					</select>
					</form>
					
				</div>
			</div>
			</p>
			<?php } ?>
			
        </div>

    </div>


</div>
<script type="application/javascript">
function MM_openBrWindow(theURL,winName,features,select = null) { //v2.0
if (select) {
	theURL += "&group="+$('#grps').val();
	}
window.open(theURL,winName,features);
}
</script>
</body>
</html>
