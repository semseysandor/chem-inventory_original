<div class="card">
	<div class="block">
		<span class="float-left"><h3>Vegyszeradatlap</h3></span>
		<span class="float-right"><?=button('erase_popup')?></span>
	</div>
	<div class="block">
	<div class="float-left pad-m">
		<table class="comp_det">
			<caption>Azonosítás</caption>
			<tbody>
				<tr>
					<th>Név</th>
					<td><?=$row['name']?></td>
				</tr>
				<tr>
					<th>Egyéb név</th>
					<td><?=($row['name_alt']) ? $row['name_alt']:'---'?></td>
				</tr>
				<tr>
					<th>Rövídítés</th>
					<td><?=($row['abbrev']) ? $row['abbrev']:'---'?></td>
				</tr>
				<tr>
					<th>Kémiai név</th>
					<td><?=($row['chem_name']) ? $row['chem_name']:'---'?></td>
				</tr>
				<tr>
					<th>IUPAC név</th>
					<?php if (isset($row['iupac'])): ?>
						<?php if (strlen($row['iupac']) > 35): ?>
						<td class="dropdown">
							<span><?=substr($row['iupac'], 0, 35)?>... </span>
							<?=button('more', ['iupac'])?>
							<div class="drop-right card" id="iupac"><?=$row['iupac']?></div>
						</td>
						<?php else: ?>
						<td><?=$row['iupac']?></td>
						<?php endif; ?>
					<?php else: ?>
					<td>---</td>
					<?php endif; ?>
				</tr>
				<tr>
					<th>Összegképlet</th>
					<td><?=($row['chem_form']) ? $row['chem_form']:'---'?></td>
				</tr>
				<tr>
					<th>CAS</th>
					<td><?=($row['cas']) ? $row['cas']:'---'?></td>
				</tr>
				<tr>
					<th>SMILES</th>
					<?php if (isset($row['smiles'])): ?>
						<?php if (strlen($row['smiles']) > 30): ?>
						<td class="dropdown">
							<span><?=substr($row['smiles'], 0, 30)?>... </span>
							<?=button('more', ['smiles'])?>
							<div class="drop-right card" id="smiles"><?=$row['smiles']?></div>
						</td>
						<?php else: ?>
						<td><?=$row['smiles']?></td>
						<?php endif; ?>
					<?php else: ?>
					<td>---</td>
					<?php endif; ?>
				</tr>
				<tr>
					<th>Kategória</th>
					<td><?=$row['cat_name']?> >> <?=$row['sub_cat_name']?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="float-left pad-m">
		<table class="comp_det">
			<caption>Fizikai-kémiai adatok</caption>
			<tbody>
				<tr>
					<th>M<sub>w</sub> [g/mol]</th>
					<td><?=($row['mol_weight']) ? $row['mol_weight']:'---'?></td>
				</tr>
				<tr>
					<th>T<sub>olv</sub> [°C]</th>
					<td><?=($row['melt']) ? $row['melt']:'---'?></td>
				</tr>
			</tbody>
		</table>
		<table class="comp_det" style="margin-top:1em">
			<caption>Információ</caption>
			<tbody>
				<tr>
					<th>OEB</th>
					<td><?=($row['oeb']) ? $row['oeb']:'---'?></td>
				</tr>
				<tr>
					<th>Megjegyzés</th>
					<td><?=($row['note']) ? $row['note']:'---'?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php // Draw structure from SMILES
	if ($row['smiles']): ?>
		<div class="float-left pad-m">
			<canvas id="structure"></canvas>
		</div>
	<?php endif; ?>
	</div>
</div>