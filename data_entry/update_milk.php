<?php
/**
 * Created by PhpStorm.
 * User: bs
 * Date: 22/05/2016
 * Time: 08:16
 */

/**
 *
 * Example of input:
 * value:13
 * id:
 * indexid:3
 * morning_quantity:13
 * keyid:morning_quantity
 * farmid:12
 *
 */

//initilize the page
require_once("../inc/init.php");
$rowid='-1';
$farmid='-1';
$key='0';
$value='0';
if (isset($_POST['indexid']) && is_numeric($_POST['indexid'])):
	$rowid = $_POST['indexid'];
endif;
if (isset($_POST['farmid']) && is_numeric($_POST['farmid'])):
	$farmid = $_POST['farmid'];
endif;
if (isset($_POST['keyid']) && $_POST['keyid']!=''):
	$key = $_POST['keyid'];
endif;
if (isset($_POST[$_POST['keyid']]) && $_POST[$_POST['keyid']]!=''):
	$value = $_POST[$_POST['keyid']];
endif;

if ($my_db->Update_Milk($farmid, $rowid, $key, $value)):
	echo $value;
endif;

?>