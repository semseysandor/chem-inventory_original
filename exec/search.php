<?php
/**
 * EXEC
 *
 * Search
 *********************************************************/

$config = require('../default.php');

// Init session
session_start();

// Abort script if session not loaded
if (session_status() != PHP_SESSION_ACTIVE) {exit;}

try {
	// Sanitizing user inputs
	$q = get_query('q', TRUE, 'string');

	// Check if query is a valid barcode

	// Flag
	$barcode_valid = FALSE;

	// If input contains only digits, 8 digits long and first digit is 0
	if (strlen($q) == 8 and ctype_digit($q) and substr($q, 0, 1) == '0') {

		// Then it assumes is a barcode
		$code = substr($q, 1, 6);

		// Convert UPC-E code to UPC-A
		$upc_a = upc_e_to_upc_a($code);

		// Calculate check digit
		$check_digit = calc_check_digit($upc_a);

		// If calculated checksum == last digit of barcode
		if ($check_digit == substr($q, -1)) {
			// Then input is accepted as a valid barcode
			$barcode_valid = TRUE;
		}
	}

	// Retrieves info for barcode
	if ($barcode_valid) {

		$result = sql_get_barcode_data($link, $q);

		if ($result->num_rows == 1) { # Barcode found

			$all_info = $result->fetch_assoc();

			include(ROOT.'/templates/tables/all_info.php');
		} else {
			echo message('0', 'Nincs ilyen vonalkód az adatbázisban');
		}

	// Input not a valid barcode -> search DB
	} else {

		echo '<h3>Keresés erre: '.$q.'</h3>';

		// Add SQL wildcards to search query (search anywhere)
		$search_query = '%'.$q.'%';

		// Search compounds
		$result = sql_search_compound($link, $search_query);

		$compounds_found = $result->num_rows;

		if ($compounds_found > 0) {

			echo '<h4>Vegyszer találatok száma: '.$compounds_found.'</h4>';

			// Compound list
			require(ROOT.'/templates/tables/index_compound.php');
		}

		// Search Batches
		$result = sql_search_batch($link, $search_query);

		$batches_found = $result->num_rows;

		if ($batches_found > 0) {

			echo '<h4>Termék találatok száma: '.$batches_found.'</h4>';

			// Show only active batches
			$historic = FALSE;

			// Batch list
			require(ROOT.'/templates/tables/index_batch.php');
		}

		// Search API
		$result = sql_search_api($link, $search_query);

		$apis_found = $result->num_rows;

		if ($apis_found > 0) {

			echo '<h4>API találatok száma: '.$apis_found.'</h4>';

			// API list
			require(ROOT.'/templates/tables/index_api.php');
		}

		// IF no results from query
		if ($compounds_found == 0 and $batches_found == 0 and $apis_found == 0) {
			echo message('0', 'Nincsenek találatok');
		}
	}
} catch (leltar_exception $e) {$e->error_handling();}?>