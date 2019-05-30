<?php
/**
 * Default config
 *********************************************************/

// Root
define('ROOT', __DIR__);

// Standard includes
require(ROOT.'/lib/function_lib.php');
require(ROOT.'/lib/error.php');
require(ROOT.'/lib/database_init.php');
require(ROOT.'/lib/database_select.php');
require(ROOT.'/lib/database_insert.php');
require(ROOT.'/lib/database_update.php');
require(ROOT.'/lib/event_handlers.php');
require(ROOT.'/templates/buttons.php');

// Array to store config
return [

	// HTML title
	'title' => 'NGX Leltár',

	// Stylesheets
	'stylesheet' => [
									'style/general.css',
									'style/table.css',
									'style/form.css',
									'style/message.css',
									'style/special.css',
									'style/button.css',
									'icons/fa-icon.css',
									'style/rwd.css',
									'style/containers.css'
									]
	]
?>