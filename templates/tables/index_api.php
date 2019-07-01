<div class="block">
<?php if ($_SESSION['USER_RIGHT_API'] >= 2 and !isset($apis_found)): ?>
	<span class="float-right"><?=button('a_api')?></span>
<?php endif; ?>
</div>
<?php if ($result->num_rows > 0): ?>
<div class="pad-s of-auto">
	<table class="list">
	<thead>
		<tr>
			<th></th>
			<th>Név</th>
			<th>Forma</th>
			<th>Értékelés állapota</th>
			<th>Fejlesztési fázis</th>
			<th>Piaci helyzet</th>
			<th>Szabadalom lejár</th>
			<th>Els. indikáció</th>
			<th>Más. indikáció</th>
			<th>T<sub>olv</sub> [°C]</th>
			<th>Víz old. [ug/ml]</th>
			<th>HCl old. [mg/ml]</th>
			<th>Megjegyzés</th>
		</tr>
	</thead>
	<tbody>
	<?php while ($row = $result->fetch_assoc()): ?>
		<tr class="cursor-pointer">
			<?php if ($_SESSION['USER_RIGHT_API'] >= 2 ): ?>
				<td><?=button('e_api', [$row['id']])?></td>
			<?php else: ?>
				<td></td>
			<?php endif; ?>
			<td <?=js('index_drop', ['drug&aid='.$row['id'], 'drug_'.$row['id']])?>>
				<?=$row['name']?>
			</td>
			<td <?=js('index_drop', ['drug&aid='.$row['id'], 'drug_'.$row['id']])?>>
				<?=$row['form']?>
			</td>
			<td <?=js('index_drop', ['drug&aid='.$row['id'], 'drug_'.$row['id']])?>>
				<?=$row['eval']?>
			</td>
			<td <?=js('index_drop', ['drug&aid='.$row['id'], 'drug_'.$row['id']])?>>
				<?=$row['dev']?>
			</td>
			<td <?=js('index_drop', ['drug&aid='.$row['id'], 'drug_'.$row['id']])?>>
				<?=$row['market']?>
			</td>
			<td <?=js('index_drop', ['drug&aid='.$row['id'], 'drug_'.$row['id']])?>>
				<?=$row['patent']?>
			</td>
			<td <?=js('index_drop', ['drug&aid='.$row['id'], 'drug_'.$row['id']])?>>
				<?=$row['pri']?>
			</td>
			<td <?=js('index_drop', ['drug&aid='.$row['id'], 'drug_'.$row['id']])?>>
				<?=$row['sec']?>
			</td>
			<td <?=js('index_drop', ['drug&aid='.$row['id'], 'drug_'.$row['id']])?>>
				<?=$row['melt']?>
			</td>
			<td <?=js('index_drop', ['drug&aid='.$row['id'], 'drug_'.$row['id']])?>>
				<?=$row['water']?>
			</td>
			<td <?=js('index_drop', ['drug&aid='.$row['id'], 'drug_'.$row['id']])?>>
				<?=$row['hcl']?>
			</td>
			<td <?=js('index_drop', ['drug&aid='.$row['id'], 'drug_'.$row['id']])?>>
				<?=$row['note']?>
			</td>
		</tr>
		<tr>
			<td style="padding:0; border:none"></td>
			<td colspan="7" style="padding:0; border:none">
				<div id="drug_<?=$row['id']?>" class="no-show"></div>
			</td>
		</tr>
	<?php endwhile; ?>
	</tbody>
</table>
</div>
<?php else: ?>
<?=message('0', 'Nincsenek API-k az adatbázisban')?>
<?php endif; ?>