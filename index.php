<?php
/**
 * Index page
 *********************************************************/

$config = require('default.php');

require(ROOT.'/templates/head.php');?>

<header>
<?php
require(ROOT.'/templates/header.php');
require(ROOT.'/templates/menu.php');?>
</header>
<main>
	<section id="msg_center"></section>
	<section id="popup"></section>
	<section id="index" class="unit"></section>
</main>
<script>
	// Event listeners

	// Show compound list on pageload
	window.addEventListener('load', retrieveData('exec/retrieve.php?q=compound', 'index'));

	// Erase Message Center on click
	window.addEventListener('click', eraseMessageCenter);

	// Close the dropdown if the user clicks outside of it
	window.addEventListener('click', function() {closeOpenedDropDown(event)});

	// Close everything on ESCAPE
	window.addEventListener('keydown', function() {closeOnESC(event)});

	// Reload data on browser navigation
	window.addEventListener('popstate', function() {retrieveData(event.state, 'index')});
</script>
<script src="js/change_log.js"></script>
<script src="js/smilesDrawer/dist/smiles-drawer.min.js"></script>
<script src="js/structure.js"></script>
<script src="js/incoming.js"></script>
<?php require(ROOT.'/templates/footer.php')?>