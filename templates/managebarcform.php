
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
</head>
<?php
$data = $this->getDataForView();
?>

<script type="text/javascript">

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function MM_jumpMenu(selObj,restore)
{ //v3.0
F1 = window.open("","Barcodedruckformat","width=800,height=900,scrollbars=yes,left=10,top=10");
eval("F1.location='"+selObj.options[selObj.selectedIndex].value+"'");
if (restore) selObj.selectedIndex=0;
}
</script>
<div><span style="color: #ff0000;font-weight: bold;">This is pure admin area! No design, no errors caught! You really need to know what you do!<br> DO NOT PLAY AROUND!<br/></div>

		
<?php 

if (!isset($data['detail']) ) {//show all available forms  ?>


<table border="1">
	<tr style="font-weight:bold;">
		<td>FormName</td>
		<td>columns</td>
		<td>rows</td>
		<td>max pages</td>
		<td></td>
		<td></td>
	</tr>
	<?php foreach ($data['barcforms'] as $barcform) { ?>
	<form method = "POST" action="?type=managebarcform" >
	<input type = "hidden" name="detail" value="<?php echo $barcform['id']; ?>">
	<tr>
		<td style="font-weight:bold;"><?php echo $barcform['name']; ?></td>
		<td><?php echo$barcform['cols']; ?></td>
		<td><?php echo$barcform['rows']; ?></td>
		<td><?php echo$barcform['maxpages']; ?></td>
		<td><input type="submit" name="change" value="Change"></td>
		<td><input type="submit" name="new" value="New" ></td>
	</tr>
	</form>
	<?php } ?>
</table>

<?php } else {
?>
<form method="POST" action="?type=managebarcform&save=true">
<?php
//show the form to be edited
$currentBarcformKey = $data['detail'];
if ($data['new']) {
$formName = "new name";
} else {
$formName = $data['barcforms'][$currentBarcformKey]['name'];
?>
<input type="hidden" name="id" value="<?php echo $data['barcforms'][$currentBarcformKey]['id']; ?> ">
<?php
}	

?>

<table >
	<tr >
		<td style="font-weight:bold;">FormName</td>
		<td><input type="text" name ="name" value="<?php echo $formName; ?>"></td>
	</tr>
	<tr >
		<td style="font-weight:bold;">margin_left</td><td><input type="text" name ="margin_left" value="<?php echo $data['barcforms'][$currentBarcformKey]['margin_left']; ?>"></td>
	</tr>
	<tr >	
		<td style="font-weight:bold;">margin_top</td><td><input type="text" name ="top" value="<?php echo $data['barcforms'][$currentBarcformKey]['top']; ?>"></td>
	</tr>
	<tr>	
		<td style="font-weight:bold;">Fontsize</td><td><input type="text" name ="fontsize" value="<?php echo $data['barcforms'][$currentBarcformKey]['fontsize']; ?>"></td>
	</tr>
	<tr >	
		<td style="font-weight:bold;">line height</td><td><input type="text" name ="lineheight" value="<?php echo $data['barcforms'][$currentBarcformKey]['lineheight']; ?>"></td>
	</tr>
	<tr >	
		<td style="font-weight:bold;">columns</td><td><input type="text" name ="cols" value="<?php echo $data['barcforms'][$currentBarcformKey]['cols']; ?>"></td>
	</tr>
	<tr >	
		<td style="font-weight:bold;">rows</td><td><input type="text" name ="rows" value="<?php echo $data['barcforms'][$currentBarcformKey]['rows']; ?>"></td>
	</tr>
	<tr >	
		<td style="font-weight:bold;">column_width</td><td><input type="text" name ="colwidth" value="<?php echo $data['barcforms'][$currentBarcformKey]['colwidth']; ?>"></td>
	</tr>
	<tr >	
		<td style="font-weight:bold;">row_height</td><td><input type="text" name ="rowheight" value="<?php echo $data['barcforms'][$currentBarcformKey]['rowheight']; ?>"></td>
	</tr>
	<tr >	
		<td style="font-weight:bold;">pic_padding_vertical</td><td><input type="text" name ="picspace_v" value="<?php echo $data['barcforms'][$currentBarcformKey]['picspace_v']; ?>"></td>
	</tr>
	<tr >	
		<td style="font-weight:bold;">text_padding_vertical</td><td><input type="text" name ="textspace_v" value="<?php echo $data['barcforms'][$currentBarcformKey]['textspace_v']; ?>"></td>
	</tr>
	<tr >	
		<td style="font-weight:bold;">text_padding_horizontal</td><td><input type="text" name ="textspace_h" value="<?php echo $data['barcforms'][$currentBarcformKey]['textspace_h']; ?>"></td>
	</tr>
	<tr >	
		<td style="font-weight:bold;">include barcode</td><td><input type="text" name ="showcode" value="<?php echo $data['barcforms'][$currentBarcformKey]['showcode']; ?>"></td>
	</tr>
	<tr >	
		<td style="font-weight:bold;">max. pages</td><td><input type="text" name ="maxpages" value="<?php echo $data['barcforms'][$currentBarcformKey]['maxpages']; ?>"></td>
	</tr>
	<tr >	
		<td style="font-weight:bold;">pic_width</td><td><input type="text" name ="picwidth" value="<?php echo $data['barcforms'][$currentBarcformKey]['picwidth']; ?>"></td>
	</tr>
	<tr >	
		<td style="font-weight:bold;">pic_height</td><td><input type="text" name ="picheight" value="<?php echo $data['barcforms'][$currentBarcformKey]['picheight']; ?>"></td>
	</tr>
	<tr >	
		<td style="font-weight:bold;">pic_ratio</td><td><input type="text" name ="ratio" value="<?php echo $data['barcforms'][$currentBarcformKey]['ratio']; ?>"></td>
	</tr>
	<tr >	
		<td style="font-weight:bold;">show_signature</td><td><input type="text" name ="signatur" value="<?php echo $data['barcforms'][$currentBarcformKey]['signatur']; ?>"></td>
	</tr>
	<tr>
		<td ><input type="submit" value="Save"></td>
	</tr>
</table>
</form>
<?php
//Insert save buttons
}
	

?>

</body>
</html>