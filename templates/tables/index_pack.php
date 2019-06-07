<div class="unit">
	<nav class="block">
	<?php if ($historic): ?>
		<span><?=button('pack_act', [$batch_id])?></span>
	<?php else: ?>
		<span><?=button('pack_hist', [$batch_id])?></span>
	<?php endif; ?>
	<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 2 and !$historic): ?>
		<span class="float-right"><?=button('a_pack', [$batch_id])?></span>
	<?php endif; ?>
	</nav>
	<table class="list">
		<thead>
			<tr>
				<?=(!$historic) ? '<th></th>':''?>
				<th></th>
				<th colspan="2">Kiszerelés</th>
				<th>Tömeg</th>
				<th>Helyzet</th>
				<th>Megjegyzés</th>
				<th>Vonalkód</th>
			</tr>
		</thead>
	<?php if ($result->num_rows > 0): ?>
		<tbody>
		<?php while ($row = $result->fetch_assoc()): ?>
			<tr class="cursor-def">
				<td><?php change_log($row['pack_id'], 'leltar_pack');?></td>
				<?php if (!$historic) {
					if ($_SESSION['USER_RIGHT_LELTAR'] >= 2) {
						echo '<td>'.button('e_pack', [$row['pack_id']]).'</td>';
					} else {
						echo '<td></td>';
					}
				}?>
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
				<td class="cursor-pointer" <?=js_spec('barcode', [$row['barcode']])?>><?=$row['barcode']?></td>
			</tr>
			<tr>
			</tr>
		<?php endwhile; ?>
		</tbody>
	</table>
	<?php else: ?>
	</table>
	<?=message('0', (($historic) ? 'Nincsenek histórikus kiszerelések' : 'Nincsenek kiszerelések'))?>
	<?php endif; ?>
</div>