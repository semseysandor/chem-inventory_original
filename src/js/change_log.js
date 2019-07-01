/**
 * JavaScript for the Changelog
 *********************************************************/

/**
 * Get change history summary from DB
 *
 * AJAX
 *
 * @param		recordID		ID for the record
 * @param		table				Referenced table
 */
function getChangeHistorySummary(recordID, table) {

	// Containers
	var changeLog = document.getElementById('changelog_' + table + '_' + recordID);
	var changeList = document.getElementById('change_list_' + table + '_' + recordID);
	var changeDetails = document.getElementById('change_detail_' + table + '_' + recordID);

	changeDetails.innerHTML = ''; // Clear changeDetails

	if (changeLog.style.display == 'none' || changeLog.style.display == '') { // If not open

		closeAllDropDown(); // Close other dropdowns

		var xhttp = new XMLHttpRequest(); // New AJAX request

		xhttp.onreadystatechange = function() {

			// Set cursor back to default
			if (this.readyState == 4) {
				document.body.style.cursor = 'auto';
			}

			if ((this.readyState == 4) && (this.status == 200)) { // When response is ready from server

				changeList.innerHTML = this.responseText; // Show response
				changeLog.style.display = 'flex'; // Show changeLog
			}
		};

		// Send request
		xhttp.open('GET', 'exec/change_history.php?id=' + recordID + '&table=' + table, true);
		xhttp.send();

		// Set cursor for progress
		document.body.style.cursor = 'progress';

	} else { // If already opened
		changeLog.style.display = 'none'; // Close changeLog
	}
}

/**
 * AJAX
 *
 * Get change history details from DB
 *
 * @param		recordID				ID for the record
 * @param		summaryTable		Referenced table
 * @param		summaryID				ID for change history summary
 */
function getChangeHistoryDetails(recordID, summaryTable, summaryID) {

	// Container for changelog details
	var changeDetails = document.getElementById('change_detail_' + summaryTable + '_' + recordID);

	var xhttp = new XMLHttpRequest(); // New AJAX request

	xhttp.onreadystatechange = function() {

		// Set cursor back to default
		if (this.readyState == 4) {
			document.body.style.cursor = 'auto';
		}

		if ((this.readyState == 4) && (this.status == 200)) { // When response is ready from PHP

			changeDetails.innerHTML = this.responseText; // Show response
			changeDetails.style.display = 'inline-block'; // Show container
		}
	};

	// Send request
	xhttp.open('GET', 'exec/change_history.php?id=' + summaryID, true);
	xhttp.send();

	// Set cursor for progress
	document.body.style.cursor = 'progress';
};