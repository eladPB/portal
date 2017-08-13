<?php
/**
 * Created by PhpStorm.
 * User: bar
 * Date: 03/06/2016
 * Time: 4:16 PM
 */

//require_once("../inc/init.php");

if ($status>-1):?>
	<!-- widget grid -->
	<section id="widget-grid" class="">
	<?php
	$ui = new SmartUI;
	$ui->start_track();
	$alert_success = SmartUI::print_alert($lang['USER_WAS_SAVED_SUCCESSFULLY'], 'success', array(), true);
	$alert_updated = SmartUI::print_alert($lang['USER_WAS_UPDATED_SUCCESSFULLY'], 'success', array(), true);
	$alert_danger = SmartUI::print_alert($lang['CANNOT_SAVE_USER_DETAILS'], 'danger', array('closebutton'=>false), true);
	$submission_error = SmartUI::print_alert($lang['CANNOT_RESUBMIT_AN_OLD_FORM'], 'warning', array('closebutton'=>false), true);
	$alert_failed = SmartUI::print_alert($lang['ERROR_SUBMITING_DETAILS'], 'warning', array('closebutton'=>false), true);
	$user_exists_error = SmartUI::print_alert($lang['USER_ALREADY_EXISTS'], 'danger', array('closebutton'=>false), true);

	// snippet
	switch ($status):
		case 0:
			$body = $alert_danger;
			break;
		case 1:
			$body = $alert_success;
			break;
		case 2:
			$body = $alert_failed;
			break;
		case 3:
			$body = $alert_updated;
			break;
		case 4:
			$body = $submission_error;
			break;
		case 5:
			$body = $user_exists_error;
			break;
	endswitch;

	// print html output
	$ui->create_widget()->body('content', $body)
        ->header('title', $lang['ALERTS'])->print_html();
endif;