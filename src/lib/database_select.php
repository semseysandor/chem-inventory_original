<?php
/**
 * SELECT queries
 *********************************************************/

// Non parameterized SELECT queries

/**
 * Retrieve frequent manufacturers
 *
 * @param		mysqli_link		$link
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_manfac_freq($link) {

	$sql = '
	SELECT
		leltar_manfac.manfac_id AS id,
		leltar_manfac.name
	FROM leltar_manfac
	WHERE leltar_manfac.is_frequent = 1
	ORDER BY leltar_manfac.name
	';
	$result = $link->query($sql);

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve infrequent manufacturers
 *
 * @param		mysqli_link		$link
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_manfac_infreq($link) {

	$sql = '
	SELECT
		leltar_manfac.manfac_id AS id,
		leltar_manfac.name
	FROM leltar_manfac
	WHERE leltar_manfac.is_frequent = 0
	ORDER BY leltar_manfac.name
	';
	$result = $link->query($sql);

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve categories
 *
 * @param		mysqli_link		$link
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_category($link) {

	$sql = '
	SELECT
		leltar_category.category_id AS id,
		leltar_category.name
	FROM leltar_category
	';
	$result = $link->query($sql);

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve labs
 *
 * @param		mysqli_link		$link
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_lab($link) {

	$sql = '
	SELECT
		leltar_loc_lab.name
	FROM leltar_loc_lab
	ORDER BY leltar_loc_lab.name
	';
	$result = $link->query($sql);

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve number of compounds (all)
 *
 * @param		mysqli_link		$link
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	int
 */
function sql_get_compound_number_all($link) {

	$sql = '
	SELECT
		count(compound_id) AS total
	FROM leltar_compound
	';
	$result = $link->query($sql);

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result->fetch_object()->total;
}

/**
 * Retrieve number of APIs (all)
 *
 * @param		mysqli_link		$link
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	int
 */
function sql_get_api_number_all($link) {

	$sql = '
	SELECT
		count(api_id) AS total
	FROM api_summary
	';
	$result = $link->query($sql);

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result->fetch_object()->total;
}

/**
 * Retrieve PK attribute list
 *
 * @param		mysqli_link		$link
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_pk_attribute($link) {

	$sql = '
	SELECT
		api_pk_attribute.pk_attribute_id AS id,
		api_pk_attribute.name
	FROM api_pk_attribute
	ORDER BY api_pk_attribute.name
	';
	$result = $link->query($sql);

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve solvents
 *
 * @param		mysqli_link		$link
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_solvents($link) {

	$sql = '
	SELECT
		leltar_solvent.solvent_id,
		leltar_solvent.name,
		leltar_solvent.type,
		leltar_solvent.volume,
		leltar_solvent.unit
	FROM leltar_solvent
	ORDER BY
		leltar_solvent.name,
		leltar_solvent.type
	';
	$result = $link->query($sql);

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

// Parameterized SELECT queries

/**
 * Retrieve compound info
 *
 * @param		mysqli_link	$link
 * @param		int					$comp_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_comp_info($link, $comp_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_compound.name
	FROM leltar_compound
	WHERE leltar_compound.compound_id = ?
	LIMIT 1
	');
	$stmt->bind_param('i', $comp_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve batch info
 *
 * @param		mysqli_link	$link
 * @param		int					$batch_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_batch_info($link, $batch_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_compound.compound_id AS comp_id,
		leltar_compound.name AS comp_name,
		leltar_manfac.name AS manfac_name,
		leltar_batch.name AS batch_name,
		leltar_batch.lot
	FROM ((leltar_batch
		INNER JOIN leltar_compound USING (compound_id))
		INNER JOIN leltar_manfac USING (manfac_id))
	WHERE leltar_batch.batch_id = ?
	LIMIT 1
	');
	$stmt->bind_param('i', $batch_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve compound data
 *
 * @param		mysqli_link	$link
 * @param		int					$comp_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_compound_data($link, $comp_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_compound.name,
		leltar_compound.name_alt,
		leltar_compound.abbrev,
		leltar_compound.chemical_name AS chem_name,
		leltar_compound.iupac_name AS iupac,
		leltar_compound.chem_formula AS chem_form,
		leltar_compound.cas,
		leltar_compound.smiles,
		leltar_compound.sub_category_id AS subcat_id,
		leltar_category.name AS cat_name,
		leltar_sub_category.name AS sub_cat_name,
		leltar_compound.mol_weight,
		leltar_compound.melting_point AS melt,
		leltar_compound.oeb,
		leltar_compound.note
	FROM ((leltar_compound
		INNER JOIN leltar_sub_category USING (sub_category_id))
		INNER JOIN leltar_category USING (category_id))
	WHERE leltar_compound.compound_id = ?
	LIMIT 1
	');
	$stmt->bind_param('i', $comp_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve batch data
 *
 * @param		mysqli_link	$link
 * @param		int					$batch_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_batch_data($link, $batch_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_batch.compound_id,
		leltar_compound.name AS comp_name,
		leltar_batch.manfac_id,
		leltar_manfac.name AS manfac_name,
		leltar_batch.name,
		leltar_batch.lot,
		leltar_batch.date_arr AS arr,
		leltar_batch.date_open AS open,
		leltar_batch.date_exp AS exp,
		leltar_batch.note
	FROM ((leltar_batch
		INNER JOIN leltar_manfac USING (manfac_id))
		INNER JOIN leltar_compound USING (compound_id))
	WHERE
		leltar_batch.batch_id = ?
		LIMIT 1
	');
	$stmt->bind_param('i', $batch_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve pack data
 *
 * @param		mysqli_link	$link
 * @param		int					$pack_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_pack_data($link, $pack_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_compound.compound_id,
		leltar_compound.name AS comp_name,
		leltar_manfac.name AS manfac_name,
		leltar_batch.name AS batch_name,
		leltar_batch.batch_id,
		leltar_batch.lot,
		leltar_pack.location_id AS loc_id,
		leltar_pack.size,
		leltar_pack.is_original AS is_orig,
		leltar_pack.weight,
		leltar_pack.note
	FROM (((leltar_pack
		INNER JOIN leltar_batch USING (batch_id))
		INNER JOIN leltar_manfac USING (manfac_id))
		INNER JOIN leltar_compound USING (compound_id))
	WHERE
		leltar_pack.pack_id = ?
		LIMIT 1
	');
	$stmt->bind_param('i', $pack_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve SMILES from compound ID
 *
 * @param		mysqli_link	$link
 * @param		int					$comp_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_smiles_compound($link, $comp_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_compound.smiles AS smiles
	FROM leltar_compound
	WHERE leltar_compound.compound_id = ?
	LIMIT 1
	');
	$stmt->bind_param('i', $comp_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result->fetch_object()->smiles;
}

/**
 * Retrieve SMILES from barcode
 *
 * @param		mysqli_link		$link
 * @param		string				$barcode
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_smiles_barcode($link, $barcode) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_all_info.smiles AS smiles
	FROM leltar_all_info
	WHERE leltar_all_info.barcode = ?
	LIMIT 1
	');
	$stmt->bind_param('s', $barcode);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result->fetch_object()->smiles;
}


/**
 * Retrieve compounds (all)
 *
 * @param		mysqli_link	$link
 * @param		int					$offset
 * @param		int					$limit
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_compounds_all($link, $offset, $limit) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_compound.compound_id AS id,
		leltar_compound.name,
		leltar_compound.name_alt,
		leltar_compound.abbrev,
		leltar_compound.note
	FROM leltar_compound
	ORDER BY leltar_compound.name
	LIMIT ? , ?
	');
	$stmt->bind_param('ii', $offset, $limit);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve compounds in a category
 *
 * @param		mysqli_link	$link
 * @param		int					$category_id
 * @param		int					$offset
 * @param		int					$limit
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_compounds_categ($link, $category_id, $offset, $limit) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_compound.compound_id AS id,
		leltar_compound.name,
		leltar_compound.name_alt,
		leltar_compound.abbrev,
		leltar_compound.note
	FROM (leltar_compound
		INNER JOIN leltar_sub_category USING (sub_category_id))
	WHERE leltar_sub_category.category_id = ?
	ORDER BY leltar_compound.name
	LIMIT ? , ?
	');
	$stmt->bind_param('iii', $category_id, $offset, $limit);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve compounds in a subcategory
 *
 * @param		mysqli_link	$link
 * @param		int					$sub_category_id
 * @param		int					$offset
 * @param		int					$limit
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_compounds_subcateg($link, $sub_category_id, $offset, $limit) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_compound.compound_id AS id,
		leltar_compound.name,
		leltar_compound.name_alt,
		leltar_compound.abbrev,
		leltar_compound.note
	FROM leltar_compound
	WHERE leltar_compound.sub_category_id = ?
	ORDER BY leltar_compound.name
	LIMIT ? , ?
	');
	$stmt->bind_param('iii', $sub_category_id, $offset, $limit);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve batches of a compound
 *
 * @param		mysqli_link	$link
 * @param		int					$comp_id
 * @param		int					$is_active
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_batch($link, $comp_id, $is_active) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_batch.batch_id AS id,
		leltar_manfac.name AS manfac_name,
		leltar_batch.name,
		leltar_batch.lot,
		leltar_batch.date_arr,
		leltar_batch.date_open,
		leltar_batch.date_exp,
		leltar_batch.date_arch,
		leltar_batch.note
	FROM (leltar_batch
		INNER JOIN leltar_manfac USING (manfac_id))
	WHERE
		leltar_batch.compound_id = ?
		AND leltar_batch.is_active = ?
	ORDER BY manfac_name, leltar_batch.name, leltar_batch.date_arr
	');
	$stmt->bind_param('ii', $comp_id, $is_active);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve packs of a batch
 *
 * @param		mysqli_link	$link
 * @param		int					$batch_id
 * @param		int					$is_active
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_pack($link, $batch_id, $is_active) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_pack.pack_id,
		leltar_pack.is_original,
		leltar_pack.size,
		leltar_pack.weight,
		leltar_pack.barcode,
		leltar_location_list.lab_name,
		leltar_location_list.place_name,
		leltar_location_list.sub_name,
		leltar_pack.note
	FROM (leltar_pack
		INNER JOIN leltar_location_list USING (location_id))
	WHERE leltar_pack.batch_id = ?
	AND leltar_pack.is_active = ?
	ORDER BY
		leltar_pack.size,
		leltar_location_list.lab_name,
		leltar_location_list.place_name,
		leltar_location_list.sub_name
	');
	$stmt->bind_param('ii', $batch_id, $is_active);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve all info on barcode
 *
 * @param		mysqli_link	$link
 * @param		string			 $barcode
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_barcode_data($link, $barcode) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT *
	FROM leltar_all_info
	WHERE
		leltar_all_info.barcode = ?
		AND leltar_all_info.pack_is_active = 1
	LIMIT 1
	');
	$stmt->bind_param('s', $barcode);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve active packs of a batch
 *
 * @param		mysqli_link	$link
 * @param		int					$batch_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_active_packs_of_batch($link, $batch_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_pack.pack_id
	FROM leltar_pack
	WHERE
		leltar_pack.batch_id = ?
		AND leltar_pack.is_active = 1
	');
	$stmt->bind_param('i', $batch_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Checks if compound exist in DB
 *
 * @param		mysqli_link	$link
 * @param		string			$name
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	bool
 *	TRUE		if record found
 *	FALSE		if no record found
 */
function sql_check_compound($link, $name) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_compound.compound_id
	FROM leltar_compound
	WHERE leltar_compound.name = ?
	LIMIT 1
	');
	$stmt->bind_param('s', $name);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	if ($result->num_rows == 1) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * Checks if batch exist in DB
 *
 * @param		mysqli_link	$link
 * @param		int					$comp_id
 * @param		string			 $name
 * @param		int					$manfac
 * @param		string			 $lot
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	bool
 *	TRUE		if record found
 *	FALSE		if no record found
 */
function sql_check_batch($link, $comp_id, $name, $manfac, $lot) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_batch.batch_id
	FROM
		leltar_batch
	WHERE
		leltar_batch.compound_id = ?
		AND leltar_batch.name = ?
		AND leltar_batch.manfac_id = ?
		AND leltar_batch.lot = ?
	LIMIT 1
	');
	$stmt->bind_param('isis', $comp_id, $name, $manfac, $lot);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	if ($result->num_rows == 1) {
		return TRUE;
	} else {
		return FALSE;
	}
} 

/**
 * Retrieve sub-categories of given category
 *
 * @param		mysqli_link	$link
 * @param		int					$category_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_subcategory($link, $category_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_sub_category.sub_category_id AS id,
		leltar_sub_category.name
	FROM leltar_sub_category
	WHERE leltar_sub_category.category_id = ?
	');
	$stmt->bind_param('i', $category_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve places $ sub inside laboratory
 *
 * @param		mysqli_link	$link
 * @param		string			 $lab_name
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_place_sub($link, $lab_name) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_location_list.location_id AS id,
		leltar_location_list.place_name AS place,
		leltar_location_list.sub_name AS sub
	FROM leltar_location_list
	WHERE leltar_location_list.lab_name = ?
	ORDER BY place, sub
	');
	$stmt->bind_param('s', $lab_name);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve number of compounds in a category
 *
 * @param		mysqli_link	$link
 * @param		int					$category_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	int
 */
function sql_get_compound_number_categ($link, $category_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		COUNT(compound_id) AS total
	FROM (leltar_compound
		INNER JOIN leltar_sub_category USING (sub_category_id))
	WHERE leltar_sub_category.category_id = ?
	');
	$stmt->bind_param('i', $category_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result->fetch_object()->total;
}

/**
 * Retrieve number of compounds in a subcategory
 *
 * @param		mysqli_link	$link
 * @param		int					$sub_category_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	int
 */
function sql_get_compound_number_subcateg($link, $sub_category_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		COUNT(compound_id) AS total
	FROM
		leltar_compound
	WHERE leltar_compound.sub_category_id = ?
	');
	$stmt->bind_param('i', $sub_category_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result->fetch_object()->total;
}

/**
 * Retrieve user info
 *
 * @param		mysqli_link	$link
 * @param		string			 $user_name
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_user_info($link, $user_name) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		main_users.user_id,
		main_users.right_level_leltar,
		main_users.right_level_api
	FROM main_users
	WHERE main_users.name = ?
	LIMIT 1
	');
	$stmt->bind_param('s', $user_name);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Search compounds
 *
 * @param		mysqli_link	$link
 * @param		string			$query
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_search_compound($link, $query) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_compound.compound_id AS id,
		leltar_compound.name,
		leltar_compound.name_alt,
		leltar_compound.abbrev,
		leltar_compound.note
	FROM
		leltar_compound
	WHERE
		(leltar_compound.name LIKE ?
		OR leltar_compound.name_alt LIKE ?
		OR leltar_compound.abbrev LIKE ?
		OR leltar_compound.chemical_name LIKE ?
		OR leltar_compound.iupac_name LIKE ?
		OR leltar_compound.chem_formula LIKE ?
		OR leltar_compound.cas LIKE ?
		OR leltar_compound.smiles LIKE ?
		OR leltar_compound.note LIKE ?)
	ORDER BY
		leltar_compound.name
	');
	$stmt->bind_param('sssssssss', $query, $query, $query, $query, $query, $query, $query, $query, $query);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Search batches
 *
 * @param		mysqli_link	$link
 * @param		string			$query
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_search_batch($link, $query) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_batch.batch_id AS id,
		leltar_batch.compound_id,
		leltar_batch.manfac_id,
		leltar_manfac.name AS manfac_name,
		leltar_batch.name,
		leltar_batch.lot,
		leltar_batch.date_arr,
		leltar_batch.date_open,
		leltar_batch.date_exp,
		leltar_batch.note
	FROM
		leltar_batch INNER JOIN leltar_manfac USING (manfac_id)
	WHERE
		(leltar_batch.name LIKE ?
		OR leltar_batch.lot LIKE ?
		OR leltar_batch.note LIKE ?
		OR leltar_manfac.name LIKE ?)
		AND leltar_batch.is_active = 1
	ORDER BY
		leltar_batch.name
	');
	$stmt->bind_param('ssss', $query, $query, $query, $query);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Search APIs
 *
 * @param		mysqli_link	$link
 * @param		string			$query
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_search_api($link, $query) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		api_summary.api_id AS id,
		api_summary.name,
		api_summary.form,
		api_summary.status_eval AS eval,
		api_summary.status_dev AS dev,
		api_summary.status_market AS market,
		api_summary.patent_expire AS patent,
		api_summary.pri_indication AS pri,
		api_summary.sec_indication AS sec,
		api_summary.melting_point AS melt,
		api_summary.solu_water AS water,
		api_summary.solu_hcl AS hcl,
		api_summary.note
	FROM
		api_summary
	WHERE
		api_summary.name LIKE ?
		OR api_summary.form LIKE ?
		OR api_summary.crystallinity LIKE ?
		OR api_summary.particle_size LIKE ?
		OR api_summary.pri_indication LIKE ?
		OR api_summary.sec_indication LIKE ?
		OR api_summary.note LIKE ?
	ORDER BY
		api_summary.name, api_summary.form, api_summary.crystallinity
	');
	$stmt->bind_param('sssssss', $query, $query, $query, $query, $query, $query, $query);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Get change history of a record
 *
 * @param		mysqli_link	$link
 * @param		string			 $table
 * @param		int					$record_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_change_history_summary($link, $table, $record_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		changelog_summary.summary_id,
		changelog_summary.action,
		changelog_summary.modified_by,
		changelog_summary.modified_on
	FROM changelog_summary
	WHERE
		changelog_summary.table_name = ?
		AND changelog_summary.record_id = ?
	ORDER BY changelog_summary.summary_id DESC
	');
	$stmt->bind_param('si', $table, $record_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Get details of a given change
 *
 * @param		mysqli_link	$link
 * @param		int					$record_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_change_history_details($link, $record_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		changelog_detail.column_name,
		changelog_detail.old_value,
		changelog_detail.new_value
	FROM changelog_detail
	WHERE changelog_detail.summary_id = ?
	ORDER BY changelog_detail.column_name
	');
	$stmt->bind_param('i', $record_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve API (all)
 *
 * @param		mysqli_link	$link
 * @param		int					$offset
 * @param		int					$limit
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_api_all($link, $offset, $limit) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		api_summary.api_id AS id,
		api_summary.name,
		api_summary.form,
		api_summary.status_eval AS eval,
		api_summary.status_dev AS dev,
		api_summary.status_market AS market,
		api_summary.patent_expire AS patent,
		api_summary.pri_indication AS pri,
		api_summary.sec_indication AS sec,
		api_summary.melting_point AS melt,
		api_summary.solu_water AS water,
		api_summary.solu_hcl AS hcl,
		api_summary.note
	FROM api_summary
	ORDER BY api_summary.name, api_summary.form, api_summary.crystallinity
	LIMIT ? , ?
	');
	$stmt->bind_param('ii', $offset, $limit);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve API data
 *
 * @param		mysqli_link	$link
 * @param		int					$api_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_api_data($link, $api_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		api_summary.name,
		api_summary.form,
		api_summary.status_eval AS eval,
		api_summary.status_dev AS dev,
		api_summary.status_market AS market,
		api_summary.patent_expire AS patent,
		api_summary.pri_indication AS pri,
		api_summary.sec_indication AS sec,
		api_summary.melting_point AS melt,
		api_summary.solu_water AS water,
		api_summary.solu_hcl AS hcl,
		api_summary.note
	FROM api_summary
	WHERE api_summary.api_id = ? LIMIT 1
	');
	$stmt->bind_param('i', $api_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Checks if API exist in DB
 *
 * @param		mysqli_link	$link
 * @param		string			$name
 * @param		string			$form
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	bool
 *	TRUE		if record found
 *	FALSE		if no record found
 */
function sql_check_api($link, $name, $form) {

	if (is_null($form)) {$form = 'null';}

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		api_summary.api_id
	FROM api_summary
	WHERE
		api_summary.name = ?
		AND IFNULL(api_summary.form, "null") = ?
	LIMIT 1
	');
	$stmt->bind_param('ss', $name, $form);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	if ($result->num_rows == 1) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * Retrieve literature for an API
 *
 * @param		mysqli_link	$link
 * @param		int					$api_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_literature($link, $api_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		api_literature.api_literature_id AS id,
		api_literature.api_id,
		api_literature.title,
		api_literature.author,
		api_literature.year,
		api_literature.journal,
		api_literature.note
	FROM api_literature
	WHERE api_literature.api_id = ?
	ORDER BY api_literature.api_literature_id
	');
	$stmt->bind_param('i', $api_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieves a literature
 *
 * @param		mysqli_link	$link
 * @param		int					$literature_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_literature_data($link, $literature_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		api_literature.api_id,
		api_literature.title,
		api_literature.author,
		api_literature.year,
		api_literature.journal,
		api_literature.note
	FROM api_literature
	WHERE api_literature.api_literature_id = ?
	LIMIT 1
	');
	$stmt->bind_param('i', $literature_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve PK data for a drug product
 *
 * @param		mysqli_link	$link
 * @param		int					$drug_product
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_pk($link, $drug_product) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		api_pk_data.pk_data_id,
		api_pk_data.pk_attribute_id,
		api_pk_data.value,
		api_pk_data.note,
		api_pk_attribute.name
	FROM api_pk_data
		INNER JOIN api_pk_attribute USING (pk_attribute_id)
	WHERE api_pk_data.drug_product_id = ?
	ORDER BY api_pk_data.pk_data_id
	');
	$stmt->bind_param('i', $drug_product);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieves a PK data
 *
 * @param		mysqli_link	$link
 * @param		int					$pk_data_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_pk_data($link, $pk_data_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		api_pk_data.drug_product_id AS drug_id,
		api_pk_data.pk_attribute_id AS pk_attr,
		api_pk_data.value,
		api_pk_data.note,
		api_pk_attribute.name
	FROM api_pk_data
		INNER JOIN api_pk_attribute USING (pk_attribute_id)
	WHERE api_pk_data.pk_data_id = ?
	LIMIT 1
	');
	$stmt->bind_param('i', $pk_data_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieve drug products of an API
 *
 * @param		mysqli_link	$link
 * @param		int					$api_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_drug_product($link, $api_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		api_drug_product.drug_product_id AS id,
		api_drug_product.name,
		api_drug_product.name_alt,
		api_drug_product.dosage_form AS dosage,
		api_drug_product.crystallinity AS crystall,
		api_drug_product.particle_size AS particle,
		api_drug_product.dose_unit_free_form AS dose_free,
		api_drug_product.dose_unit_per_day AS dose_day,
		api_drug_product.administration AS admin,
		api_drug_product.note
	FROM api_drug_product
	WHERE api_drug_product.api_id = ?
	ORDER BY api_drug_product.drug_product_id
	');
	$stmt->bind_param('i', $api_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Retrieves a drug product
 *
 * @param		mysqli_link		$link
 * @param		int						$drug_product_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_get_drug_product_data($link, $drug_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		api_drug_product.api_id,
		api_drug_product.name,
		api_drug_product.name_alt,
		api_drug_product.dosage_form AS dosage,
		api_drug_product.crystallinity AS crystall,
		api_drug_product.particle_size AS particle,
		api_drug_product.dose_unit_free_form AS dose_free,
		api_drug_product.dose_unit_per_day AS dose_day,
		api_drug_product.administration AS admin,
		api_drug_product.note
	FROM api_drug_product
	WHERE api_drug_product.drug_product_id = ?
	LIMIT 1
	');
	$stmt->bind_param('i', $drug_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}

/**
 * Checks if literature exist for an API
 *
 * @param		mysqli_link	$link
 * @param		int					$api_id
 * @param		string			$title
 * @param		string			$author
 * @param		string			$year
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	bool
 *	TRUE		if record found
 *	FALSE		if no record found
 */
function sql_check_literature($link, $api_id, $title, $author, $year) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		api_literature.api_literature_id
	FROM api_literature
	WHERE
		api_literature.api_id = ?
		AND api_literature.title = ?
		AND api_literature.author = ?
		AND api_literature.year = ?
	LIMIT 1
	');
	$stmt->bind_param('isss', $api_id, $title, $author, $year);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	if ($result->num_rows == 1) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * Checks if PK data exist for a drug product
 *
 * @param		mysqli_link	$link
 * @param		int					$drug_product_id
 * @param		int					$pk_attribute_id
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	bool
 *	TRUE		if record found
 *	FALSE		if no record found
 */
function sql_check_pk_data($link, $drug_product_id, $pk_attribute_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		api_pk_data.pk_data_id
	FROM
		api_pk_data
	WHERE
		api_pk_data.drug_product_id = ?
		AND api_pk_data.pk_attribute_id = ?
	LIMIT 1
	');
	$stmt->bind_param('ii', $drug_product_id, $pk_attribute_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	if ($result->num_rows == 1) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * Checks if drug product exist for an API
 *
 * @param		mysqli_link	$link
 * @param		int					$api_id
 * @param		string			$name
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	bool
 *	TRUE		if record found
 *	FALSE		if no record found
 */
function sql_check_drug_product($link, $api_id, $name) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		api_drug_product.drug_product_id
	FROM
		api_drug_product
	WHERE
		api_drug_product.api_id = ?
		AND api_drug_product.name = ?
	LIMIT 1
	');
	$stmt->bind_param('is', $api_id, $name);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	if ($result->num_rows == 1) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * Checks if API ID exist in DB
 *
 * @param		mysqli_link	$link
 * @param		int					$api_id
 *
 * @throws	leltar_exception if SQL query failed
 * @throws	leltar_exception if no record found
 *
 * @return
 *	TRUE		if record found
 */
function sql_check_api_id($link, $api_id) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		api_summary.api_id
	FROM
		api_summary
	WHERE
		api_summary.api_id = ?
	LIMIT 1
	');
	$stmt->bind_param('i', $api_id);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	if ($result->num_rows == 1) {
		return TRUE;
	} else {
		throw new leltar_exception('no_such_record', 1);
	}
}

/**
 * Live search
 *
 * @param		mysqli_link	$link
 * @param		string			$query
 *
 * @throws	leltar_exception if SQL query failed
 *
 * @return	mysqli_result
 */
function sql_live_search($link, $query) {

	$stmt = $link->init();
	$stmt = $link->prepare('
	SELECT
		leltar_compound.name AS search
		FROM leltar_compound
		WHERE leltar_compound.name LIKE ?
	UNION SELECT
		leltar_compound.name_alt
		FROM leltar_compound
		WHERE leltar_compound.name_alt LIKE ?
	UNION SELECT
		leltar_compound.abbrev
		FROM leltar_compound
		WHERE leltar_compound.abbrev LIKE ?
	UNION SELECT
		leltar_compound.chemical_name
		FROM leltar_compound
		WHERE leltar_compound.chemical_name LIKE ?
	UNION SELECT
		leltar_compound.iupac_name
		FROM leltar_compound
		WHERE leltar_compound.iupac_name LIKE ?
	UNION SELECT
		leltar_compound.chem_formula
		FROM leltar_compound
		WHERE leltar_compound.chem_formula LIKE ?
	UNION SELECT
		leltar_compound.cas
		FROM leltar_compound
		WHERE leltar_compound.cas LIKE ?
	UNION SELECT
		leltar_compound.smiles
		FROM leltar_compound
		WHERE leltar_compound.smiles LIKE ?
	UNION SELECT
		leltar_compound.note
		FROM leltar_compound
		WHERE leltar_compound.note LIKE ?

	UNION SELECT
		leltar_batch.name
		FROM leltar_batch
		WHERE leltar_batch.name LIKE ?
			AND leltar_batch.is_active = 1
	UNION SELECT
		leltar_batch.lot
		FROM leltar_batch
		WHERE leltar_batch.lot LIKE ?
			AND leltar_batch.is_active = 1
	UNION SELECT
		leltar_manfac.name
		FROM
			leltar_batch INNER JOIN leltar_manfac USING (manfac_id)
		WHERE leltar_manfac.name LIKE ?
			AND leltar_batch.is_active = 1
	UNION SELECT
		leltar_batch.note
		FROM leltar_batch
		WHERE leltar_batch.note LIKE ?
			AND leltar_batch.is_active = 1

	UNION SELECT
		api_summary.name
		FROM api_summary
		WHERE api_summary.name LIKE ?
	UNION SELECT
		api_summary.form
		FROM api_summary
		WHERE api_summary.form LIKE ?
	UNION SELECT
		api_summary.crystallinity
		FROM api_summary
		WHERE api_summary.crystallinity LIKE ?
	UNION SELECT
		api_summary.particle_size
		FROM api_summary
		WHERE api_summary.particle_size LIKE ?
	UNION SELECT
		api_summary.pri_indication
		FROM api_summary
		WHERE api_summary.pri_indication LIKE ?
	UNION SELECT
		api_summary.sec_indication
		FROM api_summary
		WHERE api_summary.sec_indication LIKE ?
	UNION SELECT
		api_summary.note
		FROM api_summary
		WHERE api_summary.note LIKE ?

	ORDER BY search
	LIMIT 15
	');
	$stmt->bind_param('ssssssssssssssssssss', $query, $query, $query, $query, $query,
																						$query, $query, $query, $query, $query,
																						$query, $query, $query, $query, $query,
																						$query, $query, $query, $query, $query);
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();

	if (!$result) {
		throw new leltar_exception('sql_fail', 1);
	}

	return $result;
}
?>