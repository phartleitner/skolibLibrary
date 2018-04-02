<?php

$data = $this->getDataForView();

?>
<div><span style="color: #ff0000;font-weight: bold;">This is pure admin area! No design, no errors caught! You really need to know what you do!<br> Please assign "default" if you want a signature rule for all items.<br/>
	In case you want a specific rule for a certain category enter the query in the empty lines and assign your favourite category.<br/> 
	Do not forget to enter an ordinal! Do not leave any fields blank!
</div>


<?php
$x = 0;
if ( $data['signature'] ) { ?>
	<form method = "POST" action="?type=managesig&mode=write">
	<table  >
	<tr style="font-weight: bold;">
		<td>Datenfeld</td>
		<td>Query</td>
		<td>Kategorie</td>
		<td>Länge</td>
		<td>Reihenfolge</td>
		<td>laufende Nr</td>
	</tr>
	<?php
	foreach ($data['sigsettings'] as $setting) {
		 
		?>
		<input type="hidden" name="settingId[]" value="<?php echo $setting['id']; ?>" >
		<input type="hidden" name="id[]" value="<?php echo $setting['ruleId']; ?>" >
		<tr>
			<td>
				<select name="field[]" > 
				
				<option value="0">Feld wählen</option>	
				<?php
				foreach($data['dataFields'] as $field) { 
				if ($field['id'] == $data['sigrules'][$setting['ruleId']]['field'] ) {$mode =  "selected" ;}else {$mode = "";} ?>
				<option value="<?php echo $field['id']; ?>" <?php echo $mode; ?> > <?php echo $field['label']; ?> </option>
				<?php	
					}
				
				?>
				</select>
			</td>
			<td><textarea cols = "100" rows = "3" name="query[]"><?php echo $data['sigrules'][$setting['ruleId']]['query']; ?></textarea> </td> 
			<td>
				<select name="cat[]" > 
				
				<?php
				if ($setting['hkatId'] == 0) { $mode = "selected" ;}else { $mode = ""; }?>
				<option value="0" <?php echo $mode; ?> >default</option>	
				<?php
				foreach($data['categories'] as $cat) { 
				if ($cat['id'] == $setting['hkatId'] ) {$mode =  "selected" ;}else {$mode = "";} ?>
				<option value="<?php echo $cat['id']; ?>" <?php echo $mode; ?> > <?php echo $cat['value']; ?> </option>
				<?php	
					}
				
				?>
				</select>
			</td>
			<td><input type="text" name="length[]" size="2" value="<?php echo $setting['length']; ?>"> </td>
			<td><input type="text" name="ordinal[]" size="2"  value="<?php echo $setting['ordinal']; ?>"> </td>
			<td><input type="text" size = "1" name="addNr[]" value="<?php echo $setting['addNr']; ?>" ></td>
			
		</tr>
		<?php
		$x++;
		}
	?>
	<!-- new line for a new entry -->
		<tr>
			<td>
				<select name="field[]" > 
				
				<option value="0" selected>Feld wählen</option>	
				<?php
				foreach($data['dataFields'] as $field) { ?> 
				<option value="<?php echo $field['id']; ?>" <?php echo $mode; ?> > <?php echo $field['label']; ?> </option>
				<?php	
					}
				
				?>
				</select>
			</td>
			<td><textarea cols = "100" rows = "3" name="query[]"></textarea></td>
			<td>
				<select name="cat[]" > 
					<option value="0" >default</option>	
				<?php
				foreach($data['categories'] as $cat) { ?> 
					<option value="<?php echo $cat['id']; ?>"> <?php echo $cat['value']; ?> </option>
				<?php	
					}
				?>
				</select>
			</td>			
			<td><input type="text" name="length[]" size="2" value=""> </td>
			<td><input type="text" name="ordinal[]" size="2" value=""> </td>
			
		</tr>
		<tr>
			<td colspan="5" align="right">
			<input type="submit" value="Speichern">
			</td>
		</tr>
	</table>
	</form>
	<?php
	} else{
	echo "<b>No Signatures assigned!</b>";
	}
	?>




</body>
</html>
