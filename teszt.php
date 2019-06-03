<?php

$config = require('default.php');

require(ROOT.'/templates/head.php');

$cas = '7235-40-7';
$iupac = NULL;

// Get info from:
$url='https://cactus.nci.nih.gov/chemical/structure/'.$cas.'/iupac_name';

// Curl init
$ch = curl_init();

// Curl options
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CRLF, 1);
//curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_URL, $url);

// HTTP GET
//$data = curl_exec($ch);
var_dump(curl_exec($ch));

// HTTP response code
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Close Curl
curl_close($ch);

if ($code == 200) { # If response OK
	var_dump($data);
	echo '<div>cica</div>';
	echo '<div>'.$data.'</div>';
} else {
	echo '<div>kutya</div>';
}



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