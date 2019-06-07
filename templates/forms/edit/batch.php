<div class="card">
<form action="exec/update.php" method="post" <?=$javascript?>>
<div class="block">
	<span class="float-left">
		<div>
			<h3>Termék módosítása</h3>
			<h4><?=$comp_name?> - <?=$manfac_name?> - <?=$name?> (LOT#<?=$lot?>)</h4>
		</div>
	</span>
	<span class="float-right"><?=button('erase_popup')?></span>
	<span class="float-right"><?=button('submit')?></span>
</div>
	<input type="hidden" id="batch_id" value="<?=$batch_id?>"/>
	<input type="hidden" id="selector" value="batch"/>
	<div class="block">
	<div class="float-left pad-m">
		<table class="form">
			<caption>Azonosítás</caption>
			<tr>
				<th>Gyártó</th>
				<td>
					<select id="manfac" required>
					<option></option>
						<?php // Retrieve manufacturers
						$freq = sql_get_manfac_freq($link);
						$in_freq = sql_get_manfac_infreq($link);?>

						<optgroup label="-----">
						<?php while ($row = $freq->fetch_assoc()): ?>
							<option value="<?=$row['id']?>" <?=($row['id'] == $manfac_id) ? 'selected ':''?>>
								<?=$row['name']?>
							</option>
						<?php endwhile; ?>
						</optgroup>
						<optgroup label="-----">
						<?php while ($row = $in_freq->fetch_assoc()): ?>
							<option value="<?=$row['id']?>" <?=($row['id'] == $manfac_id) ? 'selected ':''?>>
								<?=$row['name']?>
							</option>
						<?php endwhile; ?>
						</optgroup>
					</select>
				</td>
			</tr>
			<tr>
				<th>Név</th>
				<td><input type="text" id="name" value="<?=$name?>" required /></td>
			</tr>
			<tr>
				<th>LOT#</th>
				<td><input type="text" id="lot" value="<?=$lot?>" required /></td>
			</tr>
		</table>
	</div>
	<div class="float-left pad-m">
		<table class="form">
			<caption>Dátumok</caption>
			<tr>
				<th>Érkezett</th>
				<td><input type="date" id="arr" value="<?=$arr?>" required /></td>
			</tr>
			<tr>
				<th>Bontva</th>
				<td><input type="date" id="open" value="<?=$open?>"/></td>
			</tr>
			<tr>
				<th>Lejárat</th>
				<td><input type="date" id="exp" value="<?=$exp?>"/></td>
			</tr>
		</table>
	</div>
	<div class="float-left pad-m">
		<table class="form">
			<caption>Megjegyzés</caption>
			<tr>
				<td colspan="3"><textarea id="note" cols="30"><?=$note?></textarea></td>
			</tr>
		</table>
	</div>
	</div>
</form>
</div>