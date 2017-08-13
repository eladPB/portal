<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = $lang['SOCIAL_WALL'];

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["dashboard"]["sub"]["social"]["active"] = true;
include("inc/nav.php");
$status = -1;

$userid = $_SESSION['user_id'];
$msg =  $util->GetPost('msg');
$delete_msg = $util->GetPost('delete_msg');
$page=$util->GetPost('page');

$user_details = (array) $my_db->User_Get_Details($_SESSION['user_id']);
$image = $user_details['image'];
if (isset($_POST['csrf_token'])):
	if ($_POST['csrf_token'] == $_SESSION['last_csrf_token']):

		if ($userid>0 && $delete_msg>0):
			if ($my_db->User_Delete_Message($delete_msg)):
				$status = 6;
			else:
				$status = 5;
			endif;
		elseif ($userid > 0 && $msg!= ''):
			// Check if the user exists
			if ($my_db->User_Message($userid, $msg)):
				//    echo mysql_error();
		//		$status = 1;
			else:
				$status = 2;
			endif;
		//else:
		//	echo 'shouldnt arrive here';exit;
		endif;
	else:
//	echo $_POST['csrf_token'] .' aaaa '.$_SESSION['last_csrf_token'].' bbb '.$_SESSION['new_csrf_token'];exit;
		$status = 4;
	endif;
endif;
$messages_per_page = 15;
$num_of_message = $my_db->Get_Messages_Num();
if ($page==''):
	$page = 1;
endif;
$messages = $my_db->Get_Messages($page);
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Misc"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<!-- row -->
		<div class="row">

			<!-- col -->
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
				<h1 class="page-title txt-color-blueDark"><!-- PAGE HEADER --><i class="fa-fw fa fa-home"></i> <?=$lang['DASHBOARD']?> <span>>
                        <?=$lang['SOCIAL_WALL']?> </span></h1>
			</div>
			<!-- end col -->

		</div>
		<!-- end row -->

		<!--
		The ID "widget-grid" will start to initialize all widgets below
		You do not need to use widgets if you dont want to. Simply remove
		the <section></section> and you can use wells or panels instead
		-->

<? include(DIRECTORY . '/setting/user_msgs_error_status.php') ?>

		<!-- widget grid -->
		<section id="widget-grid" class="">

			<!-- row -->
			<div class="row">

				<!-- a blank row to get started -->
				<div class="col-sm-12 col-lg-12">

					<!-- your contents here -->
					<div class="panel panel-default">
						<div class="panel-body status">
							<ul class="comments">
							<? foreach($messages as $msg):?>
								<li>
								<?
								 $msg_user_details = (array) $my_db->User_Get_Details($msg['user_id']);
								 $msg_image = $msg_user_details['image'];
								if (isset($msg_image) && $msg_image!=''):?><img src="data:image/jpeg;base64,<?= base64_encode( $msg_image )?>" class="online" /><? endif;?>
									<span class="name"><?= $msg_user_details['first_name'].' '.$msg_user_details['last_name']?></span>
									<?= $msg['msg'];?>
									<? if ($_SESSION['privileges']==2):?>
									<form id="form_user" class="smart-form" novalidate="novalidate" enctype="multipart/form-data"
								  		method="post">
										<input type="hidden" name="delete_msg" id="delete_msg" class="form-control" value="<?= $msg['id']?>">
										<button class="btn btn-primary" type="submit"><?=$lang['DELETE']?></button>
										<input type="hidden" name="csrf_token" value="<?= $_SESSION['new_csrf_token']?>" />
										<input type="hidden" name="userid" value="<?= $userid?>" />
									</form>
									<? endif?>
								</li>
							<? endforeach;?>
								<li>
									<? if (isset($image) && $image!=''):?><img src="data:image/jpeg;base64,<?= base64_encode( $image )?>" width="200" class="online" /><? endif;?>
									<form id="form_user" class="smart-form" novalidate="novalidate" enctype="multipart/form-data"
								  		method="post">
										<input type="text" name="msg" id="msg" class="form-control" placeholder="Post your comment...">
										<button class="btn btn-primary" type="submit"><?=$lang['SAVE']?></button>
										<input type="hidden" name="csrf_token" value="<?= $_SESSION['new_csrf_token']?>" />
										<input type="hidden" name="userid" value="<?= $userid?>" />
									</form>
								</li>
							</ul>
						</div>
					</div>

				</div>


			</div>

			<!-- end row -->

			<div class="row">
				<?
				if (($num_of_message%$messages_per_page)>0):
					$remainder = 1;
				else:
					$remainder = 0;
				endif;
				$num_of_pages = (int)($num_of_message/$messages_per_page)+$remainder;?>
				<? if ($page>1):?><a href="?page=<?= ($page-1)?>">Previous</a> <?endif;?>Page <?= $page?> of <?= $num_of_pages?><? if($num_of_pages>$page):?> <a href="?page=<?= ($page+1)?>">Next</a><? endif;?>
			</div>

		</section>
		<!-- end widget grid -->

	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<!-- PAGE FOOTER -->
<?php
	// include page footer
	include("inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="<?php echo ASSETS_URL; ?>/js/plugin/YOURJS.js"></script>-->

<script>

	$(document).ready(function() {
		// PAGE RELATED SCRIPTS
	})

</script>

<?php 
	//include footer
	include("inc/google-analytics.php"); 
?>