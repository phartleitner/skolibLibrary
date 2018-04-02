<?php 
$data = $this->getDataForView();
$menue = $data['menue'];
include("header.php");
?>



<div class="container">

    <div class="card ">
        <div class="card-content">
            <span class="card-title">
                <?php echo $data['header']; ?>
            </span>
            <p>
                überprüfte Datensätze: <?php echo $data['fileData'][0]; ?><br>
                eingefügte Datensätze: <?php echo $data['fileData'][1]; ?><br>
                gelöschte Datensätze: <?php echo $data['fileData'][2]; ?>
            </p>

            <p>
                <?php //echo $data["action"]; 
				?></br />
            </p>


        </div>

    </div>

</div>


</body>
</html>
