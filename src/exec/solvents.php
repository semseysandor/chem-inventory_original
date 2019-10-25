<?php
/**
 * EXECUTE
 *
 * Modify solvent units
 *********************************************************/

$config = require('../default.php');

// Init session
session_start();

// Abort script if session not loaded
if (session_status() != PHP_SESSION_ACTIVE) {exit;}

try {

	// Init response
	$response = [];

	// Security check
	security_check('solvent', 2);

	// Get solvent id (sid)
	$solvent_id = get_query('sid');

	// Get solvent units (sid)
	$unit = get_query('unit');

	if (sql_update_solvent($link, $solvent_id, $unit, $_SESSION['USER_NAME'])) {

		$response['text'] = 'Mennyiség módosítva';

	} else {
		$response['text'] = 'Nem sikerült a módosítás';
		$error_flag = TRUE;
	}
} catch (leltar_exception $e) {$e->error_handling();}

// Set response flag
$response['flag'] = ($error_flag ? 'neg' : 'pos');

// Encode response to JSON
$response = json_encode($response, JSON_UNESCAPED_UNICODE);

echo $response;?>