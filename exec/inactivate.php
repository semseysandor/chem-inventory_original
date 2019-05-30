<?php
/**
 * EXECUTE
 *
 * Inactivate pack
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
	security_check('leltar', 2);

	// Get batch id (bid)
	$batch_id = get_query('bid');
	// Get pack id (pid)
	$pack_id = get_query('pid');

	// Inactivate pack (set is_active = 0)
	if (!(sql_inactivate_pack($link, $pack_id, $_SESSION['USER_NAME']))) { # Inactivation failed

		$response['text'] = 'Nem sikerült a kiszerelés törlése (ID:'.$pack_id.')';
		$error_flag = TRUE;

	} else { # Inactivation successful

		$response['text'] = 'Kiszerelés törölve';

		if ((sql_get_active_packs_of_batch($link, $batch_id)->num_rows) == 0) { # If there is no active packs left

			// Inactivate batch as well
			if (!(sql_inactivate_batch($link, $batch_id, $_SESSION['USER_NAME']))) { # Update failed

				$response['text'] = 'Nem sikerült a termék törlése (ID:'.$batch_id.')';
				$error_flag = TRUE;
			}
		}
	}
} catch (leltar_exception $e) {$e->error_handling();}

// Set response flag
$response['flag'] = ($error_flag ? 'neg' : 'pos');

// Encode response to JSON
$response = json_encode($response, JSON_UNESCAPED_UNICODE);
echo $response;?>