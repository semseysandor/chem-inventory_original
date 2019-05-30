<div class="card">
<form action="exec/update.php" method="post" <?=$javascript?>>
<div class="block">
	<span class="float-left"><h3>Kiszerelés módosítása</h3></span>
	<span class="float-right"><?=button('erase_popup')?></span>
	<span class="float-right"><?=button('submit')?></span>
</div>
<h4><?=$comp_name?> - <?=$manfac_name?> - <?=$batch_name?> (<?=$lot?>) [<?=$size?>]</h4>
	<input type="hidden" id="pack_id" value="<?=$pack_id?>"/>
	<input type="hidden" id="selector" value="pack"/>
	<div class="flex-container">
	<div class="pad-m">
		<table class="form">
			<caption>Azonosítás</caption>
			<tr>
				<th>Kiszerelés</th>
				<td><input type="text" id="size" value="<?=$size?>" required /></td>
			</tr>
			<tr>
				<th>Eredeti kiszerelés</th>
				<td><input type="checkbox" id="is_orig" value="1" <?=($is_orig) ? 'checked ':''?>/></td>
			</tr>
			<tr>
				<th>Tömeg [g]<br/>(bruttó/tára)</th>
				<td><input type="text" id="weight" value="<?=$weight?>"/></td>
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

						while ($row_pl = $place->fetch_assoc()): ?>

							<?php if ($row_pl['sub'] == NULL) {
								$location = $row_pl['place'];
							} else {
								$location = $row_pl['place'].' > '.$row_pl['sub'];
							}?>

							<option value="<?=$row_pl['id']?>" <?=($row_pl['id'] == $loc_id) ? 'selected ':''?>>
								<?=$location?>
							</option>
						<?php endwhile; ?>
						</optgroup>
					<?php endwhile; ?>
				</select>
				</td>
			</tr>
		</table>
	</div>
	<div class="pad-m">
		<table class="form">
			<caption>Megjegyzés</caption>
			<tr>
				<td colspan="3"><textarea id="note"><?=$note?></textarea></td>
			</tr>
		</table>
	</div>
	</div>
</form>
</div>