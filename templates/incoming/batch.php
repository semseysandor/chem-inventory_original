<nav class="block">
<?php if (isset($comp_id) and $_SESSION['USER_RIGHT_LELTAR'] >= 2): ?>
	<span class="float-left font-m"><?=button('a_batch', [$comp_id, 'incoming'])?></span>
<?php endif; ?>
</nav>
<?php if($result->num_rows > 0): ?>
<div class="pad-s of-auto">
	<table class="list">
		<thead>
			<tr>
				<th></th>
				<th>Gyártó</th>
				<th>Név</th>
				<th>LOT</th>
				<th>Érkezett</th>
				<th>Bontva</th>
				<th>Lejár</th>
				<th>Megjegyzés</th>
			</tr>
		</thead>
		<tbody>
		<?php while ($row = $result->fetch_assoc()): ?>
			<tr class="cursor-pointer">
				<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 2) {
						echo '<td>'.button('e_batch', [$row['id'], 'incoming']).'</td>';
					} else {
						echo '<td></td>';
					}?>
				<td>
					<?=$row['manfac_name']?>
				</td>
				<td>
					<?=$row['name']?>
				</td>
				<td>
					<?=$row['lot']?>
				</td>
				<td>
					<?=$row['date_arr']?>
				</td>
				<td>
					<?=$row['date_open']?>
				</td>
				<?php if (isset($row['date_exp'])): ?>

					<?php // DateTime objects
					$date_expire = date_create($row['date_exp']);
					$date_today = date_create(date('Y-m-d'));?>

					<?php if ($date_expire <= $date_today): ?>
						<td class="red bold" >
							<?=$row['date_exp']?>
						</td>
					<?php else: ?>
						<td >
							<?=$row['date_exp']?>
						</td>
					<?php endif; ?>
				<?php else: ?>
					<td ></td>
				<?php endif; ?>

				<td>
					<?=$row['note']?>
				</td>
			</tr>
			<tr></tr>
		<?php endwhile; ?>
		</tbody>
	</table>
</div>
<?php else: ?>
<?=message('0', 'Nincsenek termékek')?>
<?php endif; ?>