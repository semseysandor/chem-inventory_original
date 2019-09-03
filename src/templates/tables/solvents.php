<?php if ($result->num_rows > 0): ?>
<div class="pad-s of-auto">
	<table class="list">
		<thead>
			<tr>
				<th>Anyag</th>
				<th colspan="2">Kiszerelés</th>
				<th>Darab</th>
				<th>Összesen</th>
				<th colspan="4"></th>
			</tr>
		</thead>
		<tbody>
		<?php while ($row = $result->fetch_assoc()): ?>
			<tr>
				<td class="bold"><?=$row['name']?></td>
				<td><?=$row['type']?></td>
				<td class="right"><?=$row['volume'].' L'?></td>
				<td class="centered bold"><?=$row['unit']?></td>
				<td class="centered"><?=($row['volume'] * $row['unit']) .' L'?></td>
				<?php if ($row['unit'] >= 4): ?>
					<td><?=button('s_reduce', [4, $row['solvent_id'], $row['unit']-4])?></td>
				<?php else: ?>
					<td></td>
				<?php endif; ?>
				<?php if ($row['unit'] >= 1): ?>
					<td><?=button('s_reduce', [1, $row['solvent_id'], $row['unit']-1])?></td>
				<?php else: ?>
					<td></td>
				<?php endif; ?>
					<td><?=button('s_add', [1, $row['solvent_id'], $row['unit']+1])?></td>
					<td><?=button('s_add', [4, $row['solvent_id'], $row['unit']+4])?></td>
				</td>
			</tr>
			<tr>
			</tr>
		<?php endwhile; ?>
		</tbody>
	</table>
</div>
<?php else: ?>
<?=message('0', 'Nincsenek oldószerek a leltárban')?>
<?php endif; ?>