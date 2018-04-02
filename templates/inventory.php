<?php
include("popupheader.php"); 
$data = $this->getDataForView();
$inventory = $data['inventory'];



?>

<div class="container">
	
    <div class="card">
        <div class="card-content">
		<div class="row" >
		<a href="#" class="mdl-navigation__link orange-text btn-flat action right" onClick="window.close()">Fenster schließen</a>
		
		<?php if(isset($data['dwnld'])) {
		$file = "./".$data['dwnld'];
		?>
		<a href="<?php echo $file; ?>"  class="mdl-navigation__link orange-text btn-flat action right">Inventarliste herunterladen</a>
		
		<?php } ?>
		</div>
          <span class="card-title">
            <?php echo $this->getTitle(); ?>
          </span>
		  
		 
            
			<p style="margin-top: 20px;">
			<table class="striped">
				<?php 
				if (count($inventory) > 0 ){
					foreach ($inventory as $item) { ?>
						<tr>
							<td>
								<?php echo $item['title']; ?>
							</td>
							<td>
								<?php echo $item['category']; ?>
							</td>
							<?php if ($data['serieslib']) { ?>
							<td>
								Exemplare:&nbsp;<?php echo $item['amount']; ?>
							</td>
							<?php } ?>
							<td>
								<?php
								$color = ($item['borrowed'] > 0 ) ? "red-text" : "green-text";
								?>	
								<a class="<?php echo $color; ?>">verliehen:&nbsp;<?php echo $item['borrowed']; ?></a>
							</td>
						</tr>
						<?php
						}
					}
			?>
            
			</table>
            </p>
		</div>

    </div>


</div>

<!-- TO DO
Zeige entliehene Titel an
Integriere Möglichkeit zum löschen einer Serie
und zum Bearbeiten einer Serie

Erstelle Inventarliste
Erstelle detaillierte Titelliste

-->


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
