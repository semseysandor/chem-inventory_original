/**
 * JavaScript library for dropdowns
 *********************************************************/

/**
 * Toggle dropdown
 *
 * @param		string		ID of element (dropdown content)
 */
function dropDown(element) {

	var content = document.getElementById(element);

	if (content.style.display == 'inline-block') { // If opened

		hide(content) // Close it

	} else { // If not opened

		closeAllDropDown(); // Close other dropdowns
		show(content) // Open this one
	}
}

/**
 * Show an element
 *
 * @param		element
 */
function show(elem) {
	elem.style.display = 'inline-block';
};

/**
 * Hide an element
 *
 * @param		element
 */
function hide(elem) {
	elem.style.display = 'none';
};

/**
 * Close all dropdown on page
 */
function closeAllDropDown() {

	var allDropDown = document.querySelectorAll('.drop-right, .drop-left'); // All open dropdowns

	for (i = 0; i < allDropDown.length; i++) { // Close them
		allDropDown[i].style.display = 'none';
	}
}

/**
 * Close every opened element (dropdown, popup, messages)
 */
function closeAllOpened (event) {

	var allOpened = document.querySelectorAll('.drop-right, .drop-left, .no-show'); // All open dropdowns

	for (i = 0; i < allOpened.length; i++) { // Close them
		allOpened[i].style.display = 'none';
	}

	erasePopup(); // Erase popup
	eraseMessageCenter(); // Erase message center
}

/**
 * Close dropdown if clicked outside of it
 *
 * @param		event
 */
function closeOpenedDropDown(event) {

	var element = closestByClass(event.target, 'dropdown'); // Check element (or parent) has class dropdown

	if (!element) { // Element hasn't got class dropdown -> it was clicked outside
		closeAllDropDown();
	}
}

/**
 * Close everything on ESC keyCode
 *
 * @param		event
 */
function closeOnESC(event) {

	var key = event.keyCode;

	if (key == 27) { // ESC key
		closeAllOpened();
	}
}