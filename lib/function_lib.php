<?php
/**
 * Function library
 *********************************************************/

/**
 * Cleans input string
 *
 * @param		string	$input
 *
 * @return	string
 */
function clean_input($input) {

	$input = trim($input);
	$input = strip_tags($input);
	$input = htmlspecialchars($input);
	$input = stripslashes($input);

	return $input;
}

/**
 * Check if user meets right level
 *
 * @param		string		$domain		right domain
 * @param		int				$level		level to meet
 *
 * @throws	leltar_exception	if user right level not enough
 *
 * @return	bool
 */
function security_check($domain, $level) {

	switch ($domain) {

	case 'leltar':
		if ((!isset($_SESSION['USER_RIGHT_LELTAR'])) or ($_SESSION['USER_RIGHT_LELTAR'] < $level)) {
			throw new leltar_exception('no_right',1);
			return FALSE;
		} else {
			return TRUE;
		}

	case 'api':
		if ((!isset($_SESSION['USER_RIGHT_API'])) or ($_SESSION['USER_RIGHT_API'] < $level)) {
			throw new leltar_exception('no_right',1);
			return FALSE;
		} else {
			return TRUE;
		}

	default:
		throw new leltar_exception('no_right',1);
		return FALSE;
	}
}

/**
 * Get URL param from query
 *
 * @param		string		name of param
 * @param		bool			is required?
 * @param		string		type of param
 *
 * @throws	leltar_exception	if no query present
 * @throws	leltar_exception	if query not valid
 *
 * @return
 *  parameter on success
 */
function get_query($name, $required = TRUE, $type = 'int' ) {

	// Check param
	if (isset($_GET[$name]) and ($_GET[$name] != NULL)) {

		// Get param
		$param = $_GET[$name];

		// Validate param
		if ($type == 'string') { # Parameter is a string

			$param = clean_input($param);

			if ($param != '') {

				return $param;

			} else {

				if ($required) {
					throw new leltar_exception('not_valid_id',1);
				} else {
					return FALSE;
				}
			}

		} elseif ($type == 'int') { # Parameter is an integer

			$param = filter_var($param, FILTER_SANITIZE_NUMBER_INT);
			$param = intval($param);

			if ($param > 0) {

				return $param;

			} else {

				if ($required) {
					throw new leltar_exception('not_valid_id',1);
				} else {
					return FALSE;
				}
			}
		} else {
			return FALSE;
		}
	} else {
		if ($required) {
			throw new leltar_exception('no_id',1);
		} else {
			return FALSE;
		}
	}
}

/**
 * GET page number from URL
 *
 * @param		int		last_page
 *
 * @return	int		current page
 */
function get_page($last_page) {

	if ((isset($_GET['page'])) and ($_GET['page'] != NULL)) { # If page exist in URL

		// Get page number
		$current_page = $_GET['page'];

		// Cleans input & cast to integer
		$current_page = filter_var($current_page, FILTER_SANITIZE_NUMBER_INT);
		$current_page = intval($current_page);

		if ($current_page <= 0) {
			$current_page = 1;
		}
		if ($current_page > $last_page) {
			$current_page = $last_page;
		}
	} else {
		$current_page = 1; # Default
	}

	return $current_page;
}

/**
 * Authenticate with LDAP
 *
 * @param		string	$user
 * @param		string	$password
 *
 * @return	bool
 *	TRUE		on success and SET $_SESSION['USER_NAME']
 */
function authenticate($user, $password) {

	if ((empty($user)) or (empty($password))) {
		return FALSE;
	}

	// LDAP server
	$ldap_host = 		LDAP_HOST;
	$ldap_dn =			LDAP_DN;
	$ldap_usr_dom = LDAP_USR_DOM;

	// Connect to LDAP
	$ldap = ldap_connect($ldap_host);

	if (!$ldap) {
		return FALSE;
	}

	// Configure LDAP params
	ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

	// Verify user and password
	$bind = ldap_bind($ldap, $user.$ldap_usr_dom, $password);

	if ($bind) { # Valid

		session_regenerate_id();

		$filter = '(sAMAccountname='.$user.')';
		$attr = ['cn'];
		$sr = ldap_search($ldap, $ldap_dn, $filter, $attr) or exit('Unable to search LDAP server');
		$entry = ldap_get_entries($ldap, $sr);

		// Set $_SESSION['USER_NAME']
		$_SESSION['USER_NAME'] = $entry[0]['cn'][0];

		ldap_unbind($ldap);

		return TRUE;
	} else { # Not valid
		return FALSE;
	}
}

/**
 * Search user in database
 *
 * @param		mysqli_link	$link
 * @param		string			$user
 *
 * @return	bool
 *	TRUE		if user is in DB
 *
 * @set
 *	$_SESSION['USER_ID']
 *	$_SESSION['USER_RIGHT_LELTAR']
 *	$_SESSION['USER_RIGHT_API']
 */
function search_user($link, $user) {

	// Search for user
	$result = sql_get_user_info($link, $user);

	if ($result->num_rows == 1) { # User is in DB

		$member = $result->fetch_assoc();
		$_SESSION['USER_ID'] = $member['user_id'];
		$_SESSION['USER_RIGHT_LELTAR'] = $member['right_level_leltar'];
		$_SESSION['USER_RIGHT_API'] = $member['right_level_api'];

		return TRUE;
	} else { # User not in DB
		return FALSE;
	}
}

/**
 * Convert UPC-E code to UPC-A
 *
 * @param		string	$code		UPC-E code
 *
 * @return	array		UPC-A code in an array
 */
function upc_e_to_upc_a($code) {

	// String -> array
	$upc_e = str_split($code);

	// Convert UPC-E code to UPC-A
	switch ($upc_e[5]) {
		case '0':
			$upc_a = '0'.$upc_e[0].$upc_e[1].$upc_e[5].'0000'.$upc_e[2].$upc_e[3].$upc_e[4];
			break;
		case '1':
			$upc_a = '0'.$upc_e[0].$upc_e[1].$upc_e[5].'0000'.$upc_e[2].$upc_e[3].$upc_e[4];
			break;
		case '2':
			$upc_a = '0'.$upc_e[0].$upc_e[1].$upc_e[5].'0000'.$upc_e[2].$upc_e[3].$upc_e[4];
			break;
		case '3':
			$upc_a = '0'.$upc_e[0].$upc_e[1].$upc_e[2].'00000'.$upc_e[3].$upc_e[4];
			break;
		case '4':
			$upc_a = '0'.$upc_e[0].$upc_e[1].$upc_e[2].$upc_e[3].'00000'.$upc_e[4];
			break;
		case '5':
			$upc_a = '0'.$upc_e[0].$upc_e[1].$upc_e[2].$upc_e[3].$upc_e[4].'0000'.$upc_e[5];
			break;
		case '6':
			$upc_a = '0'.$upc_e[0].$upc_e[1].$upc_e[2].$upc_e[3].$upc_e[4].'0000'.$upc_e[5];
			break;
		case '7':
			$upc_a = '0'.$upc_e[0].$upc_e[1].$upc_e[2].$upc_e[3].$upc_e[4].'0000'.$upc_e[5];
			break;
		case '8':
			$upc_a = '0'.$upc_e[0].$upc_e[1].$upc_e[2].$upc_e[3].$upc_e[4].'0000'.$upc_e[5];
			break;
		case '9':
			$upc_a = '0'.$upc_e[0].$upc_e[1].$upc_e[2].$upc_e[3].$upc_e[4].'0000'.$upc_e[5];
			break;
	}

	// String -> array
	$upc_a = str_split($upc_a);

	return $upc_a;
}

/**
 * Calculate check digit for UPC-A code
 *
 * @param		array		$upc_a
 *
 * @return	int			check digit
 */
function calc_check_digit($upc_a) {

	$odd_total = $upc_a[0] + $upc_a[2] + $upc_a[4] + $upc_a[6] + $upc_a[8] + $upc_a[10];
	$even_total = $upc_a[1] + $upc_a[3] + $upc_a[5] + $upc_a[7] + $upc_a[9];
	$total = (3 * $odd_total) + $even_total;

	$modulo = $total % 10;
	if ($modulo == 0) {
		$check_digit = 0;
	} else {
		$check_digit = 10 - $modulo;
	}

	return $check_digit;
}

/**
 * Search file
 *
 * @param		string		type of file
 * @param		int				file ID
 *
 * @return
 *	string	link to file on success
 *	FALSE		if no file found
 */
function search_file($type, $id) {

	// Possible extensions
	$pos_ext = ['pdf','doc','docx'];

	// Check all possible extensions
	foreach ($pos_ext as $ext) {

		switch ($type) {

			case 'coa':
				$file = 'docs/CoA/CoA_batch_'.$id.'.'.$ext;
				break;
			case 'msds':
				$file = 'docs/MSDS/MSDS_batch_'.$id.'.'.$ext;
				break;
			case 'literature':
				$file = 'docs/api_literature/literature_'.$id.'.'.$ext;
				break;
			default:
				return FALSE;
		}

		if (is_readable(ROOT.'/'.$file)) { # File found
			return $file;
		}
	}

	// No file was found -> CoA doesn't exist
	return FALSE;
}

/**
 * Get compound info from cactus.nci.nih.gov
 *
 * @param		string	$cas
 * @param		string	$attribute	(formula || mw || smiles || iupac_name)
 *
 * @return
 *	string	on success
 *	FALSE		on fail
 */
function cactus_get_compound_info($cas, $attribute) {

	// Get info from:
	$url='https://cactus.nci.nih.gov/chemical/structure/'.$cas.'/'.$attribute;

	// Curl init
	$ch = curl_init();

	// Curl options
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_URL, $url);

	// HTTP GET
	$data = curl_exec($ch);

	// HTTP response code
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	// Close Curl
	curl_close($ch);

	if ($code == 200) { # If response OK
		return $data;
	} else {
		return FALSE;
	}
}

/**
 * Put changelog HTML
 *
 * @param		int			$record_id
 * @param		string	$table			Referenced table
 */
function change_log($record_id, $table) {
	include(ROOT.'/templates/change_log.php');
}

/**
 * Returns HTML message box
 *
 * @param		string	$style
 * @param		string	$message
 *
 * @return	string	msg box HTML
 */
function message($style, $message = NULL) {

	switch ($style) {

	case '+':
		$style = 'positive';
		$icon = 'fas fa-smile fa-lg';
		break;

	case '0':
		$style = 'neutral';
		$icon = 'far fa-meh fa-lg';
		break;

	case '-':
		$style = 'negative';
		$icon = 'far fa-frown fa-lg';
		break;

	case '#':
		$style = 'error';
		$icon = 'fas fa-exclamation-triangle';
		break;

	default:
		return FALSE;
	}

	return '<div class="message '. $style .'"><i class="'. $icon .'"></i> '. $message .'</div>';
}

/**
 * Check CAS number
 *
 * @param		string		$cas
 *
 * @return	bool
 */
function check_cas($cas) {

	// Check correct format
	if (preg_match('/[0-9]{2,7}-[0-9]{2}-[0-9]/', $cas) == 1) {

		// Init
		$sum = 0;
		$i = 1;

		// Get check digit
		$check = intval(substr($cas, -1));

		// Remove hyphens
		$cas = str_replace('-', '', $cas);

		// Remove check digit
		$cas = substr($cas, 0, -1);

		// Convert to array
		$cas = str_split($cas);

		// Reverse array
		$cas = array_reverse($cas);

		// Calculate check digit
		foreach ($cas as $digit) {
			$sum += $digit * $i;
			$i++;
		}

		// Check if calculated equals given
		if (($sum % 10) == $check) {
			return TRUE;
		}
	}

	return FALSE;
}
?>