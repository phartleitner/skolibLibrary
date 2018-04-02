<?php
include("popupheader.php"); 
$data = $this->getDataForView();

foreach ($data['items'] as $item) {
$item->getItemDetails();	
$item->makeSignature();
echo $item->getTitle().'/'.$item->getAuthor()." -- ".mb_convert_encoding($item->getSignature(),"UTF-8");
echo '<br>';	
}



?>


</body>
</html>
