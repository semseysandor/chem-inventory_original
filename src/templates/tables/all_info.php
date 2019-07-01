<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 2): ?>
<div>
	<?=button('inactivate', [$all_info['batch_id'], $all_info['pack_id'], $all_info['barcode']])?>
</div>
<?php endif; ?>
<div class="block">
<div class="float-left pad-m">
	<table class="comp_det">
		<caption>
			<span class="float-left" style="margin-top:.25em">Vegyszer</span>
			<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 1): ?>
			<span class="float-right">
				<?=button('e_comp_search', [$all_info['comp_id'], $all_info['barcode']])?>
			<span>
			<?php endif; ?>
		</caption>
		<tbody>
			<tr>
				<th>Név</th>
				<td><?=$all_info['comp']?></td>
			</tr>
			<?php if (isset($all_info['name_alt'])): ?>
			<tr>
				<th>Egyéb név</th>
				<td><?=$all_info['name_alt']?></td>
			</tr>
			<?php endif; ?>
			<?php if (isset($all_info['abbrev'])): ?>
			<tr>
				<th>Rövídítés</th>
				<td><?=$all_info['abbrev']?></td>
			</tr>
			<?php endif; ?>
			<?php if (isset($all_info['chemical'])): ?>
			<tr>
				<th>Kémiai név</th>
				<td><?=$all_info['chemical']?></td>
			</tr>
			<?php endif; ?>
			<?php if (isset($all_info['iupac'])): ?>
			<tr>
				<th>IUPAC név</th>
				<?php if (strlen($all_info['iupac']) > 35): ?>
				<td class="dropdown">
					<span><?=substr($all_info['iupac'], 0, 35)?>... </span>
					<?=button('more', ['iupac'])?>
					<div class="drop-right card" id="iupac"><?=$all_info['iupac']?></div>
				</td>
				<?php else: ?>
				<td><?=$all_info['iupac']?></td>
				<?php endif; ?>
			</tr>
			<?php endif; ?>
			<?php if (isset($all_info['chem_formula'])): ?>
			<tr>
				<th>Összegképlet</th>
				<td><?=$all_info['chem_formula']?></td>
			</tr>
			<?php endif; ?>
			<?php if (isset($all_info['cas'])): ?>
			<tr>
				<th>CAS</th>
				<td><?=$all_info['cas']?></td>
			</tr>
			<?php endif; ?>
			<?php if (isset($all_info['smiles'])): ?>
			<tr>
				<th>SMILES</th>
				<?php if (strlen($all_info['smiles']) > 30): ?>
						<td class="dropdown">
							<span><?=substr($all_info['smiles'], 0, 30)?>... </span>
							<?=button('more', ['smiles'])?>
							<div class="drop-right card" id="smiles"><?=$all_info['smiles']?></div>
						</td>
						<?php else: ?>
						<td><?=$all_info['smiles']?></td>
						<?php endif; ?>
			</tr>
			<?php endif; ?>
			<tr>
				<th>Kategória</th>
				<td><?=$all_info['category']?> >> <?=$all_info['subcategory']?></td>
			</tr>
			<?php if (isset($all_info['mol_weight'])): ?>
			<tr>
				<th>M<sub>w</sub> [g/mol]</th>
				<td><?=$all_info['mol_weight']?></td>
			</tr>
			<?php endif; ?>
			<?php if (isset($all_info['comp_melt'])): ?>
			<tr>
				<th>T<sub>olv</sub> [°C]</th>
				<td><?=$all_info['comp_melt']?></td>
			</tr>
			<?php endif; ?>
			<?php if (isset($all_info['oeb'])): ?>
			<tr>
				<th>OEB</th>
				<td><?=$all_info['oeb']?></td>
			</tr>
			<?php endif; ?>
			<?php if (isset($all_info['comp_note'])): ?>
			<tr>
				<th>Megjegyzés</th>
				<td><?=$all_info['comp_note']?></td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
<div class="float-left pad-m">
	<table class="comp_det">
		<caption>
			<span class="float-left" style="margin-top:.25em">Termék</span>
			<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 2): ?>
			<span class="float-right">
				<?=button('e_batch_search', [$all_info['batch_id'], $all_info['barcode']])?>
			</span>
			<?php endif; ?>
		</caption>
		<tbody>
			<tr>
				<th>Gyártó</th>
				<td><?=$all_info['manfac']?></td>
			</tr>
			<tr>
				<th>Termék</th>
				<td><?=$all_info['batch']?></td>
			</tr>
			<tr>
				<th>LOT#</th>
				<td><?=$all_info['lot']?></td>
			</tr>
			<?php if ($coa = search_file('coa', ($all_info['batch_id']))): ?>
			<tr>
				<th>CoA</th>
				<td><a target="_blank" rel="noopener noreferrer" href="<?=$coa?>">Coa <?=$all_info['batch']?> - <?=$all_info['lot']?></a></td>
			</tr>
			<?php endif; ?>
			<?php if ($msds = search_file('msds', $all_info['batch_id'])): ?>
			<tr>
				<th>MSDS</th>
				<td><a target="_blank" rel="noopener noreferrer" href="<?=$msds?>">MSDS <?=$all_info['batch']?></a></td>
			</tr>
			<?php endif; ?>
			<tr>
				<th>Érkezett</th>
				<td><?=$all_info['date_arr']?></td>
			</tr>
			<?php if (isset($all_info['date_open'])): ?>
			<tr>
				<th>Bontva</th>
				<td><?=$all_info['date_open']?></td>
			</tr>
			<?php endif;
			if (isset($all_info['date_exp'])) {

				echo '<tr><th>Lejár</th>';

				// DateTime objects
				$date_expire = date_create($all_info['date_exp']);
				$date_today = date_create(date('Y-m-d'));

				if ($date_expire <= $date_today) {
					echo '<td class="red bold">'.$all_info['date_exp'].'</td></tr>';
				} else {
					echo '<td>'.$all_info['date_exp'].'</td></tr>';
				}
			}
			if (isset($all_info['batch_note'])): ?>
				<tr>
					<th>Megjegyzés</th>
					<td><?=$all_info['batch_note']?></td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
<div class="float-left pad-m">
	<table class="comp_det">
		<caption>
			<span class="float-left" style="margin-top:.25em">Kiszerelés</span>
			<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 2): ?>
			<span class="float-right">
				<?=button('e_pack_search', [$all_info['pack_id'], $all_info['barcode']])?>
			</span>
			<?php endif; ?>
		</caption>
		</tbody>
			<tr>
				<th>Kiszerelés</th>
				<td><?=$all_info['size']?> (<?=($all_info['is_original'] == 1)?'eredeti':'kiszerelt'?>)</td>
			</tr>
				<?php if ($all_info['sub'] == NULL) {
					$location = $all_info['lab'].' > '.$all_info['place'];
				} else {
					$location = $all_info['lab'].' > '.$all_info['place'].' > '.$all_info['sub'];
				}?>
			<tr>
				<th>Helyzet</th>
				<td><?=$location?></td>
			</tr>
			<?php if (isset($all_info['weight'])): ?>
			<tr>
				<th><?=($all_info['is_original'] == 1) ? 'Bruttó tömeg [g]':'Tára [g]'?></th>
				<td><?=$all_info['weight']?></td>
			</tr>
			<?php endif; ?>
			<tr>
				<th>Vonalkód</th>
				<td><?=$all_info['barcode']?></td>
			</tr>
			<?php if (isset($all_info['pack_note'])): ?>
			<tr>
				<th>Megjegyzés</th>
				<td><?=$all_info['pack_note']?></td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
<?php if ($all_info['smiles']): ?>
<div class="float-left pad-m">
	<canvas id="structure"></canvas>
</div>
<?php endif; ?>
</div>