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

if (isset($_POST['email']) &&  isset($_POST['csrf_token'])):
	if ($_POST['csrf_token'] == $_SESSION['last_csrf_token']):
		$token = $my_db->User_get_reset_pass_token($_POST['email']);
//		echo 'Token URL needs to be sent by email. '.$token;
		require_once('lib/phpmailer.php');
		require_once('lib/phpmailer.smtp.php');
		$mail = new PHPMailer;

//		$mail->SMTPDebug = 3;                               // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'No-reply@projectbar.co.il';        // SMTP username
		$mail->Password = 'dm123456!';                        // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to

		$mail->setFrom('No-reply@projectbar.co.il', 'Project Bar Website');
//		$mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
		$mail->addAddress($_POST['email']);               // Name is optional
//		$mail->addReplyTo('info@example.com', 'Information');
//		$mail->addCC('cc@example.com');
//		$mail->addBCC('bcc@gmail.com');

//		$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//		$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = 'Website password reset requested';
		$mail->Body    = 'Hello A website password reset has been requested for your account.<Br />Please click on the following link to continue: <a href="http://www.projectbar.net/resetpassword.php?token='.$token.'">Reset my password</a>';
		$mail->AltBody = strip_tags($mail->Body);

		if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {?>
			<html>
			<head>
				<meta http-equiv="refresh" content="3; url=http://projectbar.net/" />
			</head>
			<body>An email message was sent with a reset password link. Please check your email.<br />You will now be redirected back to the portal.</body>
			</html>
			<?exit;
		}

		// Send forgot password mail.

		exit;
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
				<form action="forgotpassword.php" method="post" enctype="multipart/form-data" id="forgotpassword-form" class="smart-form client-form" novalidate="novalidate">
					<header>
						Forgot Password
					</header>
					<fieldset>
						<section>
							<label class="label">Enter your email address</label>
							<label class="input"> <i class="icon-append fa fa-envelope"></i>
								<input type="email" name="email">
								<b class="tooltip tooltip-top-right"><i class="fa fa-envelope txt-color-teal"></i> Please enter email address for password reset</b></label>
						</section>
					</fieldset>
					<footer>
						<button type="submit" class="btn btn-primary">
							<i class="fa fa-refresh"></i> Reset Password
						</button>
					</footer>
					<input type="hidden" name="csrf_token" value="<?= $_SESSION['new_csrf_token']?>" />
				</form>
			</div>
		</div>
	</div>

<?php //include required scripts
include(DIRECTORY . "/inc/scripts.php");
?>

