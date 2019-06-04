<?php
// Authenticate user
require(ROOT.'/exec/auth.php');

// User info
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
<header class="block pad-s">
<div class="float-left col-4 col-s-4">
	<a href ="index.php">
		<img src="icons/logo_nangenex.png" alt="NGX" class="rwd" width="252" height="63" />
	</a>
</div>
<div class="col-8 col-s-8">
	<div class="float-right">
		<div class="block">
			<div class="rwd float-left"><?=date('Y. M d.')?>
			<i class="fas fa-user"></i> <?=$_SESSION['USER_NAME']?> (<?=$level?>)
			</div>
			<div class="rwd float-right">
				<button class="button logout fa-lg" <?=js_spec('logout')?>>
					<i class="fas fa-sign-out-alt"></i>
				</button>
			</div>
		</div>
		<div>
			<form class="search" action="exec/search.php" <?=js_spec('search')?> method="get" autocomplete="off" >
				<span class="float-right dropdown">
					<input type="text" id="q" <?=js_spec('live_search')?> autofocus required placeholder="Keresés.."/>
					<button type="submit" class="button submit fa-lg" id="search" value="Keresés" >
						<i class="fas fa-search"></i>
					</button>
					<div class="drop-right" id="live_search"></div>
				</span>
			</form>
		</div>
	</div>
</div>
</header>