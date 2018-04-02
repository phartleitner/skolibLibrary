<?php 
$data = $this->getDataForView();
$menue = $data['menue']; 
include("header.php");
echo '<br><br>Menue:<br/>';

foreach ($menue as $key => $value) {

if ($value['navarea'] == $data['navarea']	) {
echo $value['value'];
echo '<br/>';
//make submenue
createCollapsibleMenue1($menue,$value['id'],$key,"--");
}
	
}










/**
* create a collapsible menue
* by iterating throug items
**/
function createCollapsibleMenue1($items,$navarea,$key,$list){
unset($items[$key]);
$list .= "--";
foreach ($items as $key => $value){
		if($value['navarea'] == $navarea) {
			echo $list." ".$value['value']; 
			echo '<br/>';
			createCollapsibleMenue1($items,$value['id'],$key,$list);
			}	
	}
}

/**
* create a collapsible menue
* by iterating throug items
**/
function createCollapsibleMenue($items,$navarea,$key){
unset($items[$key]);
foreach ($items as $key => $value){
		if($value['navarea'] == $navarea) {
			createMenueEntry($value);				
			createCollapsibleMenue($items,$value['id'],$key);
			}	
	}
}

function createMenueEntry($value){
if ($value['collapsible']) { 
	$collapsible =  '<span class="right"><i class="material-icons right">keyboard_arrow_down</i></i>';
	$link = '';
	?>
	<ul class="collapsible" data-collapsible="accordion">
	<?php
	}
	else {
	$collapsible = "";	
	if($value['popup'] == 1) {
			$link = "href=\"#\" onClick=\"MM_openBrWindow('?type=".$value['type']."','','width=700,height=700')\"";
		} else {
			$link = 'href="?type='.$value['type'].'"';
		}
	}
	?>
	
	<li>
	<div class="collapsible-header">
	<a class="mdl-navigation__link orange-text btn-flat" <?php echo $link; ?> >
	   <i class="material-icons left"><?php echo $value['icon']; ?></i>
	   <?php echo $value['value']; ?></a> 
	   <?php echo $collapsible; ?>
	</div>
	</li>
	<?php if ($value['collapsible']) { ?>
	<div class="collapsible-body"><span><?php createCollapsibleMenue($menue,$value['id'],$key,"--"); ?></span></div>
	</ul>
	
	<?php
	}	
}



?>

<div class="container">

    <div class="card">
        <div class="card-content">
          <span class="card-title">
            <?php echo $this->getTitle(); ?>
          </span>
			<div style="margin-top: 20px;">
			 <ul class="collapsible" data-collapsible="accordion">
                <?php
                foreach($menue as $key => $value) {
					if ($value['navarea'] == $data['navarea']) {?> 
			    
				<?php 
					createMenueEntry($value);
					createCollapsibleMenue($menue,$value['id'],$key);
										
				?>
				
            <?php
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
