<?php
//initilize the page
require_once("../inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once(DIRECTORY."/inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = $lang['ANIMAL_TEMPERATURE_GROUP'];

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include(DIRECTORY."/inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav ["monitoring"] ["sub"] ["smaxtec"] ["sub"] ["animal_temperature_group"] ["active"] = true;
include(DIRECTORY."/inc/nav.php");


if (! isset($_GET['PeriodBack'])):
    $PeriodBack = 24;
    $Tag = $lang['LAST_24_HOURS'];
else:
    $PeriodBack = $_GET['PeriodBack'];
    $Tag = $_GET['Tag'];
endif;

$my_group_id = $util->GetPostOrNumbertOrZero('group_id');
$groups = $my_db->Get_Smaxtec_Group($_SESSION['farm_id']);

// Check group id:
if ($my_group_id == 0):
    $my_group_id = $groups[0]['id'];
    $Tag_Group_Name= $groups[0]['name'];
    elseif(count($groups) > 0):
        $group_details =  (array)$my_db->Get_Smaxtec_Group_Name($_SESSION['farm_id'],$my_group_id);
        $Tag_Group_Name = $group_details['name'];
else:
    $my_group_id = 0;
    $Tag_Group_Name = $lang['GROUP'];
endif;

//echo $group_id;exit;
$result = $my_db->Get_Smaxtec_Group_Animal_Avg_Temperature($my_group_id, $_SESSION['farm_id'],$PeriodBack);
$animals_arr = $my_db->Get_Smaxtec_Group_Animal_Temperature_Reduction($my_group_id, $_SESSION['farm_id'],$PeriodBack);
$group_times = $my_db->Get_Smaxtec_Group_Times($my_group_id, $_SESSION['farm_id']);
$LimitLine="39.5";


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
                                <?=$lang['SMAXTEC']?> > <?=$lang['ANIMAL_TEMPERATURE_GROUP']?>
							</span>
						</h1>
					</div>
					<!-- end col -->

					<!-- right side of the page with the sparkline graphs -->
					<!-- col -->
					<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
						<? /*		<!-- sparks -->
						<ul id="sparks">
							<li class="sparks-info">
								<h5> My Income <span class="txt-color-blue">$47,171</span></h5>
								<div class="sparkline txt-color-blue hidden-mobile hidden-md hidden-sm">
									1300, 1877, 2500, 2577, 2000, 2100, 3000, 2700, 3631, 2471, 2700, 3631, 2471
								</div>
							</li>
							<li class="sparks-info">
								<h5> Site Traffic <span class="txt-color-purple"><i class="fa fa-arrow-circle-up" data-rel="bootstrap-tooltip" title="Increased"></i>&nbsp;45%</span></h5>
								<div class="sparkline txt-color-purple hidden-mobile hidden-md hidden-sm">
									110,150,300,130,400,240,220,310,220,300, 270, 210
								</div>
							</li>
							<li class="sparks-info">
								<h5> Site Orders <span class="txt-color-greenDark"><i class="fa fa-shopping-cart"></i>&nbsp;2447</span></h5>
								<div class="sparkline txt-color-greenDark hidden-mobile hidden-md hidden-sm">
									110,150,300,130,400,240,220,310,220,300, 270, 210
								</div>
							</li>
						</ul>
						<!-- end sparks -->*/ ?>
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
						<button class="btn btn-primary btn-sm dropdown-toggle col-sm-12" data-toggle="dropdown">
							<?echo $Tag; ?> <span class="caret"></span>
						</button>
						<ul class="dropdown-menu col-md-12 col-sm-12" style="position:absolute;">
							<li><a href="./animal_temperature_group.php?PeriodBack=3&Tag=<?=$lang['LAST_3_HOURS']?>&group_id=<?=$my_group_id?>"><?=$lang['LAST_3_HOURS']?></a></li>
							<li><a href="./animal_temperature_group.php?PeriodBack=24&Tag=<?=$lang['LAST_24_HOURS']?>&group_id=<?=$my_group_id?>"><?=$lang['LAST_24_HOURS']?></a></li>
							<li><a href="./animal_temperature_group.php?PeriodBack=48&Tag=<?=$lang['LAST_48_HOURS']?>&group_id=<?=$my_group_id?>"><?=$lang['LAST_48_HOURS']?></a></li>
							<li><a href="./animal_temperature_group.php?PeriodBack=168&Tag=<?=$lang['LAST_WEEK']?>&group_id=<?=$my_group_id?>"><?=$lang['LAST_WEEK']?></a></li>
							<li><a href="./animal_temperature_group.php?PeriodBack=504&Tag=<?=$lang['MAX']?>&group_id=<?=$my_group_id?>"><?=$lang['MAX']?></a></li>
						</ul>
					</div>
                    <div class="btn-group">
                        <button class="btn btn-primary btn-sm dropdown-toggle col-sm-12" data-toggle="dropdown">
                            <?echo $Tag_Group_Name; ?> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu col-md-12 col-sm-12" style="position:absolute;">
							<? foreach ($groups as $group):?>
                            <li><a href="./animal_temperature_group.php?group_id=<?= $group['id']?>&PeriodBack=<?=$PeriodBack?>&Tag=<?=$Tag?>"><?= $group['name']?></a></li>
							<? endforeach?>
                        </ul>
					</div>
					<div class="row">
						<? foreach ($group_times as $group_time):?>
						<div class="onoffswitch_container col-md-4 col-sm-12 col-xs-12 text-align-right">
							<label for="st1"><?= $group_time['name']?>:</label>
								<span class="onoffswitch">
									<input type="checkbox" name="onoffswitch<?= $group_time['id']?>" class="onoffswitch-checkbox" checked="checked" id="st<?= $group_time['id']?>">
									<label class="onoffswitch-label" for="st<?= $group_time['id']?>">
										<span class="onoffswitch-inner" data-swchon-text="<?=$lang['SHOW']?>" data-swchoff-text="<?=$lang['HIDE']?>"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</span>
						</div>
						<? endforeach;?>
                    </div>
					<script>
//						$('.onoffswitch-checkbox') = function(){
//							console.log($('.onoffswitch-checkbox');)
//						}
					</script>
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
									<h2><?=$lang['AVERAGE_ANIMAL_ACTIVITY']?></h2>
                                    <div class="widtget-toolbar">
                                        <button class="btn pull-right" onclick="exportToCsv('<?= date('Ymd');?>_average_all_animals.csv',animal_header+animal_data, true);">CSV</button>
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

							<? foreach (array_keys($animals_arr) as $animal_name):?>

							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget" id="wid-id-<?= $animal_name?>" data-widget-editbutton="false">
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
									<h2><?=$lang['REPORT_DATA_FOR']?> <?=$animal_name?></h2>
                                    <div class="widtget-toolbar">
                                        <button class="btn pull-right" onclick="exportToCsv('<?= date('Ymd');?>_animal_<?= $animal_name?>.csv',animal<?= $animal_name?>_data, true);">CSV</button>
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
										<div id="noroll_<?=$animal_name?>" style="width:100%; height:300px;"></div>

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
<!--                --><?php // echo '<pre>' . print_r(get_defined_vars(), true) . '</pre>';?>
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
            var animal_header = 'Date,Temperature\n';
			var animal_data = "<?echo $result; ?>";

			<? foreach ($animals_arr as $animal_line):
				echo 'var animal'.$animal_line['name'].'_data = "'.$animal_line['data'].'"'."\n";
			endforeach;?>

			$(document).ready(function() {
				/*
				 * PAGE RELATED SCRIPTS
				 */
<?
//if (isset($_GET['show']) && $_GET['show'] == 'true'):

$iterator=1;
if ($PeriodBack>=24):
	$iterator = $PeriodBack/24;
	if ($PeriodBack % 24>0):
		$iterator++;
	endif;
 endif;
 for ($i=$iterator; $i>=0;$i--):
				// every day 8-9 (red), 10-11 (green), 14-15 (blue)
				foreach ($group_times as $group_time):
				$diff = strtotime($group_time['end_time'])-strtotime($group_time['start_time']);
				$start_date = date('U',strtotime($group_time['start_time'].' -'.$i.' days'.' +1 hour'));
				$end_date = $start_date+$diff;
				?>
				var highlight_start<?=$i.'_'.$group_time['id'];?> = new Date("<?= date('Y-m-d',strtotime(' -'.$i.' days'));?> <?=date('H:i',$start_date)?>").getTime();
				var highlight_end<?=$i.'_'.$group_time['id'];?> = new Date("<?= date('Y-m-d',$end_date);?> <?=date('H:i',$end_date)?>").getTime(); // <?= $start_date.' '.$end_date.' '.$diff."\r\n"?>
				<?endforeach;?>
<? endfor;
//endif;
?>

				g1 = new Dygraph(document.getElementById("noroll"), animal_header+animal_data, {
					customBars : false,
					title : 'Average Animal Temperature',
                    rollPeriod : 5,
                    showRoller: false,
					ylabel : '',
					legend : 'always',
                    labelsDivWidth : 280,
                    labelsDivStyles : {
                        'textAlign' : 'right'
                    },
					showRangeSelector : true,
					underlayCallback: function(canvas, area, g) {
						drawColors(canvas, area, g);
						drawHorizontalLine(canvas, area, g);
					}
				});
				<? $i=3;foreach (array_keys($animals_arr) as $animal_name):$i++?>
				g<?=$i?> = new Dygraph(document.getElementById("noroll_<?= $animal_name?>"), animal<?= $animal_name?>_data, {
					customBars : false,
					title : 'Animal <?= $animal_name?> - Temperature',
                    rollPeriod : 1,
                    showRoller: false,
					ylabel : '',
					legend : 'always',
                    labelsDivWidth : 280,
					labelsDivStyles : {
						'textAlign' : 'right'
					},
					showRangeSelector : true,
					underlayCallback: function(canvas, area, g) {
						drawColors(canvas, area, g);
						drawHorizontalLine(canvas, area, g);
					}
				});
				<? endforeach?>

				$('.onoffswitch-checkbox').change(function(){
					console.log('im here');
					g1.updateOptions({
						'file': animal_header+animal_data,
						underlayCallback: function(canvas, area, g) {
							console.log('herehrere');
							drawColors(canvas, area, g);
							drawHorizontalLine(canvas, area, g);
						}
					});
					<?$i=3;foreach (array_keys($animals_arr) as $animal_name):$i++?>
					g<?=$i?>.updateOptions({
						'file': animal<?= $animal_name?>_data,
						underlayCallback: function(canvas, area, g) {
//							console.log('herehrere <?//=$i?>//');
							drawColors(canvas, area, g);
							drawHorizontalLine(canvas, area, g);
						}
					});
					<? endforeach?>
				});

				function drawColors(canvas, area, g)
				{
					console.log('drawing colors');
					<? foreach ($group_times as $group_time):?>
					var checker1=$("input:checkbox[name='onoffswitch<?= $group_time['id']?>']").is(':checked');
					<? for ($i=$iterator; $i>=0;$i--): ?>
					// <?= $i?> range
					if (checker1==true) {
//						console.log('a');
						canvas.fillStyle = "<?= $group_time['color']?>";
						var bottom_left<?=$i?>_0 = g.toDomXCoord(highlight_start<?=$i.'_'.$group_time['id']?>);
						var top_right<?=$i?>_0 = g.toDomXCoord(highlight_end<?=$i.'_'.$group_time['id']?>);
						canvas.fillRect(bottom_left<?=$i?>_0, area.y, bottom_left<?=$i?>_0 - top_right<?=$i?>_0, area.h);
					}
					<? endfor;?>
					<? endforeach;?>
				};

				function drawHorizontalLine(canvas, area, g) {
					var yCoords = g.toDomYCoord(<?=$LimitLine?>);

					canvas.fillStyle = 'red';
					canvas.fillRect(area.x, yCoords, area.w, 1);
				};
			})
		</script>
<?php 
	//include footer
	include(DIRECTORY."/inc/google-analytics.php");
?>