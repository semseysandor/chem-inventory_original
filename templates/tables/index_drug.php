<div class="card">
	<nav class="block">
	<?php if ($_SESSION['USER_RIGHT_API'] >= 2): ?>
		<span class="float-right"><?=button('a_liter', [$api_id])?></span>
		<span class="float-right"><?=button('a_drug', [$api_id])?></span>
	<?php endif; ?>
	</nav>
	<table class="list">
		<thead>
			<tr>
				<th></th>
				<th>Név</th>
				<th>Egyéb név</th>
				<th>Kiszerelés</th>
				<th>Kristályosság</th>
				<th>Részecske méret</th>
				<th>Dózis egység<br/>(szabad formára)</th>
				<th>Dózis egység<br/>naponta</th>
				<th>Bevétel</th>
				<th>Megjegyzés</th>
			</tr>
		</thead>
	<?php if ($result->num_rows >= 1) : ?>
		<tbody>
		<?php while ($row = $result->fetch_assoc()): ?>
			<tr class="cursor-pointer">
				<?php if ($_SESSION['USER_RIGHT_API'] >= 2 ): ?>
					<td><?=button('e_drug', [$row['id']])?></td>
				<?php else: ?>
					<td></td>
				<?php endif; ?>
				<td <?=js('index_drop', ['pk&did='.$row['id'], 'pk_'.$row['id']])?>>
					<?=$row['name']?>
				</td>
				<td <?=js('index_drop', ['pk&did='.$row['id'], 'pk_'.$row['id']])?>>
					<?=$row['name_alt']?>
				</td>
				<td <?=js('index_drop', ['pk&did='.$row['id'], 'pk_'.$row['id']])?>>
					<?=$row['dosage']?>
				</td>
				<td <?=js('index_drop', ['pk&did='.$row['id'], 'pk_'.$row['id']])?>>
					<?=$row['crystall']?>
				</td>
				<td <?=js('index_drop', ['pk&did='.$row['id'], 'pk_'.$row['id']])?>>
					<?=$row['particle']?>
				</td>
				<td <?=js('index_drop', ['pk&did='.$row['id'], 'pk_'.$row['id']])?>>
					<?=$row['dose_free']?>
				</td>
				<td <?=js('index_drop', ['pk&did='.$row['id'], 'pk_'.$row['id']])?>>
					<?=$row['dose_day']?>
				</td>
				<td <?=js('index_drop', ['pk&did='.$row['id'], 'pk_'.$row['id']])?>>
					<?=$row['admin']?>
				</td>
				<td <?=js('index_drop', ['pk&did='.$row['id'], 'pk_'.$row['id']])?>>
					<?=$row['note']?>
				</td>
			</tr>
			<tr>
				<td style="padding:0; border:none"></td>
				<td colspan="9" style="padding:0; border:none">
					<div id="pk_<?=$row['id']?>" class="no-show"></div>
				</td>
			</tr>
		<?php endwhile; ?>
		</tbody>
	</table>
	<?php else: ?>
	</table>
	<?=message('0', 'Nincsenek gyógyszerek')?>
	<?php endif; ?>