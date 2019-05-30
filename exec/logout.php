<?php
/**
 * EXECUTE
 *
 * Logout user
 *********************************************************/

// Resume session
session_start();

// Abort script if session not loaded
if (session_status() != PHP_SESSION_ACTIVE) {exit;}

// Unset session variables
session_unset();

// Destroy session
session_destroy();

if (session_status() == PHP_SESSION_NONE) { # Session closed

	$response['text'] = 'Sikeres kilépés';
	$response['flag'] = 'pos';

} else { # Session not closed

	$response['text'] = 'Kilépés nem sikerült';
	$response['flag'] = 'neg';
}

// Encode response to JSON
$response = json_encode($response, JSON_UNESCAPED_UNICODE);
echo $response;?>