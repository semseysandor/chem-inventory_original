<div class="card">
<form action="exec/update.php" method="post" <?=js('submit', ['location', 'index'])?>>
	<div class="block">
		<span class="float-left"><h3>Lokáció módosítása</h3></span>
		<span class="float-right"><?=button('erase_popup')?></span>
		<span class="float-right"><?=button('submit')?></span>
	</div>
	<input type="hidden" id="selector" value="location"/>
	<input type="hidden" id="loc_id" value="<?=$location_id?>"/>
	<div class="block">
		<table class="form">
			<tr>
				<th>Labor</th>
				<td>
				<select id="lab_id" required >
					<option></option>
					<?php
					$result = sql_get_lab($link);

					while ($row = $result->fetch_assoc()): ?>

						<option value="<?=$row['id']?>" <?=($row['id'] == $lab_id) ? 'selected ':''?>>
							<?=$row['name']?>
						</option>

					<?php endwhile; ?>
				</select>
				</td>
			</tr>
			<tr>
				<th>Hely</th>
				<td>
				<select id="place_id" required>
					<option></option>
					<?php
					$result = sql_get_place($link);

					while ($row = $result->fetch_assoc()): ?>

						<option value="<?=$row['id']?>" <?=($row['id'] == $place_id) ? 'selected ':''?>>
							<?=$row['name']?>
						</option>

					<?php endwhile; ?>
				</select>
				</td>
			</tr>
			<tr>
				<th>Alhely</th>
				<td>
				<select id="sub_id" required>
					<option></option>
					<?php
					$result = sql_get_sub($link);

					while ($row = $result->fetch_assoc()): ?>

						<option value="<?=$row['id']?>" <?=($row['id'] == $sub_id) ? 'selected ':''?>>
							<?=($row['name']=='' ? 'nincs' : $row['name'])?>
						</option>

					<?php endwhile; ?>
				</select>
				</td>
			</tr>
		</table>
	</div>
</form>
</div>