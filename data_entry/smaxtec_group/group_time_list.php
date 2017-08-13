<?php //initilize the page
require_once("../../inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("../../inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = $lang['TIME_TABLE'];

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include(DIRECTORY . "/inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php

$page_nav ["data_entry"] ["sub"] ["group_mng"] ["sub"] ["group_list"] ["active"] = true;

include(DIRECTORY . "/inc/nav.php");

$group_id =  $util->GetPostOrZero('group_id');

// Get table data
$table = $my_db->Group_Time_Get_Table($_SESSION['farm_id'],$group_id);


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
                    <i class="fa fa-table fa-fw "></i><?=$lang['DATA_ENTRY']?>
                    <span>>
                        <?=$lang['TIME_TABLE']?>
					</span>
                </h1>
            </div>
            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
            </div>
        </div>

        <!-- widget grid -->
        <section id="widget-grid" class="">
            <!-- row -->
            <div class="row">

                <!-- NEW WIDGET START -->
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    <!-- Widget ID (each widget will need unique ID)-->
                    <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false" >
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
                            <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                            <h2><?=$lang['UPDATE_TIME']?></h2>
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

                                <table id="datatable_tabletools2" class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                    <tr>
                                        <th data-class="expand"><?=$lang['GROUP']?></th>
                                        <th data-class="expand"><?=$lang['NAME']?></th>
                                        <th data-class="expand"><?=$lang['START_TIME']?></th>
                                        <th data-class="expand"><?=$lang['END_TIME']?></th>
                                        <th data-hide="expand"><?=$lang['EDIT']?></th>
                                        <th data-hide="expand"><?=$lang['DELETE']?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?echo $table ?>
                                    </tbody>
                                </table>

                            </div>
                            <!-- end widget content -->

                        </div>
                        <!-- end widget div -->

                    </div>
                    <!-- end widget -->

                </article>
                <!-- WIDGET END -->

            </div>

            <!-- end row -->

            <!-- end row -->

        </section>
        <!-- end widget grid -->

        <!-- widget grid -->
        <section id="widget-grid" class="">

            <!-- row -->
            <div class="row">

                <!-- NEW WIDGET START -->
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    <!-- Widget ID (each widget will need unique ID)-->
                    <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false" >
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

                    </div>
                    <!-- end widget -->

                </article>
                <!-- WIDGET END -->

            </div>

            <!-- end row -->

            <!-- end row -->

        </section>
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
<link rel="stylesheet" type="text/css" href="<?php echo ASSETS_URL; ?>/js/plugin/datatables_new/DataTables-1.10.11/css/dataTables.bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ASSETS_URL; ?>/js/plugin/datatables_new/Buttons-1.1.2/css/buttons.bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ASSETS_URL; ?>/js/plugin/datatables_new/ColReorder-1.3.1/css/colReorder.bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ASSETS_URL; ?>/js/plugin/datatables_new/Responsive-2.0.2/css/responsive.bootstrap.min.css"/>

<!--<script type="text/javascript" src="jQuery-2.2.0/jquery-2.2.0.min.js"></script>-->
<!--<script type="text/javascript" src="--><?php //echo ASSETS_URL; ?><!--/js/plugin/datatables_new/Bootstrap-3.3.6/js/bootstrap.min.js"></script>-->
<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/plugin/datatables_new/pdfmake-0.1.18/build/pdfmake.min.js"></script>
<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/plugin/datatables_new/JSZip-2.5.0/jszip.min.js"></script>
<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/plugin/datatables_new/pdfmake-0.1.18/build/vfs_fonts.js"></script>
<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/plugin/datatables_new/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/plugin/datatables_new/DataTables-1.10.11/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/plugin/datatables_new/Buttons-1.1.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/plugin/datatables_new/Buttons-1.1.2/js/buttons.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/plugin/datatables_new/Buttons-1.1.2/js/buttons.colVis.min.js"></script>
<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/plugin/datatables_new/Buttons-1.1.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/plugin/datatables_new/Buttons-1.1.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/plugin/datatables_new/ColReorder-1.3.1/js/dataTables.colReorder.min.js"></script>
<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/plugin/datatables_new/Responsive-2.0.2/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/plugin/datatables_new/Responsive-2.0.2/js/responsive.bootstrap.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>

<script type="text/javascript">

    // DO NOT REMOVE : GLOBAL FUNCTIONS!

    $(document).ready(function() {

        /* // DOM Position key index //

         l - Length changing (dropdown)
         f - Filtering input (search)
         t - The Table! (datatable)
         i - Information (records)
         p - Pagination (paging)
         r - pRocessing
         < and > - div elements
         <"#id" and > - div with an id
         <"class" and > - div with a class
         <"#id.class" and > - div with an id and class

         Also see: http://legacy.datatables.net/usage/features
         */

        /* BASIC ;*/
        var responsiveHelper_datatable_tabletools2 = undefined;

        var breakpointDefinition = {
            tablet : 1024,
            phone : 480
        };

        // custom toolbar
        $("div.toolbar").html('<div class="text-right"><img src="../../img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

        // Apply the filter
        $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

            otable
                .column( $(this).parent().index()+':visible' )
                .search( this.value )
                .draw();

        } );
        /* END COLUMN FILTER */


        /* TABLETOOLS */

        $('#datatable_tabletools2').dataTable({
            pagingType: "full_numbers",


            // Tabletools options:
            //   https://datatables.net/extensions/tabletools/button_options
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs text-right'BT>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
            buttons: [
                'copy', 'csv', 'excelHtml5', 'pdf','print'
            ],
            "autoWidth" : true,
            "lengthMenu": [ 10 ],
            "preDrawCallback" : function() {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_datatable_tabletools2) {
                    responsiveHelper_datatable_tabletools2 = new ResponsiveDatatablesHelper($('#datatable_tabletools2'), breakpointDefinition);
                }
            },
            "rowCallback" : function(nRow) {
                responsiveHelper_datatable_tabletools2.createExpandIcon(nRow);
            },
            "drawCallback" : function(oSettings) {
                responsiveHelper_datatable_tabletools2.respond();

                $('.smart-mod-eg1').click(function(e) {
                    $this = $(this);
                    $this_groupid = $this.data('groupid');
                    $.SmartMessageBox({
                        title : "מחק זמן ?",
                        content : "האם אתה בטוח שאתה רוצה למחוק את הזמן ?",
                        sound : false,
                        buttons : '[לא][כן]'
                    }, function(ButtonPressed) {
                        if (ButtonPressed === "כן") {
                            // ADD AJAX Call to delete the group
                            $.ajax({
                                url: "/data_entry/smaxtec_group/ajax_group_time.php",
                                data: {
                                    'action':'delete_group',
                                    'groupid': $this_groupid,
                                    'csrf_token' : '<?= $_SESSION['new_csrf_token']?>'
                                }
                            })
                                .done(function() {
                                    $.smallBox({
                                        title : "זמן נמחקה",
                                        content : "<i class='fa fa-clock-o'></i> <i>זמן נמחקה...</i>",
                                        sound : false,
                                        color : "#659265",
                                        iconSmall : "fa fa-check fa-2x fadeInRight animated",
                                        timeout : 4000
                                    });
                                    location.reload();
                                })
                                .fail(function() {
                                    $.smallBox({
                                        title : "זמן נמחקה",
                                        content : "<i class='fa fa-clock-o'></i> <i>הזמן לא נמחקה</i>",
                                        sound : false,
                                        color : "#c26565",
                                        iconSmall : "fa fa-exclamation fa-2x fadeInRight animated",
                                        timeout : 6000
                                    });
                                    location.reload();
                                });
                        }
                        if (ButtonPressed === "לא") {
                            $.smallBox({
                                title : "ביטול מחיקה",
                                content : "<i class='fa fa-clock-o'></i> <i>מחיקה בוטלה...</i>",
                                sound : false,
                                color : "#C46A69",
                                iconSmall : "fa fa-times fa-2x fadeInRight animated",
                                timeout : 4000
                            });
                        }

                    });
                    e.preventDefault();
                });
            }
        });
    })

</script>

