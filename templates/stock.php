<?php
$dueItems = array();
$overdueNotices = array();
$today = date('Ymd');


		
if (isset($data['dashboard']['inventoryAmount']) || isset($data['dashboard']['borrowedItemsAmount']) ) {
?>

<div class = "card-panel white" id="stock-details">
<?php 

?>
<?php if (isset($data['dashboard']['inventoryAmount']) ) { ?>
<h5><b>Buchbestand:</b>
<?php  echo $data['dashboard']['inventoryAmount']; ?> Titel 
</h5> 
<?php } 
if (isset($data['dashboard']['borrowedItemsAmount']) ) {?>
<h5>
<?php  echo $data['dashboard']['borrowedItemsAmount']; ?> Titel verliehen 
</h5>
<?php } ?>
</div>
<?php 
}
if (isset($data['dashboard']['dueItems']) ) {
if (count($data['dashboard']['dueItems']) > 0) { 
	//get important data for display
	foreach ($data['dashboard']['dueItems'] as $item) {
		$itemDetails = $item->getItemDetails();
		$itemDueDetails = $item->getDueDetails( $item->getId() );
		$itemBorrower = $item->getItemStatus()['customer'];
		$itemBorrower->setCustomerData();
		$dueItems[] = array("id" => $item->getId(),"titel" => $itemDetails['titel'],
			"autor" => $itemDetails['autor'], "signatur" => $itemDetails['signatur'],
			"due" => Model::getInstance()->makeProperDate($itemDueDetails['due']),"customer" => $itemBorrower->getFullName().'('.$itemBorrower->getForm().')');
		}
	}

?>
<div class = "card-panel orange-text" id="due-items">
<h4>fällige Titel</h4>

<?php echo count($data['dashboard']['dueItems']); ?> Titel 
<?php if (count($data['dashboard']['dueItems']) > 0 ) { ?>
<a href="#" onClick="MM_openBrWindow('?type=reminder','','width=600,height=600');location.reload();" class="btn-flat right waves-effect waves-teal right">Mahnungen drucken</a>
<a href="#" onClick="showDues();">
<i id="duesNav" class="material-icons right grey-text">keyboard_arrow_down</i>
</a>
<div id="duelist" style="display: none">
<table class="striped black-text">
<?php foreach ($dueItems as $item) { ?>
<tr>
	<td><b><?php echo $item['titel']; ?></b>(<?php echo $item['autor']; ?>)</td>
	<td><b>Entleiher:</b> <?php echo $item['customer']; ?></td>
	<td><b>fällig:</b> <?php echo $item['due']; ?></td>

</tr>	
<?php } ?>
</table>
</div>
<?php } ?>
</div>
<?php 
}

 ?>
<?php 
if (isset($data['dashboard']['warnedItems']) ) {
if (count($data['dashboard']['warnedItems']) > 0) { 
foreach($data['dashboard']['warnedItems'] as $item) {
	$itemDetails = $item->getItemDetails();
	$itemDueDetails = $item->getDueDetails( $item->getId() );
	$itemBorrower = $item->getItemStatus()['customer'];
	$itemBorrower->setCustomerData();
	$overdueNotices[] = array("id" => $item->getId(),"titel" => $itemDetails['titel'],
		"autor" => $itemDetails['autor'], "signatur" => $itemDetails['signatur'],
		"due" => Model::getInstance()->makeProperDate($itemDueDetails['due']),"customer" => $itemBorrower->getFullName().'('.$itemBorrower->getForm().')',"customerId"=>$itemBorrower->getId());	
	}	
?>
<div class = "card-panel red-text">
<h4>Mahnungen</h4>
<?php echo count($overdueNotices); ?> offene Mahnungen
<a href="#" onClick="showOverDues();">
<i id="overduesNav" class="material-icons right grey-text">keyboard_arrow_down</i>
</a>
<div id="overduelist" style="display: none">
<table class="striped black-text">
<?php foreach ($overdueNotices as $item) { ?>
<tr>
	<td><b><?php echo $item['titel']; ?></b>(<?php echo $item['autor']; ?>)</td>
	<td><b>Entleiher:</b> <?php echo $item['customer']; ?></td>
	<td><b>fällig:</b> <?php echo $item['due']; ?></td>
	<td><a href="#" onClick="MM_openBrWindow('./index.php?type=reminder&rpt=<?php echo $item['customerId']; ?>','','width=600,height=600,resizable=yes' )"><i class="material-icons right grey-text">print</i> </a></td>

</tr>	
<?php } ?>
</table>
</div>
</div>
<?php }
} ?>

<script>
var showDueState = false;
var showNoticeState = false;
var showFavState = false;
function showDues() {
	if(this.showDueState == false) {
	$('#duelist').show();
	$('#duesNav').html("keyboard_arrow_up");
		this.showDueState = true;
	} else {
	$('#duelist').hide();
	$('#duesNav').html("keyboard_arrow_down");	
	this.showDueState = false;
	}
	
	
}

function showOverDues() {
	if(this.showNoticeState == false) {
	$('#overduelist').show();
	$('#overduesNav').html("keyboard_arrow_up");
		this.showNoticeState = true;
	} else {
	$('#overduelist').hide();
	$('#overduesNav').html("keyboard_arrow_down");	
	this.showNoticeState = false;
	}
	
	
}


function showFavs() {
	if(this.showFavState == false) {
	$('#favTitles').show();
	this.showFavState = true;
	$('#favNav').html('keyboard_arrow_up');
	} else {
	$('#favTitles').hide();
	this.showFavState = false;
	$('#favNav').html('keyboard_arrow_down');	
	}
}

function MM_openBrWindow(theURL,winName,features,select) { //v2.0
if(select === undefined) {select = null;}
if (select) {
	theURL += "&group="+$('#grps').val();
	}
window.open(theURL,winName,features);
}



</script>
