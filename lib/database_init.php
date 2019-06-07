<?php
/**
 * DataBase init
 *********************************************************/

// DB login info
$db = [
'host' => 'localhost',
'user' => 'leltar_USER',
'pass' => 'leltar',
'name' => 'inventory'
];

// Connect to SQL server
try {

	// Open a link to the database
	$link = new mysqli($db['host'], $db['user'], $db['pass'], $db['name']);

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