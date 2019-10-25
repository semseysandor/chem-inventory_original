<?php if ($result->num_rows > 0): ?>
<div class="float-left pad-m of-auto">
<h3>Engedélyezett lokációk</h3>
	<table class="list">
		<thead>
			<tr>
				<th></th>
				<th>Labor</th>
				<th>Hely</th>
				<th>Alhely</th>
			</tr>
		</thead>
		<tbody>
		<?php while ($row = $result->fetch_assoc()): ?>
			<tr>
				<td>
					<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 3 ) {
					echo button('e_loc', [$row['id']]);}?>
				</td>
				<td><?=$row['lab_name']?></td>
				<td><?=$row['place_name']?></td>
				<td><?=$row['sub_name']?></td>
			</tr>
			<tr></tr>
		<?php endwhile; ?>
		</tbody>
	</table>
</div>
<?php else: ?>
<?=message('0', 'Nincsenek engedélyezett lokációk az adatbázisban')?>
<?php endif; ?>