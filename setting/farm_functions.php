<?php
/**
 * Created by PhpStorm.
 * User: bs
 * Date: 03/06/2016
 * Time: 13:54
 */

$farm_id =  $util->GetPostOrZero('farm_id');
$status = -1;
$state = "none";

$page_nav["setting"]["sub"]["farm"]["active"] = true;

if ($farm_id>0):
    //fetch farm details
    $farm_details = (array) $my_db->Farm_Get_Details($farm_id);
    if (!isset($farm_details['id'])):
        $state = "new_farm";
        $farm_id=0;
    else:
        $state = "existing_farm";
        if(isset($_POST['csrf_token']) && $_POST['csrf_token'] == $_SESSION['last_csrf_token']):
            $state = "update_existing_farm";
        endif;
    endif;
else:
    $state = "new_farm";
    if(isset($_POST['csrf_token']) && $_POST['csrf_token'] == $_SESSION['last_csrf_token']):
        $state = "save_new_farm";
    endif;
endif;

include(DIRECTORY . "/inc/nav.php");


if (($state ==  "new_farm") || ($state ==  "save_new_farm")|| ($state ==  "update_existing_farm")):
    $farm_name = $util->GetPost('farm_name');
    $alias = $util->GetPost('alias');
    $address = $util->GetPost('address');

    if (! isset($_GET['StartDate'])):
        $start_date = date('Y-m-d');
    else:
        $start_date = $util->GetPost('start_date');
    endif;

    $qlikview_code = $util->GetPost('qlikview_code');
    $country = $util->GetPost('country');

    $diary_milk_username = $util->GetPost('diary_milk_username');
    $diary_milk_password = $util->GetPost('diary_milk_password');
    $diary_milk_type = $util->GetPost('diary_milk_type');
    $diary_milk_enable = $util->GetPostOrZero('diary_milk_enable');

    $sensor_username = $util->GetPost('sensor_username');
    $sensor_password = $util->GetPost('sensor_password');
    $sensor_type = $util->GetPost('sensor_type');
    $sensor_enable = $util->GetPostOrZero('sensor_enable');

    $feed_center_username = $util->GetPost('feed_center_username');
    $feed_center_password = $util->GetPost('feed_center_password');
    $feed_center_type = $util->GetPost('feed_center_type');
    $feed_center_enable = $util->GetPostOrZero('feed_center_enable');

    $bacarit_username = $util->GetPost('bacarit_username');
    $bacarit_password = $util->GetPost('bacarit_password');
    $bacarit_type = $util->GetPost('bacarit_type');
    $bacarit_enable = $util->GetPostOrZero('bacarit_enable');

    $milk_production_username = $util->GetPost('milk_production_username');
    $milk_production_password = $util->GetPost('milk_production_password');
    $milk_production_type = $util->GetPost('milk_production_type');
    $milk_production_enable = $util->GetPostOrZero('milk_production_enable');

    $data_entry_enable = $util->GetPostOrZero('data_entry_enable');
    $analytics_enable = $util->GetPostOrZero('analytics_enable');


elseif ($state == "existing_farm"):
    // form was submitted SAVE
    $farm_name = $farm_details['name'];
    $alias = $farm_details['alias'];
    $address = $farm_details['address'];
    $start_date = $farm_details['start_date'];

    $qlikview_code = $farm_details['external_code'];
    $country = $farm_details['country'];

    $diary_milk_username = $farm_details['diary_milk_username'];
    $diary_milk_password = $farm_details['diary_milk_password'];
    $diary_milk_type = $farm_details['diary_milk_type'];
    $diary_milk_enable = $farm_details['diary_milk_enable'];

    $sensor_username = $farm_details['sensor_username'];
    $sensor_password = $farm_details['sensor_password'];
    $sensor_type = $farm_details['sensor_type'];
    $sensor_enable = $farm_details['sensor_enable'];

    $feed_center_username = $farm_details['feed_center_username'];
    $feed_center_password = $farm_details['feed_center_password'];
    $feed_center_type = $farm_details['feed_center_type'];
    $feed_center_enable = $farm_details['feed_center_enable'];

    $bacarit_username = $farm_details['bacarit_username'];
    $bacarit_password = $farm_details['bacarit_password'];
    $bacarit_type = $farm_details['bacarit_type'];
    $bacarit_enable = $farm_details['bacarit_enable'];

    $milk_production_username = $farm_details['milk_production_username'];
    $milk_production_password = $farm_details['milk_production_password'];
    $milk_production_type = $farm_details['milk_production_type'];
    $milk_production_enable = $farm_details['milk_production_enable'];

    $data_entry_enable = $farm_details['data_entry_enable'];
    $analytics_enable = $farm_details['analytics_enable'];
endif;


if (isset($_POST['csrf_token']) && $_POST['csrf_token'] == $_SESSION['last_csrf_token']):
    // if a form was submitted SAVE
    if ($state == "save_new_farm"):
        // INSERT
        if ($my_db->Farm_register($farm_name ,$alias ,$address,$start_date, $qlikview_code,  $country,  $diary_milk_username,  $diary_milk_password,  $diary_milk_type,  $diary_milk_enable,  $sensor_username,  $sensor_password,  $sensor_type,  $sensor_enable,  $feed_center_username,  $feed_center_password,  $feed_center_type,  $feed_center_enable,  $bacarit_username,  $bacarit_password,  $bacarit_type,  $bacarit_enable,  $milk_production_username,  $milk_production_password,  $milk_production_type,  $milk_production_enable,  $data_entry_enable, $analytics_enable)):
            $status = 1;
        endif;
    elseif ($state == "update_existing_farm"):
        // Update
        if ($my_db->Farm_Update_Details($farm_id, $alias, $address, $start_date, $farm_name, $qlikview_code, $country, $diary_milk_username, $diary_milk_password, $diary_milk_type, $diary_milk_enable, $sensor_username, $sensor_password, $sensor_type, $sensor_enable, $feed_center_username, $feed_center_password, $feed_center_type, $feed_center_enable, $bacarit_username, $bacarit_password, $bacarit_type, $bacarit_enable, $milk_production_username, $milk_production_password, $milk_production_type, $milk_production_enable, $data_entry_enable , $analytics_enable)):
            $status = 3;
        endif;
    endif;
endif;
















