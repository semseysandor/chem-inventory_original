<div class="card">
	<nav class="block">
	<?php if ($_SESSION['USER_RIGHT_API'] >= 2): ?>
		<span class="float-right"><?=button('a_pk', [$drug_id])?></span>
	<?php endif; ?>
	</nav>
	<?php if ($result->num_rows >= 1): ?>
	<table class="list">
		<thead>
			<tr>
				<th></th>
				<th>PK tulajdonság</th>
				<th>Érték</th>
				<th>Megjegyzés</th>
			</tr>
		</thead>
		<tbody>
		<?php while ($row = $result->fetch_assoc()): ?>
			<tr>
				<?php if ($_SESSION['USER_RIGHT_API'] >= 2 ): ?>
					<td><?=button('e_pk', [$row['pk_data_id']])?></td>
				<?php else: ?>
					<td></td>
				<?php endif; ?>
				<td><?=$row['name']?></td>
				<td><?=$row['value']?></td>
				<td><?=$row['note']?></td>
			</tr>
			<tr>
			</tr>
		<?php endwhile; ?>
		</tbody>
	</table>
	<?php else: ?>
	<?=message('0', 'Nincsenek PK adatok')?>
	<?php endif; ?>
</div>