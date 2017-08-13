<?php
/**
 * Created by PhpStorm.
 * User: bar
 * Date: 14/06/2016
 * Time: 8:44 PM
 */
require_once("init.php");

list ($bi_user,$bi_pass)= $my_db->User_Get_Bi_Details_By_UserName($_SESSION['user_id']);

$http_server = '54.210.52.142/QvAJAXZfc/opendoc.htm?document=DairyFarm_Analysis%2FControl%20Room.qvw&host=QVS%40win-qha7m6aogva';

$opts = array('http' =>
				  array(
					  'method'  => 'GET',
					  'header'=>"Content-Type: text/html; charset=utf-8",
					  'timeout' => 60
				  )
);
/*
// POST
$opts = array('http' =>
  array(
    'method'  => 'POST',
    'header'  => "Content-Type: text/xml\r\n".
      "Authorization: Basic ".base64_encode("$https_user:$https_password")."\r\n",
    'content' => $body,
    'timeout' => 60
  )
);
*/

$context  = stream_context_create($opts);
$url = 'http://'.$bi_user.':'.$bi_pass.'@'.$http_server.'?randkey='.rand(0,2000);
$result = file_get_contents($url, false, $context, -1, 40000);
echo $result;
