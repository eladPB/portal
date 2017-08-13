<?php
/**
 * Created by PhpStorm.
 * User: bar
 * Date: 14/06/2016
 * Time: 9:18 PM
 */
require_once("../inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("../inc/config.ui.php");
$util->IsIAdmin();
$status = -1;

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = "User";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include(DIRECTORY."/inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php

$userid =  $util->GetPost('userid');
if ($userid=='' || !is_numeric($userid)):
	// we are in a NEW USER form.
	$userid=0;
endif;

if ($userid>0):
	//fetch user details
	$user_details = (array) $my_db->User_Get_Details($userid);
	if (!isset($user_details['id'])):
		$page_nav["setting"]["sub"]["user_farm"]["active"] = true;
		$userid=0;
	else:
		$page_nav["setting"]["sub"]["user_farm"]["active"] = true;
	endif;
else:
	$page_nav["setting"]["sub"]["user_farm"]["active"] = true;
endif;
include(DIRECTORY . "/inc/nav.php");
if (isset($_POST['csrf_token'])):
	if ($_POST['csrf_token'] == $_SESSION['last_csrf_token']):
		// GET POST/GET params
		$duallistbox_farms = (!isset($_POST['duallistbox_farms'])?array():$_POST['duallistbox_farms']);
//		$my_db->
		if ($my_db->setUserFarms($userid, $duallistbox_farms)):
			$status=3;
		else:
			$status=2;
			if (!$result) {
				var_dump($my_db->con->error());
				exit;
			}
		endif;
//		print_r($_POST);exit;
	else:
		$status=4; // submission error
	endif;
else:
	if ($userid>0):
	endif;

endif;


$user_farm = $my_db->GetUserFarm($userid);
//print_r($user_farm);exit;
?>

<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">

	<?php
	//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
	//$breadcrumbs["New Crumb"] => "http://url.com"
	$breadcrumbs["setting"] = "";

	include(DIRECTORY . "/inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-5">
				<h1 class="page-title txt-color-blueDark">
					<i class="fa fa-user fa-fw "></i>
                    <?=$lang['SETTING']?>
					<span>>
                        <?=$lang['CHANGE_USER_FARMS']?>
					</span>
				</h1>
			</div>
		</div>
		<? include(DIRECTORY . '/setting/user_error_status.php') ?>
		<div class="row">
			<!-- NEW COL START -->
			<article class="col-sm-12 col-md-12 col-lg-12">

				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
					<!-- widget options:
					usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

data-widget-colorbutton="false"
					data-widget-editbutton="false"
					data-widget-togglebutton="false"
					data-widget-deletebutton="false"
					data-widget-fullscreenbutton="false"
					data-widget-custombutton="false"
					data-widget-collapsed="true"
					data-widget-sortable="false"

						-->
					<header>
						<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
						<h2><?=$lang['CHOOSE_FARMS']?></h2>
					</header>

					<!-- widget div-->
					<div>

						<form id="form_user" novalidate="novalidate" enctype="multipart/form-data" method="post">
						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->

						<!-- widget content -->
						<div class="widget-body">

							<select multiple="multiple" size="10" name="duallistbox_farms[]" id="initializeDuallistbox">
								<? /*

								<option value="option2">Farm 2</option>
								<option value="option3" selected="selected">Farm 3</option>*/?>
								<? foreach ($user_farm as $farm_line):?>
								<option value="<?= $farm_line['id']?>"<?= (($farm_line['farm_id']!=null)?'selected="selected"':'')?>><?= $farm_line['name']?></option>
								<? endforeach?>
							</select>

						</div>
						<!-- end widget content -->

							<footer>
								<button class="btn btn-primary" type="submit"><?=$lang['SAVE']?></button>
							</footer>
							<input type="hidden" name="csrf_token" value="<?= $_SESSION['new_csrf_token']?>" />
							<input type="hidden" name="userid" value="<?= $user_details['id']?>" />
						</form>

					</div>
					<!-- end widget div -->

				</div>
				<!-- end widget -->
			</article>
			<!-- END COL -->
		</div>
		<!-- end widget grid -->


	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<!-- PAGE FOOTER -->
<?php // include page footer
include(DIRECTORY . "/inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php //include required scripts
include(DIRECTORY . "/inc/scripts.php");
?>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js"></script>
<script type="application/javascript">
	/*
	 * BOOTSTRAP DUALLIST BOX
	 */

	var initializeDuallistbox = $('#initializeDuallistbox').bootstrapDualListbox({
		nonSelectedListLabel: 'Non-selected',
		selectedListLabel: 'Selected',
		preserveSelectionOnMove: 'moved',
		moveOnSelect: true,
		nonSelectedFilter: ''
	});

</script>