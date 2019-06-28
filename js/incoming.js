/**
 * JavaScript for incoming
 *********************************************************/

/**
 * Select a compound from search results
 *
 * @param		ID		compound ID
 * @param		name	compound name
 */
function selectCompound(ID, name) {

	// Retrieves batch list
	retrieveData('exec/incoming.php?q=batch&cid=' + ID, 'batch_list');

	// Erase compound list
	document.getElementById('compound_list').innerHTML = '';

	// Show selected compound
	document.getElementById('comp_select').innerHTML = ('Vegyszer: ' + name);
}

/**
 * Select a batch from batch list
 *
 * @param		ID				batch ID
 * @param		manfac		maufacturer
 * @param		name			batch name
 * @param		lot				LOT#
 */
function selectBatch(ID, manfac, name, lot) {

	// Retrieves pack list
	retrieveData('exec/incoming.php?q=pack&bid=' + ID, 'pack_list');

	// Erase batch list
	document.getElementById('batch_list').innerHTML = '';

	// Show selected batch
	document.getElementById('batch_select').innerHTML = ('Termék: ' + manfac + ' - ' + name + ' (' + lot + ')');
}