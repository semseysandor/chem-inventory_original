/**
 * Live Search Library
 *********************************************************/

/**
 * Live search
 */
function liveSearch(input) {
	
	var container = document.getElementById('live_search');

	if (input != '') {
		retrieveData('exec/retrieve.php?q=live_search&search=' + input, 'live_search', show, container);
	} else {
		container.innerHTML = '';
	}
}