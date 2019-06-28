<?php
/**
 * Incoming
 *********************************************************/

$config = require('../default.php');

// Init session
session_start();

// Abort script if session not loaded
if (session_status() != PHP_SESSION_ACTIVE) {exit;}

try {
	// Get query (what to retrieve)
	$query = get_query('q', TRUE, 'string');

	// Start screen
	if ($query == 'start') {
		include(ROOT.'/templates/incoming/start.php');
	}

	// Compound list
	if ($query == 'compound') {

		$comp = get_query('comp', TRUE, 'string');

		// Add SQL wildcards to search query (search anywhere)
		$comp = '%'.$comp.'%';

		$result = sql_search_compound($link, $comp);

		// Compound list
		require(ROOT.'/templates/incoming/compound.php');

	}

	// Batch list
	if ($query == 'batch') {
		
		$comp_id = get_query('cid');
		
		echo $comp_id;
	}
} catch (leltar_exception $e) {$e->error_handling();}
?>