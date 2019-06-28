<section id="compound_list">
	<nav class="block">
	<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 1): ?>
		<span class="float-right"><?=button('a_comp', ['incoming'])?></span>
	<?php endif; ?>
	</nav>
	<div class="pad-s block">
		<table class="list float-left">
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
				<td <?=js_spec('inc_select_comp', [$row['id'], $row['name']])?>><?=$row['name']?>
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
</section>
<h3 id="h3_selected_compound"></h3>
<section id="batch_list"></section>
<h3 id="h3_selected_batch"></h3>
<section id="section_pack_list"></section>