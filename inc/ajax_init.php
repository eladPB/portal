<?php

if (session_id() == ''):
    session_start();
endif;

	define('DIRECTORY',realpath(dirname(__FILE__).'\..\\'));
require_once(DIRECTORY."\lib\config.php");


?>