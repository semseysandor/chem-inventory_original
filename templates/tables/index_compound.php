<div class="block">
<div class="card float-left">
	<table class="list">
	<thead>
		<tr>
			<th></th>
			<th></th>
			<th></th>
			<th>Név</th>
			<th>Egyéb név</th>
			<th>Rövídítés</th>
			<th>Megjegyzés</th>
		</tr>
	</thead>
<?php if ($result->num_rows > 0): ?>
	<tbody>
	<?php while ($row = $result->fetch_assoc()): ?>
		<tr class="cursor-pointer">
			<td><?=button('compound', [$row['id']])?></td>
			<td><?=change_log($row['id'], 'leltar_compound')?></td>
			<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 0 ): ?>
			<td><?=button('e_comp', [$row['id']])?></td>
			<?php else: ?>
			<td></td>
			<?php endif; ?>
			<td <?=js('index_drop', ['batch&cid='.$row['id'], 'batch_'.$row['id']])?>>
				<?=$row['name']?>
			</td>
			<td <?=js('index_drop', ['batch&cid='.$row['id'], 'batch_'.$row['id']])?>>
				<?=$row['name_alt']?>
			</td>
			<td <?=js('index_drop', ['batch&cid='.$row['id'], 'batch_'.$row['id']])?>>
				<?=$row['abbrev']?>
			</td>
			<td <?=js('index_drop', ['batch&cid='.$row['id'], 'batch_'.$row['id']])?>>
				<?=$row['note']?>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding:0; border:none"></td>
			<td colspan="5" style="padding:0; border:none">
				<div id="batch_<?=$row['id']?>" class="no-show"></div>
			</td>
		</tr>
	<?php endwhile; ?>
	</tbody>
	</table>
<?php else: ?>
	</table>
	<?=message('0', 'Nincs ilyen vegyszer')?>
<?php endif; ?>
</div>
</div>