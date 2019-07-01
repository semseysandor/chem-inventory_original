<?php
/**
 * Checks if user is logged in
 *********************************************************/

// Init session
session_start();

// Abort script if session not loaded
if (session_status() != PHP_SESSION_ACTIVE) {exit;}

// If no USER_ID -> user not logged in, redirect to login
if(!isset($_SESSION['USER_ID']) or intval($_SESSION['USER_ID']) < 1) {

	header('location: /leltar/login.php');
	exit;
}?>