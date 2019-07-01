<div class="card">
<form action="exec/add.php" method="post" <?=$javascript?>>
<div class="block">
	<span class="float-left">
		<div>
			<h3>Kiszerelés hozzáadása</h3>
			<h4><?=$comp?> - <?=$manfac?> - <?=$batch?> (LOT#<?=$lot?>)</h4>
		</div>
	</span>
	<span class="float-right"><?=button('erase_popup')?></span>
	<span class="float-right"><?=button('submit')?></span>
</div>
<input type="hidden" id="batch_id" value="<?=$batch_id?>"/>
<input type="hidden" id="selector" value="pack"/>
<div class="block">
	<div class="float-left pad-m">
		<table class="form">
			<caption>Azonosítás</caption>
			<tr>
				<th>Kiszerelés</th>
				<td><input type="text" id="size" autofocus required /></td>
			</tr>
			<tr>
				<th>Eredeti kiszerelés</th>
				<td><input type="checkbox" id="is_orig" value="1"/></td>
			</tr>
			<tr>
				<th>Tömeg [g]<br/>(bruttó/tára)</th>
				<td><input type="text" id="weight"/></td>
			</tr>
			<tr>
				<th>Helyzet</th>
				<td>
				<select id="loc_id" required>
					<option></option>
					<?php // Location list from DB
					// Retrieve labs
					$lab = sql_get_lab($link);

					while ($row_lab = $lab->fetch_assoc()): ?>

						<optgroup label="<?=$row_lab['name']?>">

						<?php // Retrieve places $ sub inside laboratory
						$place = sql_get_place_sub($link, $row_lab['name']);

						while ($row_place = $place->fetch_assoc()) {

							if ($row_place['sub'] == NULL) {
								$location = $row_place['place'];
							} else {
								$location = $row_place['place'].' > '.$row_place['sub'];
							}

							echo '<option value="'.$row_place['id'].'">'.$location.'</option>';
						}?>
						</optgroup>
					<?php endwhile; ?>
				</select>
				</td>
			</tr>
		</table>
	</div>
	<div class="float-left pad-m">
		<table class="form">
			<caption>Megjegyzés</caption>
			<tr>
				<td><textarea id="note" cols="30"></textarea></td>
			</tr>
		</table>
	</div>
</div>
</form>
</div>