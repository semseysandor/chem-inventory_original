/**
 * JavaScript for inventory
 *********************************************************/

/**
 * Start inventory
 */
function inventoryStart() {
	retrieveData('exec/inventory.php?q=start', 'index');
	erasePopup();
}

/**
 * Truncate missing packs table
 */
function inventoryTruncateMissing() {
	if (confirm('Töröljük a hiányzó kiszereléseket?') == true) {
		retrieveData('exec/inventory.php?q=clear', 'index');
	}
}

/**
 * Select a location to scan
 *
 * @param		location_id
 */
function inventoryLocation(locationID) {
	retrieveData('exec/inventory.php?q=location&loc_id=' + locationID, 'inventory', inventoryFocusBarcode);
}

/**
 * Retrieve packs at a given location
 *
 * @param		location_id
 */
function inventoryLoadMissing(locationID) {
	retrieveData('exec/inventory.php?q=load_missing&loc_id=' + locationID, 'inventory', inventoryFocusBarcode);
}

/**
 * Scan barcode
 *
 * @param		barcode
 */
function inventoryScan(barcode, locationID) {
	retrieveData('exec/inventory.php?q=scan&barcode=' + barcode + '&loc_id=' + locationID, 'inventory_scan');

	inventoryFocusBarcode();
}

/**
 * Give focus to the barcode scan input
 */
function inventoryFocusBarcode() {

	try {
		var element = document.getElementById('barcode');

		element.value = '';
		element.focus();
	} catch (exception) {
		return;
	}
}

/**
 * Delete all missing packs
 */
function inventoryDeleteMissing() {

	if (confirm('Jól meggondoltad?') == true) {
		executeServer('exec/inactivate_all_missing.php', 'msg_center', inventoryStart);
	}
}

/**
 * Delete one missing package
 *
 * @param		int		packID
 */
function inventoryDeletePack(packID) {

	if (confirm('Töröljük a kiszerelést a hiányzó listáról?') == true) {
		retrieveData('exec/inventory.php?q=delete&pid=' + packID, 'msg_center', retrieveData, 'exec/inventory.php?q=show_missing', 'missing_packs');
	}
}