<?php
/**
 * JavaScript event handlers
 *********************************************************/

/**
 * Returns JavaScript code
 *
 * @param		string		$select
 * @param		array			$p			optional parameter list
 *
 * @return	string		JS code
 */
function js($select, array $p = NULL) {

	$retr = 'onclick="retrieveData(\'exec/retrieve.php?q=';
	$prevent = 'onsubmit="event.preventDefault();';

	switch ($select) {

		case 'get':
			$js = $retr;
			break;

		case 'index_drop':
			$js = $retr;
			break;

		case 'submit':
			$js = $prevent.'submitForm(this, \'msg_center\', retrieveData, \'exec/retrieve.php?q=';
			break;

		case 'upload':
			$js = $prevent.'submitFormUpload(this, \'msg_center\', retrieveData, \'exec/retrieve.php?q=';
			break;

		case 'menu':
			$js = $retr;
			break;

		default:
			return FALSE;
	}

	if ($select == 'index_drop') {
		$js .= $p[0].'\', \''.$p[1].'\');';
		$js .= 'dropDown(\''.$p[1].'\')"';
	} elseif ($select == 'menu') {
		$js .= $p[0].'\', \'index\');';
		$js .= 'closeAllOpened()"';
	} else {
		$js .= $p[0].'\', \''.$p[1].'\')"';
	}

	return $js;
}

/**
 * Returns JavaScript code
 *
 * @param		string		$select
 * @param		array			$parameters	optional parameter list
 *
 * @return	string		JS code
 */
function js_get_form($select, array $parameters = NULL) {

	$js = 'onclick="retrieveData(\'exec/retrieve_form.php?q=';
	$js .= $select;

	if (is_array($parameters)) {
		foreach ($parameters as $p) {
			$js .= '&'.$p;
		}
	}

	$js .= '\', \'popup\')"';

	return $js;
}

/**
 * Returns JavaScript code
 *
 * @param		string		$select
 * @param		array			$p	optional parameter list
 *
 * @return	string		JS code
 */
function js_spec($select, array $p = NULL) {

	$retr = 'onclick="retrieveData(\'exec/';
	$exec = 'onclick="executeServer(\'exec/';
	$prevent = 'onsubmit="event.preventDefault();';
	$submit = 'submitForm(this, \'msg_center\',';
	$close = 'closeAllOpened();';

	switch ($select) {

	// Dropdown
	case 'drop':
		$js = 'onclick="dropDown(\''.$p[0].'\')"';
		break;

	// Retrieve data
	case 'compound':
		$js = $retr.'retrieve.php?q=compound_det&cid='.$p[0].'\', \'popup\', getSmiles, \'compID\', '.$p[0].')"';
		break;

	case 'barcode':
		$js = $retr.'search.php?q='.$p[0].'\', \'index\', getSmiles, \'barcode\', \''.$p[0].'\');';
		$js .= $close.'"';
		break;

	// Get compound info from CAS
	case 'calc_cas':
		$js = 'onclick="event.preventDefault();getCompoundInfo()"';
		break;

	// Execute
	case 'login':
		$js = $prevent.$submit.'redirect, \'index.php\')"';
		break;

	case 'logout':
		$js = $exec.'logout.php\', \'msg_center\', redirect, \'index.php\')"';
		break;

	case 'inactivate':
		$js = 'onclick="inactivate('.$p[0].','.$p[1].',\''.$p[2].'\')"';
		break;

	// Forms
	case 'search_form':
		$js = $prevent.$submit.'retrieveData, \'exec/search.php?q='.$p[0].'\', \'index\', getSmiles, \'barcode\', \''.$p[0].'\')"';
		break;

	case 'search':
		$js = $prevent.'retrieveData(\'exec/search.php?q=\' + this.q.value, \'index\', getSmiles, \'barcode\', this.q.value);';
		$js .= $close.'"';
		break;

	// Random
	case 'erase_popup':
		$js = 'onclick="erasePopup()"';
		break;

	// Change history
	case 'chng_hist':
		$js = 'onclick="getChangeHistorySummary('.$p[0].', \''.$p[1].'\')"';
		break;

	case 'chng_hist_det':
		$js = 'onclick="getChangeHistoryDetails('.$p[0].',\''.$p[1].'\','.$p[2].')"';
		break;

	// Default
	default:
		return FALSE;
	}

	return $js;
}
?>