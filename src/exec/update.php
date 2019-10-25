<?php
/**
 * EXECUTE
 *
 * UPDATE data in DB
 *********************************************************/

$config = require('../default.php');

// Init session
session_start();

// Abort script if session not loaded
if (session_status() != PHP_SESSION_ACTIVE) {exit;}

try {

if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['data'])) {

	// Init response
	$response = [];

	// Decode JSON
	$data = json_decode($_POST['data'], TRUE);

	// Abort script if no selector present
	if (!isset($data['selector'])) {
		exit;
	} else {
		$selector = $data['selector'];
	}

	// Security check
	switch ($selector) {
		case 'compound':
			security_check('leltar', 1);
			break;

		case 'batch':
		case 'pack':
			security_check('leltar', 2);
			break;

		case 'manfac':
		case 'lab':
		case 'place':
		case 'sub':
		case 'location':
		case 'user':
			security_check('leltar', 3);
			break;

		case 'api':
		case 'drug':
		case 'literature':
		case 'pk':
			security_check('api', 2);
			break;

		default:
			exit;
	}

	// Compound
	if ($selector == 'compound') {

		do {

			// Sanitizing user inputs
			if (empty($data['comp_id']) or (intval($data['comp_id']) < 1)) {
				$response['text'] = 'Vegyszer ID hiányzik';
				$error_flag = TRUE;
				break;
			}

			if (empty($data['name']) or (clean_input($data['name']) == '')) {
				$response['text'] = 'Név hiányzik';
				$error_flag = TRUE;
				break;
			}

			if (empty($data['subcat_id']) or (intval($data['subcat_id']) < 1)) {
				$response['text'] = 'Kategória hiányzik';
				$error_flag = TRUE;
				break;
			}

			$comp_id = intval($data['comp_id']);
			$name = clean_input($data['name']);
			$subcat_id = intval($data['subcat_id']);

			if (!empty($data['name_alt'])) {$name_alt = clean_input($data['name_alt']);}
			else {$name_alt = NULL;}

			if (!empty($data['abbrev'])) {$abbrev = clean_input($data['abbrev']);}
			else {$abbrev = NULL;}

			if (!empty($data['chem_name'])) {$chem_name = clean_input($data['chem_name']);}
			else {$chem_name = NULL;}

			if (!empty($data['iupac'])) {$iupac = clean_input($data['iupac']);}
			else {$iupac = NULL;}

			if (!empty($data['chem_form'])) {$chem_form = clean_input($data['chem_form']);}
			else {$chem_form = NULL;}

			if (!empty($data['cas'])) {

				$cas = clean_input($data['cas']);

				// Check CAS
				if (!check_cas($cas)) {
					$response['text'] = 'Hibás CAS szám';
					$error_flag = TRUE;
					break;
				}
			} else {
				$cas = NULL;
			}

			if (!empty($data['smiles'])) {$smiles = clean_input($data['smiles']);}
			else {$smiles = NULL;}

			if (!empty($data['oeb'])) {$oeb = intval($data['oeb']);}
			else {$oeb = NULL;}

			if (!empty($data['mol_weight'])) {$mol_weight = clean_input($data['mol_weight']);}
			else {$mol_weight = NULL;}

			if (!empty($data['melt'])) {$melt = intval($data['melt']);}
			else {$melt = NULL;}

			if (!empty($data['note'])) {$note = clean_input($data['note']);}
			else {$note = NULL;}

			// Retrieve current data from DB
			$result = sql_get_compound_data($link, $comp_id);

			if ($result->num_rows != 1) {break;}

			$data_cur = $result->fetch_assoc();

			// Check if there is any modification
			if (($name == $data_cur['name'])
				and ($name_alt == $data_cur['name_alt'])
				and ($abbrev == $data_cur['abbrev'])
				and ($chem_name == $data_cur['chem_name'])
				and ($iupac == $data_cur['iupac'])
				and ($chem_form == $data_cur['chem_form'])
				and ($cas == $data_cur['cas'])
				and ($smiles == $data_cur['smiles'])
				and ($subcat_id == $data_cur['subcat_id'])
				and ($mol_weight == $data_cur['mol_weight'])
				and ($melt == $data_cur['melt'])
				and ($oeb == $data_cur['oeb'])
				and ($note == $data_cur['note'])) {break;}

			// Check for duplicate
			$duplicate = sql_check_compound($link, $name);
			if ($duplicate and $duplicate != $comp_id) { # There is a duplicate and is not self

				$response['text'] = 'Már van ilyen vegyszer';
				$error_flag = TRUE;
				break;
			}

			if (sql_update_compound($link, $comp_id, $name, $name_alt, $abbrev, $chem_name,
															$iupac, $chem_form, $cas, $smiles, $subcat_id,
															$oeb, $mol_weight, $melt, $note, $_SESSION['USER_NAME'])) {

				// Successfully inserted
				$response['text'] = 'Vegyszer módosítva';
			} else {
				$response['text'] = 'Nem sikerült módosítani a vegyszert';
				$error_flag = TRUE;
			}
		} while (FALSE);
	}

	// Batch
	if ($selector == 'batch') {

		do {

			// Sanitizing user inputs
			if (empty($data['comp_id']) or (intval($data['comp_id']) < 1)) {
				$response['text'] = 'Vegyszer ID hiányzik';
				$error_flag = TRUE;
				break;
			}

			if (empty($data['batch_id']) or (intval($data['batch_id']) < 1)) {
				$response['text'] = 'Termék ID hiányzik';
				$error_flag = TRUE;
				break;
			}

			if (empty($data['manfac']) or (intval($data['manfac']) < 1)) {
				$response['text'] = 'Gyártó hiányzik';
				$error_flag = TRUE;
				break;
			}

			if (empty($data['name']) or (clean_input($data['name']) == '')) {
				$response['text'] = 'Név hiányzik';
				$error_flag = TRUE;
				break;
			}

			if (empty($data['lot']) or (clean_input($data['lot']) == '')) {
				$response['text'] = 'LOT# hiányzik';
				$error_flag = TRUE;
				break;
			}

			if (empty($data['arr']) or (clean_input($data['arr']) == '')) {
				$response['text'] = 'Érkezési dátum hiányzik';
				$error_flag = TRUE;
				break;
			}

			$comp_id = intval($data['comp_id']);
			$batch_id = intval($data['batch_id']);
			$manfac = intval($data['manfac']);
			$name = clean_input($data['name']);
			$lot = clean_input($data['lot']);
			$arr = clean_input($data['arr']);

			if (!empty($data['open'])) {$open = clean_input($data['open']);}
			else {$open = NULL;}

			if (!empty($data['exp'])) {$exp = clean_input($data['exp']);}
			else {$exp = NULL;}

			if (!empty($data['note'])) {$note = clean_input($data['note']);}
			else {$note = NULL;}

			// Retrieve current data from DB
			$result = sql_get_batch_data($link, $batch_id);

			if ($result->num_rows != 1) {break;}

			$data_cur = $result->fetch_assoc();

			// Check if there is any modification
			if (($manfac == $data_cur['manfac_id'])
				and ($name == $data_cur['name'])
				and ($lot == $data_cur['lot'])
				and ($arr == $data_cur['arr'])
				and ($open == $data_cur['open'])
				and ($exp == $data_cur['exp'])
				and ($note == $data_cur['note'])) {break;}

			// Check for duplicate
			$duplicate = sql_check_batch($link, $comp_id, $name, $manfac, $lot);
			if ($duplicate and $duplicate != $batch_id) { # There is a duplicate and is not self

				$response['text'] = 'Már van ilyen termék';
				$error_flag = TRUE;
				break;
			}

			if (sql_update_batch($link, $batch_id, $manfac, $name, $lot, $arr,
													$open, $exp, $note, $_SESSION['USER_NAME'])) {

				// Successfully inserted
				$response['text'] = 'Termék módosítva';
			} else {
				$response['text'] = 'Nem sikerült módosítani a terméket';
				$error_flag = TRUE;
			}
		} while (FALSE);
	}

	// Pack
	if ($selector == 'pack') {

		do {

			// Sanitizing user inputs
			if (empty($data['pack_id']) or (intval($data['pack_id']) < 1)) {
				$response['text'] = 'Kiszerelés ID hiányzik';
				$error_flag = TRUE;
				break;
			}

			if (empty($data['loc_id']) or (intval($data['loc_id']) < 1)) {
				$response['text'] = 'Helyzet hiányzik';
				$error_flag = TRUE;
				break;
			}

			if (empty($data['size']) or (clean_input($data['size']) == '')) {
				$response['text'] = 'Kiszerelés hiányzik';
				$error_flag = TRUE;
				break;
			}

			$pack_id = intval($data['pack_id']);
			$loc_id = intval($data['loc_id']);
			$size = clean_input($data['size']);

			if (!empty($data['weight'])) {$weight = clean_input($data['weight']);}
			else {$weight = NULL;}

			if (!empty($data['note'])) {$note = clean_input($data['note']);}
			else {$note = NULL;}

			if (!empty($data['is_orig'])) {$is_orig = clean_input($data['is_orig']);}
			else {$is_orig = 0;}

			// Retrieve current data from DB
			$result = sql_get_pack_data($link, $pack_id);

			if ($result->num_rows != 1) {break;}

			$data_cur = $result->fetch_assoc();

			// Check if there is any modification

			if (($size == $data_cur['size'])
				and ($weight == $data_cur['weight'])
				and ($loc_id == $data_cur['loc_id'])
				and ($note == $data_cur['note'])
				and ($is_orig == $data_cur['is_orig'])) {break;}

			if (sql_update_pack($link, $pack_id, $loc_id, $is_orig,
													$size, $weight, $note, $_SESSION['USER_NAME'])) {

				// Successfully inserted
				$response['text'] = 'Kiszerelés módosítva';
			} else {
				$response['text'] = 'Nem sikerült módosítani a kiszerelést';
				$error_flag = TRUE;
			}
		} while (FALSE);
	}

	// API
	if ($selector == 'api') {

		// Sanitizing user inputs
		if (empty($data['api_id']) or (intval($data['api_id']) <= 0)) {
			$response['text'] = 'API ID hiányzik';
			$error_flag = TRUE;
		} elseif (empty($data['name']) or (clean_input($data['name']) == '')) {
			$response['text'] = 'Név hiányzik';
			$error_flag = TRUE;
		} else {
			$api_id = intval($data['api_id']);
			$name = clean_input($data['name']);
		}

		if (!empty($data['form'])) {$form = clean_input($data['form']);}
		else {$form = NULL;}

		if (!empty($data['eval'])) {$eval = clean_input($data['eval']);}
		else {$eval = NULL;}

		if (!empty($data['dev'])) {$dev = clean_input($data['dev']);}
		else {$dev = NULL;}

		if (!empty($data['market'])) {$market = clean_input($data['market']);}
		else {$market = NULL;}

		if (!empty($data['patent'])) {$patent = clean_input($data['patent']);}
		else {$patent = NULL;}

		if (!empty($data['pri'])) {$pri = clean_input($data['pri']);}
		else {$pri = NULL;}

		if (!empty($data['sec'])) {$sec = clean_input($data['sec']);}
		else {$sec = NULL;}

		if (!empty($data['melt'])) {$melt = clean_input($data['melt']);}
		else {$melt = NULL;}

		if (!empty($data['water'])) {$water = clean_input($data['water']);}
		else {$water = NULL;}

		if (!empty($data['hcl'])) {$hcl = clean_input($data['hcl']);}
		else {$hcl = NULL;}

		if (!empty($data['note'])) {$note = clean_input($data['note']);}
		else {$note = NULL;}

		// No errors -> valid user input -> DB
		if (!$error_flag) {

			// Retrieve current data from DB
			$result = sql_get_api_data($link, $api_id);

			if ($result->num_rows == 1) { # API found

				$data_cur = $result->fetch_assoc();

				// Check if there is any modification
				if (($name == $data_cur['name'])
					and ($form == $data_cur['form'])
					and ($eval == $data_cur['eval'])
					and ($dev == $data_cur['dev'])
					and ($market == $data_cur['market'])
					and ($patent == $data_cur['patent'])
					and ($pri == $data_cur['pri'])
					and ($sec == $data_cur['sec'])
					and ($melt == $data_cur['melt'])
					and ($water == $data_cur['water'])
					and ($hcl == $data_cur['hcl'])
					and ($note == $data_cur['note'])) {$change = FALSE;}
				else {$change = TRUE;}

				if ($change) { # Data changed -> UPDATE

					if (sql_update_api($link, $api_id, $name, $form, $eval, $dev,
														$market, $patent, $pri, $sec, $melt, $water,
														$hcl, $note, $_SESSION['USER_NAME'])) {

						// Successfully inserted
						$response['text'] = 'API módosítva';
					} else {
						$response['text'] = 'Nem sikerült módosítani az API-t';
						$error_flag = TRUE;
					}
				}
			}
		}
	}

	// Product
	if ($selector == 'drug') {

		// Sanitizing user inputs
		if (empty($data['drug_id']) or (intval($data['drug_id']) <= 0)) {
			$response['text'] = 'Gyógyszer ID hiányzik';
			$error_flag = TRUE;
		} elseif (empty($data['name']) or (clean_input($data['name']) == '')) {
			$response['text'] = 'Név hiányzik';
			$error_flag = TRUE;
		} else {
			$drug_id = intval($data['drug_id']);
			$name = clean_input($data['name']);
		}

		if (!empty($data['name_alt'])) {$name_alt = clean_input($data['name_alt']);}
		else {$name_alt = NULL;}

		if (!empty($data['dosage'])) {$dosage = clean_input($data['dosage']);}
		else {$dosage = NULL;}

		if (!empty($data['crystall'])) {$crystall = clean_input($data['crystall']);}
		else {$crystall = NULL;}

		if (!empty($data['particle'])) {$particle = clean_input($data['particle']);}
		else {$particle = NULL;}

		if (!empty($data['dose_free'])) {$dose_free = clean_input($data['dose_free']);}
		else {$dose_free = NULL;}

		if (!empty($data['dose_day'])) {$dose_day = clean_input($data['dose_day']);}
		else {$dose_day = NULL;}

		if (!empty($data['admin'])) {$admin = clean_input($data['admin']);}
		else {$admin = NULL;}

		if (!empty($data['note'])) {$note = clean_input($data['note']);}
		else {$note = NULL;}

		// No errors -> valid user input -> DB
		if (!$error_flag) {

			// Retrieve current data from DB
			$result = sql_get_drug_product_data($link, $drug_id);

			if ($result->num_rows == 1) { # Drug product found

				$data_cur = $result->fetch_assoc();

				// Check if there is any modification
				if (($name == $data_cur['name'])
					and ($name_alt == $data_cur['name_alt'])
					and ($dosage == $data_cur['dosage'])
					and ($crystall == $data_cur['crystall'])
					and ($particle == $data_cur['particle'])
					and ($dose_free == $data_cur['dose_free'])
					and ($dose_day == $data_cur['dose_day'])
					and ($admin == $data_cur['admin'])
					and ($note == $data_cur['note'])) {$change = FALSE;}
				else {$change = TRUE;}

				if ($change) { # Data changed -> UPDATE

					if (sql_update_drug_product($link, $drug_id, $name, $name_alt, $dosage,
																			$crystall, $particle, $dose_free, $dose_day,
																			$admin, $note, $_SESSION['USER_NAME'])) {

						// Successfully inserted
						$response['text'] = 'Gyógyszer módosítva';
					} else {
						$response['text'] = 'Nem sikerült módosítani a gyógyszert';
						$error_flag = TRUE;
					}
				}
			}
		}
	}

	// Literature
	if ($selector == 'literature') {

		// Sanitizing user inputs
		if (empty($data['literature_id']) or (intval($data['literature_id']) <= 0)) {
			$response['text'] = 'Irodalom ID hiányzik';
			$error_flag = TRUE;
		} elseif (empty($data['title']) or (clean_input($data['title']) == '')) {
			$response['text'] = 'Cím hiányzik';
			$error_flag = TRUE;
		} elseif (empty($data['author']) or (clean_input($data['author']) == '')) {
			$response['text'] = 'Szerző hiányzik';
			$error_flag = TRUE;
		} elseif (empty($data['year']) or (clean_input($data['year']) == '')) {
			$response['text'] = 'Év hiányzik';
			$error_flag = TRUE;
		} else {
			$literature_id = intval($data['literature_id']);
			$title = clean_input($data['title']);
			$author = clean_input($data['author']);
			$year = clean_input($data['year']);
		}

		if (!empty($data['journal'])) {$journal = clean_input($data['journal']);}
		else {$journal = NULL;}

		if (!empty($data['note'])) {$note = clean_input($data['note']);}
		else {$note = NULL;}

		// No errors -> valid user input -> DB
		if (!$error_flag) {

			// Retrieve current data from DB
			$result = sql_get_literature_data($link, $literature_id);

			if ($result->num_rows == 1) { # Literature found

				$data_cur = $result->fetch_assoc();

				// Check if there is any modification
				if (($title == $data_cur['title'])
					and ($author == $data_cur['author'])
					and ($year == $data_cur['year'])
					and ($journal == $data_cur['journal'])
					and ($note == $data_cur['note'])) {$change = FALSE;}
				else {$change = TRUE;}

				if ($change) { # Data changed -> UPDATE

					if (sql_update_literature($link, $literature_id, $title, $author, $year,
																		$journal, $note, $_SESSION['USER_NAME'])) {

						// Successfully inserted
						$response['text'] = 'Irodalom módosítva';
					} else {
						$response['text'] = 'Nem sikerült módosítani az irodalmat';
						$error_flag = TRUE;
					}
				}
			}
		}
	}

	// PK
	if ($selector == 'pk') {

		// Sanitizing user inputs
		if (empty($data['pk_id']) or (intval($data['pk_id']) <= 0)) {
			$response['text'] = 'PK ID hiányzik';
			$error_flag = TRUE;
		} elseif (empty($data['pk_attr']) or (intval($data['pk_attr']) <= 0)) {
			$response['text'] = 'PK tulajdonság hiányzik';
			$error_flag = TRUE;
		} elseif (empty($data['value']) or (clean_input($data['value']) == '')) {
			$response['text'] = 'Érték hiányzik';
			$error_flag = TRUE;
		} else {
			$pk_id = intval($data['pk_id']);
			$pk_attr = intval($data['pk_attr']);
			$value = clean_input($data['value']);
		}

		if (!empty($data['note'])) {$note = clean_input($data['note']);}
		else {$note = NULL;}

		// No errors -> valid user input -> DB
		if (!$error_flag) {

			// Retrieve current data from DB
			$result = sql_get_pk_data($link, $pk_id);

			if ($result->num_rows == 1) { # PK found

				$data_cur = $result->fetch_assoc();

				// Check if there is any modification
				if (($pk_attr == $data_cur['pk_attr'])
					and ($value == $data_cur['value'])
					and ($note == $data_cur['note'])) {$change = FALSE;}
				else {$change = TRUE;}

				if ($change) { # Data changed -> UPDATE

					if (sql_update_pk_data($link, $pk_id, $pk_attr, $value,
																	$note, $_SESSION['USER_NAME'])) {

						// Successfully inserted
						$response['text'] = 'PK tulajdonság módosítva';
					} else {
						$response['text'] = 'Nem sikerült módosítani a PK tulajdonságot';
						$error_flag = TRUE;
					}
				}
			}
		}
	}

	// Manfac
	if ($selector == 'manfac') {

		do {

			// Sanitizing user inputs
			if (empty($data['manfac_id']) or (intval($data['manfac_id']) < 1)) {
				$response['text'] = 'Gyártó ID hiányzik';
				$error_flag = TRUE;
				break;
			}

			if (empty($data['name']) or (clean_input($data['name']) == '')) {
				$response['text'] = 'Név hiányzik';
				$error_flag = TRUE;
				break;
			}

			$manfac_id = intval($data['manfac_id']);
			$name = clean_input($data['name']);

			if (!empty($data['is_freq'])) {$is_freq = intval($data['is_freq']);}
			else {$is_freq = 0;}

			// Retrieve current data from DB
			$result = sql_get_manfac_data($link, $manfac_id);

			if ($result->num_rows != 1) {break;}

			$data_cur = $result->fetch_assoc();

			// Check if there is any modification
			if (($name == $data_cur['name'])
				and ($is_freq == $data_cur['is_frequent'])) {break;}

			// Check for duplicate
			$duplicate = sql_check_manfac($link, $name);
			if ($duplicate and $duplicate != $manfac_id) { # There is a duplicate and is not self

				$response['text'] = 'Már van ilyen gyártó';
				$error_flag = TRUE;
				break;
			}

			// Update
			if (sql_update_manfac_data($link, $manfac_id, $name, $is_freq, $_SESSION['USER_NAME'])) {

				// Successfully inserted
				$response['text'] = 'Gyártó módosítva';
			} else {
				$response['text'] = 'Nem sikerült módosítani a gyártót';
				$error_flag = TRUE;
				break;
			}
		} while (FALSE);
	}

	// Lab
	if ($selector == 'lab') {

		do {

			// Sanitizing user inputs
			if (empty($data['lab_id']) or (intval($data['lab_id']) < 1)) {
				$response['text'] = 'Labor ID hiányzik';
				$error_flag = TRUE;
				break;
			}

			if (empty($data['name']) or (clean_input($data['name']) == '')) {
				$response['text'] = 'Név hiányzik';
				$error_flag = TRUE;
				break;
			}

			$lab_id = intval($data['lab_id']);
			$name = clean_input($data['name']);

			// Retrieve current data from DB
			$result = sql_get_lab_data($link, $lab_id);

			if ($result->num_rows != 1) {break;}

			$data_cur = $result->fetch_assoc();

			// Check if there is any modification
			if ($name == $data_cur['name']) {break;}

			// Check for duplicate
			$duplicate = sql_check_lab($link, $name);
			if ($duplicate and $duplicate != $lab_id) { # There is a duplicate and is not self

				$response['text'] = 'Már van ilyen labor';
				$error_flag = TRUE;
				break;
			}

			// Update
			if (sql_update_lab_data($link, $lab_id, $name, $_SESSION['USER_NAME'])) {

				// Successfully inserted
				$response['text'] = 'Labor módosítva';
			} else {
				$response['text'] = 'Nem sikerült módosítani a labort';
				$error_flag = TRUE;
				break;
			}
		} while (FALSE);
	}

	// Place
	if ($selector == 'place') {

		do {

			// Sanitizing user inputs
			if (empty($data['place_id']) or (intval($data['place_id']) < 1)) {
				$response['text'] = 'Hely ID hiányzik';
				$error_flag = TRUE;
				break;
			}

			if (empty($data['name']) or (clean_input($data['name']) == '')) {
				$response['text'] = 'Név hiányzik';
				$error_flag = TRUE;
				break;
			}

			$place_id = intval($data['place_id']);
			$name = clean_input($data['name']);

			// Retrieve current data from DB
			$result = sql_get_place_data($link, $place_id);

			if ($result->num_rows != 1) {break;}

			$data_cur = $result->fetch_assoc();

			// Check if there is any modification
			if ($name == $data_cur['name']) {break;}

			// Check for duplicate
			$duplicate = sql_check_place($link, $name);
			if ($duplicate and $duplicate != $place_id) { # There is a duplicate and is not self

				$response['text'] = 'Már van ilyen hely';
				$error_flag = TRUE;
				break;
			}

			// Update
			if (sql_update_place_data($link, $place_id, $name, $_SESSION['USER_NAME'])) {

				// Successfully inserted
				$response['text'] = 'Hely módosítva';
			} else {
				$response['text'] = 'Nem sikerült módosítani a helyet';
				$error_flag = TRUE;
				break;
			}
		} while (FALSE);
	}

	// Sub
	if ($selector == 'sub') {

		do {

			// Sanitizing user inputs
			if (empty($data['sub_id']) or (intval($data['sub_id']) < 1)) {
				$response['text'] = 'Alhely ID hiányzik';
				$error_flag = TRUE;
				break;
			}

			if (empty($data['name']) or (clean_input($data['name']) == '')) {
				$response['text'] = 'Név hiányzik';
				$error_flag = TRUE;
				break;
			}

			$sub_id = intval($data['sub_id']);
			$name = clean_input($data['name']);

			// Retrieve current data from DB
			$result = sql_get_sub_data($link, $sub_id);

			if ($result->num_rows != 1) {break;}

			$data_cur = $result->fetch_assoc();

			// Check if there is any modification
			if ($name == $data_cur['name']) {break;}

			// Check for duplicate
			$duplicate = sql_check_sub($link, $name);
			if ($duplicate and $duplicate != $sub_id) { # There is a duplicate and is not self

				$response['text'] = 'Már van ilyen alhely';
				$error_flag = TRUE;
				break;
			}

			// Update
			if (sql_update_sub_data($link, $sub_id, $name, $_SESSION['USER_NAME'])) {

				// Successfully inserted
				$response['text'] = 'Alhely módosítva';
			} else {
				$response['text'] = 'Nem sikerült módosítani az alhelyet';
				$error_flag = TRUE;
				break;
			}
		} while (FALSE);
	}

	// Location
	if ($selector == 'location') {

		do {

			// Sanitizing user inputs
			if (empty($data['loc_id']) or (intval($data['loc_id']) < 1)) {
				$response['text'] = 'Lokáció ID hiányzik';
				$error_flag = TRUE;
				break;
			}

			if (empty($data['lab_id']) or (intval($data['lab_id']) < 1)) {
				$response['text'] = 'Labor hiányzik';
				$error_flag = TRUE;
				break;
			}

			if (empty($data['place_id']) or (intval($data['place_id']) < 1)) {
				$response['text'] = 'Hely hiányzik';
				$error_flag = TRUE;
				break;
			}

			if (empty($data['sub_id']) or (intval($data['sub_id']) < 1)) {
				$response['text'] = 'Alhely hiányzik';
				$error_flag = TRUE;
				break;
			}

			$location_id = intval($data['loc_id']);
			$lab_id = intval($data['lab_id']);
			$place_id = intval($data['place_id']);
			$sub_id = intval($data['sub_id']);

			// Retrieve current data from DB
			$result = sql_get_location_data($link, $location_id);

			if ($result->num_rows != 1) {break;}

			$data_cur = $result->fetch_assoc();

			// Check if there is any modification
			if (($lab_id == $data_cur['lab_id'])
					and ($place_id == $data_cur['place_id'])
					and ($sub_id == $data_cur['sub_id'])) {break;}

			// Check for duplicate
			$duplicate = sql_check_location($link, $lab_id, $place_id, $sub_id);
			if ($duplicate and $duplicate != $location_id) { # There is a duplicate and is not self

				$response['text'] = 'Már van ilyen lokáció';
				$error_flag = TRUE;
				break;
			}

			// Update
			if (sql_update_location_data($link, $location_id, $lab_id, $place_id, $sub_id, $_SESSION['USER_NAME'])) {

				// Successfully inserted
				$response['text'] = 'Lokáció módosítva';
			} else {
				$response['text'] = 'Nem sikerült módosítani az lokációt';
				$error_flag = TRUE;
				break;
			}
		} while (FALSE);
	}

	// User
	if ($selector == 'user') {

		do {

			// Sanitizing user inputs
			if (empty($data['uid']) or (intval($data['uid']) < 1)) {
				$response['text'] = 'Felhasználó ID hiányzik';
				$error_flag = TRUE;
				break;
			}

			if (!isset($data['chemical']) or (intval($data['chemical']) < 0)) {
				$response['text'] = 'Vegyszerleltár szint hiányzik';
				$error_flag = TRUE;
				break;
			}

			if (!isset($data['api']) or (intval($data['api']) < 0)) {
				$response['text'] = 'API-leltár szint hiányzik';
				$error_flag = TRUE;
				break;
			}

			if (!isset($data['solvent']) or (intval($data['solvent']) < 0)) {
				$response['text'] = 'Oldószerleltár szint hiányzik';
				$error_flag = TRUE;
				break;
			}

			$user_id = intval($data['uid']);
			$chemical = intval($data['chemical']);
			$api = intval($data['api']);
			$solvent = intval($data['solvent']);

			// Retrieve current data from DB
			$result = sql_get_user_data($link, $user_id);

			if ($result->num_rows != 1) {break;}

			$data_cur = $result->fetch_assoc();

			// Check if there is any modification
			if (($chemical == $data_cur['chemical'])
					and ($api == $data_cur['api'])
					and ($solvent == $data_cur['solvent'])) {break;}

			// Update
			if (sql_update_user_data($link, $user_id, $chemical, $api, $solvent, $_SESSION['USER_NAME'])) {

				// Successfully inserted
				$response['text'] = 'Jogok módosítva';
			} else {
				$response['text'] = 'Nem sikerült módosítani a jogokat';
				$error_flag = TRUE;
				break;
			}
		} while (FALSE);
	}

	// Set response flag
	$response['flag'] = ($error_flag ? 'neg' : 'pos');

	// Encode response to JSON
	$response = json_encode($response, JSON_UNESCAPED_UNICODE);
	echo $response;
}
} catch (leltar_exception $e) {$e->error_handling();}?>