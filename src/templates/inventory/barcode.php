<h3>Lokáció kiválasztva: <?=$loc['lab_name'].' > '.$loc['place_name'].($loc['sub_name'] ? ' > ':'').$loc['sub_name']?></h3>
<form class="search" action="exec/inventory.php" <?=js_spec('invent_barcode')?> method="get" autocomplete="off" >
		<h3>Vonalkód</h3>
		<input type="search" id="barcode" required />
		<input type="hidden" id="location" value="<?=$location_id?>"/>
		<button type="submit" class="button submit fa-lg">
			<i class="fas fa-search"></i>
		</button>
</form>
<section id="inventory_scan"></section>