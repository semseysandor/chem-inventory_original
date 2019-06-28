<div class="unit">
	<nav class="block">
		<?php if (isset($comp_id) and $historic): ?>
			<span><?=button('batch_act', [$comp_id])?></span>
		<?php elseif (isset($comp_id) and !$historic): ?>
			<span><?=button('batch_hist', [$comp_id])?></span>
		<?php endif; ?>
		<?php if (isset($comp_id) and $_SESSION['USER_RIGHT_LELTAR'] >= 3 and !$historic): ?>
			<span class="float-right"><?=button('a_batch', [$comp_id, 'index'])?></span>
		<?php endif; ?>
	</nav>
	<?php if($result->num_rows > 0): ?>
	<table class="list">
		<thead>
			<tr>
				<?=(!$historic) ? '<th></th>':''?>
				<th></th>
				<th>Gyártó</th>
				<th>Név</th>
				<th>LOT</th>
				<th>Érkezett</th>
				<th>Bontva</th>
				<th>Lejár</th>
				<?=($historic) ? '<th>Archiválva</th>':''?>
				<th colspan="2">CoA</th>
				<th colspan="2">MSDS</th>
				<th>Megjegyzés</th>
			</tr>
		</thead>
		<tbody>
		<?php while ($row = $result->fetch_assoc()): ?>
			<tr class="cursor-pointer">
				<td><?=change_log($row['id'], 'leltar_batch')?></td>
				<?php if (!$historic) {
					if ($_SESSION['USER_RIGHT_LELTAR'] >= 2) {
						echo '<td>'.button('e_batch', [$row['id'], 'index']).'</td>';
					} else {
						echo '<td></td>';
					}
				}?>
				<td <?=js('index_drop', ['pack&bid='.$row['id'], 'pack_'.$row['id']])?>>
					<?=$row['manfac_name']?>
				</td>
				<td <?=js('index_drop', ['pack&bid='.$row['id'], 'pack_'.$row['id']])?>>
					<?=$row['name']?>
				</td>
				<td <?=js('index_drop', ['pack&bid='.$row['id'], 'pack_'.$row['id']])?>>
					<?=$row['lot']?>
				</td>
				<td <?=js('index_drop', ['pack&bid='.$row['id'], 'pack_'.$row['id']])?>>
					<?=$row['date_arr']?>
				</td>
				<td <?=js('index_drop', ['pack&bid='.$row['id'], 'pack_'.$row['id']])?>>
					<?=$row['date_open']?>
				</td>
				<?php if (isset($row['date_exp'])): ?>

					<?php // DateTime objects
					$date_expire = date_create($row['date_exp']);
					$date_today = date_create(date('Y-m-d'));?>

					<?php if ($date_expire <= $date_today): ?>
						<td class="red bold" <?=js('index_drop', ['pack&bid='.$row['id'], 'pack_'.$row['id']])?>>
							<?=$row['date_exp']?>
						</td>
					<?php else: ?>
						<td <?=js('index_drop', ['pack&bid='.$row['id'], 'pack_'.$row['id']])?>>
							<?=$row['date_exp']?>
						</td>
					<?php endif; ?>
				<?php else: ?>
					<td <?=js('index_drop', ['pack&bid='.$row['id'], 'pack_'.$row['id']])?>></td>
				<?php endif; ?>

				<?php if ($historic): ?>
					<td  <?=js('index_drop', ['pack&bid='.$row['id'], 'pack_'.$row['id']])?>>
						<?=$row['date_arch']?>
					</td>
				<?php endif; ?>

				<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 1): ?>
					<td> <?=button('up_coa', [$row['id']])?></td>
				<?php else: ?>
					<td></td>
				<?php endif; ?>

				<?php if ($coa = search_file('coa', $row['id'])): ?>
					<td class="left"> <?=button('file', [$coa])?></td>
				<?php else: ?>
					<td <?=js('index_drop', ['pack&bid='.$row['id'], 'pack_'.$row['id']])?>></td>
				<?php endif; ?>

				<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 1): ?>
					<td> <?=button('up_msds', [$row['id']])?></td>
				<?php else: ?>
					<td></td>
				<?php endif; ?>

				<?php if ($msds = search_file('msds', $row['id'])): ?>
					<td> <?=button('file', [$msds])?></td>
				<?php else: ?>
					<td  <?=js('index_drop', ['pack&bid='.$row['id'], 'pack_'.$row['id']])?>></td>
				<?php endif; ?>
				<td <?=js('index_drop', ['pack&bid='.$row['id'], 'pack_'.$row['id']])?>>
					<?=$row['note']?>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding:0; border:none"></td>
				<td colspan="11" style="padding:0; border:none">
					<div id="pack_<?=$row['id']?>" class="no-show"></div>
				</td>
			</tr>
		<?php endwhile; ?>
		</tbody>
	</table>
	<?php else: ?>
	<?=message('0', (($historic) ? 'Nincsenek histórikus termékek' : 'Nincsenek termékek'))?>
	<?php endif; ?>
</div>
