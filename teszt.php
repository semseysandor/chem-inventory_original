<?php

$config = require('default.php');

require(ROOT.'/templates/head.php');

echo dechex(crc32('majom'));
?>
<button onclick="maki()">MAJOM</button>



<section id="popup"></section>
<script>
	function maki() {
		console.log(document.body.style.cursor);
		document.body.style.cursor = 'wait';
	}
</script>
<?php require(ROOT.'/templates/footer.php')?>