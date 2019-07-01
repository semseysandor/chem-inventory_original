<!DOCTYPE html>
<html lang="hu-HU">
	<head>
		<?php foreach ($config['stylesheet'] as $css): ?>
			<link rel="stylesheet" type="text/css" href="<?=$css?>" media="screen"/>
		<?php endforeach; ?>
		<meta charset="UTF-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<link rel="shortcut icon" href="icons/icon.png"/>
		<link rel="apple-touch-icon" href="icons/icon.png"/>
		<title><?=$config['title']?></title>
	</head>
	<script src="js/general.js"></script>
	<script src="js/submit.js"></script>
	<script src="js/dropdown.js"></script>
	<script src="js/random.js"></script>
	<script src="js/live_search.js"></script>
	<body>