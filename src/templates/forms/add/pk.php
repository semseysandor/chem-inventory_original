<div class="card">
<form action="exec/add.php" method="post" <?=js('submit', ['pk&did='.$drug_id, 'pk_'.$drug_id])?>>
<div class="block">
	<span class="float-left"><h3>PK hozzáadása</h3></span>
	<span class="float-right"><?=button('erase_popup')?></span>
	<span class="float-right"><?=button('submit')?></span>
</div>
	<input type="hidden" id="drug_id" value="<?=$drug_id?>"/>
	<input type="hidden" id="selector" value="pk" />
	<table class="form">
		<tr>
			<th>Tulajdonság</th>
			<td>
				<select id="attr_id" required >
					<option></option>
					<?php // Get list of PK attributes
					$pk_attr= sql_get_pk_attribute($link);
					while ($row_pk = $pk_attr->fetch_assoc()): ?>
						<option value="<?=$row_pk['id']?>" ><?=$row_pk['name']?></option>
					<?php endwhile; ?>
				</select>
			</td>
		</tr>
		<tr>
			<th>Érték</th>
			<td><input type="text" id="value" required /></td>
		</tr>
		<tr>
			<th>Megjegyzés</th>
			<td><input type="text" id="note" /></td>
		</tr>
	</table>
</form>
</div>