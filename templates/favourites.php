
<?php
$favourites = $data['dashboard']['favourites'];
?>

	<div id = "favourites" class = "card-panel green-text">
	<h4>beliebteste Titel<a href="#" onClick="showFavs();">
	<i id="favNav" class="material-icons right grey-text">keyboard_arrow_down</i>
	</a></h4>
	
	<div id="favTitles" style="display: none">
	<table class="striped black-text">
	<?php
	$max =  (count($favourites) < 10) ? count($favourites) : 9;
	$x = 0;
	while ($x < $max)  { 
	$item=$favourites[$x]['item'];
	$item->getItemDetails();
	?>
	<tr>
		<td><b><?php echo $item->getTitle(); ?></b>(<?php echo $item->getAuthor(); ?>) <?php echo $favourites[$x]['count'];?> mal verliehen.</td>
	</tr>	
	<?php 
	$x++;
	} ?>
	</table>
	</div>
	</div>