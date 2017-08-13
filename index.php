<?php

//initilize the page
require_once("inc/init.php");

$farms = array();
if ($user_is_logged_in):
    $farms = $my_db->GetMyFarm($_SESSION['user_id']);
endif;

$change_farm_id = $util->GetPostOrZero('change_farm_id');
if (!empty($change_farm_id)) {
    $my_db->Reset_Farm_Credentials();
    foreach ($farms as $farm) {
        if ($change_farm_id == $farm['id']) {
            $_SESSION['my_farm'] = $farm;
            $my_db->Set_Farm_Credentials( $farm['id']);
        }
    }
}
//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = $lang['DASHBOARD'];

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["dashboard"]["sub"]["analytics"]["active"] = true;
include("inc/nav.php");

list ($bi_user,$bi_pass)= $my_db->User_Get_Bi_Details_By_UserName($_SESSION['user_id']);

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
    <?php
    //configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
    //$breadcrumbs["New Crumb"] => "http://url.com"
    include("inc/ribbon.php");
    ?>

    <!-- MAIN CONTENT -->
    <div id="content">
        <div class="row">
            <!--            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">-->
            <!--                <div class="col-lg-12 col-md-12 col-sm-12 ">-->
            <!--                <div class="col-lg-12 col-md-12 col-sm-12 embed-responsive embed-responsive iframe ">-->
            <div class="embed-responsive embed-responsive iframe ">
                <!--            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">-->

                <iframe  height="100%"  width="100%"  style="min-height:900px;"  src="http://<?=$bi_user?>:<?=$bi_pass?>@54.210.52.142/QvAJAXZfc/opendoc.htm?document=DairyFarm_Analysis%2FControl%20Room.qvw&host=QVS%40win-qha7m6aogva"></iframe>

            </div>

            <!--            --><?php // echo '<pre>' . print_r(get_defined_vars(), true) . '</pre>';?>
            <!--            </article>-->

        </div>




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
    })

</script>
