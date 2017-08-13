<?php
/**
 * Created by PhpStorm.
 * User: bar
 * Date: 03/06/2016
 * Time: 4:16 PM
 */
if ($status>-1):?>
	<!-- widget grid -->
	<section id="widget-grid" class="">
	<?php
	$ui = new SmartUI;
	$ui->start_track();
	$alert_success = SmartUI::print_alert('<strong>User was saved successfully</strong>', 'success', array(), true);
	$delete_success = SmartUI::print_alert('<strong>Message was deleted successfully</strong>', 'success', array(), true);
	$submission_error = SmartUI::print_alert('<strong>Cannot resubmit an old form. Please try again.</strong>', 'warning', array('closebutton'=>false), true);
	$alert_failed = SmartUI::print_alert('<strong>There was an error submitting the message.</strong>', 'warning', array('closebutton'=>false), true);
	$delete_failed = SmartUI::print_alert('<strong>There was an error deleting the message.</strong>', 'critical', array('closebutton'=>false), true);

	// snippet
	switch ($status):
//		case 1:
//			$body = $alert_success;
//			break;
		case 2:
			$body = $alert_failed;
			break;
		case 4:
			$body = $submission_error;
			break;
		case 5:
			$body = $delete_failed;
			break;
		case 6:
			$body = $delete_success;
			break;
	endswitch;

	// print html output
	$ui->create_widget()->body('content', $body)
        ->header('title', $lang['ALERTS'])->print_html();
endif;