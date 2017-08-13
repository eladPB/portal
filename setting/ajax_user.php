<?php
/**
 * Created by PhpStorm.
 * User: bar
 * Date: 16/06/2016
 * Time: 6:58 PM
 */
require_once("../inc/init.php");

$userid =  $util->GetPost('userid');
if ($userid=='' || !is_numeric($userid)):
	// we are in a NEW USER form.
	$userid=0;
endif;
//echo $userid;exit;

if (isset($_GET['csrf_token'])):
	if ($_GET['csrf_token'] == $_SESSION['last_csrf_token']):
		// CSRF is possible.
		$action = $util->GetPost('action');
		switch ($action):
			case 'delete_user':
				if ($userid>0):
//					$answer = array('err'=>0,'err_description'=>'');
					if ($my_db->deleteUser($userid)):
						exit;
//					else:
//						var_dump($my_db->con->error());
//						exit;
					endif;
				endif;
				break;
		endswitch;
	endif;
endif;
//if (!isset($answer)):
//	$answer = array('err'=>1,'err_description'=>'No actionable item');
//endif;

http_response_code(400);
