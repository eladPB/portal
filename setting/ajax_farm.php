<?php
/**
 * Created by PhpStorm.
 * farm: bar
 * Date: 16/06/2016
 * Time: 6:58 PM
 */
require_once("../inc/init.php");

$farmid =  $util->GetPost('farmid');
if ($farmid=='' || !is_numeric($farmid)):
	// we are in a NEW farm form.
	$farmid=0;
endif;
//echo $farmid;exit;

if (isset($_GET['csrf_token'])):
	if ($_GET['csrf_token'] == $_SESSION['last_csrf_token']):
		// CSRF is possible.
		$action = $util->GetPost('action');
		switch ($action):
			case 'delete_farm':
				if ($farmid>0):
//					$answer = array('err'=>0,'err_description'=>'');
					if ($my_db->deleteFarm($farmid)):
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
