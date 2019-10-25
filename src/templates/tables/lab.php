<?php if ($result->num_rows > 0): ?>
<div class="float-left pad-m of-auto">
<h3>Laborok</h3>
	<table class="list">
		<thead>
			<tr>
				<th></th>
				<th>Név</th>
			</tr>
		</thead>
		<tbody>
		<?php while ($row = $result->fetch_assoc()): ?>
			<tr>
				<td>
					<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 3 ) {
					echo button('e_lab', [$row['id']]);}?>
				</td>
				<td><?=$row['name']?></td>
			</tr>
			<tr></tr>
		<?php endwhile; ?>
		</tbody>
	</table>
</div>
<?php else: ?>
<?=message('0', 'Nincsenek laborok az adatbázisban')?>
<?php endif; ?>