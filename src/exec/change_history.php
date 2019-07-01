<?php
/**
 * EXECUTE
 *
 * Show change history of a record
 *********************************************************/

$config = require('../default.php');

// Init
$table_valid = FALSE;

try {

	// Get ID
	$record_id = get_query('id');

	// Check table
	if (isset($_GET['table']) and $_GET['table'] != NULL) { # Table present

		// Get table and clean input
		$table = clean_input($_GET['table']);

		// Check if table is valid
		$allowed_table = ['leltar_compound', 'leltar_batch', 'leltar_pack'];
		if (in_array($table, $allowed_table)) {$table_valid = TRUE;}
	}

	if ($table_valid) { # Table valid & record valid --> show changelog summary

		$result = sql_get_change_history_summary($link, $table, $record_id);

		if ($result->num_rows > 0) { # Data found

			while ($row = $result->fetch_assoc()) { # List results

				// Alias for action
				switch ($row['action']) {
					case 'CREATE':
						$action = 'Létrehozás';
						break;
					case 'UPDATE':
						$action = 'Módosítás';
						break;
					default:
						$action = $row['action'];
						break;
				}

				// Container for change list item
				echo '<div class="changelist_item" '.js_spec('chng_hist_det', [$record_id, $table, $row['summary_id']]).'>';

				echo '<div>'.$action.'</div>';
				echo '<div>'.$row['modified_by'].'</div>';
				echo '<div>'.$row['modified_on'].'</div>';

				echo '</div>';
			}
		} else { # No change history
			echo message('0', 'Nincsenek előzmények');
		}
	} else { # Table NOT valid but record valid --> show changelog details

		$result = sql_get_change_history_details($link, $record_id);

		if ($result->num_rows > 0) { # Data found

			echo '<table class="change_details">';
			echo '<thead>';
			echo '<tr><th>Mit</th><th>Miről</th><th>Mire</th></tr>';
			echo '</thead>';

			echo '<tbody>';

			while ($row = $result->fetch_assoc()) {

				// Alias for column_name
				switch ($row['column_name']) {
					case 'compound_id':
						$column = 'Compound ID';
						break;
					case 'name':
						$column = 'Név';
						break;
					case 'name_alt':
						$column = 'Egyéb név';
						break;
					case 'abbrev':
						$column = 'Rövidítés';
						break;
					case 'chemical_name':
						$column = 'Kémiai név';
						break;
					case 'usp_name':
						$column = 'USP név';
						break;
					case 'iupac_name':
						$column = 'IUPAC név';
						break;
					case 'chem_formula':
						$column = 'Összegképlet';
						break;
					case 'cas':
						$column = 'CAS';
						break;
					case 'smiles':
						$column = 'SMILES';
						break;
					case 'sub_category_id':
						$column = 'Alkategória ID';
						break;
					case 'handbook':
						$column = 'Handbook link';
						break;
					case 'fda':
						$column = 'FDA elfogadott';
						break;
					case 'fda_dose':
						$column = 'FDA max dózis';
						break;
					case 'oeb':
						$column = 'OEB';
						break;
					case 'mol_weight':
						$column = 'Mw [g/mol]';
						break;
					case 'melting_point':
						$column = 'T<sub>olv</sub> [°C]';
						break;
					case 'solubility':
						$column = 'Oldhatóság';
						break;
					case 'note':
						$column = 'Megjegyzés';
						break;
					case 'last_mod_by':
						$column = 'Utoljára módosította';
						break;
					case 'batch_id':
						$column = 'Batch ID';
						break;
					case 'manfac_id':
						$column = 'Gyártó';
						break;
					case 'lot':
						$column = 'LOT#';
						break;
					case 'date_arr':
						$column = 'Érkezett';
						break;
					case 'date_open':
						$column = 'Bontva';
						break;
					case 'date_exp':
						$column = 'Lejár';
						break;
					case 'date_arch':
						$column = 'Archiválva';
						break;
					case 'tg':
						$column = 'T<sub>g</sub> [°C]';
						break;
					case 'is_active':
						$column = 'Aktív?';
						break;
					case 'pack_id':
						$column = 'Pack ID';
						break;
					case 'location_id':
						$column = 'Helyzet';
						break;
					case 'is_original':
						$column = 'Eredeti?';
						break;
					case 'size':
						$column = 'Kiszerelés';
						break;
					case 'weight':
						$column = 'Tömeg';
						break;
					case 'barcode':
						$column = 'Vonalkód';
						break;
					default:
						$column = $row['column_name'];
						break;
				}

				echo '<tr>';
				echo '<td class="centered">'.$column.'</td>';
				echo '<td class="centered">'.(($row['old_value']) ? $row['old_value'] : '---').'</td>';
				echo '<td class="centered">'.(($row['new_value']) ? $row['new_value'] : '---').'</td>';
				echo '</tr>';
			}
			echo '</tbody></table>';
		} else { # No data available
			echo message('0', 'Nem található adat');
		}
	}
} catch (leltar_exception $e) {
	$e->display_error();
	exit;
}?>