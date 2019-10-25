<div class="block">
	<span class="float-left"><button class="button restart font-m" <?=js_spec('invent_start')?>> Új lokáció</button></span>
	<span class="float-left"><button class="button category font-m" <?=js_spec('invent_missing')?>> Hiányzó kiszerelések</button></span>
	<span class="float-right"><button class="button delete font-m" <?=js_spec('invent_clear')?>> Új leltárazás</button></span>
</div>
<section id="missing_packs"></section>
<div class="block">
		<h1>Leltárazás</h1>
</div>
<form id="location_select" class="search" action="exec/inventory.php" <?=js_spec('invent_location')?> method="get">
	<h3>Lokáció</h3>
	<table class="form">
		<tr>
			<td><select id="loc_id" required>
				<option></option>
				<?php // Retrieve labs
				$lab = sql_get_lab($link);

				while ($row_lab = $lab->fetch_assoc()): ?>

					<optgroup label="<?=$row_lab['name']?>">

					<?php // Retrieve places $ sub inside laboratory
					$place = sql_get_place_sub($link, $row_lab['name']);

					while ($row_place = $place->fetch_assoc()) {

						$location = ($row_place['sub'] == NULL) ? $row_place['place'] : $row_place['place'].' > '.$row_place['sub'];

						echo '<option value="'.$row_place['id'].'">'.$location.'</option>';
					}?>
					</optgroup>
				<?php endwhile; ?>
			</select></td>
			<td>
				<button type="submit" class="button submit">
					<i class="far fa-play-circle font-l"></i> Kiválasztás
				</button>
				<button class="button selected" <?=js_spec('invent_load_missing')?>>
					<i class="far fa-arrow-alt-circle-down font-l"></i> Betöltés
				</button>
			</td>
		</tr>
	</table>
</form>
<section id="inventory"></section>