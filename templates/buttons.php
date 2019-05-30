<?php
/**
 * Buttons
 *********************************************************/

/**
 * Returns HTML code for buttons
 *
 * @param		string		$button			which button?
 * @param		array			$p					optional parameters
 *
 * @return	string		HTML code for button
 */
function button($button, array $p = NULL) {

	// Opening tags
	$s = '<span ';
	$b = '<button ';

	// Classes
	$hover = 'class="hover cursor-pointer" ';
	$site = 'class="button site" ';
	$site_m = 'class="button site font-m" ';

	// Icons
	$inbox = '<i class="fas fa-inbox"></i>';
	$pencil = '<i class="fas fa-pencil-alt"></i>';
	$pencil_black = '<i class="fas fa-pencil-alt black"></i>';
	$info = '<i class="fas fa-info black"></i>';
	$exch = '<i class="fas fa-exchange-alt"></i>';
	$plus = '<i class="fas fa-plus"></i>';
	$file = '<i class="far fa-file-pdf"></i>';
	$window = '<i class="far fa-window-close"></i>';
	$upload = '<i class="fas fa-upload"></i>';
	$history = '<i class="fas fa-history black"></i>';
	$trash = '<i class="fas fa-trash"></i>';
	$check = '<i class="far fa-check-square"></i>';
	$more = '<i class="far fa-caret-square-down"></i>';
	$redo = '<i class="fas fa-redo"></i>';

	// Closing tags
	$_s = '</span>';
	$_b = '</button>';

	switch ($button) {

	// Special
	case 'chng_hist':
		$html = $s.$hover.js_spec('chng_hist', $p).'>';
		$html .= $history.$_s;
		break;

	case 'inactivate':
		$html = $b.'class="button delete font-l" '.js_spec('inactivate', $p).'>';
		$html .= $trash.' Törlés'.$_b;
		break;

	case 'submit':
		$html = $b.'type="submit" class="button submit font-m bold">';
		$html .= $check.' Mentés'.$_b;
		break;

	case 'file':
		$html = '<a target="_blank" rel="noopener noreferrer" href="'.$p[0].'">';
		$html .= $file.'</a>';
		break;

	case 'compound':
		$html = $s.$hover.js_spec('compound', $p).'>';
		$html .= $info.$_s;
		break;

	case 'erase_popup':
		$html = $b.'class="button close font-m" '.js_spec('erase_popup').'>';
		$html .= $window.' Bezárás'.$_b;
		break;

	case 'more':
		$html = $s.js_spec('drop', $p).'>'.$more.$_s;
		break;

	case 'calc_cas':
		$html = $b.'class="button selected" '.js_spec('calc_cas').'>';
		$html .= $redo.' CAS'.$_b;
		break;

	// Edit
	case 'e_comp':
		$html = $s.$hover.js_get_form('e_comp', ['cid='.$p[0], 'mode=index']).'>';
		$html .= $pencil_black.$_s;
		break;

	case 'e_comp_search':
		$html = $b.$site.js_get_form('e_comp', ['cid='.$p[0], 'mode=search', 'barcode='.$p[1]]).'>';
		$html .= $pencil.$_b;
		break;

	case 'e_batch':
		$html = $s.$hover.js_get_form('e_batch', ['bid='.$p[0], 'mode=index']).'>';
		$html .= $pencil_black.$_s;
		break;

	case 'e_batch_search':
		$html = $b.$site.js_get_form('e_batch', ['bid='.$p[0], 'mode=search', 'barcode='.$p[1]]).'>';
		$html .= $pencil.$_b;
		break;

	case 'e_pack':
		$html = $s.$hover.js_get_form('e_pack', ['pid='.$p[0], 'mode=index']).'>';
		$html .= $pencil_black.$_s;
		break;

	case 'e_pack_search':
		$html = $b.$site.js_get_form('e_pack', ['pid='.$p[0], 'mode=search', 'barcode='.$p[1]]).'>';
		$html .= $pencil.$_b;
		break;

	case 'e_api':
		$html = $s.$hover.js_get_form('e_api', ['aid='.$p[0]]).'>';
		$html .= $pencil_black.$_s;
		break;

	case 'e_drug':
		$html = $s.$hover.js_get_form('e_drug', ['did='.$p[0]]).'>';
		$html .= $pencil_black.$_s;
		break;

	case 'e_liter':
		$html = $s.$hover.js_get_form('e_liter', ['lid='.$p[0]]).'>';
		$html .= $pencil_black.$_s;
		break;

	case 'e_pk':
		$html = $s.$hover.js_get_form('e_pk', ['pkid='.$p[0]]).'>';
		$html .= $pencil_black.$_s;
		break;

	// Active/Archive
	case 'batch_act':
		$html = $b.$site.js('get', ['batch&cid='.$p[0], 'batch_'.$p[0]]).'>';
		$html .= $exch.' Archív'.$_b;
		break;

	case 'batch_hist':
		$html = $b.$site.js('get', ['batch&cid='.$p[0].'&hist=1', 'batch_'.$p[0]]).'>';
		$html .= $exch.' Aktív'.$_b;
		break;

	case 'pack_act':
		$html = $b.$site.js('get', ['pack&bid='.$p[0], 'pack_'.$p[0]]).'>';
		$html .= $exch.' Archív'.$_b;
		break;

	case 'pack_hist':
		$html = $b.$site.js('get', ['pack&bid='.$p[0].'&hist=1', 'pack_'.$p[0]]).'>';
		$html .= $exch.' Aktív'.$_b;
		break;

	// Add
	case 'a_comp':
		$html = $b.$site_m.js_get_form('a_comp').'>';
		$html .= $plus.' Vegyszer'.$_b;
		break;

	case 'a_batch':
		$html = $b.$site.js_get_form('a_batch', ['cid='.$p[0]]).'>';
		$html .= $plus.$_b;
		break;

	case 'a_pack':
		$html = $b.$site.js_get_form('a_pack', ['bid='.$p[0]]).'>';
		$html .= $plus.$_b;
		break;

	case 'a_api':
		$html = $b.$site_m.js_get_form('a_api').'>';
		$html .= $plus.' API'.$_b;
		break;

	case 'a_drug':
		$html = $b.$site.js_get_form('a_drug', ['aid='.$p[0]]).'>';
		$html .= $plus.' Gyógyszer'.$_b;
		break;

	case 'a_liter':
		$html = $b.$site.js_get_form('a_liter', ['aid='.$p[0]]).'>';
		$html .= $plus.' Irodalom'.$_b;
		break;

	case 'a_pk':
		$html = $b.$site.js_get_form('a_pk', ['did='.$p[0]]).'>';
		$html .= $plus.' PK'.$_b;
		break;

	// Upload
	case 'up_coa':
		$html = $s.$hover.js_get_form('up_coa', ['bid='.$p[0]]).'>';
		$html .= $upload.$_s;
		break;

	case 'up_msds':
		$html = $s.$hover.js_get_form('up_msds', ['bid='.$p[0]]).'>';
		$html .= $upload.$_s;
		break;

	case 'up_liter':
		$html = $s.$hover.js_get_form('up_liter', ['lid='.$p[0]]).'>';
		$html .= $upload.$_s;
		break;

	// Default
	default:
		return FALSE;
	}

	return $html;
}
?>