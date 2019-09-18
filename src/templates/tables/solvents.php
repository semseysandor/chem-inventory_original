<?php if ($result->num_rows > 0): ?>
<div class="pad-s of-auto">
	<table class="list">
		<thead>
			<tr>
				<th>Anyag</th>
				<th>Darab</th>
				<th>Összesen</th>
				<?php if ($_SESSION['USER_RIGHT_SOLVENT'] >=2): ?>
					<th colspan="3"></th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
		<?php while ($row = $result->fetch_assoc()): ?>
			<tr>
				<td>
					<strong><?=$row['name']?></strong><br/>
					<?=$row['type'] . ' - ' . $row['volume'].' L'?><br/>
				</td>
				<td class="centered bold font-l"><?=$row['unit']?></td>
				<td class="centered"><?=($row['volume'] * $row['unit']) .' L'?></td>
				<?php if ($_SESSION['USER_RIGHT_SOLVENT'] >=2): ?>
					<?php if ($row['unit'] >= 1): ?>
						<td><?=button('s_reduce', [1, $row['solvent_id'], $row['unit']-1])?></td>
					<?php else: ?>
						<td></td>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ($_SESSION['USER_RIGHT_SOLVENT'] >=2): ?>
					<td><?=button('s_add', [1, $row['solvent_id'], $row['unit']+1])?></td>
				<?php endif; ?>
				<?php if ($_SESSION['USER_RIGHT_SOLVENT'] >=2): ?>
					<td><?=button('s_add', [4, $row['solvent_id'], $row['unit']+4])?></td>
				<?php endif; ?>
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