<?php
/**
 * Inventory
 *********************************************************/

$config = require('../default.php');

// Init session
session_start();

// Abort script if session not loaded
if (session_status() != PHP_SESSION_ACTIVE) {exit;}

try {

	// Security check
	security_check('leltar', 3);

	// Get query (what to retrieve)
	$selector = get_query('q', TRUE, 'string');

	// Start
	if ($selector == 'start') {
		include(ROOT.'/templates/inventory/start.php');
	}

	// Restart inventory (delete missing packs list)
	if ($selector == 'clear') {

		$link->query('TRUNCATE temp_missing');
		include(ROOT.'/templates/inventory/start.php');
	}

	// Select a location
	if ($selector == 'location') {

		$location_id = get_query('loc_id');

		// Get location name
		$result = sql_get_location_name($link, $location_id);

		if ($result->num_rows != 1) {exit;} # No location found

		$loc = $result->fetch_assoc();

		include(ROOT.'/templates/inventory/barcode.php');

	}

	// Load missing packs at a location
	if ($selector == 'load_missing') {

		$location_id = get_query('loc_id');

		// Get location name
		$result = sql_get_location_name($link, $location_id);

		if ($result->num_rows != 1) {exit;} # No location found

		$loc = $result->fetch_assoc();

		// Get packs at a location
		$result = sql_get_pack_location($link, $location_id);

		if ($result->num_rows > 0) { # Packs found

			while ($row = $result->fetch_assoc()) {

				// Adds packs to missing list
				if (!sql_insert_pack_missing($link, $row['pack_id'])) {
					echo message('#', 'Nem sikerült hozzáadni a kiszerelést a listához (ID: '.$row['pack_id'].')');
					exit;
				}
			}

			echo '<h4>Kiszerelések betöltve</h4>';
			echo '<h4>Kiszerelések száma: '.$result->num_rows.'</h4>';

			include(ROOT.'/templates/inventory/barcode.php');

		} else {
			echo '<h4>Nincs kiszerelés ezen a lokáción</h4>';
		}
	}

	// Scan barcode
	if ($selector == 'scan') {

		// Get barcode, location
		$barcode = get_query('barcode', TRUE, 'string');
		$location_id = get_query('loc_id');

		// Flag
		$barcode_valid = FALSE;

		// If input contains only digits, 8 digits long and first digit is 0
		if (strlen($barcode) == 8 and ctype_digit($barcode) and substr($barcode, 0, 1) == '0') {

			// Then it assumes is a barcode
			$code = substr($barcode, 1, 6);

			// Convert UPC-E code to UPC-A
			$upc_a = upc_e_to_upc_a($code);

			// Calculate check digit
			$check_digit = calc_check_digit($upc_a);

			// If calculated checksum == last digit of barcode
			if ($check_digit == substr($barcode, -1)) {
				// Then input is accepted as a valid barcode
				$barcode_valid = TRUE;
			}
		}

		if (!$barcode_valid) { # Input not a valid barcode
			echo message('#', 'Nem érvényes vonalkód');
			exit;
		}

		echo '<h4>Beolvasva: <span class="barcode" style="cursor:default">'.$barcode.'</span></h4>';

		// Check barcode in DB
		$result = sql_inventory_check_pack($link, $barcode);

		if ($result->num_rows == 0) { # No barcode found
			echo message('-', 'Nincs ilyen vonalkód az adatbázisban');
			exit;
		}

		// Barcode found
		$row = $result->fetch_assoc();

		// Mislocation
		if ($row['location_id'] != $location_id) {

			// Real position
			$result = sql_get_location_name($link, $row['location_id']);

			if ($result->num_rows != 1) {exit;}

			$loc = $result->fetch_assoc();
			echo message('0', 'Miszlokáció, jó hely: '.$loc['lab_name'].' > '.$loc['place_name'].($loc['sub_name'] ? ' > ':'').$loc['sub_name']);

			// Check if pack is already missing in inventory
			$result = sql_inventory_check_missing($link, $row['pack_id']);

			if ($result->num_rows == 1) {

				if (sql_delete_pack_missing($link, $row['pack_id'])) { // Pack removed from missing list
					echo message('+', 'OK, Hiányzó listáról törölve');
				} else {
					echo message('-', 'Nem sikerült törölni a hiányzó listáról');
				}
			}

			exit;
		}

		// Check if pack is missing in inventory
		$result = sql_inventory_check_missing($link, $row['pack_id']);

		if ($result->num_rows == 1) {

			if (sql_delete_pack_missing($link, $row['pack_id'])) { // Pack removed from missing list
				echo message('+', 'OK');
			}
		} else {
			echo message('+', 'Már leltározva');
		}
	}

	// Show missing
	if ($selector == 'show_missing') {

		$result = sql_get_missing($link);
		include(ROOT.'/templates/inventory/missing_packs.php');
		
	}

	// Delete from list
	if ($selector == 'delete') {

		$pack_id = get_query('pid');

		if (sql_delete_pack_missing($link, $pack_id)) { // Pack removed from missing list
			echo message('+', 'Törölve');
		} else {
			echo message('-', 'Törlés nem sikerült');
		}
	}

} catch (leltar_exception $e) {$e->error_handling();}
?>