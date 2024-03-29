<?php
/**
 * EXECUTE
 *
 * Retrieve data from DB
 *********************************************************/

$config = require('../default.php');

// Init session
session_start();

// Abort script if session not loaded
if (session_status() != PHP_SESSION_ACTIVE) {exit;}

try {
	// Get query (what to retrieve)
	$query = get_query('q', TRUE, 'string');

	// Index compound
	if ($query == 'compound') {

		// Rows per page
		$limit = 15;

		// Array for GET params (URL query string)
		$get_array = array();

		// Get category & subcategory
		$cat = get_query('cat', FALSE);
		$subcat = get_query('subcat', FALSE);

		// Put category & sub-category to GET query
		if ($cat) {$get_array['cat'] = $cat;}
		if ($subcat) {$get_array['subcat'] = $subcat;}

		// Retrieve categories from DB
		$categories = sql_get_category($link);

		if ($categories->num_rows > 0) { # Categories exist in DB

			// Category buttons
			require(ROOT.'/templates/tables/index_compound_category.php');
		}

		if ($cat) {

			// Retrieve subcategories from DB
			$subcategories = sql_get_subcategory($link, $cat);

			if ($subcategories->num_rows > 1) { # Subcategories exist in DB
				// Sub-category buttons
				require(ROOT.'/templates/tables/index_compound_subcategory.php');
			}
		}

		// Retrieves number of records for pagination
		if (!$cat) {
			$total_record = sql_get_compound_number_all($link);
		} elseif (!$subcat) {
			$total_record = sql_get_compound_number_categ($link, $cat);
		} else {
			$total_record = sql_get_compound_number_subcateg($link, $subcat);
		}

		// Pagination info
		$last_page = ceil($total_record / $limit);
		$current_page = get_page($last_page);
		$offset = ($current_page - 1) * $limit;

		// Retrieves compounds
		if (!$cat) {
			$result = sql_get_compounds_all($link, $offset, $limit);
		} elseif (!$subcat) {
			$result = sql_get_compounds_categ($link, $cat, $offset, $limit);
		} else {
			$result = sql_get_compounds_subcateg($link, $subcat, $offset, $limit);
		}

		// Compound list
		require(ROOT.'/templates/tables/index_compound.php');

		// Pagination links
		$javascript = 'compound';
		require(ROOT.'/templates/pagination.php');
	}

	// Index batch
	if ($query == 'batch') {

		// Get ID
		$comp_id = get_query('cid');

		// Flag for historic data
		$historic = get_query('hist', FALSE);

		// Retrieves batch list
		if ($historic) { # Show historic batches
			$result = sql_get_batch($link, $comp_id, 0);
		} else { # Show active batches
			$result = sql_get_batch($link, $comp_id, 1);
		}

		// Batch list
		require(ROOT.'/templates/tables/index_batch.php');
	}

	// Index pack
	if ($query == 'pack') {

		// Get ID
		$batch_id = get_query('bid');

		// Flag for historic data
		$historic = get_query('hist', false);

		// Retrieves pack list
		if ($historic) { # Show historic packs
			$result = sql_get_pack($link, $batch_id, 0);
		} else { # Show active packs
			$result = sql_get_pack($link, $batch_id, 1);
		}

		// Pack list
		require(ROOT.'/templates/tables/index_pack.php');
	}

	// Index API
	if ($query == 'api') {

		// Rows per page
		$limit = 15;

		// Array for GET params (URL query string)
		$get_array = array();

		// Retrieves number of records for pagination
		$total_record = sql_get_api_number_all($link);

		// Pagination info
		$last_page = ceil($total_record / $limit);
		$current_page = get_page($last_page);
		$offset = ($current_page - 1) * $limit;

		$result = sql_get_api_all($link, $offset, $limit);

		// API list
		require(ROOT.'/templates/tables/index_api.php');

		// Pagination links
		$javascript = 'api';
		include(ROOT.'/templates/pagination.php');
	}

	// Index drug product
	if ($query == 'drug') {

		// Get ID
		$api_id = get_query('aid');

		// Retrieves drug products
		$result = sql_get_drug_product($link, $api_id);

		// Drug product list
		require(ROOT.'/templates/tables/index_drug.php');

		// Retrieves literatures
		$result = sql_get_literature($link, $api_id);

		// Literature list
		require(ROOT.'/templates/tables/index_literature.php');
	}

	// Index PK data
	if ($query == 'pk') {

		// Get ID
		$drug_id = get_query('did');

		// Get PK data
		$result = sql_get_pk($link, $drug_id);

		// PK list
		require(ROOT.'/templates/tables/index_pk.php');
	}

	// Compound details
	if ($query == 'compound_det') {

		// Get ID
		$comp_id = get_query('cid');

		// Retrieves compound info
		$result = sql_get_compound_data($link, $comp_id);

		if ($result->num_rows == 1) { # Compound found

			$row = $result->fetch_assoc();

			// Compound details
			require(ROOT.'/templates/tables/compound_details.php');

		} else {
			throw new leltar_exception('no_such_record');
		}
	}

	// SMILES
	if ($query == 'smiles') {

		// Get ID
		$comp_id = get_query('cid', FALSE);

		// If no compound ID present
		if (!$comp_id) {

			// Get barcode
			$barcode = get_query('barcode', TRUE, 'string');

			// Retrieves SMILES
			$result = sql_get_smiles_barcode($link, $barcode);

		} else {

			// Retrieves SMILES
			$result = sql_get_smiles_compound($link, $comp_id);
		}

		echo $result;
	}

	// Compound info
	if ($query == 'comp_info') {

		// Get CAS
		$cas = get_query('cas', TRUE, 'string');

		// Check CAS
		if (!check_cas($cas)) {
			exit;
		}

		// Get compound info from cactus
		$info = cactus_get_compound_info($cas, 'formula');
		if ($info) {
			$response['chem_form'] = $info;
		} else {
			$response['chem_form'] = NULL;
		}

		$info = cactus_get_compound_info($cas, 'mw');
		if ($info) {
			$response['mol_weight'] = $info;
		} else {
			$response['mol_weight'] = NULL;
		}

		$info = cactus_get_compound_info($cas, 'smiles');
		if ($info) {
			$response['smiles'] = $info;
		} else {
			$response['smiles'] = NULL;
		}

		$info = cactus_get_compound_info($cas, 'iupac_name');
		if ($info) {
			$response['iupac'] = $info;
		} else {
			$response['iupac'] = NULL;
		}

		// Encode response to JSON
		$response = json_encode($response, JSON_UNESCAPED_UNICODE);
		echo $response;

	}

	// Live search
	if ($query == 'live_search') {

		// Get search
		$search = get_query('search', FALSE, 'string');

		// If no search query
		if ($search == '') {exit;}

		// Extend search (search anywhere)
		$extended = '%'.$search.'%';

		// Retrieves search results
		$result = sql_live_search($link, $extended);

		if ($result->num_rows > 0) {

			// Search string lenght
			$lenght = strlen($search);

			while ($row = $result->fetch_assoc()) {

				// Full result word
				$word = $row['search'];

				// Start position of search string inside the full word
				$begin = stripos($word, $search);
				// Pre-word
				$start = $begin - 5;
				if ($start < 0) {$start = 0;}

				// Result item container
				echo '<div class="live_search" '.js_spec('live_search_item', [$word]).'>';
				// Pre-search
				echo substr($word, $start, $begin - $start);
				// Search term (bold)
				echo '<strong>'.substr($word, $begin, $lenght).'</strong>';
				// Post search part (max 25 char long altogether)
				echo substr($word, $begin + $lenght, 25 - (($begin - $start) + $lenght));
				echo '</div>';
			}
		}
	}

	// Solvents
	if ($query == 'solvent') {

		$result = sql_get_solvents($link);

		// Solvents
		require(ROOT.'/templates/tables/solvents.php');
	}

	// Manufacturers
	if ($query == 'manfac') {

		$result = sql_get_manfac($link);

		require(ROOT.'/templates/tables/manfac.php');
	}

	// Locations
	if ($query == 'location') {

		require(ROOT.'/templates/tables/location_header.php');

		echo '<div class="block">';

		$result = sql_get_lab($link);
		require(ROOT.'/templates/tables/lab.php');

		$result = sql_get_place($link);
		require(ROOT.'/templates/tables/place.php');

		$result = sql_get_sub($link);
		require(ROOT.'/templates/tables/sub.php');

		$result = sql_get_location($link);
		require(ROOT.'/templates/tables/location.php');

		echo '</div>';
	}

	// User rights
	if ($query == 'rights') {

		$result = sql_get_users($link);

		require(ROOT.'/templates/tables/users.php');
	}
} catch (leltar_exception $e) {$e->error_handling();}
?>