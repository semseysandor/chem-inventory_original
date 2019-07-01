/**
 * Drawing structure from smiles
 *
 *********************************************************/

/**
 * Get SMILES from DB
 *
 * @param		type of ID
 * @param		ID
 */
function getSmiles(type, ID) {

	var smiles = '';
	var url = 'exec/retrieve.php?q=smiles&';
	var xhttp = new XMLHttpRequest(); // New AJAX request

	xhttp.onreadystatechange = function() {

		// Set cursor back to default
		if (this.readyState == 4) {
			document.body.style.cursor = 'auto';
		}

		if ((this.readyState == 4) && (this.status == 200)) { // When response is ready from PHP

			smiles = this.responseText;

			if (smiles != '') {
				drawStructure(smiles); // Draw structure
			}
		}
	};

	// Search URL
	if (type == 'compID') {
		url += 'cid=' + ID;
	} else if (type == 'barcode') {
		url += 'barcode=' + ID;
	} else {
		return false;
	}

	// Send request
	xhttp.open('GET', url, true);
	xhttp.send();

	// Set cursor for progress
	document.body.style.cursor = 'progress';
}

/**
 * Draw structure from Smiles
 *
 * @param		string		smiles
 */
function drawStructure(smiles) {

	// Options for SmilesDrawer
	var options = {width:400, height:300};

	// Initialize the drawer
	var smilesDrawer = new SmilesDrawer.Drawer(options);

	// Parse input
	SmilesDrawer.parse(smiles, function(tree) {
		// Draw to the canvas
		smilesDrawer.draw(tree, 'structure', 'light', false);
	});
}