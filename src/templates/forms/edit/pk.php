<div class="card">
<form action="exec/update.php" method="post" <?=js('submit', ['pk&did='.$drug_id, 'pk_'.$drug_id])?>>
<div class="block">
	<span class="float-left"><h3>PK módosítása</h3></span>
	<span class="float-right"><?=button('erase_popup')?></span>
	<span class="float-right"><?=button('submit')?></span>
</div>
	<input type="hidden" id="pk_id" value="<?=$pk_id?>"/>
	<input type="hidden" id="selector" value="pk"/>
	<table class="form">
		<tr>
			<th>Tulajdonság</th>
			<td><select id="pk_attr" required >
				<option></option>
				<?php // Get list of PK attributes
				$attributes = sql_get_pk_attribute($link);
				while ($row = $attributes->fetch_assoc()): ?>
				<option value="<?=$row['id']?>" <?=($attribute == $row['id']) ? 'selected ':''?>>
					<?=$row['name']?>
				</option>
				<?php endwhile; ?>
			</select></td>
		</tr>
		<tr>
			<th>Érték</th>
			<td><input type="text" id="value" value="<?=$value?>" required /></td>
		</tr>
		<tr>
			<th>Megjegyzés</th>
			<td><input type="text" id="note" value="<?=$note?>"/></td>
		</tr>
	</table>
</form>
</div>