<nav class="block">
<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 1): ?>
	<span class="float-right"><?=button('a_comp', ['incoming'])?></span>
<?php endif; ?>
</nav>
<div class="pad-s float-left">
	<table class="list">
	<thead>
		<tr>
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
			<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 1 ): ?>
			<td><?=button('e_comp', [$row['id'], 'incoming'])?></td>
			<?php else: ?>
			<td></td>
			<?php endif; ?>
			<td><?=$row['name']?>
			</td>
			<td>
				<?=$row['name_alt']?>
			</td>
			<td>
				<?=$row['abbrev']?>
			</td>
			<td>
				<?=$row['note']?>
			</td>
		</tr>
		<tr></tr>
	<?php endwhile; ?>
	</tbody>
	</table>
<?php else: ?>
	</table>
	<?=message('0', 'Nincs ilyen vegyszer')?>
<?php endif; ?>
</div>