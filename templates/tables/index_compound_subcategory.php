<nav class="block">
<?php while ($row = $subcategories->fetch_assoc()): ?>
<span>
<button class="button category font-m <?=($subcat == $row['id']) ? 'selected':''?>"
<?=js('get',['compound&cat='.$cat.'&subcat='.$row['id'], 'index'])?>>
	<?=$row['name']?>
</button>
</span>
<?php endwhile; ?>
</nav>