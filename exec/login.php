<?php
/**
 * EXECUTE
 *
 * Login
 *********************************************************/

$config = require('../default.php');

// Init session
session_start();

// Abort script if session not loaded
if (session_status() != PHP_SESSION_ACTIVE) {exit;}

try {

	if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['data'])) {

		// Init response
		$response = [];

		// Decode JSON
		$data = json_decode($_POST['data'], TRUE);

		// Sanitizing user input
		if (empty($data['user']) or (clean_input($data['user'])) == '') {
			$response['text'] = 'Felhasználónév hiányzik';
			$error_flag = TRUE;
		} elseif (empty($data['pass']) or (clean_input($data['pass'])) == '') {
			$response['text'] = 'Jelszó hiányzik';
			$error_flag = TRUE;
		} else {
			$login = clean_input($data['user']);
			$password = clean_input($data['pass']);
		}

		if (!$error_flag) { # No error on input

			// Authenticate user with LDAP
			if (authenticate($login, $password)) { # Success

				// Search user in DB
				if (!search_user($link, $login)) { # User not in the DB -> put it in

					if (sql_insert_user($link, $login, $_SESSION['USER_NAME'])) { # User now in DB -> can search it

						if (search_user($link, $login)) { # Now it should be found
							// Welcome! :)
							$response['text'] = 'Sikeres belépés';
						}

					} else {
						$response['text'] = 'Nem sikerült a felhasználót létrehozni';
						$error_flag = TRUE;
					}
				} else { # User is the DB
					// Welcome! :)
					$response['text'] = 'Sikeres belépés';
				}

			} else { # LDAP login failed
				$response['text'] = 'Érvénytelen felhasználónév vagy jelszó';
				$error_flag = TRUE;
			}
		}
	}
} catch (leltar_exception $e) {$e->error_handling();}

// Set response flag
$response['flag'] = ($error_flag ? 'neg' : 'pos');

// Encode response to JSON
$response = json_encode($response, JSON_UNESCAPED_UNICODE);
echo $response;?>