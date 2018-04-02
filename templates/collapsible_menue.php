<?php 
$data = $this->getDataForView();
$menue = $data['menue']; 
include("header.php");

/**
* create a collapsible menue
* by iterating throug items
* @param array menueItems
* @param array current Item
* @param int currently used key for item
**/
function createCollapsibleMenue($items,$current,$key){
unset($items[$key ]);
?>
	<!-- Schreibe aktuellen Eintrag in collapsible Header -->
	<?php
	if ($current['collapsible']) { 
			$collapsible =  '<i class="material-icons right">keyboard_arrow_down</i>';
			$link = '';
			}
			else {
			$collapsible = "";	
			if($current['popup'] == 1) {
					$link = "href=\"#\" onClick=\"MM_openBrWindow('?type=".$current['type']."','','width=800,height=900')\"";
				} else {
					$link = 'href="?type='.$current['type'].'"';
				}
			}
	?>
	<li>
	<div class="collapsible-header">
	<a class="mdl-navigation__link orange-text btn-flat" <?php echo $link; ?> >
	   <i class="material-icons left"><?php echo $current['icon']; ?></i>
	   <?php echo $current['value']; ?>
	   <?php echo $collapsible; ?>
	   </a> 
	</div>
	<!-- Prüfe ob zu diesem Einbtrag ein Submenue existiert -->
	<?php if ($current['collapsible']) { ?>
		<div class="collapsible-body" >
			<ul class="collapsible" data-collapsible="expandable">
			<?php 
			foreach ($items as $key => $value) {
				if ($value['navarea'] == $current['id']) {
					createCollapsibleMenue($items,$value,$key); 
					}
				}
			?>
			</ul>
			
		</div>
		<?php
		}
	?>
	</li>
	<?php
	}
	?>
	
	
	



<div class="container">

    <div class="card">
        <div class="card-content">
          <span class="card-title">
            <?php echo $this->getTitle(); ?>
          </span>
			<div class="col s12 m12 l12" style="margin-top: 20px;">
			 <ul class="collapsible" data-collapsible="accordion">
                <?php
                foreach($menue as $key => $value) {
					if ($value['navarea'] == $data['navarea']) {
					createCollapsibleMenue($menue,$value,$key);
					}
               } ?>
			</ul>
            </div>
			
			
        </div>

    </div>


</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
 <script>
$(document).ready(function(){
    $('.collapsible').collapsible();
  });
 </script> 
<script type="application/javascript">

  
function MM_openBrWindow(theURL,winName,features) { //v2.0
 window.open(theURL,winName,features);
}
</script>
</body>
</html>
