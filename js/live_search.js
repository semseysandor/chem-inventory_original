/**
 * Live Search Library
 *********************************************************/

/**
 * Live search
 */
function liveSearch(input) {
	
	console.log(input);
	
	retrieveData('exec/retrieve.php?q=live_search&search=' + input, 'live_search');
}