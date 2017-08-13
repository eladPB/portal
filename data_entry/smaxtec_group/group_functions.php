<?php
/**
 * Created by PhpStorm.
 * User: bar
 * Date: 03/06/2016
 * Time: 12:52 PM
 */

$userid = $util->GetPostOrZero('userid');
$group_id = $util->GetPostOrZero('group_id');
$name_group = "";

$shower_start_time = "";
$shower_end_time = "";


if ($group_id > 0):
    //fetch user details
    $group_details = (array)$my_db->Group_Get_Details($group_id);
    if (!isset($group_details['id'])):
        // In correct ID no such Group
        $group_id = "";
        $name_group = "";
    else:
        // Group Exist
        $group_id = $group_details['id'];
        $name_group = $group_details['name'];
    endif;
endif;

include(DIRECTORY . "/inc/nav.php");
$status = -1;

if (isset($_POST['csrf_token'])):
    if ($_POST['csrf_token'] == $_SESSION['last_csrf_token']):
        // if a form was submitted SAVE
        $group_id = $util->GetPost('group_id');
        $name_group = $util->GetPost('name_group');
    else:
        $status = 4; // submission error
    endif;
else:
    if ($group_id > 0):
        // A form was NOT submitted
//		$name_group = $group_details['name_group'];
//		$group_id = $group_details['group_id'];
    endif;
endif;

// Register a new user START
if ($group_id == 0 && $name_group != ''):
    // Check if the user exists
    if ($my_db->Group_Get_Details_By_Name($name_group)):
        $status = 5;
    else:
        if ($my_db->Group_Register($_SESSION['farm_id'], $name_group)):
//			    echo mysql_error();
            $status = 1;
        else:
            $status = 0;
        endif;
    endif;
//else:
//	echo 'shouldnt arrive here';exit;
endif;
// Register a new user END

// Update an existing user START
if ($group_id > 0 && $name_group != ''):
    //check if userid is of current user.
    if (isset($_POST['csrf_token']) && isset($_SESSION['privileges'])):
        if ($_SESSION['privileges'] == 2 || ($_SESSION['privileges'] < 2 && $userid == $_SESSION['user_id'])):
            if ($my_db->Group_Update($group_id, $name_group)):
                // user updated successfully
                $status = 3;
            else:
                $status = 0;
            endif;
        endif;
    endif;
else:
    // no update
//	echo '-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= wtf =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- ?';exit;
endif;
// Update an existing group END
?>