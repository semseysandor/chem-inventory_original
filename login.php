<?php
/**
 * Login page
 *********************************************************/

$config = require('default.php');

$config['title'] = 'NGX LelTár - Bejelentkezés';

require(ROOT.'/templates/head.php');?>
<header class="pad-s">
	<div>
		<a href ="index.php">
			<img src="icons/logo_nangenex.png" alt="NGX" class="rwd" width="252" height="63" />
		</a>
	</div>
</header>
<main>
<section id="msg_center"></section>
<form class="login" action="exec/login.php" method="post" <?=js_spec('login')?>>
	<table class="form">
		<caption>Bejelentkezés</caption>
		<tr>
			<th>Felhasználónév</th>
			<td><input type="text" id="user" autofocus required /></td>
		</tr>
		<tr>
			<th>Jelszó</th>
			<td><input type="password" id="pass" required /></td>
		</tr>
		<tr>
			<th></th>
			<td><button type="submit" class="button submit font-l"><i class="fas fa-sign-in-alt"></i> Belépés</button></td>
		</tr>
	</table>
</form>
</main>
<?php require(ROOT.'/templates/footer.php');?>