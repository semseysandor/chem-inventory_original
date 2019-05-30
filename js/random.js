/**
 * Random JavaScript functions
 *********************************************************/

/**
 * Gets compound info based on CAS
 */
function getCompoundInfo() {

	// Edit form
	var form = document.getElementById('e_comp');

	if (!form) {return;}

	// Retrieve URL
	var url = 'exec/retrieve.php?q=comp_info&cas=' + form.cas.value;

	var xhttp = new XMLHttpRequest(); // New AJAX request

	xhttp.onreadystatechange = function() {

		// Set cursor back to default
		if (this.readyState == 4) {
			document.body.style.cursor = 'auto';
		}

		if ((this.readyState == 4) && (this.status == 200)) { // When response is ready from server

			// Parse JSON
			try {
				var responseObj = JSON.parse(this.responseText);
			} catch (exception) {
				return;
			}

			// Set form inputs
			if (responseObj.iupac != null) {
				form.iupac.value = responseObj.iupac;
			}
			if (responseObj.chem_form != null) {
				form.chem_form.value = responseObj.chem_form;
			}
			if (responseObj.smiles != null) {
				form.smiles.value = responseObj.smiles;
			}
			if (responseObj.mol_weight != null) {
				form.mol_weight.value = responseObj.mol_weight;
			}
		}
	};

	// Send request
	xhttp.open('GET', url, true);
	xhttp.send();

	// Set cursor for progress
	document.body.style.cursor = 'progress';
}

/**
 * Inactivate
 *
 * @param		int			bid		batch ID
 * @param		int			pid		pack ID
 * @param		string				barcode
 */
function inactivate(bid, pid, barcode) {

	var url = 'exec/inactivate.php?bid=' + bid + '&pid=' + pid;

	if (confirm('Valóban töröljük?') == true) {
		executeServer(url, 'msg_center', retrieveData, 'exec/search.php?q=' + barcode, 'index');
	}
}