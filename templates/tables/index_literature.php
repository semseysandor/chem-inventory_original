	<table class="list">
		<thead>
			<tr>
				<th></th>
				<th>Cím</th>
				<th>Szerző</th>
				<th>Év</th>
				<th>Folyóirat</th>
				<th>Megjegyzés</th>
				<th colspan="2">Fájl</th>
			</tr>
		</thead>
	<?php if ($result->num_rows >= 1) : ?>
		<tbody>
		<?php while ($row = $result->fetch_assoc()): ?>
			<?php if ($literature = search_file('literature', $row['id'])): ?>
			<tr>
				<?php if ($_SESSION['USER_RIGHT_API'] >= 2 ): ?>
					<td><?=button('e_liter', [$row['id']])?></td>
				<?php else: ?>
					<td></td>
				<?php endif; ?>
				<td><?=$row['title']?></td>
				<td><?=$row['author']?></td>
				<td><?=$row['year']?></td>
				<td><?=$row['journal']?></td>
				<td><?=$row['note']?></td>
				<td class="centered"><?=button('file', [$literature])?></td>
				<?php if ($_SESSION['USER_RIGHT_API'] >= 2): ?>
					<td class="cursor-pointer"><?=button('up_liter', [$row['id']])?></td>
				<?php else: ?>
					<td></td>
				<?php endif; ?>
			</tr>
			<?php endif; ?>
		<?php endwhile; ?>
		</tbody>
	</table>
	<?php else: ?>
	</table>
	<?=message('0', 'Nincs irodalom')?>
	<?php endif; ?>
</div>