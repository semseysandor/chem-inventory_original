<?php
/**
 * EXECUTE
 *
 * Inactivate all missing packs
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
	security_check('leltar', 3);

	// Get list of missing packs
	$result = sql_get_missing($link);

	if ($result->num_rows > 0) {

		while ($row = $result->fetch_assoc()) {

			// Inactivate pack (set is_active = 0)
			if (!(sql_inactivate_pack($link, $row['pack_id'], $_SESSION['USER_NAME']))) { # Inactivation failed

				$response['text'] = 'Nem sikerült a kiszerelés törlése (ID:'.$row['pack_id'].')';
				$error_flag = TRUE;
				break;

			} else { # Inactivation successful

				// Delete pack from missing list
				if (!sql_delete_pack_missing($link, $row['pack_id'])) {
					$response['text'] = 'Nem sikerült a kiszerelés törlése a hiányzó listáról (ID:'.$row['pack_id'].')';
					$error_flag = TRUE;
					break;
				}

				if ((sql_get_active_packs_of_batch($link, $row['batch_id'])->num_rows) == 0) { # If there is no active packs left

					// Inactivate batch as well
					if (!(sql_inactivate_batch($link, $row['batch_id'], $_SESSION['USER_NAME']))) { # Update failed

						$response['text'] = 'Nem sikerült a termék törlése (ID:'.$row['batch_id'].')';
						$error_flag = TRUE;
						break;
					}
				}
			}
		}
		if (!$error_flag) {$response['text'] = 'Összes kiszerelés törölve';}
	}
} catch (leltar_exception $e) {$e->error_handling();}

// Set response flag
$response['flag'] = ($error_flag ? 'neg' : 'pos');

// Encode response to JSON
$response = json_encode($response, JSON_UNESCAPED_UNICODE);
echo $response;?>