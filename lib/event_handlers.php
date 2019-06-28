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

	$cli = 'onclick=';
	$sub = 'onsubmit=';

	$retr = '"retrieveData(\'exec/';
	$exec = '"executeServer(\'exec/';
	$prevent = '"event.preventDefault();';
	$submit = 'submitForm(this, \'msg_center\',';
	$close = 'closeAllOpened();';

	switch ($select) {

	// Live search bar
	case 'live_search':
		$js = 'oninput="liveSearch(this.value)"';
		$js .= ' onkeydown="liveSearchSelect(event)"';
		break;

	// Live search item
	case 'live_search_item':
		$js = $cli.'"q.value=\''.$p[0].'\';';
		$js .= 'search_button.click();"';
		break;

	// Dropdown
	case 'drop':
		$js = $cli.'"dropDown(\''.$p[0].'\')"';
		break;

	// Retrieve data
	case 'compound':
		$js = $cli.$retr.'retrieve.php?q=compound_det&cid='.$p[0].'\', \'popup\', getSmiles, \'compID\', '.$p[0].')"';
		break;

	case 'barcode':
		$js = $cli.$retr.'search.php?q='.$p[0].'\', \'index\', getSmiles, \'barcode\', \''.$p[0].'\');';
		$js .= $close.'"';
		break;

	// Get compound info from CAS
	case 'calc_cas':
		$js = $cli.$prevent.'getCompoundInfo()"';
		break;

	// Execute
	case 'login':
		$js = $sub.$prevent.$submit.'redirect, \'index.php\')"';
		break;

	case 'logout':
		$js = $cli.$exec.'logout.php\', \'msg_center\', redirect, \'index.php\')"';
		break;

	case 'inactivate':
		$js = $cli.'"inactivate('.$p[0].','.$p[1].',\''.$p[2].'\')"';
		break;

	// Incoming
	case 'inc_start':
		$js = $cli.$retr.'incoming.php?q=start\', \'index\')"';
		break;

	case 'inc_comp':
		$js = $sub.$prevent.'retrieveData(\'exec/incoming.php?q=compound&comp=\' + this.q.value, \'incoming\')"';
		break;

	case 'inc_form_comp':
		$js = $sub.$prevent.$submit.'retrieveData, \'exec/incoming.php?q=compound&comp=\' + this.name.value, \'incoming\')"';
		break;

	case 'inc_form_batch':
		$js = $sub.$prevent.$submit.'retrieveData, \'exec/incoming.php?q=batch&cid='.$p[0].'\', \'batch_list\')"';
		break;

	case 'inc_select_comp':
		$js = $cli.'"selectCompound('.$p[0].', \''.$p[1].'\')"';
		break;

	case 'inc_select_batch':
		$js = $cli.'"selectBatch('.$p[0].', \''.$p[1].'\', \''.$p[2].'\', \''.$p[3].'\')"';
		break;

	// Forms
	case 'search_form':
		$js = $sub.$prevent.$submit.'retrieveData, \'exec/search.php?q='.$p[0].'\', \'index\', getSmiles, \'barcode\', \''.$p[0].'\')"';
		break;

	case 'search':
		$js = $sub.$prevent.'retrieveData(\'exec/search.php?q=\' + this.q.value, \'index\', getSmiles, \'barcode\', this.q.value);';
		$js .= $close.'"';
		break;

	// Erase popup
	case 'erase_popup':
		$js = $cli.$prevent.';erasePopup()"';
		break;

	// Change history
	case 'chng_hist':
		$js = $cli.'"getChangeHistorySummary('.$p[0].', \''.$p[1].'\')"';
		break;

	case 'chng_hist_det':
		$js = $cli.'"getChangeHistoryDetails('.$p[0].',\''.$p[1].'\','.$p[2].')"';
		break;

	// Default
	default:
		return FALSE;
	}

	return $js;
}
?>