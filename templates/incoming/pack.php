<nav class="block">
<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 2): ?>
	<span class="float-left font-m"><?=button('a_pack', [$batch_id])?></span>
<?php endif; ?>
</nav>
<?php if ($result->num_rows > 0): ?>
<div class="pad-s of-auto">
	<table class="list">
		<thead>
			<tr>
				<th></th>
				<th colspan="2">Kiszerelés</th>
				<th>Tömeg</th>
				<th>Helyzet</th>
				<th>Megjegyzés</th>
				<th>Vonalkód</th>
			</tr>
		</thead>
		<tbody>
		<?php while ($row = $result->fetch_assoc()): ?>
			<tr class="cursor-def">
				<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 2): ?>
						<td><?=button('e_pack', [$row['pack_id'], 'incoming'])?></td>
					<?php else: ?>
						<td></td>
					<?php endif; ?>
				<td class="right"><?=$row['size']?></td>
				<td>(<?=($row['is_original'] == 1) ? 'eredeti' : 'kiszer.'?>)</td>
				<?php
				if (isset($row['weight'])) {
					if ($row['is_original'] == 1) {
						echo '<td class="right">Bruttó: '.$row['weight'].' g</td>';
					} else {
						echo '<td class="right">Tára: '.$row['weight'].' g</td>';
					}
				} else {
					echo '<td></td>';
				} ?>
				<?php if ($row['sub_name'] == NULL) {
					$location = $row['lab_name'].' > '.$row['place_name'];
				} else {
					$location = $row['lab_name'].' > '.$row['place_name'].' > '.$row['sub_name'];
				}?>
				<td><?=$location?></td>
				<td><?=$row['note']?></td>
				<td <?=js_spec('barcode', [$row['barcode']])?>>
					<button class="barcode"><?=$row['barcode']?></button>
				</td>
			</tr>
			<tr></tr>
		<?php endwhile; ?>
		</tbody>
	</table>
</div>
<?php else: ?>
<?=message('0', 'Nincsenek kiszerelések')?>
<?php endif; ?>