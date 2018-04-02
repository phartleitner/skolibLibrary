<?php 
$data = $this->getDataForView();
$menue = $data['menue']; 
include("header.php");
?>

<div class="container">

    <div class="card">
        <div class="card-content">
          <span class="card-title">
            <?php echo $this->getTitle(); ?>
          </span>
		  
		  <div >
            <p style="margin-top: 20px;">
			<ul class="collection">
                <?php
                foreach($menue as $m) {
					if ($m['navarea'] == $data['navarea']) {?> 
            <li class="collection-item">
			
                <a class="mdl-navigation__link orange-text btn-flat action" id="menueItem"
                   <?php
				   if($m['popup'] == 1) {?>
						 href="#" onClick="MM_openBrWindow('?type=<?php echo $m['type'];?>','','width=700,height=700')">
				   <?php
				   } else
				   {?>
					   href="?type=<?php echo $m['type'];?>">
				   <?php 
				   }
				   ?>
				   
				   <i class="material-icons left"><?php echo $m['icon']; ?></i>
				   <?php echo $m['value']; ?></a>
            
			</li>
            <?php
				}
               } ?>
			</ul>
            </p>
			</div>
			
        </div>

    </div>


</div>
<script type="application/javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
 window.open(theURL,winName,features);
}
</script>
</body>
</html>
