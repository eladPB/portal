<?php

//configure constants

$document_root = realpath($_SERVER['DOCUMENT_ROOT']);

// $base_url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ) . '://' .
//     $_SERVER['HTTP_HOST'];
// if(strpos($directory, $document_root)===0) {
//     $base_url .= str_replace(DIRECTORY_SEPARATOR, '/', substr($directory, strlen($document_root)));
// }
$base_url = "";
//$base_url = "http://ec2-54-165-190-160.compute-1.amazonaws.com/Portal";
#http://52.91.213.205
#192.168.0.111

// Database
$config["database"] = "cowshed";
$config["host"]= "localhost";
$config["username"]= "cowshed";
$config["password"]= "cowshed";

require_once(DIRECTORY."/lib/MySQL.php");
$my_db = new MySQL($config);
require_once(DIRECTORY."/lib/general_util.php");
$util = new general_util();

// Language Addition:
require_once(DIRECTORY.'/lib/Language.php');
if (isset($_SESSION['language'])):
	$lang = new Language($_SESSION['language']);
else:
	$lang = new Language('en');
endif;
$lang = $lang->userLanguage();


//require_once("vendor/autoload.php");
//
//$db = new medoo([
//    'database_type' => 'mysql',
//    'database_name' => $config["database"],
//    'server' => $config["host"],
//    'username' => $config["username"],
//    'password' => $config["password"],
//    'charset' => 'utf8'
//]);

//require_once ("db.class.php");
//$db = new DB ( $config ['database'], $config ['host'], $config ['username'], $config ['password'] );

// LOAD USER DETAILS START

// require_once("...");
ini_set('date.timezone','Asia/Jerusalem');
//ini_set('date.timezone','Europe/Berlin');

$user_is_logged_in = false;
//if (isset($_SESSION['user']) || if there is a cookie set with the information): @TODO
if (isset($_SESSION['user'])):
	$user_is_logged_in = true;
//	echo 'user is logged in';
else:
	$user_is_logged_in = false;
//    echo 'user is not logged in';

    if( (strpos($_SERVER['REQUEST_URI'], "/login.php") !== FALSE) || (strpos($_SERVER['REQUEST_URI'], "/forgotpassword.php") !== FALSE) || (strpos($_SERVER['REQUEST_URI'], "/resetpassword.php") !== FALSE)){
        // User is not logged in, but he is in  Login page.
    } else{
        // User is not logged in, and not in  Login page.
        // Soo redirect to Login Page
        header('Location: ' . $base_url . '/login.php');
    }

endif;

// LOAD USER DETAILS END

defined("APP_URL") ? null : define("APP_URL", str_replace("/lib", "", $base_url));
//Assets URL, location of your css, img, js, etc. files
defined("ASSETS_URL") ? null : define("ASSETS_URL", APP_URL);


//require library files
//require_once("util.php");

require_once("func.global.php");

require_once("smartui/class.smartutil.php");
require_once("smartui/class.smartui.php");

// smart UI plugins
require_once("smartui/class.smartui-widget.php");
require_once("smartui/class.smartui-datatable.php");
require_once("smartui/class.smartui-button.php");
require_once("smartui/class.smartui-tab.php");
require_once("smartui/class.smartui-accordion.php");
require_once("smartui/class.smartui-carousel.php");
require_once("smartui/class.smartui-smartform.php");
require_once("smartui/class.smartui-nav.php");

SmartUI::$icon_source = 'fa';

// register our UI plugins
SmartUI::register('widget', 'Widget');
SmartUI::register('datatable', 'DataTable');
SmartUI::register('button', 'Button');
SmartUI::register('tab', 'Tab');
SmartUI::register('accordion', 'Accordion');
SmartUI::register('carousel', 'Carousel');
SmartUI::register('smartform', 'SmartForm');
SmartUI::register('nav', 'Nav');

require_once("class.html-indent.php");
require_once("class.parsedown.php");

?>