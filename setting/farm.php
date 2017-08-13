<?php //initilize the page
require_once("../inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("../inc/config.ui.php");
$util->IsIAdmin();
/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = $lang['FARM'];

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include(DIRECTORY . "/inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
//$page_nav["setting"]["sub"]["farm"]["active"] = true;
//include(DIRECTORY . "/inc/nav.php");

//$farm_id =  $util->GetPost('farm_id');
//if ($farm_id=='' || !is_numeric($farm_id)):
//    // we are in a NEW USER form.
//    $farm_id=0;
//endif;
include(DIRECTORY . '/setting/farm_functions.php');

//$farm_name = $util->GetPost('farm_name');
//$qlikview_code = $util->GetPost('qlikview_code');
//$country = $util->GetPost('country');


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
                        <?=$lang['FARM']?>
					</span>
				</h1>
			</div>
		</div>
    <!--Alerts-->
    <? include(DIRECTORY . '/setting/farm_error_status.php') ?>

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
                    <h2><?=$lang['FARM']?></h2>
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

                        <form id="form_farm" class="smart-form" novalidate="novalidate" enctype="multipart/form-data"
                              method="post">
                            <fieldset>
                                <div class="row">
                                    <label class="label col col-2"><?=$lang['FARM_NAME']?>:</label>
                                    <section class="col col-4">
                                        <label class="input">
                                            <input type="text" name="farm_name" value="<?=$farm_name?>">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['ALIAS']?>:</label>
                                    <section class="col col-4">
                                        <label class="input">
                                            <input type="text" name="alias" value="<?=$alias?>">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['ADDRESS']?>:</label>
                                    <section class="col col-4">
                                        <label class="input">
                                            <input type="text" name="address" value="<?=$address?>">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['START_DATE']?>:</label>
                                    <section class="col col-4">
                                        <label class="input"> <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="start_date" id="start_date" placeholder=<?echo $start_date?>>
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['QLIKVIEW_CODE']?>:</label>
                                    <section class="col col-4">
                                        <label class="input">
                                            <input type="text" name="qlikview_code" value="<?=$qlikview_code?>">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['COUNTRY']?></label>
                                    <section class="col col-4">
                                        <label class="select">
                                            <i></i>
                                            <select name="country">
                                                <option value="0"><?=$lang['SELECT_FARM_COUNTRY']?></option>
                                                <option value="1"<?=($country==1?' selected="selected"':'')?>>Israel</option>
                                                <option value="2"<?=($country==2?' selected="selected"':'')?>>China</option>
                                            </select>
                                        </label>
                                    </section>
                                </div>

                            </fieldset>

                                <header>
                                    <b><?=$lang['DIARY_MILK']?>  </b><img src="<?= ASSETS_URL; ?>/img/cowshed/setting/Diary_Milk.png" />
                                </header>

                            <fieldset>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['DIARY_MILK']?></label>
                                    <section class="col col-4">
                                        <label class="select">
                                            <i></i>
                                            <select name="diary_milk_type">
                                                <option value="0"><?=$lang['SELECT_USER_DIARY']?></option>
                                                <option value="1"<?=($diary_milk_type==1?' selected="selected"':'')?>>Tara</option>
                                                <option value="2"<?=($diary_milk_type==2?' selected="selected"':'')?>>Strauss</option>
                                                <option value="3"<?=($diary_milk_type==3?' selected="selected"':'')?>>Tnuva</option>
                                                <option value="4"<?=($diary_milk_type==4?' selected="selected"':'')?>>Muller</option>
                                                <option value="5"<?=($diary_milk_type==5?' selected="selected"':'')?>>Danone</option>
                                                <option value="6"<?=($diary_milk_type==6?' selected="selected"':'')?>>Nestle</option>
                                            </select>
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['USERNAME']?>:</label>
                                    <section class="col col-4">
                                        <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                            <input type="text" name="diary_milk_username" value="<?=$diary_milk_username?>">
                                        </label>
                                    </section>
                                </div>


                                <div class="row">
                                    <label class="label col col-2"><?=$lang['PASSWORD']?>:</label>
                                    <section class="col col-4">
                                        <label class="input"> <i class="icon-prepend fa  fa-lock"></i>
                                            <input type="text" name="diary_milk_password" value="<?=$diary_milk_password?>">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['LICENSE']?>:</label>
                                    <section>
                                        <div class="inline-group col col-4">
                                            <label class="radio">
                                                <input type="radio" value="0" name="diary_milk_enable" <? if($diary_milk_enable == 0 ){ echo 'checked=""'; } ?>  >
                                                <i></i><?=$lang['DISABLE']?></label>
                                            <label class="radio">
                                                <input type="radio" value="1" name="diary_milk_enable" <? if($diary_milk_enable == 1 ){ echo 'checked=""'; } ?>  >
                                                <i></i><?=$lang['ENABLE']?></label>
                                        </div>
                                    </section>
                                    </div>


                            </fieldset>

                            <header>
                                <b><?=$lang['SENSOR']?>  </b><img src="<?= ASSETS_URL; ?>/img/cowshed/setting/sensor.png" />
                            </header>

                            <fieldset>
                                <div class="row">
                                    <label class="label col col-2"><?=$lang['SENSORS']?></label>
                                    <section class="col col-4">
                                        <label class="select">
                                            <i></i>
                                            <select name="sensor_type">
                                                <option value="0"><?=$lang['SELECT_SENSOR_COMPANY']?></option>
                                                <option value="1"<?=($sensor_type==1?' selected="selected"':'')?>>SmaXtec</option>
                                                <option value="2"<?=($sensor_type==2?' selected="selected"':'')?>>Dol Sensors</option>
                                                <option value="3"<?=($sensor_type==3?' selected="selected"':'')?>>BHTC</option>
                                                <option value="4"<?=($sensor_type==4?' selected="selected"':'')?>>Climant Earth</option>
                                                <option value="5"<?=($sensor_type==5?' selected="selected"':'')?>>Temp humidity light intensity sensor </option>
                                            </select>
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['USERNAME']?>:</label>
                                    <section class="col col-4">
                                        <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                            <input type="text" name="sensor_username" value="<?=$sensor_username?>">
                                        </label>
                                    </section>
                                </div>


                                <div class="row">
                                    <label class="label col col-2"><?=$lang['PASSWORD']?>:</label>
                                    <section class="col col-4">
                                        <label class="input"> <i class="icon-prepend fa  fa-lock"></i>
                                            <input type="text" name="sensor_password" value="<?=$sensor_password?>">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['LICENSE']?>:</label>
                                    <section>
                                        <div class="inline-group col col-4">
                                            <label class="radio">
                                                <input type="radio" value="0" name="sensor_enable" <? if($sensor_enable == 0 ){ echo 'checked=""'; } ?>  >
                                                <i></i><?=$lang['DISABLE']?></label>
                                            <label class="radio">
                                                <input type="radio" value="1" name="sensor_enable" <? if($sensor_enable == 1 ){ echo 'checked=""'; } ?>  >
                                                <i></i><?=$lang['ENABLE']?></label>
                                        </div>
                                    </section>
                                </div>
                            </fieldset>
                            

                            <header>
                                <b><?=$lang['FEED_CENTER']?>  </b><img src="<?= ASSETS_URL; ?>/img/cowshed/setting/Feed_Center.png" />
                            </header>

                            <fieldset>
                                <div class="row">
                                    <label class="label col col-2"><?=$lang['FEED_CENTER']?></label>
                                    <section class="col col-4">
                                        <label class="select">
                                            <i></i>
                                            <select name="feed_center_type">
                                                <option value="0"><?=$lang['SELECT_FEED_CENTER']?></option>
                                                <option value="1"<?=($feed_center_type==1?' selected="selected"':'')?>>Ambar</option>
                                                <option value="2"<?=($feed_center_type==2?' selected="selected"':'')?>>Ables</option>
                                                <option value="3"<?=($feed_center_type==3?' selected="selected"':'')?>>Digi Star</option>
                                                <option value="4"<?=($feed_center_type==4?' selected="selected"':'')?>>RMH</option>
                                                <option value="5"<?=($feed_center_type==5?' selected="selected"':'')?>>SILOKING</option>
                                            </select>
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['USERNAME']?>:</label>
                                    <section class="col col-4">
                                        <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                            <input type="text" name="feed_center_username" value="<?=$feed_center_username?>">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['PASSWORD']?>:</label>
                                    <section class="col col-4">
                                        <label class="input"> <i class="icon-prepend fa  fa-lock"></i>
                                            <input type="text" name="feed_center_password" value="<?=$feed_center_password?>">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['LICENSE']?>:</label>
                                    <section>
                                        <div class="inline-group col col-4">
                                            <label class="radio">
                                                <input type="radio" value="0" name="feed_center_enable" <? if($feed_center_enable == 0 ){ echo 'checked=""'; } ?>  >
                                                <i></i><?=$lang['DISABLE']?></label>
                                            <label class="radio">
                                                <input type="radio" value="1" name="feed_center_enable" <? if($feed_center_enable == 1 ){ echo 'checked=""'; } ?>  >
                                                <i></i><?=$lang['ENABLE']?></label>
                                        </div>
                                    </section>
                                </div>
                                
                            </fieldset>

                            <header>
                                <b>Bacarit   </b><img src="<?= ASSETS_URL; ?>/img/cowshed/setting/bacarit.png" />
                            </header>

                            <fieldset>
                                <div class="row">
                                    <label class="label col col-2">Bacarit</label>
                                    <section class="col col-4">
                                        <label class="select">
                                            <i></i>
                                            <select name="bacarit_type">
                                                <option value="0"><?=$lang['SELECT_BACARIT']?></option>
                                                <option value="1"<?=($bacarit_type==1?' selected="selected"':'')?>>Gavish</option>
                                                <option value="2"<?=($bacarit_type==2?' selected="selected"':'')?>>One</option>
                                                <option value="3"<?=($bacarit_type==3?' selected="selected"':'')?>>Digi Star</option>
                                            </select>
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['USERNAME']?>:</label>
                                    <section class="col col-4">
                                        <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                            <input type="text" name="bacarit_username" value=<?=$bacarit_username?>>
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['PASSWORD']?>:</label>
                                    <section class="col col-4">
                                        <label class="input"> <i class="icon-prepend fa  fa-lock"></i>
                                            <input type="text" name="bacarit_password" value="<?=$bacarit_password?>">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['LICENSE']?>:</label>
                                    <section>
                                        <div class="inline-group col col-4">
                                            <label class="radio">
                                                <input type="radio" value="0" name="bacarit_enable" <? if($bacarit_enable == 0 ){ echo 'checked=""'; } ?>  >
                                                <i></i><?=$lang['DISABLE']?></label>
                                            <label class="radio">
                                                <input type="radio" value="1" name="bacarit_enable" <? if($bacarit_enable == 1 ){ echo 'checked=""'; } ?>  >
                                                <i></i><?=$lang['ENABLE']?></label>
                                        </div>
                                    </section>
                                </div>
                                
                            </fieldset>

                            <header>
                                <b><?=$lang['MILK_PRODUCTION']?>   </b><img src="<?= ASSETS_URL; ?>/img/cowshed/setting/milk_production.png" />
                            </header>

                            <fieldset>
                                <div class="row">
                                    <label class="label col col-2"><?=$lang['MILK_PRODUCTION']?></label>
                                    <section class="col col-4">
                                        <label class="select">
                                            <i></i>
                                            <select name="milk_production_type">
                                                <option value="0"><?=$lang['SELECT_MILK_PRODUCTION']?></option>
                                                <option value="1"<?=($milk_production_type==1?' selected="selected"':'')?>>SCR</option>
                                                <option value="2"<?=($milk_production_type==2?' selected="selected"':'')?>>Afimilk</option>
                                                <option value="3"<?=($milk_production_type==3?' selected="selected"':'')?>>LELY</option>
                                                <option value="4"<?=($milk_production_type==3?' selected="selected"':'')?>>BouMatic</option>
                                                <option value="5"<?=($milk_production_type==3?' selected="selected"':'')?>>DeLaval</option>
                                            </select>
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['USERNAME']?>:</label>
                                    <section class="col col-4">
                                        <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                            <input type="text" name="milk_production_username" value="<?=$milk_production_username?>">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['PASSWORD']?>:</label>
                                    <section class="col col-4">
                                        <label class="input"> <i class="icon-prepend fa  fa-lock"></i>
                                            <input type="text" name="milk_production_password" value="<?=$milk_production_password?>">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-2"><?=$lang['LICENSE']?>:</label>
                                    <section>
                                        <div class="inline-group col col-4">
                                            <label class="radio">
                                                <input type="radio" value="0" name="milk_production_enable" <? if($milk_production_enable == 0 ){ echo 'checked=""'; } ?>  >
                                                <i></i><?=$lang['DISABLE']?></label>
                                            <label class="radio">
                                                <input type="radio" value="1" name="milk_production_enable" <? if($milk_production_enable == 1 ){ echo 'checked=""'; } ?>  >
                                                <i></i><?=$lang['ENABLE']?></label>
                                        </div>
                                    </section>
                                </div>
                                
                            </fieldset>

                            <header>
                                <b><?=$lang['DATA_ENTRY']?></b>
                            </header>

                            <fieldset>
                            <div class="row">
                                <label class="label col col-2"><?=$lang['LICENSE']?>:</label>
                                <section>
                                    <div class="inline-group col col-4">
                                        <label class="radio">
                                            <input type="radio" value="0" name="data_entry_enable" <? if($data_entry_enable == 0 ){ echo 'checked=""'; } ?>  >
                                            <i></i><?=$lang['DISABLE']?></label>
                                        <label class="radio">
                                            <input type="radio" value="1" name="data_entry_enable" <? if($data_entry_enable == 1 ){ echo 'checked=""'; } ?>  >
                                            <i></i><?=$lang['ENABLE']?></label>
                                    </div>
                                </section>
                            </div>

                            </fieldset>

                            <header>
                                <b><?=$lang['ANALYTICS']?></b>
                            </header>

                            <fieldset>
                                <div class="row">
                                    <label class="label col col-2"><?=$lang['LICENSE']?>:</label>
                                    <section>
                                        <div class="inline-group col col-4">
                                            <label class="radio">
                                                <input type="radio" value="0" name="analytics_enable" <? if($analytics_enable == 0 ){ echo 'checked=""'; } ?>  >
                                                <i></i><?=$lang['DISABLE']?></label>
                                            <label class="radio">
                                                <input type="radio" value="1" name="analytics_enable" <? if($analytics_enable == 1 ){ echo 'checked=""'; } ?>  >
                                                <i></i><?=$lang['ENABLE']?></label>
                                        </div>
                                    </section>
                                </div>

                            </fieldset>


                            <footer>
                                <button class="btn btn-primary" type="submit"><?=$lang['SAVE']?></button>
                            </footer>
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['new_csrf_token']?>" />
                            <input type="hidden" name="farm_id" value="<?= $farm_details['id']?>" />
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
<!-- PAGE RELATED PLUGIN(S) -->
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/jquery.dataTables.min.js"></script>

<script type="text/javascript">

// DO NOT REMOVE : GLOBAL FUNCTIONS!


// START DATE
$('#start_date').datepicker({
    dateFormat : 'yy-mm-dd',
    prevText : '<i class="fa fa-chevron-left"></i>',
    nextText : '<i class="fa fa-chevron-right"></i>'
});


var $orderForm = $("#form_farm").validate({
    // Rules for form validation
    rules : {
        farm_name : {
            required : true
        }
    },

    // Messages for form validation
    messages : {
        farm_name : {
            required : 'Please enter farm name'
        }
    },

    // Do not change code below
    errorPlacement : function(error, element) {
        error.insertAfter(element.parent());
    }
});



</script>

