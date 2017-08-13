<?php

//initilize the page
require_once("../inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once(DIRECTORY."/inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = $lang['SENSOR_TEMPERATURE'];

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include(DIRECTORY."/inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav ["monitoring"] ["sub"] ["smaxtec"] ["sub"] ["sensor_temperature"] ["active"] = true;
include(DIRECTORY."/inc/nav.php");

if (! isset($_GET['PeriodBack'])):
    $PeriodBack = 24;
    $Tag = $lang['LAST_24_HOURS'];
else:
    $PeriodBack = $_GET['PeriodBack'];
    $Tag = $_GET['Tag'];
endif;
//$_SESSION['sensor_username']
$Avg_Temperature = $my_db->Get_Smaxtec_Sensor_Avg_Temperature($_SESSION['farm_id'],$PeriodBack);
$sensor_arr = $my_db->Get_Smaxtec_Sensor_Temperature($_SESSION['farm_id'],$PeriodBack);



?>
<!-- ==========================CONTENT STARTS HERE ========================== -->

		<!-- MAIN PANEL -->
		<div id="main" role="main">

			<?php
				//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
				//$breadcrumbs["New Crumb"] => "http://url.com"
				$breadcrumbs["Monitoring"] = "";
                $breadcrumbs["smaxtec"] = "";
				include(DIRECTORY."/inc/ribbon.php");
			?>
			<!-- MAIN CONTENT -->
			<div id="content">
				<!-- row -->
				<div class="row">
					
					<!-- col -->
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-5">
						<h1 class="page-title txt-color-blueDark">
							
							<!-- PAGE HEADER -->
							<i class="fa fa-fw fa-bar-chart-o"></i>
                            <?=$lang['MONITORING']?>
							<span>>
                                <?=$lang['SMAXTEC']?> > <?=$lang['SENSOR_TEMPERATURE']?>
							</span>
						</h1>
					</div>
					<!-- end col -->
				</div>
				<!-- end row -->

				<!--
					The ID "widget-grid" will start to initialize all widgets below 
					You do not need to use widgets if you dont want to. Simply remove 
					the <section></section> and you can use wells or panels instead 
					-->

				<!-- widget grid -->
				<section id="widget-grid" class="">

                    <div class="btn-group">
                        <button class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                            <?echo $Tag ?> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="./sensor_temperature.php?PeriodBack=3&Tag=<?=$lang['LAST_3_HOURS']?>"><?=$lang['LAST_3_HOURS']?></a></li>
                            <li><a href="./sensor_temperature.php?PeriodBack=24&Tag=<?=$lang['LAST_24_HOURS']?>"><?=$lang['LAST_24_HOURS']?></a></li>
                            <li><a href="./sensor_temperature.php?PeriodBack=48&Tag=<?=$lang['LAST_48_HOURS']?>"><?=$lang['LAST_48_HOURS']?></a></li>
                            <li><a href="./sensor_temperature.php?PeriodBack=168&Tag=<?=$lang['LAST_WEEK']?>"><?=$lang['LAST_WEEK']?></a></li>
                            <li><a href="./sensor_temperature.php?PeriodBack=504&Tag=<?=$lang['MAX']?>"><?=$lang['MAX']?></a></li>
                        </ul>
                    </div>
					<!-- row -->
					<div class="row">
						
						<!-- NEW WIDGET START -->
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							
	
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
								<!-- widget options:
									usage: <div class="jarviswidget" id="wid-id-0" >
									
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
									<span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
									<h2><?=$lang['AVERAGE_SENSOR_TEMPERATURE']?></h2>
                                    <div class="widtget-toolbar">
                                        <button class="btn pull-right" onclick="exportToCsv('<?= date('Ymd');?>_average_all_sensors.csv',sensor_header+sensor_data, true);">CSV</button>
                                    </div>
								</header>

								<!-- widget div-->
								<div>
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
										<input class="form-control" type="text">	
									</div>
									<!-- end widget edit box -->
									
									<!-- widget content -->
									<div class="widget-body">
										
										<!-- this is what the user will see -->
										<div id="noroll" style="width:100%; height:300px;"></div>

									</div>
									<!-- end widget content -->
									
								</div>
								<!-- end widget div -->
								
							</div>
							<!-- end widget -->

							<? foreach (array_keys($sensor_arr) as $sensor_name):?>

							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget" id="wid-id-<?= $sensor_name?>" data-widget-editbutton="false">
								<!-- widget options:
									usage: <div class="jarviswidget" id="wid-id-0" >

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
									<span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
									<h2><?=$lang['REPORT_DATA_FOR']?> <?=$sensor_name?></h2>
                                    <div class="widtget-toolbar">
                                        <button class="btn pull-right" onclick="exportToCsv('<?= date('Ymd');?>_sensor_<?= $sensor_name?>.csv',sensor<?= $sensor_name?>_data, true);">CSV</button>
                                    </div>
								</header>

								<!-- widget div-->
								<div>
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
										<input class="form-control" type="text">
									</div>
									<!-- end widget edit box -->

									<!-- widget content -->
									<div class="widget-body">

										<!-- this is what the user will see -->
										<div id="noroll_<?=$sensor_name?>" style="width:100%; height:300px;"></div>

									</div>
									<!-- end widget content -->

								</div>
								<!-- end widget div -->
							</div>
							<? endforeach?>


						</article>
						<!-- WIDGET END -->
						
					</div>

					<!-- end row -->

					<!-- row -->

					<div class="row">

						<!-- a blank row to get started -->
						<div class="col-sm-12">
							<!-- your contents here -->
						</div>
							
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

		<!-- PAGE RELATED PLUGIN(S) -->
		<? /*<script src="<?php echo ASSETS_URL; ?>/js/plugin/dygraphs/demo-data.min.js"></script>*/ ?>
		<!-- DYGRAPH -->
		<script src="<?php echo ASSETS_URL; ?>/js/plugin/dygraphs/dygraph-combined.min.js"></script>
		<script type="text/javascript">
            function exportToCsv(filename, rows, raw) {
                if (raw==false) {
                    var processRow = function (row) {
                        var finalVal = '';
                        for (var j = 0; j < row.length; j++) {
                            var innerValue = row[j] === null ? '' : row[j].toString();
                            if (row[j] instanceof Date) {
                                innerValue = row[j].toLocaleString();
                            };
                            var result = innerValue.replace(/"/g, '""');
                            if (result.search(/("|,|\n)/g) >= 0)
                                result = '"' + result + '"';
                            if (j > 0)
                                finalVal += ',';
                            finalVal += result;
                        }
                        return finalVal + '\n';
                    };

                    var csvFile = '';
                    for (var i = 0; i < rows.length; i++) {
                        csvFile += processRow(rows[i]);
                    }
                }
                else {
                    csvFile = rows;
                }
                var blob = new Blob([csvFile], { type: 'text/csv;charset=utf-8;' });
                if (navigator.msSaveBlob) { // IE 10+
                    navigator.msSaveBlob(blob, filename);
                } else {
                    var link = document.createElement("a");
                    if (link.download !== undefined) { // feature detection
                        // Browsers that support HTML5 download attribute
                        var url = URL.createObjectURL(blob);
                        link.setAttribute("href", url);
                        link.setAttribute("download", filename);
                        link.style.visibility = 'hidden';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                }
            }
            var sensor_header = 'Date,Temperature\n';
			var sensor_data = "<?echo $Avg_Temperature; ?>";

			<? foreach ($sensor_arr as $sensor_line):
				echo 'var sensor'.$sensor_line['name'].'_data = "'.$sensor_line['data'].'"'."\n";
			endforeach;?>

			$(document).ready(function() {

				/*
				 * PAGE RELATED SCRIPTS
				 */
				
				g1 = new Dygraph(document.getElementById("noroll"), sensor_header+sensor_data, {
					customBars : false,
					title : 'Average Sensor Temperature',
                    rollPeriod : 15,
					ylabel : '',
					legend : 'always',
                    labelsDivWidth : 280 ,
					labelsDivStyles : {
                        'textAlign' : 'right'
					},
					showRangeSelector : true
				});
				<? $i=3;foreach (array_keys($sensor_arr) as $sensor_name):$i++?>
				g<?=$i?> = new Dygraph(document.getElementById("noroll_<?= $sensor_name?>"), sensor<?= $sensor_name?>_data, {
					customBars : false,
					title : 'Sensor <?= $sensor_name?> - Temperature',
                    rollPeriod : 5,
                    ylabel : '',
					legend : 'always',
                    labelsDivWidth : 280 ,
					labelsDivStyles : {
						'textAlign' : 'right'
					},
					showRangeSelector : true
				});
				<? endforeach?>

				g2 = new Dygraph(document.getElementById("roll14"), sensor_data, {
					rollPeriod : 5,
					showRoller : true,
					customBars : true,
					ylabel : 'Temperature (C)',
					legend : 'always',
                    labelsDivWidth : 280 ,
					labelsDivStyles : {
						'textAlign' : 'right'
					},
					showRangeSelector : true,
					rangeSelectorHeight : 30,
					rangeSelectorPlotStrokeColor : 'yellow',
					rangeSelectorPlotFillColor : 'lightyellow'
				});
			
			})
		</script>
<?php 
	//include footer
	include(DIRECTORY."/inc/google-analytics.php");
?>d