<?php

//initilize the page
require_once("../inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("../inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = $lang['MILK_COMPONENTS'];
//if ( ! session_id() ) @ session_start();
//
if (! isset($_GET['Year'])):
    $chosen_year = date("Y");
else:
    $chosen_year = $_GET['Year'];
endif;

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include(DIRECTORY."/inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["data_sheets"]["sub"]["tara"]["sub"]["milk_components"]["active"] = true;
include(DIRECTORY."/inc/nav.php");
list ($fat_table,$protein_table,$sumatim_table,$table_haidakim) = $my_db->Get_Tara_Milk_Components($_SESSION['diary_milk_username'],$chosen_year);
//list ($fat_table, $sumatim_table, $protein_table,$table_haidakim) = $my_db->Get_Tara_Milk_Components($_SESSION['diary_milk_username'],$chosen_year);
?>
    <!-- ==========================CONTENT STARTS HERE ========================== -->
    <!-- MAIN PANEL -->
    <div id="main" role="main">
    <?php
        //configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
        //$breadcrumbs["New Crumb"] => "http://url.com"
        $breadcrumbs["Data Sheets"] = "";
        $breadcrumbs["Tara"] = "";
        include(DIRECTORY."/inc/ribbon.php");
    ?>

        <!-- MAIN CONTENT -->
        <div id="content">

            <!-- Bread crumb is created dynamically -->
            <!-- row -->
            <div class="row">

                <!-- col -->
                <div class="col-xs-12 col-sm-7 col-md-7 col-lg-5">
                    <h1 class="page-title txt-color-blueDark">

                        <!-- PAGE HEADER -->
                        <i class="fa-fw fa fa-home"></i>
                        <?=$lang['DATA_SHEETS']?>
					<span>>  
						<?=$lang['TARA']?> > <?=$lang['MILK_COMPONENTS']?>
					</span>
                    </h1>
                </div>
                <!-- end col -->

            </div>
            <!-- end row -->

            <!-- widget grid -->
            <section id="widget-grid" class="">

                <div class="btn-group">
                    <button class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                        <?echo $chosen_year?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <? for ($i=0; $i<=5; $i++):?>
                        <li><a href="milk_components.php?Year=<?= date("Y")-$i?>"><?echo date("Y")-$i?></a></li>
                        <? endfor;?>
                    </ul>
                </div>



                <!-- FAT TABLE -->
                <div class="row">

                    <div class="col-sm-12">

                        <div class="col-sm-12 well">
                            <div class="col-sm-6">
                                <table class="highchart table table-hover table-bordered" data-graph-container=".. .. .highchart-container" data-graph-type="line">
                                    <caption><?=$lang['FAT_PERCENT']?></caption>
                                    <thead>
                                    <tr>
                                        <?=$lang['COL_MONTH']?>
                                        <?=$lang['COL_COUNTRY_FATS']?>
                                        <?=$lang['COL_TARA_FATS']?>
                                        <?=$lang['COL_YAZRAN_FATS']?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <? echo $fat_table ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-6">
                                <div class="highchart-container"></div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- end row -->

                <!-- PROTEIN TABLE -->
                <div class="row">

                        <div class="col-sm-12">

                            <div class="col-sm-12 well">
                                <div class="col-sm-6">
                                    <table class="highchart table table-hover table-bordered" data-graph-container=".. .. .highchart-container" data-graph-type="line">
                                        <caption><?=$lang['HELBON_PERCENT']?></caption>
                                        <thead>
                                        <tr>
                                            <?=$lang['COL_MONTH']?>
                                            <?=$lang['COL_COUNTRY_HELBON']?>
                                            <?=$lang['COL_TARA_HELBON']?>
                                            <?=$lang['COL_YAZRAN_HELBON']?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <? echo $protein_table ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-6">
                                    <div class="highchart-container"></div>
                                </div>
                            </div>

                            </div>
                            <!-- end row -->
                   </div>
                <!-- end row -->

                <!-- SUMATIM TABLE -->
                <div class="row">

                        <div class="col-sm-12">

                            <div class="col-sm-12 well">
                                <div class="col-sm-6">
                                    <table class="highchart table table-hover table-bordered" data-graph-container=".. .. .highchart-container" data-graph-type="line">
                                        <caption><?=$lang['SUMATIM']?></caption>
                                        <thead>
                                        <tr>
                                            <?=$lang['COL_MONTH']?>
                                            <?=$lang['COL_COUNTRY_SUMATIM']?>
                                            <?=$lang['COL_TARA_SUMATIM']?>
                                            <?=$lang['COL_YAZRAN_SUMATIM']?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <? echo $sumatim_table ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-6">
                                    <div class="highchart-container"></div>
                                </div>
                            </div>

                        </div>
                        <!-- end row -->
                 </div>
                <!-- end row -->

                <!-- HAIDAKIM TABLE -->
                <div class="row">

                    <div class="col-sm-12">

                        <div class="col-sm-12 well">
                            <div class="col-sm-6">
                                <table class="highchart table table-hover table-bordered" data-graph-container=".. .. .highchart-container" data-graph-type="line">
                                    <caption><?=$lang['HAIDAKIM']?></caption>
                                    <thead>
                                    <tr>
                                        <?=$lang['COL_MONTH']?>
                                        <?=$lang['COL_YAZRAN_HAIDAKIM']?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <? echo $table_haidakim ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-6">
                                <div class="highchart-container"></div>
                            </div>
                        </div>

                    </div>
                    <!-- end row -->
                </div>
                <!-- end row -->

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
include(DIRECTORY."/inc/footer.php");
?>
    <!-- END PAGE FOOTER -->

<?php
//include required scripts
include(DIRECTORY."/inc/scripts.php");
?>

    <!-- PAGE RELATED PLUGIN(S)
<script src="<?php echo ASSETS_URL; ?>/js/plugin/YOURJS.js"></script>-->
    <script src="<?php echo ASSETS_URL; ?>/js/plugin/highChartCore/highcharts-custom.min.js"></script>
    <script src="<?php echo ASSETS_URL; ?>/js/plugin/highchartTable/jquery.highchartTable.min.js"></script>

    <script>

        $(document).ready(function() {
            /* DO NOT REMOVE : GLOBAL FUNCTIONS!
             *
             * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
             *
             * // activate tooltips
             * $("[rel=tooltip]").tooltip();
             *
             * // activate popovers
             * $("[rel=popover]").popover();
             *
             * // activate popovers with hover states
             * $("[rel=popover-hover]").popover({ trigger: "hover" });
             *
             * // activate inline charts
             * runAllCharts();
             *
             * // setup widgets
             * setup_widgets_desktop();
             *
             * // run form elements
             * runAllForms();
             *
             ********************************
             *
             * pageSetUp() is needed whenever you load a page.
             * It initializes and checks for all basic elements of the page
             * and makes rendering easier.
             *
             */

            pageSetUp();

            /*
             * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
             * eg alert("my home function");
             *
             * var pagefunction = function() {
             *   ...
             * }
             * loadScript("js/plugin/_PLUGIN_NAME_.js", pagefunction);
             *
             * TO LOAD A SCRIPT:
             * var pagefunction = function (){
             *  loadScript(".../plugin.js", run_after_loaded);
             * }
             *
             * OR
             *
             * loadScript(".../plugin.js", run_after_loaded);
             */

            $('table.highchart').highchartTable();
        })

    </script>

<?php
//include footer
include(DIRECTORY."/inc/google-analytics.php");
?>

