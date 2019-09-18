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
		$level = 'ÜberAdmin';
		break;
	case 4:
		$level = 'System Manager';
		break;
	default:
		$level = 'HACK';
		break;
}?>
<header class="block">
<div class="float-left col-s-4">
	<a href ="index.php">
		<img src="icons/logo.png" alt="logo" class="rwd" width="293" height="91" />
	</a>
</div>
<div class="col-s-8">
	<div id="msg_center" class="float-left"></div>
	<div class="float-right">
		<div>
			<div class="rwd">
				<i class="far fa-calendar-alt"></i>
				<span> <?=date('Y. M d.')?></span>
			</div>
			<div class="rwd">
				<i class="fas fa-user"></i>
				<span> <?=$_SESSION['USER_NAME']?></span>
			</div>
			<div class="rwd">
				<i class="fas fa-user-shield"></i>
				<span> <?=$level?></span>
			</div>
			<div class="rwd">
				<button class="button logout fa-lg" <?=js_spec('logout')?>>
					<i class="fas fa-sign-out-alt"></i>
				</button>
			</div>
		</div>
		<div>
			<form class="search" action="exec/search.php" <?=js_spec('search')?> method="get" autocomplete="off" >
				<span class="dropdown float-right">
					<input type="search" id="q" <?=js_spec('live_search')?> autofocus required placeholder="Keresés.."/>
					<button type="submit" class="button submit fa-lg" id="search_button">
						<i class="fas fa-search"></i>
					</button>
					<div class="drop-right pad-s" id="live_search"></div>
				</span>
			</form>
		</div>
	</div>
</div>
</header>