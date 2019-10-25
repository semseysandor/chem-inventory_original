<nav class="block">
<?php if ($_SESSION['USER_RIGHT_LELTAR'] >= 3): ?>
	<span class="float-left"><?=button('a_lab')?></span>
	<span class="float-left"><?=button('a_place')?></span>
	<span class="float-left"><?=button('a_sub')?></span>
	<span class="float-left"><?=button('a_loc')?></span>
<?php endif; ?>
</nav>