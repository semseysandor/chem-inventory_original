<?php
/**
 * UPDATE queries
 *********************************************************/

/**
 * UPDATE compound
 *
 * @param		mysqli_link	$link
 * @param		int					$comp_id
 * @param		string			$name
 * @param		string			$name_alt
 * @param		string			$abbrev
 * @param		string			$chem_name
 * @param		string			$iupac
 * @param		string			$chem_form
 * @param		string			$cas
 * @param		string			$smiles
 * @param		int					$subcat
 * @param		int					$oeb
 * @param		double			$mol_weight
 * @param		string			$melt
 * @param		string			$note
 * @param		string			$user_name (= $_SESSION['USER_NAME'])
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	bool
 *	TRUE		on success
 */
function sql_update_compound($link, $comp_id, $name, $name_alt, $abbrev, $chem_name,
														$iupac, $chem_form, $cas, $smiles, $subcat, $oeb,
														$mol_weight, $melt, $note, $user_name) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	UPDATE
		leltar_compound
	SET
		name = ?,
		name_alt = ?,
		abbrev = ?,
		chemical_name = ?,
		iupac_name = ?,
		chem_formula = ?,
		cas = ?,
		smiles = ?,
		sub_category_id = ?,
		oeb = ?,
		mol_weight = ?,
		melting_point = ?,
		note = ?,
		last_mod_by = ?
	WHERE leltar_compound.compound_id = ?
	');
	$stmt->bind_param('ssssssssiidsssi',$name, $name_alt, $abbrev, $chem_name, $iupac,
																			$chem_form, $cas, $smiles, $subcat, $oeb,
																			$mol_weight, $melt, $note, $user_name, $comp_id);

	if (!($stmt->execute())) {
		throw new leltar_exception('sql_fail', 1);
	}

	if ($stmt->affected_rows == 1) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * UPDATE batch
 *
 * @param		mysqli_link	$link
 * @param		int					$batch_id
 * @param		int					$manfac
 * @param		string			$name
 * @param		string			$lot
 * @param		string			$arr
 * @param		string			$open
 * @param		string			$exp
 * @param		string			$note
 * @param		string			$user_name (= $_SESSION['USER_NAME'])
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	bool
 *	TRUE		on success
 */
function sql_update_batch($link, $batch_id, $manfac, $name, $lot, $arr,
													$open, $exp, $note, $user_name) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	UPDATE
		leltar_batch
	SET
		manfac_id = ?,
		name = ?,
		lot = ?,
		date_arr = ?,
		date_open = ?,
		date_exp = ?,
		note = ?,
		last_mod_by = ?
	WHERE
		leltar_batch.batch_id = ?
	');
	$stmt->bind_param('isssssssi',$manfac, $name, $lot, $arr, $open,
																$exp, $note, $user_name, $batch_id);

	if (!($stmt->execute())) {
		throw new leltar_exception('sql_fail', 1);
	}

	if ($stmt->affected_rows == 1) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * UPDATE pack
 *
 * @param		mysqli_link	$link
 * @param		int					$pack_id
 * @param		int					$loc_id
 * @param		int					$is_orig
 * @param		string			$size
 * @param		string			$weight
 * @param		string			$note
 * @param		string			$user_name (= $_SESSION['USER_NAME'])
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	bool
 *	TRUE		on success
 */
function sql_update_pack($link, $pack_id, $loc_id, $is_orig,
												$size, $weight, $note, $user_name) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	UPDATE
		leltar_pack
	SET
		location_id = ?,
		is_original = ?,
		size = ?,
		weight = ?,
		note = ?,
		last_mod_by = ?
	WHERE
		leltar_pack.pack_id = ?
	');
	$stmt->bind_param('sisissi',$loc_id, $is_orig, $size, $weight,
															$note, $user_name, $pack_id);

	if (!($stmt->execute())) {
		throw new leltar_exception('sql_fail', 1);
	}

	if ($stmt->affected_rows == 1) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * UPDATE barcode
 *
 * @param		mysqli_link	$link
 * @param		int					$pack_id
 * @param		string			$barcode
 * @param		string			$user_name (= $_SESSION['USER_NAME'])
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	bool
 *	TRUE		on success
 */
function sql_update_barcode($link, $pack_id, $barcode, $user_name) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	UPDATE leltar_pack
	SET
		barcode = ?,
		last_mod_by = ?
	WHERE leltar_pack.pack_id = ?
	');
	$stmt->bind_param('ssi', $barcode, $user_name, $pack_id);

	if (!($stmt->execute())) {
		throw new leltar_exception('sql_fail', 1);
	}

	if ($stmt->affected_rows == 1) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * Inactivate batch
 *
 * @param		mysqli_link	$link
 * @param		int					$batch_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	bool
 *	TRUE		on success
 */
function sql_inactivate_batch($link, $batch_id, $user_name) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	UPDATE leltar_batch
	SET
		date_arch = CURRENT_DATE(),
		is_active = 0,
		last_mod_by = ?
	WHERE leltar_batch.batch_id = ?
	');
	$stmt->bind_param('si', $user_name, $batch_id);
	
	if (!($stmt->execute())) {
		throw new leltar_exception('sql_fail', 1);
	}

	if ($stmt->affected_rows == 1) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * Inactivate pack
 *
 * @param		mysqli_link	$link
 * @param		int					$pack_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	bool
 *	TRUE		on success
 */
function sql_inactivate_pack($link, $pack_id, $user_name) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	UPDATE
		leltar_pack
	SET
		is_active = 0,
		last_mod_by = ?
	WHERE leltar_pack.pack_id = ?
	');
	$stmt->bind_param('si', $user_name, $pack_id);

	if (!($stmt->execute())) {
		throw new leltar_exception('sql_fail', 1);
	}

	if ($stmt->affected_rows == 1) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * UPDATE API
 *
 * @param		mysqli_link	$link
 * @param		int					$api_id
 * @param		string			$name
 * @param		string			$form
 * @param		string			$eval
 * @param		string			$dev
 * @param		string			$market
 * @param		string			$patent
 * @param		string			$pri
 * @param		string			$sec
 * @param		string			$melt
 * @param		string			$water
 * @param		string			$hcl
 * @param		string			$note
 * @param		string			$user_name (= $_SESSION['USER_NAME'])
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	bool
 *	TRUE		on success
 */
function sql_update_api($link, $api_id, $name, $form, $eval, $dev,
												$market,$patent, $pri, $sec, $melt, $water,
												$hcl, $note, $user_name) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	UPDATE
		api_summary
	SET
		name = ?,
		form = ?,
		status_eval = ?,
		status_dev = ?,
		status_market = ?,
		patent_expire = ?,
		pri_indication = ?,
		sec_indication = ?,
		melting_point = ?,
		solu_water = ?,
		solu_hcl = ?,
		note = ?,
		last_mod_by = ?
	WHERE
		api_summary.api_id = ?
	');
	$stmt->bind_param('sssssssssssssi', $name, $form, $eval, $dev, $market, $patent,
																			$pri, $sec, $melt, $water, $hcl, $note,
																			$user_name, $api_id);

	if (!($stmt->execute())) {
		throw new leltar_exception('sql_fail', 1);
	}

	if ($stmt->affected_rows == 1) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * UPDATE PK data
 *
 * @param		mysqli_link	$link
 * @param		int					$pk_id
 * @param		int					$attr_id
 * @param		string			$value
 * @param		string			$note
 * @param		string			$user_name (= $_SESSION['USER_NAME'])
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	bool
 *	TRUE		on success
 */
function sql_update_pk_data($link, $pk_id, $attr_id, $value, $note, $user_name) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	UPDATE 
		api_pk_data
	SET
		pk_attribute_id = ?,
		value = ?,
		note = ?,
		last_mod_by = ?
	WHERE
		api_pk_data.pk_data_id = ?
	');
	$stmt->bind_param('isssi', $attr_id, $value, $note, $user_name, $pk_id);

	if (!($stmt->execute())) {
		throw new leltar_exception('sql_fail', 1);
	}

	if ($stmt->affected_rows == 1) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * UPDATE drug product
 *
 * @param		mysqli_link	$link
 * @param		int					$drug_id
 * @param		string			$name
 * @param		string			$name_alt
 * @param		string			$dosage
 * @param		string			$crystall
 * @param		string			$particle
 * @param		string			$dose_free
 * @param		string			$dose_day
 * @param		string			$admin
 * @param		string			$note
 * @param		string			$user_name (= $_SESSION['USER_NAME'])
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	bool
 *	TRUE		on success
 */
function sql_update_drug_product($link, $drug_id, $name, $name_alt, $dosage, $crystall,
																$particle, $dose_free, $dose_day, $admin, $note, $user_name) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	UPDATE
		api_drug_product
	SET
		name = ?,
		name_alt = ?,
		dosage_form = ?,
		crystallinity = ?,
		particle_size = ?,
		dose_unit_free_form = ?,
		dose_unit_per_day = ?,
		administration = ?,
		note = ?,
		last_mod_by = ?
	WHERE
		api_drug_product.drug_product_id = ?
	');
	$stmt->bind_param('ssssssssssi',$name, $name_alt, $dosage, $crystall, $particle, $dose_free,
																	$dose_day, $admin, $note, $user_name, $drug_id);

	if (!($stmt->execute())) {
		throw new leltar_exception('sql_fail', 1);
	}

	if ($stmt->affected_rows == 1) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * UPDATE literature
 *
 * @param		mysqli_link	$link
 * @param		int					$literature_id
 * @param		string			$title
 * @param		string			$author
 * @param		string			$year
 * @param		string			$journal
 * @param		string			$note
 * @param		string			$user_name (= $_SESSION['USER_NAME'])
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	bool
 *	TRUE		on success
 */
function sql_update_literature($link, $literature_id, $title, $author,
															$year, $journal, $note, $user_name) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	UPDATE
		api_literature
	SET
		title = ?,
		author = ?,
		year = ?,
		journal = ?,
		note = ?,
		last_mod_by = ?
	WHERE
		api_literature.api_literature_id = ?
	');
	$stmt->bind_param('ssssssi',$title, $author, $year, $journal,
															$note, $user_name, $literature_id);

	if (!($stmt->execute())) {
		throw new leltar_exception('sql_fail', 1);
	}

	if ($stmt->affected_rows == 1) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * UPDATE solvent units
 *
 * @param		mysqli_link	$link
 * @param		int					$solvent_id
 * @param		int					$unit
 * @param		string			$user_name (= $_SESSION['USER_NAME'])
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	bool
 *	TRUE		on success
 */
function sql_update_solvent($link, $solvent_id, $unit, $user_name) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	UPDATE
		leltar_solvent
	SET
		unit = ?,
		last_mod_by = ?
	WHERE
		leltar_solvent.solvent_id = ?
	');
	$stmt->bind_param('isi', $unit, $user_name, $solvent_id);

	if (!($stmt->execute())) {
		throw new leltar_exception('sql_fail', 1);
	}

	if ($stmt->affected_rows == 1) {
		return TRUE;
	} else {
		return FALSE;
	}
}
?>