<?php // Authenticate user
require(ROOT.'/exec/auth.php')?>
<header class="block pad-s">
<div class="float-left">
	<a class="logo" href ="index.php"></a>
</div>
<div class="float-right">
	<div>
		<span><?=date('Y. M d.')?></span>
		<?php // User info
		switch ($_SESSION['USER_RIGHT_LELTAR']) {
			case 0:
				$level = 'Vendég';
				break;
			case 1:
				$level = 'Felhasználó';
				break;
			case 2:
				$level = 'Admin';
				break;
			case 3:
				$level = 'System Manager';
				break;
			default:
				$level = 'HACK';
				break;
		}?>
		<span><i class="fas fa-user"></i><?=$_SESSION['USER_NAME']?> (<?=$level?>)</span>
		<button class="button logout fa-lg" <?=js_spec('logout')?>><i class="fas fa-sign-out-alt"></i></button>
	</div>
	<div>
		<form class="search" action="exec/search.php" <?=js_spec('search')?> method="get" autocomplete="off" >
			<span class="float-right dropdown">
				<input type="text" id="q" <?=js_spec('live_search')?> autofocus required placeholder="Keresés.."/>
				<button type="submit" class="button submit fa-lg" value="Keresés" ><i class="fas fa-search"></i></button>
				
			</span>
			<div id="live_search"></div>
		</form>
	</div>
</div>
</header>