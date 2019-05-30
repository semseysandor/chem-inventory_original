<?php
/**
 * Error handling
 *********************************************************/

// Set error reporting level
error_reporting(E_ALL);

// Setting top level exception handler for uncaught exceptions
set_exception_handler('toplevel');

// Error flag
$error_flag = FALSE;

/**
 * Top level exception handler
 *
 * @param		exception		to handle
 */
function toplevel($exception) {

	// Message to screen
	echo '<div class="message error">';
	echo 'FATAL ERROR!<br/><br/>';
	echo 'Please try again...<br/><br/>';
	echo 'If problem persist alert system manager!<br/><br/>';
	echo 'Message: '.$exception->getMessage();
	echo '</div>';

	// Message to log
	$msg = '########################'.PHP_EOL;
	$msg .= date('D, Y. M d. H:i').PHP_EOL;
	$msg .= $exception->getMessage().PHP_EOL;
	$msg .= 'CODE: '.$exception->getCode().' in: '.$exception->getFile().' @ line: '.$exception->getLine().PHP_EOL;

	error_logging($msg);
}

/**
 * Write error to the log
 *
 * @param		string		error message
 */
function error_logging($error_msg) {

	$error_log = fopen(ROOT.'/log/error_log.txt', 'ab');

	if ($error_log) {
		fwrite($error_log, $error_msg);
		fclose($error_log);
	}
}

/**
 * Custom exception class
 */

class leltar_exception extends Exception {

	/**
	 * User friendly error message
	 * 
	 * @return	string	user-friendly error message
	 */
	public function error_message() {

		// Short error_msg to user-friendly
		$msg = $this->getMessage();
		switch ($msg) {
			case 'no_id':
				return 'ID hiányzik';
				break;
			case 'not_valid_id':
				return 'Nem érvényes ID';
				break;
			case 'no_conn_db':
				return 'Nem sikerült csatlakozni az adatbázishoz';
				break;
			case 'sql_fail':
				return 'Hiba az adatbázis lekérdezés során';
				break;
			case 'no_record':
				return 'Nincsenek tételek';
				break;
			case 'no_such_record':
				return 'Nincs ilyen tétel az adatbázisban';
				break;
			case 'no_right':
				return 'Nincs jogosultságod ehhez a művelethez :(';
				break;
			default:
				return $msg;
				break;
		}
	}

	/**
	 * Displays error in HTML
	 */
	public function display_error() {

		// Error message
		$error = $this->error_message();
	 
		// Error message box
		echo message('#', $error);
	}

	/**
	 * Default Error Handling Process
	 *
	 * 1. display error in HTML
	 * 2. if fatal error -> show footer then terminate script
	 */
	public function error_handling() {

		// Display error
		$this->display_error();

		// If FATAL ERROR (error code=1) --> terminate script
		if ($this->getCode() == 1) {exit;}
	}
}?>