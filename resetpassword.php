<?php
/**
 * Created by PhpStorm.
 * User: bar
 * Date: 24/05/2016
 * Time: 8:48 PM
 */

//initilize the page
require_once("inc/init.php");
//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

if (isset($_POST['password']) &&  isset($_POST['password2']) &&  isset($_POST['pass_token']) &&  isset($_POST['csrf_token'])):
	if ($_POST['csrf_token'] == $_SESSION['last_csrf_token']):
		//set up the new password in the database
		if ($my_db->User_set_pass_token($_POST['pass_token'], $_POST['password'])):?>
			<html>
			<head>
				<meta http-equiv="refresh" content="5; url=http://projectbar.net/" />
			</head>
			<body>Password has been reset.<br />You will now be redirected back to the portal.</body>
			</html>
		<?exit;
		endif;
	endif;
endif;

$page_css[] = "your_style.css";
include(DIRECTORY . "/inc/header.php");

?>
<!-- MAIN PANEL -->
<div id="main" role="main" style="margin:0 !important;">
	<br /><br />
	<div id="content">

		<div class="col-xs-12 col-sm-12 col-md-6 col-md-push-4 col-lg-4 col-lg-push-4">
			<div class="well no-padding">
				<form action="resetpassword.php" method="post" enctype="multipart/form-data" id="forgotpassword-form" class="smart-form client-form" novalidate="novalidate">
					<header>
						Reset Password
					</header>

					<fieldset>
						<section>
							<label class="label">Enter your new password</label>
							<label class="input"> <i class="icon-append fa fa-lock"></i>
								<input type="password" name="password">
								<b class="tooltip tooltip-top-right"><i class="fa fa-envelope txt-color-teal"></i> Please enter your new password</b></label>
						</section>
						<section>
							<label class="label">Re-enter your new password</label>
							<label class="input"> <i class="icon-append fa fa-lock"></i>
								<input type="password" name="password2">
								<b class="tooltip tooltip-top-right"><i class="fa fa-envelope txt-color-teal"></i> Please enter your new password again</b></label>
						</section>
					</fieldset>
					<footer>
						<button type="submit" class="btn btn-primary">
							<i class="fa fa-refresh"></i> Reset Password
						</button>
					</footer>
					<input type="hidden" name="csrf_token" value="<?= $_SESSION['new_csrf_token']?>" />
					<? if (isset($_GET['token'])):?>
					<input type="hidden" name="pass_token" value="<?= mysql_real_escape_string($_GET['token'])?>" />
					<? endif;?>
				</form>
			</div>
		</div>
	</div>

<?php //include required scripts
include(DIRECTORY . "/inc/scripts.php");
?>

