<nav class="block">
	<span class="float-left">
		<button class="button category font-m <?=($cat) ? '':'selected'?>"
		<?=js('get', ['compound', 'index'])?>>Mind</button>
	</span>
<?php while ($row = $categories->fetch_assoc()): ?>
	<span class="float-left">
		<button class="button category font-m <?=($cat == $row['id']) ? 'selected':''?>"
		<?=js('get', ['compound&cat='.$row['id'], 'index'])?>>
			<?=$row['name']?>
		</button>
	</span>
<?php endwhile; ?>
<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 3): ?>
	<span class="float-right"><?=button('a_comp', ['index'])?></span>
<?php endif; ?>
<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 2): ?>
	<span class="float-right"><?=button('incoming')?></span>
<?php endif; ?>
</nav>