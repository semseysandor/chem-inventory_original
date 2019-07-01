<?php
/**
 * EXECUTE
 *
 * Upload files
 *********************************************************/

$config = require('../default.php');

// Init session
session_start();

// Abort script if session not loaded
if (session_status() != PHP_SESSION_ACTIVE) {exit;}

try {

if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['selector'])) {

	// Init response
	$response = [];

	$selector = $_POST['selector'];

	// Security check
	if (in_array($selector, ['coa', 'msds'])) {
		security_check('leltar', 1);
	} elseif (in_array($selector, ['literature', 'new_literature'])) {
		security_check('api', 2);
	} else {
		exit;
	}

	// Set file handler
	if ($selector == 'coa') {

		if (isset($_FILES['coa#0'])) {
			$file = $_FILES['coa#0'];
		} else {
			$message['text'] = 'Nincs fájl kiválasztva';
			$message['flag'] = 'neg';
			$response []= $message;
			$error_flag = TRUE;
		}

	} elseif ($selector == 'msds') {

		if (isset($_FILES['msds#0'])) {
			$file = $_FILES['msds#0'];
		} else {
			$message['text'] = 'Nincs fájl kiválasztva';
			$message['flag'] = 'neg';
			$response []= $message;
			$error_flag = TRUE;
		}

	} elseif (in_array($selector, ['literature', 'new_literature'])) {

		if (isset($_FILES['literature#0'])) {
			$file = $_FILES['literature#0'];
		} else {
			$message['text'] = 'Nincs fájl kiválasztva';
			$message['flag'] = 'neg';
			$response []= $message;
			$error_flag = TRUE;
		}
	}

	// Sanitizing user inputs
	if (in_array($selector, ['coa', 'msds'])) {

		if (empty($_POST['batch_id']) or (intval($_POST['batch_id']) <= 0)) {
			$response['text'] = 'Termék ID hiányzik';
			$error_flag = TRUE;
		} else {
			$batch_id = intval($_POST['batch_id']);
		}

	} elseif ($selector == 'literature') {

		if (empty($_POST['api_id']) or (intval($_POST['api_id']) <= 0)) {
			$message['text'] = 'API ID hiányzik';
			$message['flag'] = 'neg';
			$response []= $message;
			$error_flag = TRUE;
		} elseif (empty($_POST['title']) or (clean_input($_POST['title']) == '')) {
			$message['text'] = 'Cím hiányzik';
			$message['flag'] = 'neg';
			$response []= $message;
			$error_flag = TRUE;
		} elseif (empty($_POST['author']) or (clean_input($_POST['author']) == '')) {
			$message['text'] = 'Szerző hiányzik';
			$message['flag'] = 'neg';
			$response []= $message;
			$error_flag = TRUE;
		} elseif (empty($_POST['year']) or (clean_input($_POST['year']) == '')) {
			$message['text'] = 'Év hiányzik';
			$message['flag'] = 'neg';
			$response []= $message;
			$error_flag = TRUE;
		} else {
			$api_id = intval($_POST['api_id']);
			$title = clean_input($_POST['title']);
			$author = clean_input($_POST['author']);
			$year = clean_input($_POST['year']);
		}

		if (!empty($_POST['journal'])) {$journal = clean_input($_POST['journal']);}
		else {$journal = NULL;}

		if (!empty($_POST['note'])) {$note = clean_input($_POST['note']);}
		else {$note = NULL;}

	} elseif ($selector == 'new_literature') {

		if (empty($_POST['literature_id']) or (intval($_POST['literature_id']) <= 0)) {
			$message['text'] = 'Irodalom ID hiányzik';
			$message['flag'] = 'neg';
			$response []= $message;
			$error_flag = TRUE;
		} else {
			$literature_id = intval($_POST['literature_id']);
		}
	}

	// No errors -> valid user input -> validate uploaded file
	if (!$error_flag) {

		// Allowed file extensions
		$allow_ext = array('pdf','doc','docx');

		// Uploaded file extension
		$ext = pathinfo($file['name'],PATHINFO_EXTENSION);

		if (!(is_uploaded_file($file['tmp_name']))) { # Is really an uploaded file? (not a hack)
			$message['text'] = 'Nincs fájl kiválasztva';
			$message['flag'] = 'neg';
			$response []= $message;
			$error_flag = TRUE;
		} elseif (!(in_array($ext, $allow_ext))) { # Has it an allowed extension?
			$message['text'] = 'Csak pdf, doc vagy docx tölthető fel';
			$message['flag'] = 'neg';
			$response []= $message;
			$error_flag = TRUE;
		} elseif ($file['size'] == 0) { # Not a blank file
			$message['text'] = 'Üres fájl';
			$message['flag'] = 'neg';
			$response []= $message;
			$error_flag = TRUE;
		} elseif ($file['size'] > 5242880) { # Not bigger than 5MB
			$message['text'] = 'Fájl nagyobb mint 5MB';
			$message['flag'] = 'neg';
			$response []= $message;
			$error_flag = TRUE;
		}

		// In case of adding new literature
		if ($selector == 'literature' and !$error_flag) {

			// Check for duplicate
			if (sql_check_literature($link, $api_id, $title, $author, $year)) { # There is a duplicate

				$message['text'] = 'Már van ilyen irodalom';
				$message['flag'] = 'neg';
				$response []= $message;
				$error_flag = TRUE;

			} else { # No duplicate -> INSERT into DB

				if (sql_insert_literature($link, $api_id, $title, $author, $year,
																	$journal, $note, $_SESSION['USER_NAME'])) {

					// Successfully inserted

					// Get literature_id for the new compound
					$literature_id = mysqli_insert_id($link);

					// Error if there was problem with SQL
					if ($literature_id == 0) {throw new leltar_exception('sql_fail',1);}

					$message['text'] = 'Sikeresen hozzáadva';
					$message['flag'] = 'pos';
					$response []= $message;
				} else {
					$message['text'] = 'Nem sikerült hozzáadni';
					$message['flag'] = 'neg';
					$response []= $message;
					$error_flag = TRUE;
				}
			}
		}

		if (!$error_flag) { # File valid

			// Path to save
			if ($selector == 'coa') {
				$target_file = ROOT.'/docs/CoA/CoA_batch_'.$batch_id.'.'.$ext;
			} elseif ($selector == 'msds') {
				$target_file = ROOT.'/docs/MSDS/MSDS_batch_'.$batch_id.'.'.$ext;
			} elseif (in_array($selector, ['literature', 'new_literature'])) {
				$target_file = ROOT.'/docs/api_literature/literature_'.$literature_id.'.'.$ext;
			}

			if ($file['error'] == 0) { # If no error during upload (client side)

				// Search old file
				if ($selector == 'coa') {
					$old_file = search_file('coa', $batch_id);
				} elseif ($selector == 'msds') {
					$old_file = search_file('msds', $batch_id);
				} elseif (in_array($selector, ['literature', 'new_literature'])) {
					$old_file = search_file('literature', $literature_id);
				}

				// If there is a file already uploaded
				if ($old_file) {
					// Delete old file
					unlink(ROOT.'/'.$old_file);
				}

				// Then move new file to permament position
				if (move_uploaded_file($file['tmp_name'], $target_file)) {

					// Success
					$message['text'] = 'Fájl feltöltve';
					$message['flag'] = 'pos';
					$response []= $message;

				} else {

					$message['text'] = 'Nem sikerült a feltöltés (szerver oldali hiba miatt)';
					$message['flag'] = 'neg';
					$response []= $message;
					$error_flag = TRUE;
				}

			} else {
				$message['text'] = 'Nem sikerült a feltöltés (kliens oldali hiba miatt)';
				$message['flag'] = 'neg';
				$response []= $message;
				$error_flag = TRUE;
			}
		}
	}

	// Encode response to JSON
	$response = json_encode($response, JSON_UNESCAPED_UNICODE);
	echo $response;
}
} catch (leltar_exception $e) {$e->error_handling();}?>