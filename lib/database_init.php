<?php
/**
 * DataBase init
 *********************************************************/

// Connect to SQL server
try {

	// Open a link to the database
	$link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	// If error in connection
	if ($link->connect_error) {
		throw new leltar_exception('no_conn_db',1);
	}

} catch (leltar_exception $e) {$e->error_handling();}

// Show all errors: DEVELOPMENT ONLY
//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
// Don't show errors: PRODUCTION ONLY
mysqli_report(MYSQLI_REPORT_OFF);

// Set character set
$link->set_charset('utf8');?>