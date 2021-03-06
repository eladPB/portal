<?php //initilize the page
require_once("../../inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("../../inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = $lang['GROUP'];

/* ---------------- END PHP GROUP Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include(DIRECTORY . "/inc/header.php");
$page_nav ["data_entry"] ["sub"] ["group_mng"] ["sub"] ["group_time"] ["active"] = true;

include(DIRECTORY . '/data_entry/smaxtec_group/group_time_functions.php');

$group_list = $my_db->GetGroupList($_SESSION['farm_id']);
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">

    <?php
    //configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
    //$breadcrumbs["New Crumb"] => "http://url.com"
    $breadcrumbs ["Data Entry"] = "";

    include(DIRECTORY . "/inc/ribbon.php");
    ?>

    <!-- MAIN CONTENT -->
    <div id="content">
        <div class="row">
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-5">
                <h1 class="page-title txt-color-blueDark">
                    <!-- PAGE HEADER -->
                    <i class="fa fa-table fa-fw "></i><?=$lang['DATA_ENTRY']?> <span>>
                        <?=$lang['SMAXTEC']?></span>
                    <span>>
                        <?= ($group_time_id>0)?$lang['UPDATE_GROUP']:$lang['GROUP_TIME'] ?>
					</span>
                </h1>
            </div>
        </div>
        <? include(DIRECTORY . '/data_entry/smaxtec_group/group_time_error_status.php') ?>
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
                        <h2><?= ($group_time_id>0)?$lang['GROUP_TIME_UPDATE']:$lang['GROUP_TIME_NEW'] ?></h2>
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

                            <form id="form_group" class="smart-form" novalidate="novalidate" enctype="multipart/form-data" method="post">
                                <fieldset>

                                    <div class="row">
                                        <label class="label col col-2"><?= $lang['GROUP'] ?>:</label>
                                        <section class="col col-4">
                                            <label class="select">
                                                <i></i>
                                                <select name="group_id">
                                                    <option value="0"><?= $lang['SELECT_GROUP'] ?></option>
                                                    <? foreach ($group_list as $group):
                                                        ?>
                                                        <option value="<?= $group['id'] ?>" <?= ($group_id == $group['id'] ? ' selected="selected"' : '') ?> >
                                                            <?= $group['name'] ?>
                                                        </option>
                                                    <? endforeach; ?>
                                                </select>
                                            </label>
                                        </section>
                                    </div>

                                    <div class="row">
                                        <label class="label col col-2"><?= $lang['NAME'] ?>:</label>
                                        <section class="col col-4">
                                            <label class="input">
                                                <input type="text" name="name_group" value="<?= $name_group ?>">
                                            </label>
                                        </section>
                                    </div>

                                    <header>
                                        <b><?= $lang['TIME'] ?></b>
                                    </header>

                                    <div class="row">
                                        <label class="label col col-2"><?= $lang['START_TIME'] ?></label>
                                        <section class="col col-2">
                                            <label class="input" placeholder="Select time"> <i class="icon-prepend fa fa-clock-o"></i>
                                                <input type="text" id="timepicker_start_time" name="start_time" value="<?= $start_time ?>">
                                            </label>
                                        </section>
                                    </div>

                                    <div class="row">
                                        <label class="label col col-2"><?= $lang['END_TIME'] ?></label>
                                        <section class="col col-2">
                                            <label class="input" placeholder="Select time"> <i class="icon-prepend fa fa-clock-o"></i>
                                                <input type="text" id="timepicker_end_time" name="end_time" value="<?= $end_time ?>">
                                            </label>
                                        </section>
                                    </div>

                                    <header>
                                        <b><?= $lang['COLOR'] ?></b>
                                    </header>

                                    <div class="row">
                                        <label class="label col col-2"><?= $lang['CHOOSE_COLOR'] ?></label>
                                        <section class="col col-2">
                                            <label class="input">
                                                <input class="form-control" name="color" id="color" type="text" value="<?=$color?>" data-color-format="rgba">
                                            </label>
                                        </section>
                                    </div>

                                    <footer>
                                        <button class="btn btn-primary" type="submit"><?= $lang['SAVE'] ?></button>
                                    </footer>
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['new_csrf_token'] ?>"/>
                                    <input type="hidden" name="group_time_id" value="<?=$group_time_id?>"/>
                            </form>
                        </div>
                        </fieldset>

                    </div>
                    <!-- end widget content -->

                </div>
                <!-- end widget div -->

        </div>
        <!-- end widget -->
        </article>
        <!-- END COL -->
<!--        --><?php // echo '<pre>' . print_r(get_defined_vars(), true) . '</pre>';?>
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
<script src="<?php echo ASSETS_URL; ?>/js/plugin/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/colorpicker/bootstrap-colorpicker.min.js"></script>
<script type="text/javascript">

    // DO NOT REMOVE : GLOBAL FUNCTIONS!




    var $orderForm = $("#form_group").validate({
        // Rules for form validation
        rules : {
            name_group: {
                required : true
            },
            group_id: {
                required : true,
                min : 1
            }
        },

        // Messages for form validation
        messages : {
            name_group : {
                required : 'אנא בחר שם'
            },
            group_id : {
                required : 'אנא בחר קבוצה',
                min : 'אנא בחר קבוצה'
            }
        },

        // Do not change code below
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        }
    });


    /*
     * TIMEPICKER
     */

    $('#timepicker_start_time').timepicker({
        minuteStep: 1,
        showMeridian: false
    });

    $('#timepicker_end_time').timepicker({
        minuteStep: 1,
        showMeridian: false
    });

    $('#color').colorpicker()




</script>
