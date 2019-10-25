<div class="unit">
	<?php if ($result->num_rows > 0): ?>
	<?php $counter = 1;?>
	<nav>
		<button class="button delete font-l" <?=js_spec('invent_delete_missing')?>>Összes hiányzó kiszerelés törlése az adatbázisból</button>
	<nav>
	<table class="list">
		<thead>
			<tr>
				<th></th>
				<th></th>
				<th>Vegyszer</th>
				<th>Termék</th>
				<th>LOT#</th>
				<th>Érkezett</th>
				<th>Kiszerelés</th>
				<th>Helyzet</th>
				<th>Megjegyzés</th>
				<th>Vonalkód</th>
			</tr>
		</thead>
		<tbody>
		<?php while ($row = $result->fetch_assoc()): ?>
			<tr class="cursor-def centered">
				<td><?=button('invent_delete', [$row['pack_id']])?></td>
				<td><?=$counter++?></td>
				<td><?=$row['comp']?></td>
				<td><?=$row['batch']?></td>
				<td><?=$row['lot']?></td>
				<td><?=$row['date_arr']?></td>
				<td><?=$row['size']?></td>
				<td><?=$row['lab'].' > '.$row['place'].($row['sub'] ? ' > ':'').$row['sub']?></td>
				<td><?=$row['pack_note']?></td>
				<td <?=js_spec('barcode', [$row['barcode']])?>>
					<button class="barcode"><?=$row['barcode']?></button>
				</td>
			</tr>
			<tr>
			</tr>
		<?php endwhile; ?>
		</tbody>
	</table>
	<?php else: ?>
	<?=message('0', 'Nincsenek hiányzó kiszerelések')?>
	<?php endif; ?>
</div>