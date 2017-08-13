<?php //initilize the page
require_once("../inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("../inc/config.ui.php");
$util->IsIAdmin();
/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = $lang['USER'];

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include(DIRECTORY . "/inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php

$userid =  $util->GetPost('userid');
if ($userid=='' || !is_numeric($userid)):
	// we are in a NEW USER form.
	$userid=0;
endif;
include(DIRECTORY . '/setting/user_functions.php');
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
						<?= ($userid>0)?$lang['UPDATEUSER']:$lang['NEWUSER'] ?>
					</span>
				</h1>
			</div>
		</div>
<? include(DIRECTORY . '/setting/user_error_status.php') ?>
            <div class="row">
            <!-- NEW COL START -->
            <article class="col-sm-12 col-md-12 col-lg-12">
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget jarviswidget-color-blueLight" id="wid-id-3" data-widget-editbutton="false" data-widget-custombutton="false">
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
                    <h2><?= ($userid>0)?$lang['UPDATEUSER']:$lang['NEWUSER'] ?></h2>
                </header>

                <!-- widget div-->
                <div>

                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->

                    </div>
                    <!-- end widget edit box -->

                    <!-- widget content -->
                    <div class="widget-body no-padding">

                        <form id="form_user" class="smart-form" novalidate="novalidate" enctype="multipart/form-data"
                              method="post">
                            <fieldset>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['EMAIL']?>:</label>
                                    <section class="col col-4">
                                        <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                            <input type="text" name="user_name" value="<?= $user_name ?>" autocomplete="off">
                                        </label>
                                    </section>
                                </div>


                                <div class="row">
                                    <label class="label col col-2"><?=$lang['PASSWORD']?>:</label>
                                    <section class="col col-4">
                                        <label class="input"> <i class="icon-prepend fa  fa-lock"></i>
                                            <input type="text" name="pass" value="" autocomplete="off">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['FIRST_NAME']?>:</label>
                                    <section class="col col-4">
                                        <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                            <input type="text" name="first_name" value="<?= $first_name ?>">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['LAST_NAME']?>:</label>
                                    <section class="col col-4">
                                        <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                            <input type="text" name="last_name" value="<?= $last_name ?>">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['IMAGE']?>:</label>
                                    <section class="col col-4">
										<? if (isset($image) && $image!=''):?><img src="data:image/jpeg;base64,<?= base64_encode( $image )?>" width="200" />
											<? elseif (isset($user_details['image']) && $user_details['image']!=''):?><img src="data:image/jpeg;base64,<?= base64_encode( $user_details['image'] )?>" width="200" />
										<? endif;?>
                                        <label class="input">
                                            <i class="icon-prepend fa fa-image"></i>
                                            <input type="file" name="image" accept=".jpg">
                                        </label>

                                        <div class="note">
                                            <strong><?=$lang['NOTE']?>:</strong> <?=$lang['UPLOAD_JPG']?> 120 X 100.
                                        </div>
                                    </section>
                                </div>


                                <div class="row">
                                    <label class="label col col-2"><?=$lang['PRIVILEGES']?>:</label>
                                    <section class="col col-4">
                                        <label class="select">
                                            <i></i>
                                            <select name="role">
                                                <option value="0"><?=$lang['SELECT_PRIVILEGE']?></option>
                                                <option value="1"<?= ($role == 1 ? ' selected="selected"' : '') ?>>
													<?=$lang['USER']?>
                                                </option>
                                                <option value="2"<?= ($role == 2 ? ' selected="selected"' : '') ?>>
													<?=$lang['SYSTEM_ADMINISTRATOR']?>
                                                </option>
                                                <option value="3"<?= ($role == 3 ? ' selected="selected"' : '') ?>>
													<?=$lang['DISABLED']?>
                                                </option>
                                            </select>
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['LANGUAGE']?>:</label>
                                    <section class="col col-4">
                                        <label class="select">
                                            <i></i>
                                            <select name="language">
                                                <option value="0"><?=$lang['SELECT_LANGUAGE']?></option>
                                                <option value="1"<?= ($language == 1 ? ' selected="selected"' : '') ?>>
                                                    עברית
                                                </option>
                                                <option value="2"<?= ($language == 2 ? ' selected="selected"' : '') ?>>
                                                    English
                                                </option>
                                                <option value="3"<?= ($language == 3 ? ' selected="selected"' : '') ?>>
                                                    Chinese
                                                </option>
                                            </select>
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['BIUSER']?>:</label>
                                    <section class="col col-4">
                                        <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                            <input type="text" name="bi_user" value="<?= $bi_user ?>">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['BIPASSWORD']?>:</label>
                                    <section class="col col-4">
                                        <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                            <input type="text" name="bi_pass" value="<?= $bi_pass ?>">
                                        </label>
                                    </section>
                                </div>


                                <footer>
                                    <button class="btn btn-primary" type="submit"><?=$lang['SAVE']?></button>
                                </footer>
								<input type="hidden" name="csrf_token" value="<?= $_SESSION['new_csrf_token']?>" />
								<input type="hidden" name="userid" value="<?= $userid?>" />
                        </form>

                    </div>
                    <!-- end widget content -->

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

<script type="text/javascript">

// DO NOT REMOVE : GLOBAL FUNCTIONS!




var $orderForm = $("#form_user").validate({
    // Rules for form validation
    rules : {
        first_name: {
            required : true
        },
        last_name: {
            required : true
        },
        user_name : {
            required : true,
            email : true
        },
        pass: {
            required :  <?if ($userid > 0): echo "false"; else: echo "true";  endif ?>
        },
        image: {
            required :  <?if ($userid > 0): echo "false"; else: echo "true";  endif ?>
        },
        role: {
            required : true,
            min: 1
        },
        language: {
            required : true,
            min: 1
        },
        bi_user: {
            required : true
        },
        bi_pass: {
            required : true
        }
    },

    // Messages for form validation
    messages : {
        first_name : {
            required : 'Please enter First Name'
        },
        last_name : {
            required : 'Please enter Last Name'
        },
        user_name : {
            required : 'Please enter user name',
            email : 'Please enter a VALID email address'
        },
		pass : {
			required : 'Please enter the password'
		},
        image : {
            required : 'Please enter the image'
        },
        role : {
            required : 'Please select privileges',
            min : 'Please select privileges'
        },
        language : {
            required : 'Please select language',
            min : 'Please select language'
        },
        bi_user : {
            required : 'Please enter the BI user name'
        },
        bi_pass : {
            required : 'Please enter the BI password'
        }
    },

    // Do not change code below
    errorPlacement : function(error, element) {
        error.insertAfter(element.parent());
    }
});

</script>
