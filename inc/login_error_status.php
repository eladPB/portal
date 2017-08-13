<?php
/**
 * Created by PhpStorm.
 * User: bar
 * Date: 03/06/2016
 * Time: 4:16 PM
 */
if ($login_status>-1):?>
	<!-- widget grid -->
	<section id="widget-grid" class="">
	<?php
	$ui = new SmartUI;
	$ui->start_track();
//	$alert_success = SmartUI::print_alert('<strong>User was saved successfully</strong>', 'success', array(), true);
//	$alert_danger = SmartUI::print_alert('<strong>Cannot save user details</strong>!', 'danger', array('closebutton'=>false), true);
	$submission_error = SmartUI::print_alert('<strong>You cannot log in with those credentials.</strong>', 'warning', array('closebutton'=>false), true);
//	$alert_failed = SmartUI::print_alert('<strong>There was an error submiting the details.</strong>', 'warning', array('closebutton'=>false), true);
//	$user_exists_error = SmartUI::print_alert('<strong>The user already exists.</strong>', 'danger', array('closebutton'=>false), true);

	// snippet
	switch ($login_status):
		case 0:
//			$body = $alert_danger;
			$body = '';
			break;
		case 1:
			$body = $submission_error;
			break;
	endswitch;
	$options = array(
		"editbutton" => false,
		"colorbutton" => false,
		"collapsed" => false,
		"fullscreenbutton" => false,
		"togglebutton" => false
	);
	$contents = array(
		"body" => $body,
		"header" => array(
			"icon" => 'fa fa-exclamation',
			"title" => '<h2>Alerts</h2>'
		)
	);
	// print html output
	$ui->create_widget($options,$contents)->print_html();
endif;