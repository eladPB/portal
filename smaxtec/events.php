<?php

// initilize the page
require_once("../inc/init.php");

// require UI configuration (nav, ribbon, etc.)
require_once("../inc/config.ui.php");

/*
 * ---------------- PHP Custom Scripts ---------
 *
 * YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 * E.G. $page_title = "Custom Title"
 */

$page_title = $lang['SMAXTEC_EVENTS'];

/* ---------------- END PHP Custom Scripts ------------- */

// include header
// you can add your custom css in $page_css array.
// Note: all css files are inside css/ folder
$page_css [] = "your_style.css";
include(DIRECTORY."/inc/header.php");


// include left panel (navigation)
// follow the tree in inc/config.ui.php
$page_nav ["monitoring"] ["sub"] ["smaxtec"] ["sub"] ["events"] ["active"] = true;
include(DIRECTORY."/inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
	// configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
	// $breadcrumbs["New Crumb"] => "http://url.com"
	$breadcrumbs ["Monitoring"] = "";
    $breadcrumbs["smaxtec"] = "";
	include(DIRECTORY."/inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content" class="container">

		<!-- row -->
		<div class="row">

			<!-- col -->
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-5">
				<h1 class="page-title txt-color-blueDark">
					<!-- PAGE HEADER -->
					<i class="fa fa-lg fa-fw fa-soundcloud"></i> <?=$lang['MONITORING']?> <span>>
                        <?=$lang['SMAXTEC']?> > <?=$lang['EVENTS']?></span>
				</h1>
			</div>
			<!-- end col -->

			<!-- right side of the page with the sparkline graphs -->
		</div>
		<!-- end row -->

		<div class="smart-form row">
		<div class="inline-group checkbox-inline col-md-12">
			<?
$categories = array('Drinking','Health','Feeding','Activity');
foreach ($categories as $category):
			?>
				<? /*<input id="<?= $category?>" type="checkbox" name="checkbox-inline" value="<?= $category?>" checked="checked" > <label for="<?= $category?>"><?= $category?></label>*/ ?>
				<input id="<?= $category?>" value="<?= $category?>" checked="checked" type="checkbox" class="checkbox style-0" />
				<span><?= $category?></span>

<? endforeach?>

<?

$events = $my_db->Get_Smaxtec_Events($_SESSION['farm_id']);
foreach ($events as $event):
	?>

		<!-- row -->
<div class="container <?= $event['category']?>">
	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="well well-sm">
			<!-- Timeline Content -->
			<div class="smart-timeline">
				<ul class="smart-timeline-list">
					<li>
						<div class="smart-timeline-icon">
							<img src="<?= ASSETS_URL; ?>/img/avatars/<?= $event['type'];?>.png" width="24" height="24"/>
						</div>
						<div class="smart-timeline-time">
							<small><?= $event['time'];?></small>
						</div>
						<div class="smart-timeline-content">
							<div class="well-sm display-inline">
								<a style="width:75px; display:inline-block;"><strong><?= $event['name']?></strong></a><img style="margin-left:20px;" src="<? echo ASSETS_URL; ?>/img/avatars/<?= $event['img'];?>" width="32" height="32"/>
									<?= $event['description'];?>
							</div>
						</div>
					</li>


				</ul>
			</div>
			<!-- END Timeline Content -->
		</div>
	</div>
</div>
<? endforeach; ?>
		</div>
		</div>
<!-- ++++++++++++++++++++++++++++++++++++++++++++++++++-->
	<style type="text/css">
<?
foreach ($categories as $category):
?>
.<?= $category?> > .row { margin:0; }
input[value="<?= $category?>"]:checked ~ .<?= $category?>{
	display:inline-block;
}
input[value="<?= $category?>"] ~ .<?= $category?>{
	display:none;
}

<? endforeach?>
/*div input[type=checkbox].checkbox:checked+span:before {
	color: #2E7BCC;
}
div input[type=checkbox].checkbox:checked+span:before {
	content: "\f00c";
}*/
.smart-form .checkbox-inline input {
	border-radius: 0;
	text-align: center;
	vertical-align: middle;
	padding: 1px;
	height: 20px;
	min-width: 20px;
	margin-right: 5px !important;
	margin-left: 5px !important;
	border: 1px solid #bfbfbf;
	background-color: #f4f4f4;
}
div input[type=checkbox].checkbox+span:before {
	content: "\a0";
}
.checkbox-inline input[type=checkbox].checkbox+span {
float:left;
}
/*div input[type=checkbox].checkbox+span:before, label input[type=radio].radiobox+span:before {
	font-family: FontAwesome;
	font-size: 12px;
	border-radius: 0;
	content: "\a0";
	display: inline-block;
	text-align: center;
	vertical-align: middle;
	padding: 1px;
	height: 12px;
	line-height: 12px;
	min-width: 12px;
	margin-right: 5px;
	border: 1px solid #bfbfbf;
	background-color: #f4f4f4;
	font-weight: 400;
	margin-top: -1px;
}*/
.smart-form .inline-group .checkbox, .smart-form {
	/*margin-right:0 !important;*/
	}

input[type=checkbox].checkbox
{
	visibility: visible !important;
	position: relative !important;

}
.checkbox-inline input[type=checkbox]{
	/*margin-left:0 !important;*/
}
.checkbox-inline input[type=checkbox].checkbox+span{
	margin-left:0 !important;
	line-height:28px;
}
	</style>






	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<!-- PAGE FOOTER -->
<?php
// include page footer
include(DIRECTORY."/inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php
// include required scripts
include(DIRECTORY."/inc/scripts.php");
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->

<script>

	$(document).ready(function() {
		// PAGE RELATED SCRIPTS
	})

</script>

<?php
// include footer
include(DIRECTORY."/inc/google-analytics.php");
?>