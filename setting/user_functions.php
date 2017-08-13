<?php
/**
 * Created by PhpStorm.
 * User: bar
 * Date: 03/06/2016
 * Time: 12:52 PM
 */

$uploadOk=-1;
$image = '';
$first_name = '';
$last_name  = '';
$user_name = '';
$pass = '';
$role = '';
$language = '';
$bi_user = '';
$bi_pass = '';
$user_details = '';

if ($userid>0):
	//fetch user details
	$user_details = (array) $my_db->User_Get_Details($userid);
	if (!isset($user_details['id'])):
//		$page_nav["setting"]["sub"]["new_user"]["active"] = true;
        $page_nav["setting"]["sub"]["user"]["active"] = true;
		$userid=0;
	else:
		$page_nav["setting"]["sub"]["user"]["active"] = true;
	endif;
else:
//	$page_nav["setting"]["sub"]["new_user"]["active"] = true;
    $page_nav["setting"]["sub"]["user"]["active"] = true;
endif;

include(DIRECTORY . "/inc/nav.php");
$status = -1;
if (isset($_POST['csrf_token'])):
	if ($_POST['csrf_token'] == $_SESSION['last_csrf_token']):
		// if a form was submitted SAVE
		$first_name = $util->GetPost('first_name');
		$last_name  = $util->GetPost('last_name');
		$user_name = $util->GetPost('user_name');
		$language = $util->GetPost('language');
		$pass = $util->GetPost('pass');
		$role = $util->GetPost('role');
		$bi_user = $util->GetPost('bi_user');
		$bi_pass = $util->GetPost('bi_pass');
	else:
		$status=4; // submission error
	endif;
else:
	if ($userid>0):
		// A form was NOT submitted
		$first_name = $user_details['first_name'];
		$last_name  = $user_details['last_name'];
		$user_name = $user_details['username'];
		$role = $user_details['role'];
		$language = $user_details['language'];
		$bi_user = $user_details['bi_user'];
		$bi_pass = $user_details['bi_pass'];
	endif;

endif;

// Handle image upload.
if ( (isset($_FILES['image'])) && !empty( $_FILES["image"]["name"] )  && $_SESSION['privileges']==2):
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["image"]["name"]);
	$uploadOk = 1;
	// check mimetype
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$finfo = new finfo(FILEINFO_MIME_TYPE);
	$fileContents = file_get_contents($_FILES['image']['tmp_name']);
	$mimeType = $finfo->buffer($fileContents);
//	echo $imageFileType.' '. $mimeType;exit;
	if (strtolower($imageFileType) == 'jpg' && $mimeType == 'image/jpeg'):
		$check = getimagesize($_FILES["image"]["tmp_name"]);
		if ($check !== false):
//			        echo "File is an image - " . $check["mime"] . ".";
			$tmp_dir = str_replace('\\','/',ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir());
			while (true) {
				$middle_file = uniqid('user_upd_', true);
				if (!file_exists($tmp_dir .'/'. $middle_file)) break;
			}
			$middle_file = $tmp_dir.'/'.$middle_file;
			move_uploaded_file($_FILES["image"]["tmp_name"], $middle_file);
			$util->resize_image($middle_file,240,200,false);
			$image = $middle_file;
//			echo 'replacing image with: '.$middle_file;exit;
//			$user_details['image']='';
			$uploadOk = 1;
		else:
			        echo "File is not an image.";exit;
			$uploadOk = 0;
		endif;
//	else:
//		echo 'The file is not a jpeg file. '.$imageFileType.' - '.$mimeType;exit;
	endif;
else:
//	if ($userid>0):
//		$image = $user_details['image'];
//	endif;
    $image = '';

endif;
//exit;


// Register a new user START
if ($userid == 0 && $user_name!= '' && $pass != '' && $first_name != '' && $last_name != ''
	&& $image != '' && $role != '' && $language != '' && $bi_user != '' && $bi_pass != ''):
	// Check if the user exists
	if ($my_db->User_Get_Details_By_UserName($user_name)):
		$status = 5;
	else:
		if ($my_db->User_register($user_name, $pass, $first_name, $last_name, $image, $role, $language, $bi_user, $bi_pass)):
			//    echo mysql_error();
			$status = 1;

			// check the last id of the insert
			$User_Id = $my_db->User_Get_User_Id($user_name);
			// move the profile image to a folder:
			$target_file = DIRECTORY . '/img/profile/' . $User_Id . '.jpg';

			$image = file_get_contents($middle_file);
			//		echo 'asdfasdfasdf ';exit;
			if (!rename($middle_file,$target_file)):
				echo 'there was an issue!!!';exit;
			endif;
		else:
			$status = 0;
		endif;
	endif;
//else:
//	echo 'shouldnt arrive here';exit;
endif;
// Register a new user END

// Update an existing user START
if ($userid>0 && $first_name != '' && $last_name != '' && $language != ''):
	//check if userid is of current user.
	if (isset($_POST['csrf_token']) && isset($_SESSION['privileges'])):
		if ($_SESSION['privileges']==2 || ($_SESSION['privileges']<2 && $userid == $_SESSION['user_id'])):
			if ($my_db->User_update($userid, $user_name, $pass, $first_name, $last_name, $image, $role, $language, $bi_user, $bi_pass)):
				// user updated successfully
				$status = 3;
				if ($uploadOk==1):
					$target_file = DIRECTORY.'/img/profile/'.$userid.'.jpg';
					$image = file_get_contents($middle_file);
					if (!rename($middle_file,$target_file)):
						echo 'there was an issue!!!';exit;
					endif;
				endif;
			else:
				$status = 0;
			endif;
		endif;
	endif;
else:
	// no update
//	echo '-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= wtf =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- ?';exit;
endif;
// Update an existing user END