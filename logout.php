<?php
session_start();
if (isset($_COOKIE['email_token'])):
	unset($_COOKIE['email_token']);
	unset($_COOKIE['email_token']);
	setcookie('email_token', null, -1, '/');
	setcookie('email_token', null, -1, '/');
endif;
if(!isset($_SESSION['user'])):

else:
    session_destroy();
    unset($_SESSION['user']);
endif;
header("Location: /");
?>