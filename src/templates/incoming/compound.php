<section id="compound_list">
	<nav class="block">
		<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 1): ?>
			<span class="float-left"><?=button('a_comp', ['incoming'])?></span>
		<?php endif; ?>
	</nav>
	<?php if ($result->num_rows > 0): ?>
	<div class="pad-s of-auto">
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
		<tbody>
		<?php while ($row = $result->fetch_assoc()): ?>
			<tr class="cursor-pointer">
				<td><?=button('compound', [$row['id']])?></td>
				<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 1 ): ?>
				<td><?=button('e_comp', [$row['id'], 'incoming'])?></td>
				<?php else: ?>
				<td></td>
				<?php endif; ?>
				<td <?=js_spec('inc_select_comp', [$row['id'], $row['name']])?>>
					<?=$row['name']?>
				</td>
				<td <?=js_spec('inc_select_comp', [$row['id'], $row['name']])?>>
					<?=$row['name_alt']?>
				</td>
				<td <?=js_spec('inc_select_comp', [$row['id'], $row['name']])?>>
					<?=$row['abbrev']?>
				</td>
				<td <?=js_spec('inc_select_comp', [$row['id'], $row['name']])?>>
					<?=$row['note']?>
				</td>
			</tr>
			<tr></tr>
		<?php endwhile; ?>
		</tbody>
		</table>
		</div>
	<?php else: ?>
		<?=message('0', 'Nincs ilyen vegyszer')?>
	<?php endif; ?>
</section>
<h3 id="comp_select"></h3>
<section id="batch_list"></section>
<h3 id="batch_select"></h3>
<section id="pack_list"></section>