<div class="card">
<form action="exec/update.php" method="post" id="e_comp" <?=$javascript?>>
<div class="block">
	<span class="float-left"><h3>Vegyszer módosítása</h3></span>
	<span class="float-right"><?=button('erase_popup')?></span>
	<span class="float-right"><?=button('submit')?></span>
</div>
<h4><?=$name?></h4>
	<input type="hidden" id="comp_id" value="<?=$comp_id?>"/>
	<input type="hidden" id="selector" value="compound"/>
	<div class="flex-container">
	<div class="pad-m">
		<table class="form">
			<caption>Azonosítás</caption>
			<tr>
				<th>Név</th>
				<td><input type="text" id="name" value="<?=$name?>" required /></td>
			</tr>
			<tr>
				<th>Egyéb név</th>
				<td><input type="text" id="name_alt" value="<?=$name_alt?>"/></td>
			</tr>
			<tr>
				<th>Rövidítés</th>
				<td><input type="text" id="abbrev" value="<?=$abbrev?>"/></td>
			</tr>
			<tr>
				<th>Kémiai név</th>
				<td><input type="text" id="chem_name" value="<?=$chem_name?>"/></td>
			</tr>
			<tr>
				<th>IUPAC név</th>
				<td><input type="text" id="iupac" value="<?=$iupac?>"/></td>
			</tr>
			<tr>
				<th>Összegképlet</th>
				<td><input type="text" id="chem_form" value="<?=$chem_form?>"/></td>
			</tr>
			<tr>
				<th><?=button('calc_cas')?>CAS</th>
				<td><input type="text" id="cas" value="<?=$cas?>" pattern="[0-9]{2,7}-[0-9]{2}-[0-9]" title="xxxxxxx-xx-x"/></td>
			</tr>
				<tr>
				<th>SMILES</th>
			<td><input type="text" id="smiles" value="<?=$smiles?>"/></td>
			</tr>
			<tr>
				<th>Kategória</th>
				<td><select id="subcat_id" required>
				<option></option>
				<?php // Retrieve categories
				$categories = sql_get_category($link);
				while ($cat = $categories->fetch_assoc()): ?>
					<optgroup label="<?=$cat['name']?>">
					<?php // Retrieve sub-categories
					$subcategory = sql_get_subcategory($link, $cat['id']);

					while ($subcat = $subcategory->fetch_assoc()): ?>
						<option value="<?=$subcat['id']?>" <?=($subcat['id'] == $subcat_id) ? 'selected ':''?>>
							<?=$subcat['name']?>
						</option>
					<?php endwhile; ?>
					</optgroup>
				<?php endwhile; ?>
				</select></td>
			</tr>
		</table>
	</div>
	<div class="pad-m">
		<table class="form">
		<caption>Fizikai-kémiai adatok</caption>
			<tr>
				<th>M<sub>w</sub> [g/mol]</th>
				<td><input type="text" id="mol_weight" value="<?=$mol_weight?>"/></td>
			</tr>
			<tr>
				<th>T<sub>olv</sub> [°C]</th>
				<td><input type="text" id="melt" value="<?=$melt?>"/></td>
			</tr>
		</table>
		<table class="form">
			<caption>Információ</caption>
			<tr>
				<th>OEB kategória</th>
				<td>
					<select id="oeb">
						<option></option>
						<?php for ($i = 1; $i <= 5; $i++): ?>
						<option value="<?=$i?>" <?=($oeb == $i) ? 'selected ':''?>><?=$i?></option>
						<?php endfor; ?>
					</select>
				</td>
			</tr>
		</table>
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