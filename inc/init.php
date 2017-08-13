<?php

if (session_id() == ''):
    session_start();
	// Create a new CSRF token.
	if (isset($_SESSION['new_csrf_token'])) {
		$_SESSION['last_csrf_token'] = $_SESSION['new_csrf_token'];
	}
	$_SESSION['new_csrf_token'] = base64_encode(openssl_random_pseudo_bytes(32));

endif;

	define('DIRECTORY',realpath(dirname(__FILE__).'\..\\'));
require_once(DIRECTORY."\lib\config.php");


?>