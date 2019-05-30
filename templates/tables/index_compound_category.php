<nav class="block">
	<span>
		<button class="button category font-m <?=($cat) ? '':'selected'?>"
		<?=js('get', ['compound', 'index'])?>>Mind</button>
	</span>
<?php while ($row = $categories->fetch_assoc()): ?>
	<span>
		<button class="button category font-m <?=($cat == $row['id']) ? 'selected':''?>"
		<?=js('get', ['compound&cat='.$row['id'], 'index'])?>>
			<?=$row['name']?>
		</button>
	</span>
<?php endwhile; ?>
<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 1): ?>
	<span class="float-right"><?=button('a_comp')?></span>
<?php endif; ?>
</nav>