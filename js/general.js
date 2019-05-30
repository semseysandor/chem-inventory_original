/**
 * General JavaScript library
 *********************************************************/

/**
 * Function for debugging
 */
function debug() {
	for (var i = 0; i < arguments.length; i++) {
		console.log('type=' + typeof(arguments[i]) + ' value=' + arguments[i]);
	}
}

/**
 * Put message in HTML message box
 *
 * @param		string		message
 * @param		string		flag
 *
 * @return	string		Message in HTML container
 */
function messageHTML(message, flag) {

	// Put response in container
	var response = '';

	if (flag == 'pos') { // Positive response
		response += '<div class="message positive"><i class="fas fa-smile fa-lg"></i>'
	} else if (flag == 'neg') { // Negative response
		response += '<div class="message negative"><i class="far fa-frown fa-lg"></i>';
	} else {
		response += '<div class="message">';
	}

	response += ' ' + message.toString() + '</div>';

	return response;
}

/**
 * Build HTML query string from arguments
 *
 * @format
 *	?argument[0]=argument[1]&argument[odd]=argument[even]
 *
 * @return
 *	query string
 */
function queryBuilder() {

	var query = '';

	// Loop through arguments
	for (var i = 0; i < arguments.length; i = i + 2) {

		if (arguments[i] !== undefined && arguments[i+1] !== undefined) {

			if (i == 0) { // First arguments
				query += '?';
			} else {
				query += '&';
			}

			query += arguments[i] + '=' + arguments[(i+1)];
		}
	}

	return query;
}

/**
 * Delete content of all child of an HTML element
 *
 * @param		string		ID of HTML element
 */
function eraseAllChild(elementID) {

	var nodes = document.getElementById(elementID).childNodes; // Get all child nodes

	for (var i = 0; i < nodes.length; i++) {
		if (nodes[i]) {
			nodes[i].innerHTML = ''; // Clear content
		}
	}
}

/**
 * Erase message center
 */
function eraseMessageCenter() {

	var msgCenter = document.getElementById('msg_center');

	if (msgCenter) {
		msgCenter.innerHTML = '';
	}
}

/**
 * Erase Popup
 */
function erasePopup() {

	var popup = document.getElementById('popup');

	if (popup) {
		popup.innerHTML = '';
	}
}

/**
 * Get the closest (parent)element of a given element by class
 *
 * @param		element		el			The element to start from
 * @param		string		clazz		The class name
 * @return	element						The closest element
 */
function closestByClass(el, clazz) {

	try {
		while (!el.classList.contains(clazz)) { // Traverse the DOM up

			// Increment the loop to the parent node

			el = el.parentNode;

			if (el.tagName == 'HTML') { // At the top of DOM
				return false;
			}
		}
	} catch (exception) {return false;}

	return el;
}

/**
 * Redirect to URL
 *
 * @param		string		url
 */
function redirect(url) {
	window.location.replace(url);
}