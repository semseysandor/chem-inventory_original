/**
 * Submitting forms & retrieving data
 *
 * AJAX
 *********************************************************/

/**
 * Retrieves data (GET method)
 *
 * @param		string							url
 * @param		string							responseID
 * @param		callBack						callback function after response
 */
function retrieveData(url, responseID, callBack) {

	// Put all parameters for callback in an array (if available)
	if (callBack !== undefined) {

		var callBackParams = [];
		var j = 0;

		for (var i = 3; i < arguments.length; i++) {
			callBackParams[j] = arguments[i];
			j++;
		}
	}

	var responseContainer = document.getElementById(responseID);

	var xhttp = new XMLHttpRequest(); // New AJAX request

	xhttp.onreadystatechange = function() {

		// Set cursor back to default
		if (this.readyState == 4) {
			document.body.style.cursor = 'auto';
		}

		if ((this.readyState == 4) && (this.status == 200)) { // When response is ready from server

			// Put AJAX in history
			if (responseID == 'index') {
				history.pushState(url, null, null);
			}

			responseContainer.innerHTML = this.responseText; // Show response

			// Perform callback
			if (callBack !== undefined) {
				callBack.apply(this, callBackParams);
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
 * Execute a server script (GET method)
 *
 * @param		string							url
 * @param		string							responseID
 * @param		callBack						callback function after response
 */
function executeServer(url, responseID, callBack) {

	// Put all parameters for callback in an array (if available)
	if (callBack !== undefined) {

		var callBackParams = [];
		var j = 0;

		for (var i = 3; i < arguments.length; i++) {
			callBackParams[j] = arguments[i];
			j++;
		}
	}

	var responseContainer = document.getElementById(responseID);

	var xhttp = new XMLHttpRequest(); // New AJAX request

	xhttp.onreadystatechange = function() {

		// Set cursor back to default
		if (this.readyState == 4) {
			document.body.style.cursor = 'auto';
		}

		if ((this.readyState == 4) && (this.status == 200)) { // When response is ready from PHP

			// Clear responseContainer
			responseContainer.innerHTML = '';

			// Parse JSON
			try {
				var responseObj = JSON.parse(this.responseText);
			} catch (exception) {
				responseContainer.innerHTML = this.responseText;
				return;
			}

			// Exit if no text field in response
			if (responseObj.text == undefined) {return;}

			// If successful
			if (responseObj.flag == 'pos') {

				// Close dropdowns
				closeAllDropDown();

				// Erase popup
				erasePopup();

				// Perform callback
				if (callBack !== undefined) {
					callBack.apply(this, callBackParams);
				}
			}

			// Show response
			responseContainer.innerHTML = messageHTML(responseObj.text, responseObj.flag);
		}
	};

	// Send request
	xhttp.open('GET', url, true);
	xhttp.send();

	// Set cursor for progress
	document.body.style.cursor = 'progress';
}

/**
 * General JS form submit
 *
 * @param		form												the form
 * @param		formResponseID							ID of HTML element to put response from server
 * @param		callBack										callback function after response
 */
function submitForm(form, formResponseID, callBack) {

	// Put all parameters for callback in an array (if available)
	if (callBack !== undefined) {

		var callBackParams = [];
		var j = 0;

		for (var i = 3; i < arguments.length; i++) {
			callBackParams[j] = arguments[i];
			j++;
		}
	}

	// Element to put response from server
	var responseContainer = document.getElementById(formResponseID);

	// Form data to send
	var data = {};

	// Collect the form data while iterating over the inputs
	for (var i = 0; i < form.length; i++) {

		if (form[i].id != '') {
			if (form[i].type == 'checkbox') {
				if (form[i].checked) {
					data[form[i].id] = form[i].value;
				}
			} else {
				data[form[i].id] = form[i].value;
			}
		}
	}

	// Data object to JSON
	data = JSON.stringify(data);
	// URL enncode
	data = encodeURI(data);

	// New AJAX request
	var xhttp = new XMLHttpRequest();

	xhttp.onreadystatechange = function() {

		// Set cursor back to default
		if (this.readyState == 4) {
			document.body.style.cursor = 'auto';
		}

		if ((this.readyState == 4) && (this.status == 200)) { // When response is ready from server

			// Clear responseContainer
			responseContainer.innerHTML = '';

			// Parse JSON
			try {
				var responseObj = JSON.parse(this.responseText);
			} catch (exception) {
				responseContainer.innerHTML = this.responseText;
				return;
			}

			// Exit if no text field in response
			if (responseObj.text == undefined) {return;}

			// If successful
			if (responseObj.flag == 'pos') {

				for (i = 0; i < form.length; i++) {

					if (form[i].type != 'hidden') { // Don't delete hidden inputs
						form[i].value = ''; // Delete form inputs
					}
				}

				// Close dropdowns
				closeAllDropDown();

				// Erase popup
				erasePopup();

				// Perform callback
				if (callBack !== undefined) {
					callBack.apply(this, callBackParams);
				}
			}

			// Show response
			responseContainer.innerHTML = messageHTML(responseObj.text, responseObj.flag);
		}
	};

	// Send request
	xhttp.open(form.method, form.action, true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send('data=' + data);

	// Set cursor for progress
	document.body.style.cursor = 'progress';
}

/**
 * General JS form submit with file upload
 *
 * @param		form												the form
 * @param		formResponseID							ID of HTML element to put response from server
 * @param		callBack										callback function after response
 */
function submitFormUpload(form, formResponseID, callBack) {

	// Put all parameters for callback in an array (if available)
	if (callBack !== undefined) {

		var callBackParams = [];
		var j = 0;

		for (var i = 3; i < arguments.length; i++) {
			callBackParams[j] = arguments[i];
			j++;
		}
	}

	// Element to put response from server
	var responseContainer = document.getElementById(formResponseID);

	// Form data
	var data = new FormData();

	// Flag for successful upload (response from PHP)
	var success = true;

	// Collect the form data while iterating over the inputs
	for (var i = 0; i < form.length; i++) {

		// If file upload
		if (form[i].type == 'file') {

			// For multiple file upload
			for (var j = 0; j < form[i].files.length; j++) {
				data.append(form[i].id + '#' + j, form[i].files[j]);
			}
		}

		// If text or hidden
		if (form[i].type == 'text' || form[i].type == 'hidden') {
			data.append(form[i].id, form[i].value);
		}
	}

	var xhttp = new XMLHttpRequest(); // New AJAX request

	xhttp.onreadystatechange = function() {

		// Set cursor back to default
		if (this.readyState == 4) {
			document.body.style.cursor = 'auto';
		}

		if ((this.readyState == 4) && (this.status == 200)) { // When response is ready from PHP

			// Clear responseContainer
			responseContainer.innerHTML = '';

			// Parse JSON
			try {
				var responseObj = JSON.parse(this.responseText);
			} catch (exception) {
				responseContainer.innerHTML = this.responseText;
				return;
			}

			// Check response
			for (i = 0; i < responseObj.length; i++) {

				// If no flag present
				if (responseObj[i].flag == undefined ) {return;} 

				// If there is a negative response
				if (responseObj[i].flag == 'neg') {
					success = false;
				}

				responseContainer.innerHTML += messageHTML(responseObj[i].text, responseObj[i].flag);
			}

			// If successful
			if (success == true) {

				for (i = 0; i < form.length; i++) {

					if (form[i].type != 'hidden') { // Don't delete hidden inputs
						form[i].value = ''; // Delete form inputs
					}
				}

				// Close dropdowns
				closeAllDropDown();

				// Erase popup
				erasePopup();

				// Perform callback
				if (callBack !== undefined) {
					callBack.apply(this, callBackParams);
				}
			}
		}
	};

	// Send request
	xhttp.open(form.method, form.action, true); 
	xhttp.send(data);

	// Set cursor for progress
	document.body.style.cursor = 'progress';
}