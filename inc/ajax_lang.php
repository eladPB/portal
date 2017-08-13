<?php
/**
 * Created by PhpStorm.
 * User: dpointk
 * Date: 20/06/2016
 * Time: 10:20 PM
 */
require_once("../inc/init.php");

if ($util->GetPost('lang') != '' && strlen($util->GetPost('lang'))==2):
	$_SESSION['language'] = $util->GetPost('lang');
	exit;
endif;
http_response_code(400);
exit;