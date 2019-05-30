<div class="card">
<form action="exec/add.php" method="post" <?=js('submit', ['batch&cid='.$comp_id, 'batch_'.$comp_id])?>>
<div class="block">
	<span class="float-left"><h3>Termék hozzáadása</h3></span>
	<span class="float-right"><?=button('erase_popup')?></span>
	<span class="float-right"><?=button('submit')?></span>
</div>
<input type="hidden" id="comp_id" value="<?=$comp_id?>"/>
<input type="hidden" id="selector" value="batch"/>
<div class="flex-container">
	<div class="pad-m">
		<table class="form">
			<caption>Azonosítás</caption>
			<tr>
				<th>Gyártó</th>
				<td>
					<select id="manfac" required autofocus >
					<option></option>
					<?php // Retrieve manufacturers
						$freq = sql_get_manfac_freq($link);
						$in_freq = sql_get_manfac_infreq($link);?>

						<optgroup label="-----">
						<?php while ($row = $freq->fetch_assoc()): ?>
							<option value="<?=$row['id']?>"><?=$row['name']?></option>
						<?php endwhile; ?>
						</optgroup>
						<optgroup label="-----">
						<?php while ($row = $in_freq->fetch_assoc()): ?>
							<option value="<?=$row['id']?>"><?=$row['name']?></option>
						<?php endwhile; ?>
						</optgroup>
					</select>
				</td>
			</tr>
			<tr>
				<th>Név</th>
				<td><input type="text" id="name" required /></td>
			</tr>
			<tr>
				<th>LOT#</th>
				<td><input type="text" id="lot" required /></td>
			</tr>
		</table>
	</div>
	<div class="pad-m">
		<table class="form">
		<caption>Dátumok</caption>
			<tr>
				<th>Érkezett</th>
				<td><input type="date" id="arr" value="<?=date('Y-m-d')?>" required /></td>
			</tr>
			<tr>
				<th>Bontva</th>
				<td><input type="date" id="open"/></td>
			</tr>
			<tr>
				<th>Lejárat</th>
				<td><input type="date" id="exp"/></td>
			</tr>
		</table>
	</div>
	<div class="pad-m">
		<table class="form">
		<caption>Megjegyzés</caption>
			<tr>
				<td><textarea id="note"></textarea></td>
			</tr>
		</table>
	</div>
</div>
</form>
</div>