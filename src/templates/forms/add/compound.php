<div class="card">
<form action="exec/add.php" method="post" <?=$javascript?>>
<div class="block">
	<span class="float-left"><h3>Vegyszer hozzáadása</h3></span>
	<span class="float-right"><?=button('erase_popup')?></span>
	<span class="float-right"><?=button('submit')?></span>
</div>
	<input type="hidden" id="selector" value="compound"/>
	<div class="block">
	<div class="float-left pad-m">
		<table class="form">
			<caption>Azonosítás</caption>
			<tr>
				<th>Név</th>
				<td><input type="text" id="name" required /></td>
			</tr>
				<tr>
				<th>Egyéb név</th>
				<td><input type="text" id="name_alt"/></td>
			</tr>
			<tr>
				<th>Rövidítés</th>
				<td><input type="text" id="abbrev"/></td>
			</tr>
			<tr>
				<th>CAS</th>
				<td><input type="text" id="cas" pattern="[0-9]{2,7}-[0-9]{2}-[0-9]" title="xxxxxxx-xx-x"/></td>
			</tr>
			<tr>
			<tr>
				<th>SMILES</th>
				<td><input type="text" id="smiles"/></td>
			</tr>
			<tr>
				<th>Kategória</th>
				<td><select id="subcat_id" required >
				<option></option>
				<?php // Retrieve categories
					$categories = sql_get_category($link);

					while ($row_cat = $categories->fetch_assoc()): ?>
						<optgroup label="<?=$row_cat['name']?>">
						<?php // Retrieve sub-categories
						$subcategory = sql_get_subcategory($link, $row_cat['id']);

						while ($row_sub = $subcategory->fetch_assoc()): ?>
							<option value="<?=$row_sub['id']?>"><?=$row_sub['name']?></option>
						<?php endwhile; ?>
						</optgroup>
					<?php endwhile; ?>
				</select></td>
			</tr>
		</table>
	</div>
	<div class="float-left pad-m">
		<table class="form">
			<caption>Információ</caption>
			<tr>
				<th>T<sub>olv</sub> [°C]</th>
				<td><input type="text" id="melt"/></td>
			</tr>
			<tr>
				<th>OEB kategória</th>
				<td>
					<select id="oeb">
						<option></option>
						<?php for ($i = 1; $i <= 5; $i++): ?>
						<option value="<?=$i?>"><?=$i?></option>
						<?php endfor; ?>
					</select>
				</td>
			</tr>
		</table>
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