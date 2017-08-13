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

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$login_status = -1;
if (!$user_is_logged_in):
	if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['csrf_token']) && !isset($_COOKIE['email_token'])):
		if ($_POST['csrf_token'] == $_SESSION['last_csrf_token']):
			if ($my_db->User_pass_login($_POST['email'],$_POST['password'],$_POST['remember'])):
				// User is logged in. redirect to the original page:
				header('location: /');

	//			 $_SESSION['user'] = $_POST['email']; // No need it's in the MySQL class.
			else:
				// User is not logged in.
//                session_destroy();
//			echo 'im here';exit;
				$login_status = 1; // user login failed
                unset($_SESSION['user']);
			endif;
		endif;
	elseif (isset($_COOKIE['email_token'])):
		if ($my_db->User_cookie_login($_COOKIE['email_token'])):
			// User is logged in.
			header('location: '.APP_URL);
	//		echo $_COOKIE['email_token'] . ' '.sha1('asdflkjhASFDLKJ1234'.$_COOKIE['email_token']);
		else:
			// user is not logged in
			// unset cookie. it's not valid.
			header('location: '.APP_URL.'/logout.php');
		endif;

	endif;
else:
	header('location: '.APP_URL);
endif;

$page_css[] = "your_style.css";
include(DIRECTORY . "/inc/header.php");


if ($user_is_logged_in):
	$page_nav["setting"]["sub"]["new_user"]["active"] = true;
	include(DIRECTORY . "/inc/nav.php");
endif;
?>
<!-- MAIN PANEL -->
<div id="main" role="main"  <? if (!$user_is_logged_in): ?>style="margin:0 !important;"<?endif;?>>

	<?php
	//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
	//$breadcrumbs["New Crumb"] => "http://url.com"
	$breadcrumbs["setting"] = "";

	if ($user_is_logged_in):
		include(DIRECTORY . "/inc/ribbon.php");
	endif;
	?><br /><br />
	<? if ($user_is_logged_in):?>
	<? else:?>
		<div id="content" class="container">
			<? if ($login_status>-1):?>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6 col-md-push-4 col-lg-4 col-lg-push-4">
					<? include_once('inc/login_error_status.php');?>
				</div>
			</div>
			<?endif;?>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6 col-md-push-4 col-lg-4 col-lg-push-4">
					<div class="well no-padding">
						<form action="login.php" enctype="multipart/form-data" method="post" id="login-form" class="smart-form client-form" novalidate="novalidate">
							<header>
								Sign In
							</header>

							<fieldset>

								<section>
									<label class="label">E-mail</label>
									<label class="input"> <i class="icon-append fa fa-user"></i>
										<input type="email" name="email">
										<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Please enter email address/username</b></label>
								</section>

								<section>
									<label class="label">Password</label>
									<label class="input"> <i class="icon-append fa fa-lock"></i>
										<input type="password" name="password">
										<b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Enter your password</b> </label>
									<div class="note">
										<a href="/forgotpassword.php">Forgot password?</a>
									</div>
								</section>

								<section>
									<label class="checkbox">
										<input type="checkbox" name="remember" >
										<i></i>Stay signed in</label>
								</section>
							</fieldset>
							<footer>
								<button type="submit" class="btn btn-primary">
									Sign in
								</button>
							</footer>
							<input type="hidden" name="csrf_token" value="<?= $_SESSION['new_csrf_token']?>" />
						</form>

					</div>

				</div>
			</div>
		</div>
	<? endif;?>
<!--                --><?php // echo '<pre>' . print_r(get_defined_vars(), true) . '</pre>';?>
</div>

<? if ($user_is_logged_in || $_SERVER['REQUEST_URI']=='/login.php'):?>
<!-- PAGE FOOTER -->
<?php // include page footer
include(DIRECTORY . "/inc/footer.php");
?>
<!-- END PAGE FOOTER -->
<? endif;?>

<?php //include required scripts
include(DIRECTORY . "/inc/scripts.php");
?>

