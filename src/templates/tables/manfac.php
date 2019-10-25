<nav>
	<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 3) {echo button('a_manfac');}?>
</nav>
<?php if ($result->num_rows > 0): ?>
<div class="pad-s of-auto">
	<table class="list">
		<thead>
			<tr>
				<th></th>
				<th>Gyártó</th>
				<th>Gyakori</th>
			</tr>
		</thead>
		<tbody>
		<?php while ($row = $result->fetch_assoc()): ?>
			<tr>
				<td>
				<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 3 ) {
				echo button('e_manfac', [$row['id']]);}?>
				</td>
				<td><?=$row['name']?></td>
				<td class="centered">
					<?php if ($row['freq'] == 1) {echo '<i class="far fa-check-circle"></i>';}?>
				</td>
			</tr>
			<tr>
			</tr>
		<?php endwhile; ?>
		</tbody>
	</table>
</div>
<?php else: ?>
<?=message('0', 'Nincsenek gyártók az adatbázisban')?>
<?php endif; ?>