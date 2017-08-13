<?php //initilize the page
require_once("../inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("../inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = $lang['HERD'];

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include(DIRECTORY."/inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["data_entry"]["sub"]["herd"]["active"] = true;
include(DIRECTORY."/inc/nav.php");

if (! isset($_GET['StartDate'])):
    $StartDate = date('Y-m-d', strtotime('-7 days'));
else:
	$StartDate = $_GET['StartDate'];
endif;

if (! isset($_GET['FinishDate'])):
	$FinishDate = date("Y-m-d");
else:
	$FinishDate = $_GET['FinishDate'];
endif;
$farmid = $_SESSION['farm_id'] ;

// Get table data
$table = $my_db->Get_Herd($farmid,$StartDate,$FinishDate);
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">

	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
	$breadcrumbs["Data Entry"] = "";
		include(DIRECTORY."/inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
				<h1 class="page-title txt-color-blueDark">
					<i class="fa fa-table fa-fw "></i>
						<?=$lang['DATA_ENTRY']?>
					<span>>
                        <?=$lang['HERD']?>
					</span>
				</h1>
			</div>
		</div>

		<!-- widget grid -->
		<section id="widget-grid" class="">



            <!--       ------------------------------------------------------------------------------------------------ -->
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
                    <h2><?=$lang['CHOOSE_DATES']?></h2>
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

                        <form id="filter-form" class="smart-form" novalidate="novalidate">
                            <fieldset>
                                <div class="row">

                                    <label class="label col col-1"><?=$lang['START_DATE']?>:</label>
                                    <section class="col col-4">
                                        <label class="input"> <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="StartDate" id="StartDate" placeholder=<?echo $StartDate?>>
                                        </label>
                                    </section>

                                </div>
                                <div class="row">
                                    <label class="label col col-1"><?=$lang['END_DATE']?>:</label>
                                    <section class="col col-4">
                                        <label class="input"> <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="FinishDate" id="FinishDate" placeholder=<?echo $FinishDate?>>
                                        </label>
                                    </section>
                                </div>

                            </fieldset>
                            <footer>
                                <button type="submit" class="btn btn-primary">
                                    <?=$lang['FILTER']?>
                                </button>
                            </footer>
                        </form>

                    </div>
                    <!-- end widget content -->

                </div>
                <!-- end widget div -->

            </div>
            <!-- end widget -->
            <!--       ------------------------------------------------------------------------------------------------ -->

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
							<h2><?=$lang['HERD']?></h2>
		
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
		
								<table id="datatable_tabletools" class="table table-striped table-bordered table-hover" width="100%">
									<thead>
										<tr>
                                            <?=$lang['COL_DATE']?>
											<?=$lang['COL_GROUP_NUMBER']?>
											<?=$lang['COL_SQFT_COWSHED']?>
											<?=$lang['COL_NUM_MILKED']?>
											<?=$lang['COL_AVERAGE_LITERS_PER_COW_PER_DAY']?>
											<?=$lang['COL_AVERAGE_LITERS_PER_MILKED_COW_PER_DAY']?>
											<?=$lang['COL_NUM_COWS']?>
											<?=$lang['COL_DRY']?>
											<?=$lang['COL_CALVES']?>
											<?=$lang['COL_DRY_MATTER_WEIGHT_DAIRY_DISH']?>
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


	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<!-- PAGE FOOTER -->
<?php // include page footer
include(DIRECTORY."/inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php //include required scripts
include(DIRECTORY."/inc/scripts.php");
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
<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/jquery.jeditable.js"></script>
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
		var responsiveHelper_datatable_tabletools = undefined;

		var breakpointDefinition = {
			tablet : 1024,
			phone : 480
		};

    // custom toolbar
    $("div.toolbar").html('<div class="text-right"><img src="../img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

    // Apply the filter
    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

        otable
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();

    } );
    /* END COLUMN FILTER */


	/* TABLETOOLS */
	$('#datatable_tabletools').dataTable({





		
		// Tabletools options: 
		//   https://datatables.net/extensions/tabletools/button_options
		"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs text-right'BT>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
        buttons: [
            'copy', 'csv', 'excelHtml5', 'pdf','print'
        ],
		"autoWidth" : true,
        "lengthMenu": [ 15 ],
		"preDrawCallback" : function() {
			// Initialize the responsive datatables helper once.
			if (!responsiveHelper_datatable_tabletools) {
				responsiveHelper_datatable_tabletools = new ResponsiveDatatablesHelper($('#datatable_tabletools'), breakpointDefinition);
			}
		},
		"rowCallback" : function(nRow) {
			responsiveHelper_datatable_tabletools.createExpandIcon(nRow);
		},
		"drawCallback" : function(oSettings) {
			responsiveHelper_datatable_tabletools.respond();
		}




	});




// //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//    /* Apply the jEditable handlers to the table */
//    $('#datatable_tabletools tbody td').editable( function( sValue ) {
//        /* Get the position of the current data from the node */
//        var aPos = oTable.fnGetPosition( this );
//
//        /* Get the data array for this row */
//        var aData = oTable.fnGetData( aPos[0] );
//
//        /* Update the data array and return the value */
//        aData[ aPos[1] ] = sValue;
//        console.log(sValue);
//
//
//        return sValue;
//    }, { "onblur": 'submit' } ); /* Submit the form when bluring a field */
//
//    /* Init DataTables */
//    oTable = $('#datatable_tabletools').dataTable();

// //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $('#datatable_tabletools tbody td').editable('./update_herd.php', {
        type      : 'textarea',
        name      : $(this).data('tdid'),
		fatherid  : 'indexid',
        "onblur"  : 'submit',
        indicator : 'Saving...',
        tooltip   : 'Click to edit...',
		submitdata: {
			farmid: '<?= $farmid?>'
		}
        });

// //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// //////////////////////////////////////////////////////////////////////////////////////////////////////////////////





	/* END TABLETOOLS */

})







// START AND FINISH DATE
$('#StartDate').datepicker({
    dateFormat : 'yy-mm-dd',
    prevText : '<i class="fa fa-chevron-left"></i>',
    nextText : '<i class="fa fa-chevron-right"></i>',
    onSelect : function(selectedDate) {
        $('#FinishDate').datepicker('option', 'minDate', selectedDate);
    }
});

$('#FinishDate').datepicker({
    dateFormat : 'yy-mm-dd',
    prevText : '<i class="fa fa-chevron-left"></i>',
    nextText : '<i class="fa fa-chevron-right"></i>',
    onSelect : function(selectedDate) {
        $('#StartDate').datepicker('option', 'maxDate', selectedDate);
    }
});

var $orderForm = $("#filter-form").validate({
    // Rules for form validation
    rules : {
        StartDate : {
            required : true
        },
        FinishDate: {
            required : true
        }
    },

    // Messages for form validation
    messages : {
        StartDate : {
            required : 'Please enter Start Date'
        },
        FinishDate : {
            required : 'Please enter End Date'
        }
    },

    // Do not change code below
    errorPlacement : function(error, element) {
        error.insertAfter(element.parent());
    }
});

</script>

<?php
//include footer
include(DIRECTORY."/inc/google-analytics.php");
?>