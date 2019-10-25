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
	$minus = '<i class="fas fa-minus"></i>';
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

	// Change history
	case 'chng_hist':
		$html = $s.$hover.js_spec('chng_hist', $p).'>';
		$html .= $history.$_s;
		break;

	// Inactivate
	case 'inactivate':
		$html = $b.'class="button delete font-l" '.js_spec('inactivate', $p).'>';
		$html .= $trash.' Törlés'.$_b;
		break;

	// Submit
	case 'submit':
		$html = $b.'type="submit" class="button submit font-m bold">';
		$html .= $check.' Mentés'.$_b;
		break;

	// File
	case 'file':
		$html = '<a target="_blank" rel="noopener noreferrer" href="'.$p[0].'">';
		$html .= $file.'</a>';
		break;

	// Compound details
	case 'compound':
		$html = $s.$hover.js_spec('compound', $p).'>';
		$html .= $info.$_s;
		break;

	// Erase popup
	case 'erase_popup':
		$html = $b.'class="button close font-m" '.js_spec('erase_popup').'>';
		$html .= $window.' Bezárás'.$_b;
		break;

	// More
	case 'more':
		$html = $s.js_spec('drop', $p).'>'.$more.$_s;
		break;

	// Calculate CAS
	case 'calc_cas':
		$html = $b.'class="button selected" '.js_spec('calc_cas').'>';
		$html .= $redo.' CAS'.$_b;
		break;

	// Incoming
	case 'incoming':
		$html = $b.$site_m.js_spec('inc_start').'>';
		$html .= $inbox.' Bevételezés'.$_b;
		break;

	// Inventory delete missing pack
	case 'invent_delete':
		$html = $s.$hover.js_spec('invent_delete_pack', [$p[0]]).'>';
		$html .= $trash.$_s;
		break;

	// Edit
	case 'e_comp':
		$html = $s.$hover.js_get_form('e_comp', ['cid='.$p[0], 'mode='.$p[1]]).'>';
		$html .= $pencil_black.$_s;
		break;

	case 'e_comp_search':
		$html = $b.$site.js_get_form('e_comp', ['cid='.$p[0], 'mode=search', 'barcode='.$p[1]]).'>';
		$html .= $pencil.$_b;
		break;

	case 'e_batch':
		$html = $s.$hover.js_get_form('e_batch', ['bid='.$p[0], 'mode='.$p[1]]).'>';
		$html .= $pencil_black.$_s;
		break;

	case 'e_batch_search':
		$html = $b.$site.js_get_form('e_batch', ['bid='.$p[0], 'mode=search', 'barcode='.$p[1]]).'>';
		$html .= $pencil.$_b;
		break;

	case 'e_pack':
		$html = $s.$hover.js_get_form('e_pack', ['pid='.$p[0], 'mode='.$p[1]]).'>';
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

	case 'e_manfac':
		$html = $s.$hover.js_get_form('e_manfac', ['manfac_id='.$p[0]]).'>';
		$html .= $pencil_black.$_s;
		break;

	case 'e_lab':
		$html = $s.$hover.js_get_form('e_lab', ['lab_id='.$p[0]]).'>';
		$html .= $pencil_black.$_s;
		break;

	case 'e_place':
		$html = $s.$hover.js_get_form('e_place', ['place_id='.$p[0]]).'>';
		$html .= $pencil_black.$_s;
		break;

	case 'e_sub':
		$html = $s.$hover.js_get_form('e_sub', ['sub_id='.$p[0]]).'>';
		$html .= $pencil_black.$_s;
		break;

	case 'e_loc':
		$html = $s.$hover.js_get_form('e_loc', ['loc_id='.$p[0]]).'>';
		$html .= $pencil_black.$_s;
		break;

	case 'e_user':
		$html = $s.$hover.js_get_form('e_user', ['uid='.$p[0]]).'>';
		$html .= $pencil_black.$_s;
		break;

	// Active/Archive
	case 'batch_act':
		$html = $b.$site.js('get', ['batch&cid='.$p[0], 'batch_'.$p[0]]).'>';
		$html .= $exch.' Histórikus termékek'.$_b;
		break;

	case 'batch_hist':
		$html = $b.$site.js('get', ['batch&cid='.$p[0].'&hist=1', 'batch_'.$p[0]]).'>';
		$html .= $exch.' Aktív termékek'.$_b;
		break;

	case 'pack_act':
		$html = $b.$site.js('get', ['pack&bid='.$p[0], 'pack_'.$p[0]]).'>';
		$html .= $exch.' Histórikus kiszerelések'.$_b;
		break;

	case 'pack_hist':
		$html = $b.$site.js('get', ['pack&bid='.$p[0].'&hist=1', 'pack_'.$p[0]]).'>';
		$html .= $exch.' Aktív kiszerelések'.$_b;
		break;

	// Add
	case 'a_comp':
		$html = $b.$site_m.js_get_form('a_comp', ['mode='.$p[0]]).'>';
		$html .= $plus.' Vegyszer'.$_b;
		break;

	case 'a_batch':
		$html = $b.$site.js_get_form('a_batch', ['cid='.$p[0], 'mode='.$p[1]]).'>';
		$html .= $plus.' Termék'.$_b;
		break;

	case 'a_pack':
		$html = $b.$site.js_get_form('a_pack', ['bid='.$p[0], 'mode='.$p[1]]).'>';
		$html .= $plus.' Kiszerelés'.$_b;
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

	case 'a_manfac':
		$html = $b.$site_m.js_get_form('a_manfac').'>';
		$html .= $plus.' Gyártó'.$_b;
		break;

	case 'a_lab':
		$html = $b.$site_m.js_get_form('a_lab').'>';
		$html .= $plus.' Labor'.$_b;
		break;

	case 'a_place':
		$html = $b.$site_m.js_get_form('a_place').'>';
		$html .= $plus.' Hely'.$_b;
		break;

	case 'a_sub':
		$html = $b.$site_m.js_get_form('a_sub').'>';
		$html .= $plus.' Alhely'.$_b;
		break;

	case 'a_loc':
		$html = $b.$site_m.js_get_form('a_loc').'>';
		$html .= $plus.' Lokáció'.$_b;
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

	// Modify solvent unit
	case 's_add':
		$html = $b.'class="button submit font-l" '.js_spec('solvent', [$p[1], $p[2]]).'>'.$plus.' '.$p[0].$_b;
		break;

	case 's_reduce':
		$html = $b.'class="button delete font-l" '.js_spec('solvent', [$p[1], $p[2]]).'>'.$minus.' '.$p[0].$_b;
		break;

	// Default
	default:
		return FALSE;
	}

	return $html;
}
?>