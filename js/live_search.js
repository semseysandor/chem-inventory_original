/**
 * Live Search Library
 *********************************************************/

/**
 * Live search
 */
function liveSearch(input) {

	// Live search container
	var container = document.getElementById('live_search');

	if (input != '') {

		// Get live search results
		retrieveData('exec/retrieve.php?q=live_search&search=' + input, 'live_search', show, container);

	} else {

		// Clear results
		container.innerHTML = '';
	}
}

/**
 * Select search results
 *
 * @param		event		keypress event
 */
function liveSearchSelect(event) {

	// Keycode pressed
	var code = event.keyCode;

	// UP arrow(38)
	// DOWN arrow(40)
	// ENTER (13)
	// INSERT (45)
	if (code == 38 || code == 40 || code == 13 || code == 45) {

		// Currently selected item
		var currentFocus = -1;

		// Live search container
		var x = document.getElementById('live_search');

		// Search result items
		if (x) {x = x.getElementsByTagName('div');}

		// Look for already selected item
		for (var i = 0; i < x.length; i++) {

			if (x[i].classList.contains('focus')) {
				currentFocus = i;
				break;
			}
		}

		if (code == 40) { // Down arrow

			// Remove focus if there is selected item
			if (currentFocus > -1) {
				x[currentFocus].classList.remove('focus');
			}

			// Move focus
			currentFocus++;

			// If end of list
			if (currentFocus >= x.length) {currentFocus = 0;}

			// Add focus
			x[currentFocus].classList.add('focus');

		} else if (code == 38) { // UP arrow

			if (currentFocus > -1) {
				x[currentFocus].classList.remove('focus');
			}

			currentFocus--;

			if (currentFocus < 0) {currentFocus = x.length -1;}

			x[currentFocus].classList.add('focus');

		} else if (code == 13) { // ENTER

			// If there is a selected item
			if (currentFocus > -1) {

				// Prevent submitting the form
				event.preventDefault();

				// Simulate click on item
				x[currentFocus].click();
			}

		} else if (code == 45) { // INSERT

			// If there is a selected item
			if (currentFocus > -1) {

				// Autocomplete the input field
				q.value = x[currentFocus].innerHTML;
			}
		}
	}
}