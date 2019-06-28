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
	retrieveData('exec/retrieve.php?q=list_pack_incoming&bid=' + ID, 'section_pack_list');

	// Erase batch list
	document.getElementById('section_batch_list').innerHTML = '';

	// Show selected batch
	document.getElementById('h3_selected_batch').innerHTML = ('Termék: ' + manfac + ' - ' + name + ' (' + lot + ')');

	eraseMessageCenter();
}

/**
 * Submit compound search form
 *
 * @param		query		form or string
 */
function submitCompoundSearch(query) {

	// Search url
	var url = 'exec/retrieve.php?q=search_compound_incoming&search=';
	var search = '';

	if (typeof(query) == 'string') {
		search = query;
	} else if (typeof(query) == 'object') {
		search = query.text_compound.value; // If query is a form -> form input id: text_compound
	}

	eraseAllChild('incoming');

	// Set form input to search query
	document.getElementById('form_search_comp').text_compound.value = search;

	url += search;

	// Retrieve compounds
	retrieveData(url, 'section_compound_list');
}