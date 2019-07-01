<?php
/**
 * EXECUTE
 *
 * Insert data to DB
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
	if ($selector == 'compound') {
		security_check('leltar', 1);
	} elseif (in_array($selector, ['batch', 'pack'])) {
		security_check('leltar', 2);
	} elseif (in_array($selector, ['api', 'drug', 'pk'])) {
		security_check('api', 2);
	} else {
		exit;
	}

	// Compound
	if ($selector == 'compound') {

		// Sanitizing user inputs
		if (empty($data['name']) or (clean_input($data['name']) == '')) {
			$response['text'] = 'Név hiányzik';
			$error_flag = TRUE;
		} elseif (empty($data['subcat_id']) or (intval($data['subcat_id']) <= 0)) {
			$response['text'] = 'Kategória hiányzik';
			$error_flag = TRUE;
		} else {
			$name = clean_input($data['name']);
			$subcategory_id = intval($data['subcat_id']);
		}

		if (!empty($data['name_alt'])) {$name_alt = clean_input($data['name_alt']);}
		else {$name_alt = NULL;}

		if (!empty($data['abbrev'])) {$abbrev = clean_input($data['abbrev']);}
		else {$abbrev = NULL;}

		if (!empty($data['cas'])) {

			$cas = clean_input($data['cas']);

			// Check CAS
			if (!check_cas($cas)) {
				$response['text'] = 'Hibás CAS szám';
				$error_flag = TRUE;
			}
		} else {
			$cas = NULL;
		}

		if (!empty($data['oeb'])) {$oeb = intval($data['oeb']);}
		else {$oeb = NULL;}

		if (!empty($data['melt'])) {$melt = intval($data['melt']);}
		else {$melt = NULL;}

		if (!empty($data['note'])) {$note = clean_input($data['note']);}
		else {$note = NULL;}

		// Init
		$chem_form = NULL;
		$mol_weight = NULL;
		$smiles = NULL;
		$iupac = NULL;
		$chem_name = NULL;

		// No errors -> valid user input -> DB
		if (!$error_flag) {

			// Check for duplicate
			if (sql_check_compound($link, $name)) { # There is a duplicate

				$response['text'] = 'Már van ilyen vegyszer';
				$error_flag = TRUE;

			} else { # No duplicate -> INSERT into DB

				if ($cas != NULL) { # If there is a CAS# supplied

					// Get compound info from cactus
					$info = cactus_get_compound_info($cas, 'formula');
					if ($info) {$chem_form = $info;}

					$info = cactus_get_compound_info($cas, 'mw');
					if ($info) {$mol_weight = $info;}

					$info = cactus_get_compound_info($cas, 'smiles');
					if ($info) {$smiles = $info;}

					$info = cactus_get_compound_info($cas, 'iupac_name');
					if ($info) {$iupac = $info;}
				}

				if (sql_insert_compound($link, $name, $name_alt, $abbrev, $chem_name,
																$iupac, $chem_form, $cas, $smiles, $subcategory_id,
																$oeb, $mol_weight, $melt, $note, $_SESSION['USER_NAME'])) {

					// Successfully inserted
					$response['text'] = 'Vegyszer hozzáadva';
				} else {
					$response['text'] = 'Nem sikerült hozzáadni a vegyszert';
					$error_flag = TRUE;
				}
			}
		}
	}

	// Batch
	if ($selector == 'batch') {

		// Sanitizing user inputs
		if (empty($data['comp_id']) or (intval($data['comp_id']) <= 0)) {
			$response['text'] = 'Vegyszer ID hiányzik';
			$error_flag = TRUE;
		} elseif (empty($data['manfac']) or (intval($data['manfac']) <= 0)) {
			$response['text'] = 'Gyártó hiányzik';
			$error_flag = TRUE;
		} elseif (empty($data['name']) or (clean_input($data['name']) == '')) {
			$response['text'] = 'Név hiányzik';
			$error_flag = TRUE;
		} elseif (empty($data['lot']) or (clean_input($data['lot']) == '')) {
			$response['text'] = 'LOT# hiányzik';
			$error_flag = TRUE;
		} elseif (empty($data['arr']) or (clean_input($data['arr']) == '')) {
			$response['text'] = 'Érkezési dátum hiányzik';
			$error_flag = TRUE;
		} else {
			$comp_id = intval($data['comp_id']);
			$manfac = intval($data['manfac']);
			$name = clean_input($data['name']);
			$lot = clean_input($data['lot']);
			$arr = clean_input($data['arr']);
		}

		if (!empty($data['open'])) {$open = clean_input($data['open']);}
		else {$open = NULL;}

		if (!empty($data['exp'])) {$exp = clean_input($data['exp']);}
		else {$exp = NULL;}

		if (!empty($data['note'])) {$note = clean_input($data['note']);}
		else {$note = NULL;}

		// No errors -> valid user input -> DB
		if (!$error_flag) {

			// Check for duplicate
			if (sql_check_batch($link, $comp_id, $name, $manfac, $lot)) { # There is a duplicate

				$response['text'] = 'Már van ilyen termék';
				$error_flag = TRUE;

			} else { # No duplicate -> INSERT into DB

				if (sql_insert_batch($link, $comp_id, $manfac, $name, $lot, $arr,
														$open, $exp, $note, $_SESSION['USER_NAME'])) {

					// Successfully inserted
					$response['text'] = 'Termék hozzáadva';
				} else {
					$response['text'] = 'Nem sikerült hozzáadni a terméket';
					$error_flag = TRUE;
				}
			}
		}
	}

	// Pack
	if ($selector == 'pack') {

		// Sanitizing user inputs
		if (empty($data['batch_id']) or (intval($data['batch_id']) <= 0)) {
			$response['text'] = 'Termék ID hiányzik';
			$error_flag = TRUE;
		} elseif (empty($data['loc_id']) or (intval($data['loc_id']) <= 0)) {
			$response['text'] = 'Helyzet hiányzik';
			$error_flag = TRUE;
		} elseif (empty($data['size']) or (clean_input($data['size']) == '')) {
			$response['text'] = 'Kiszerelés hiányzik';
			$error_flag = TRUE;
		} else {
			$batch_id = intval($data['batch_id']);
			$loc_id = intval($data['loc_id']);
			$size = clean_input($data['size']);
		}

		if (!empty($data['weight'])) {$weight = clean_input($data['weight']);}
		else {$weight = NULL;}

		if (!empty($data['note'])) {$note = clean_input($data['note']);}
		else {$note = NULL;}

		if (!empty($data['is_orig'])) {$is_orig = clean_input($data['is_orig']);}
		else {$is_orig = 0;}

		// No errors -> valid user input -> DB
		if (!$error_flag) {

			if (sql_insert_pack($link, $batch_id, $loc_id, $is_orig,
													$size, $weight, $note, $_SESSION['USER_NAME'])) { # Successfully added to DB

				// Get pack ID for inserted pack
				$pack_id = mysqli_insert_id($link);

				// Error if there was problem with SQL
				if ($pack_id == 0) {throw new leltar_exception('sql_fail',1);}

				// pack ID -> fixed length, leading zeros CODE
				$code = sprintf('%06d', $pack_id);

				// Convert UPC-E code to UPC-A
				$upc_a = upc_e_to_upc_a($code);

				// Calculate check digit
				$check_digit = calc_check_digit($upc_a);

				// Produce final UPC-E barcode

				// Put number system digit (0) and check digit
				$upc_e = '0'.$code.$check_digit;

				// Put barcode into DB
				if (sql_update_barcode($link, $pack_id, $upc_e, $_SESSION['USER_NAME'])) { # Successful

					$response['text'] = 'Kiszerelés hozzáadva';

				} else {
					$response['text'] = 'Vonalkód generálása nem sikerült';
					$error_flag = TRUE;
				}
			} else {
				$response['text'] = 'Nem sikerült hozzáadni az adatbázishoz';
				$error_flag = TRUE;
			}
		}
	}

	// API
	if ($selector == 'api') {

		// Sanitizing user inputs
		if (empty($data['name']) or (clean_input($data['name']) == '')) {
			$response['text'] = 'Név hiányzik';
			$error_flag = TRUE;
		} else {
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

			// Check for duplicate
			if (sql_check_api($link, $name, $form)) { # There is a duplicate

				$response['text'] = 'Már van ilyen API';
				$error_flag = TRUE;

			} else { # No duplicate -> INSERT into DB

				if (sql_insert_api($link, $name, $form, $eval, $dev, $market,
													$patent, $pri, $sec, $melt, $water, $hcl,
													$note, $_SESSION['USER_NAME'])) {

					// Successfully inserted
					$response['text'] = 'API hozzáadva';
				} else {
					$response['text'] = 'Nem sikerült hozzáadni az API-t';
					$error_flag = TRUE;
				}
			}
		}
	}

	// Drug product
	if ($selector == 'drug') {

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

			// Check for duplicate
			if (sql_check_drug_product($link, $api_id, $name)) { # There is a duplicate

				$response['text'] = 'Már van ilyen gyógyszer';
				$error_flag = TRUE;

			} else { # No duplicate -> INSERT into DB

				if (sql_insert_drug_product($link, $api_id, $name, $name_alt, $dosage,
																		$crystall, $particle, $dose_free, $dose_day,
																		$admin, $note, $_SESSION['USER_NAME'])) {

					// Successfully inserted
					$response['text'] = 'Sikeresen hozzáadva';
				} else {
					$response['text'] = 'Nem sikerült hozzáadni';
					$error_flag = TRUE;
				}
			}
		}
	}

	// PK
	if ($selector == 'pk') {

		// Sanitizing user inputs
		if (empty($data['drug_id']) or (intval($data['drug_id']) <= 0)) {
			$response['text'] = 'Gyógyszer ID hiányzik';
			$error_flag = TRUE;
		} elseif (empty($data['attr_id']) or (intval($data['attr_id']) <= 0)) {
			$response['text'] = 'PK tulajdonság hiányzik';
			$error_flag = TRUE;
		} elseif (empty($data['value']) or (clean_input($data['value']) == '')) {
			$response['text'] = 'Érték hiányzik';
			$error_flag = TRUE;
		} else {
			$drug_id = intval($data['drug_id']);
			$attr_id = intval($data['attr_id']);
			$value = clean_input($data['value']);
		}

		if (!empty($data['note'])) {$note = clean_input($data['note']);}
		else {$note = NULL;}

		// No errors -> valid user input -> DB
		if (!$error_flag) {

			// Check for duplicate
			if (sql_check_pk_data($link, $drug_id, $attr_id)) { # There is a duplicate

				$response['text'] = 'Már van ilyen PK tulajdonság';
				$error_flag = TRUE;

			} else { # No duplicate -> INSERT into DB

				if (sql_insert_pk_data($link, $drug_id, $attr_id, $value,
															$note, $_SESSION['USER_NAME'])) {

					// Successfully inserted
					$response['text'] = 'Sikeresen hozzáadva';
				} else {
					$response['text'] = 'Nem sikerült hozzáadni';
					$error_flag = TRUE;
				}
			}
		}
	}

	// Set response flag
	$response['flag'] = ($error_flag ? 'neg' : 'pos');

	// Encode response to JSON
	$response = json_encode($response, JSON_UNESCAPED_UNICODE);
	echo $response;
}
} catch (leltar_exception $e) {$e->error_handling();}?>