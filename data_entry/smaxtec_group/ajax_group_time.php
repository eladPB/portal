<?php
/**
 * Created by PhpStorm.
 * farm: bar
 * Date: 16/06/2016
 * Time: 6:58 PM
 */
require_once("../../inc/init.php");

$groupid =  $util->GetPost('groupid');
if ($groupid=='' || !is_numeric($groupid)):
    // we are in a NEW farm form.
    $groupid=0;
endif;
//echo $groupid;exit;

if (isset($_GET['csrf_token'])):
    if ($_GET['csrf_token'] == $_SESSION['last_csrf_token']):
        // CSRF is possible.
        $action = $util->GetPost('action');
        switch ($action):
            case 'delete_group':
                if ($groupid>0):
//					$answer = array('err'=>0,'err_description'=>'');
                    if ($my_db->Group_Time_Delete_By_Id($_SESSION['farm_id'],$groupid)):
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
