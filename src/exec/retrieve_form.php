<?php
/**
 * EXECUTE
 *
 * Retrieve forms
 *********************************************************/

$config = require('../default.php');

// Init session
session_start();

// Abort script if session not loaded
if (session_status() != PHP_SESSION_ACTIVE) {exit;}

try {
	// Get query (what to retrieve)
	$query = get_query('q', TRUE, 'string');

	// Security check
	switch ($query) {
		case 'a_comp':
		case 'e_comp':
		case 'up_coa':
		case 'up_msds':
			security_check('leltar', 1);
			break;

		case 'a_batch':
		case 'a_pack':
		case 'e_batch':
		case 'e_pack':
			security_check('leltar', 2);
			break;

		case 'a_manfac':
		case 'a_lab':
		case 'a_place':
		case 'a_sub':
		case 'a_loc':
		case 'e_manfac':
		case 'e_lab':
		case 'e_place':
		case 'e_sub':
		case 'e_loc':
		case 'e_user':
			security_check('leltar', 3);
			break;

		case 'a_api':
		case 'a_drug':
		case 'a_liter':
		case 'a_pk':
		case 'e_api':
		case 'e_drug':
		case 'e_liter':
		case 'e_pk':
		case 'up_liter':
			security_check('api', 2);
			break;

		default:
			exit;
	}

	// Add compound
	if ($query == 'a_comp') {

		// Get mode
		$mode = get_query('mode', TRUE, 'string');

		if ($mode == 'index') {
			$javascript = js('submit', ['compound', 'index']);
		} elseif ($mode == 'incoming') {
			$javascript = js_spec('inc_form_comp');
		} else {
			exit;
		}

		include(ROOT.'/templates/forms/add/compound.php');
	}

	// Add batch
	if ($query == 'a_batch') {

		// Get
		$comp_id = get_query('cid');
		$mode = get_query('mode', TRUE, 'string');

		$result = sql_get_comp_info($link, $comp_id);

		if ($result->num_rows == 1) { # Compound found

			$comp_name = $result->fetch_object()->name;

			if ($mode == 'index') {
				$javascript = js('submit', ['batch&cid='.$comp_id, 'batch_'.$comp_id]);
			} elseif ($mode == 'incoming') {
				$javascript = js_spec('inc_form_batch', [$comp_id]);
			} else {
				exit;
			}

			include(ROOT.'/templates/forms/add/batch.php');

		} else {throw new leltar_exception('no_such_record',1);}
	}

	// Add pack
	if ($query == 'a_pack') {

		// Get
		$batch_id = get_query('bid');
		$mode = get_query('mode', TRUE, 'string');

		$result = sql_get_batch_info($link, $batch_id);

		if ($result->num_rows == 1) { # Batch found

			$data = $result->fetch_assoc();

			if ($mode == 'index') {
				$javascript = js('submit', ['pack&bid='.$batch_id, 'pack_'.$batch_id]);
			} elseif ($mode == 'incoming') {
				$javascript = js_spec('inc_form_pack', [$batch_id]);
			} else {
				exit;
			}

			// Batch info
			$comp = $data['comp_name'];
			$manfac = $data['manfac_name'];
			$batch = $data['batch_name'];
			$lot = $data['lot'];

			include(ROOT.'/templates/forms/add/pack.php');

		} else {throw new leltar_exception('no_such_record',1);}
	}

	// Add API
	if ($query == 'a_api') {
		include(ROOT.'/templates/forms/add/api.php');
	}

	// Add drug
	if ($query == 'a_drug') {

		$api_id = get_query('aid');

		include(ROOT.'/templates/forms/add/drug.php');
	}

	// Add literature
	if ($query == 'a_liter') {

		$api_id = get_query('aid');

		include(ROOT.'/templates/forms/add/literature.php');
	}

	// Add PK
	if ($query == 'a_pk') {

		$drug_id = get_query('did');

		include(ROOT.'/templates/forms/add/pk.php');
	}

	// Add manufacturer
	if ($query == 'a_manfac') {
		include(ROOT.'/templates/forms/add/manfac.php');
	}

	// Add lab
	if ($query == 'a_lab') {
		include(ROOT.'/templates/forms/add/lab.php');
	}

	// Add place
	if ($query == 'a_place') {
		include(ROOT.'/templates/forms/add/place.php');
	}

	// Add sub
	if ($query == 'a_sub') {
		include(ROOT.'/templates/forms/add/sub.php');
	}

	// Add location
	if ($query == 'a_loc') {
		include(ROOT.'/templates/forms/add/location.php');
	}

	// Edit compound
	if ($query == 'e_comp') {

		// Get
		$comp_id = get_query('cid');
		$mode = get_query('mode', TRUE, 'string');
		$barcode = get_query('barcode', FALSE, 'string');

		// Retrieve current data from DB
		$result = sql_get_compound_data($link, $comp_id);

		if ($result->num_rows == 1) { # Compound found

			$data = $result->fetch_assoc();

			// Current compound data
			$name = $data['name'];
			$name_alt = $data['name_alt'];
			$abbrev = $data['abbrev'];
			$chem_name = $data['chem_name'];
			$iupac = $data['iupac'];
			$chem_form = $data['chem_form'];
			$cas = $data['cas'];
			$smiles = $data['smiles'];
			$subcat_id = $data['subcat_id'];
			$mol_weight = $data['mol_weight'];
			$melt = $data['melt'];
			$oeb = $data['oeb'];
			$note = $data['note'];

			if ($mode == 'index') {
				$javascript = js('submit', ['compound', 'index']);
			} elseif ($mode == 'search') {
				$javascript = js_spec('search_form', [$barcode]);
			} elseif ($mode == 'incoming') {
				$javascript = js_spec('inc_form_comp');
			} else {
				exit;
			}

			// Edit compound form
			include(ROOT.'/templates/forms/edit/compound.php');

		} else {throw new leltar_exception('no_such_record',1);}
	}

	// Edit batch
	if ($query == 'e_batch') {

		// Get
		$batch_id = get_query('bid');
		$mode = get_query('mode', TRUE, 'string');
		$barcode = get_query('barcode', FALSE, 'string');

		// Retrieve current data from DB
		$result = sql_get_batch_data($link, $batch_id);

		if ($result->num_rows == 1) { # Batch found

			$data = $result->fetch_assoc();

			// Current batch data
			$comp_id = $data['compound_id'];
			$comp_name = $data['comp_name'];
			$manfac_id = $data['manfac_id'];
			$manfac_name = $data['manfac_name'];
			$name = $data['name'];
			$lot = $data['lot'];
			$arr = $data['arr'];
			$open = $data['open'];
			$exp = $data['exp'];
			$note = $data['note'];

			if ($mode == 'index') {
				$javascript = js('submit', ['batch&cid='.$comp_id, 'batch_'.$comp_id]);
			} elseif ($mode == 'search') {
				$javascript = js_spec('search_form', [$barcode]);
			} elseif ($mode == 'incoming') {
				$javascript = js_spec('inc_form_batch',[$comp_id]);
			} else {
				exit;
			}

			// Edit batch form
			include(ROOT.'/templates/forms/edit/batch.php');

		} else {throw new leltar_exception('no_such_record',1);}
	}

	// Edit pack
	if ($query == 'e_pack') {

		// Get
		$pack_id = get_query('pid');
		$mode = get_query('mode', TRUE, 'string');
		$barcode = get_query('barcode', FALSE, 'string');

		// Retrieve current data from DB
		$result = sql_get_pack_data($link, $pack_id);

		if ($result->num_rows == 1) { # Pack found

			$data = $result->fetch_assoc();

			// Current pack data
			$comp_id = $data['compound_id'];
			$comp_name = $data['comp_name'];
			$manfac_name = $data['manfac_name'];
			$batch_name = $data['batch_name'];
			$batch_id = $data['batch_id'];
			$lot = $data['lot'];
			$loc_id = $data['loc_id'];
			$size = $data['size'];
			$is_orig = $data['is_orig'];
			$weight = $data['weight'];
			$note = $data['note'];

			if ($mode == 'index') {
				$javascript = js('submit', ['pack&bid='.$batch_id, 'pack_'.$batch_id]);
			} elseif ($mode == 'search') {
				$javascript = js_spec('search_form', [$barcode]);
			} elseif ($mode == 'incoming') {
				$javascript = js_spec('inc_form_pack',[$batch_id]);
			} else {
				exit;
			}

			// Edit pack form
			include(ROOT.'/templates/forms/edit/pack.php');

		} else {throw new leltar_exception('no_such_record',1);}
	}

	// Edit API
	if ($query == 'e_api') {

		// Get ID
		$api_id = get_query('aid');

		// Retrieve current data from DB
		$result = sql_get_api_data($link, $api_id);

		if ($result->num_rows == 1) { # API found

			$data = $result->fetch_assoc();

			// Current API data
			$name = $data['name'];
			$form = $data['form'];
			$eval = $data['eval'];
			$dev = $data['dev'];
			$market = $data['market'];
			$patent = $data['patent'];
			$pri = $data['pri'];
			$sec = $data['sec'];
			$melt = $data['melt'];
			$water = $data['water'];
			$hcl = $data['hcl'];
			$note = $data['note'];

			// Edit API form
			include(ROOT.'/templates/forms/edit/api.php');

		} else {throw new leltar_exception('no_such_record',1);}
	}

	// Edit drug product
	if ($query == 'e_drug') {

		// Get ID
		$drug_id = get_query('did');

		// Retrieve current data from DB
		$result = sql_get_drug_product_data($link, $drug_id);

		if ($result->num_rows == 1) { # Drug product found

			$data = $result->fetch_assoc();

			// Current drug product data
			$api_id = $data['api_id'];
			$name = $data['name'];
			$name_alt = $data['name_alt'];
			$dosage = $data['dosage'];
			$crystall = $data['crystall'];
			$particle = $data['particle'];
			$dose_free = $data['dose_free'];
			$dose_day = $data['dose_day'];
			$admin = $data['admin'];
			$note = $data['note'];

			// Edit drug product form
			include(ROOT.'/templates/forms/edit/drug.php');

		} else {throw new leltar_exception('no_such_record',1);}
	}

	// Edit literature
	if ($query == 'e_liter') {

		// Get ID
		$literature_id = get_query('lid');

		// Retrieve current data from DB
		$result = sql_get_literature_data($link, $literature_id);

		if ($result->num_rows == 1) { # Literature found

			$data = $result->fetch_assoc();

			// Current literature data
			$api_id = $data['api_id'];
			$title = $data['title'];
			$author = $data['author'];
			$year = $data['year'];
			$journal = $data['journal'];
			$note = $data['note'];

			// Edit literature form
			include(ROOT.'/templates/forms/edit/literature.php');

		} else {throw new leltar_exception('no_such_record',1);}
	}

	// Edit PK
	if ($query == 'e_pk') {

		// Get ID
		$pk_id = get_query('pkid');

		// Retrieve current data from DB
		$result = sql_get_pk_data($link, $pk_id);

		if ($result->num_rows == 1) { # PK found

			$data = $result->fetch_assoc();

			// Current PK data
			$drug_id = $data['drug_id'];
			$attribute = $data['pk_attr'];
			$value = $data['value'];
			$note = $data['note'];

			// Edit PK form
			include(ROOT.'/templates/forms/edit/pk.php');

		} else {throw new leltar_exception('no_such_record',1);}
	}

	// Upload CoA or MSDS
	if (in_array($query, ['up_coa', 'up_msds'])) {

		// Get ID
		$batch_id = get_query('bid');

		// Retrieve batch info from DB
		$result = sql_get_batch_info($link, $batch_id);

		if ($result->num_rows == 1) { # Batch found

			$data = $result->fetch_assoc();

			// Batch data
			$comp_id = $data['comp_id'];
			$comp_name = $data['comp_name'];
			$manfac_name = $data['manfac_name'];
			$name = $data['batch_name'];
			$lot = $data['lot'];

			// File upload form
			if ($query == 'up_coa') {
				include(ROOT.'/templates/forms/upload/coa.php');
			} elseif ($query == 'up_msds') {
				include(ROOT.'/templates/forms/upload/msds.php');
			}
		}
	}

	// Upload literature
	if ($query == 'up_liter') {

		// Get ID
		$literature_id = get_query('lid');

		// Retrieve current data from DB
		$result = sql_get_literature_data($link, $literature_id);

		if ($result->num_rows == 1) { # Literature found

			$data = $result->fetch_assoc();

			// Current literature data
			$api_id = $data['api_id'];

			// Upload literature form
			include(ROOT.'/templates/forms/upload/literature.php');

		} else {throw new leltar_exception('no_such_record',1);}
	}

	// Edit manufacturer
	if ($query == 'e_manfac') {

		// Get ID
		$manfac_id = get_query('manfac_id');

		// Retrieve current data from DB
		$result = sql_get_manfac_data($link, $manfac_id);

		if ($result->num_rows == 1) { # Manufacturer found

			$data = $result->fetch_assoc();

			// Current data
			$name = $data['name'];
			$is_freq = $data['is_frequent'];

			// Edit manfac form
			include(ROOT.'/templates/forms/edit/manfac.php');

		} else {throw new leltar_exception('no_such_record',1);}
	}

	// Edit lab
	if ($query == 'e_lab') {

		// Get ID
		$lab_id = get_query('lab_id');

		// Retrieve current data from DB
		$result = sql_get_lab_data($link, $lab_id);

		if ($result->num_rows == 1) { # Lab found

			$data = $result->fetch_assoc();

			// Current data
			$name = $data['name'];

			// Edit lab form
			include(ROOT.'/templates/forms/edit/lab.php');

		} else {throw new leltar_exception('no_such_record',1);}
	}

	// Edit place
	if ($query == 'e_place') {

		// Get ID
		$place_id = get_query('place_id');

		// Retrieve current data from DB
		$result = sql_get_place_data($link, $place_id);

		if ($result->num_rows == 1) { # Place found

			$data = $result->fetch_assoc();

			// Current data
			$name = $data['name'];

			// Edit place form
			include(ROOT.'/templates/forms/edit/place.php');

		} else {throw new leltar_exception('no_such_record',1);}
	}

	// Edit sub
	if ($query == 'e_sub') {

		// Get ID
		$sub_id = get_query('sub_id');

		// Retrieve current data from DB
		$result = sql_get_sub_data($link, $sub_id);

		if ($result->num_rows == 1) { # Sub found

			$data = $result->fetch_assoc();

			// Current data
			$name = $data['name'];

			// Edit sub form
			include(ROOT.'/templates/forms/edit/sub.php');

		} else {throw new leltar_exception('no_such_record',1);}
	}

	// Edit location
	if ($query == 'e_loc') {

		// Get ID
		$location_id = get_query('loc_id');

		// Retrieve current data from DB
		$result = sql_get_location_data($link, $location_id);

		if ($result->num_rows == 1) { # Location found

			$data = $result->fetch_assoc();

			// Current data
			$lab_id = $data['lab_id'];
			$place_id = $data['place_id'];
			$sub_id = $data['sub_id'];

			// Edit location form
			include(ROOT.'/templates/forms/edit/location.php');

		} else {throw new leltar_exception('no_such_record',1);}
	}

	// Edit user
	if ($query == 'e_user') {

		// Get ID
		$user_id = get_query('uid');

		// Retrieve current data from DB
		$result = sql_get_user_data($link, $user_id);

		if ($result->num_rows == 1) { # User found

			$data = $result->fetch_assoc();

			// Current data
			$name = $data['name'];
			$chemical = $data['chemical'];
			$api = $data['api'];
			$solvent = $data['solvent'];

			// Edit sub form
			include(ROOT.'/templates/forms/edit/user.php');

		} else {throw new leltar_exception('no_such_record',1);}
	}
} catch (leltar_exception $e) {$e->error_handling();}?>