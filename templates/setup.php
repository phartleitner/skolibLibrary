<?php
$data = $this->getDataForView(); 
$setups = $data['setups'];			

?>

<html>
<head>
</head>
<body>
<?php if(isset($data['message'])) { ?>
<div style="font-family:Arial;font-size:30px;color:red;font-weight:bold">
	<?php echo $data['message']; ?>
</div>	
<?php } ?>
<div>
<form action="?type=setup&update" method="POST" style="position:relative;display:inline">
<?php foreach($setups as $setup) { ?>
<div>
	<div id="header" style="font-family:Arial;font-size:30px;margin-top:20px">
		<?php echo $setup['category']; ?>
	</div>
	
	<?php foreach($setup['settings'] as $field) { ?>
	<div id="content" style="margin-top:20px;">
		<div style="font-family:Arial;font-size:15px;position:relative;display:inline; width:300px;">
			<?php echo $field['name']; ?>
			<?php if (isset($field['comment'])) { ?>
			<br/>
			<span style="font-family:Arial;font-size:12px;color:red;"><?php echo $field['comment']; ?></span>
			<?php } ?>
		</div>
		<div style="position:absolute;display:inline;left:400px;">
			<input type="text" name="<?php echo $field['feld']; ?>" value="<?php echo $field['value']; ?>" size="5"/>
		</div>
	</div>
<?php }?>
</div>
<?php }?>
<div style="margin-top:20px">
<input  type="submit" value="Speichern">
</div>

</div>
</form>
</div>

<?php 
//header('Content-Type: application/json');
//echo $jsonSetups; ?>

</body>
</html>