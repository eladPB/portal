<?php
//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Profile";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
#$page_nav["views"]["sub"]["profile"]["active"] = true;
include("inc/nav.php");


$first_name =  $util->GetPostOrSession('first_name');
$last_name  =  $util->GetPostOrSession('last_name');
$image  =  '';
$pass = '';
//$user_name =  $util->GetPost('user_name');
//$role  =  $util->GetPostOrSession('role');
//$bi_user  =  $util->GetPost('bi_user');
//$bi_pass  =  $util->GetPost('bi_pass');

$language  =  $util->GetPostOrSession('language');

if (isset($_POST['csrf_token'])):
    // if a form was submitted SAVE
    $pass =  $util->GetPostOrSession('pass');
else:
    $user_details = (array) $my_db->User_Get_Details($_SESSION['user_id']);
    $image = $user_details['image'];
endif;


if ( (isset($_FILES['image'])) && !empty( $_FILES["image"]["name"] ) )
{
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
//        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
//        echo "File is not an image.";
        $uploadOk = 0;
    }
    $image = mysql_real_escape_string($_FILES["image"]["tmp_name"]);
}
else {
    $image = '';
}


$status = -1;
//if ( $pass != '' && $first_name != '' && $last_name != '' && $image != '' &&  $language != ''):
if (isset($_POST['csrf_token'])):

//    / Handle image upload.
if ( (isset($_FILES['image'])) && !empty( $_FILES["image"]["name"] ) ):
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["image"]["name"]);
	$uploadOk = 1;
	// check mimetype
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$finfo = new finfo(FILEINFO_MIME_TYPE);
	$fileContents = file_get_contents($_FILES['image']['tmp_name']);
	$mimeType = $finfo->buffer($fileContents);
//	echo $imageFileType.' '. $mimeType;exit;
	if ($imageFileType == 'jpg' && $mimeType == 'image/jpeg'):
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false):
//			        echo "File is an image - " . $check["mime"] . ".";
            $tmp_dir = str_replace('\\','/',ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir());
            while (true) {
                $middle_file = uniqid('user_upd_', true);
                if (!file_exists($tmp_dir .'/'. $middle_file)) break;
            }
            $middle_file = $tmp_dir.'/'.$middle_file;
            move_uploaded_file($_FILES["image"]["tmp_name"], $middle_file);
            $util->resize_image($middle_file,240,200,false);
            $image = $middle_file;
//			echo 'replacing image with: '.$middle_file;exit;
//			$user_details['image']='';
            $uploadOk = 1;
        else:
            echo "File is not an image.";exit;
            $uploadOk = 0;
        endif;
    endif;
endif;

        if ($my_db->User_update($_SESSION['user_id'], '', $pass, $first_name, $last_name, $image, '', $language, '','')):
            $my_db->User_reset_env($_SESSION['user']);
            echo("<meta http-equiv='refresh' content='1'>"); //Refresh by HTTP META

        $status = 1;

    else:
        $status = 0;
    endif;
endif;


?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Other Pages"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<!-- Bread crumb is created dynamically -->
		<!-- row -->
		<div class="row">
		
			<!-- col -->
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
				<h1 class="page-title txt-color-blueDark"><!-- PAGE HEADER --><i class="fa-fw fa fa-file-o"></i> Other Pages <span>>
					Profile </span></h1>
			</div>
			<!-- end col -->

		</div>
		<!-- end row -->
		
		<!-- row -->
		
		<div class="row">
		
			<div class="col-sm-12">
		
		
					<div class="well well-sm">
		
						<div class="row">
		
							<div class="col-sm-12 col-md-12 col-lg-6">
								<div class="well well-light well-sm no-margin no-padding">
		
									<div class="row">
		
										<div class="col-sm-12">
											<div id="myCarousel" class="carousel fade profile-carousel">
												<div class="air air-top-left padding-10">
													<h4 class="txt-color-white font-md"><?echo date('jS \of F Y');?></h4>
												</div>
												<ol class="carousel-indicators">
													<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
													<li data-target="#myCarousel" data-slide-to="1" class=""></li>
													<li data-target="#myCarousel" data-slide-to="2" class=""></li>
												</ol>
												<div class="carousel-inner">
													<!-- Slide 1 -->
													<div class="item active">
														<img src="<?php echo ASSETS_URL; ?>/img/demo/s3.jpg" alt="">
													</div>
													<!-- Slide 2 -->
													<div class="item">
														<img src="<?php echo ASSETS_URL; ?>/img/demo/s2.jpg" alt="">
													</div>
													<!-- Slide 3 -->
													<div class="item">
														<img src="<?php echo ASSETS_URL; ?>/img/demo/s1.jpg" alt="">
													</div>
												</div>
											</div>
										</div>
		
										<div class="col-sm-12">

											<div class="row">

												<div class="col-sm-3 profile-pic">
                                                    <? if (isset($image) && $image!=''):?><img src="data:image/jpeg;base64,<?= base64_encode( $image )?>" width="200" />
                                                    <? elseif (isset($user_details['image']) && $user_details['image']!=''):?><img src="data:image/jpeg;base64,<?= base64_encode( $user_details['image'] )?>" width="200" />
                                                    <? endif;?>
												</div>
												<div class="col-sm-6">
                                                    <h1><?=$first_name?> <span class="semi-bold"><?=$last_name?></span>
													<br>

													<ul class="list-unstyled">
														<li>
															<p class="text-muted">
                                                                <i class="fa fa-envelope"></i>&nbsp;&nbsp;<a href="mailto:<?=$_SESSION['user']?>"><?=$_SESSION['user']?></a>
															</p>
														</li>
													</ul>
												</div>

											</div>

										</div>


                                        <!-- widget content -->
                                        <div class="col-sm-12">

                                            <form id="form_user" class="smart-form" novalidate="novalidate" enctype="multipart/form-data"
                                                  method="post">
                                                <fieldset>

                                                    <div class="row">
                                                        <label class="label col col-2"><?=$lang['PASSWORD']?>:</label>
                                                        <section class="col col-8">
                                                            <label class="input"> <i class="icon-prepend fa  fa-lock"></i>
                                                                <input type="password" name="pass" value="<?= $pass ?>"">
                                                            </label>
                                                        </section>
                                                    </div>

                                                    <div class="row">
                                                        <label class="label col col-2"><?=$lang['FIRST_NAME']?>: </label>
                                                        <section class="col col-8">
                                                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                                                <input type="text" name="first_name" value="<?=$first_name?>">
                                                            </label>
                                                        </section>
                                                    </div>

                                                    <div class="row">
                                                        <label class="label col col-2"><?=$lang['LAST_NAME']?>: </label>
                                                        <section class="col col-8">
                                                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                                                <input type="text" name="last_name" value="<?=$last_name?>">
                                                            </label>
                                                        </section>
                                                    </div>

                                                    <div class="row">
                                                        <label class="label col col-2"><?=$lang['IMAGE']?>: </label>
                                                        <section class="col col-8">
                                                            <label class="input">
                                                                <i class="icon-prepend fa fa-image"></i>
                                                                <input type="file" name="image" accept=".jpg" value=<?=$image?>>
                                                            </label>

                                                            <div class="note">
                                                                <strong>Note:</strong> Upload jpg only!, recommend resolution 120 X 100.
                                                            </div>
                                                        </section>
                                                    </div>

													<div class="row">
														<label class="label col col-2"><?=$lang['LANGUAGE']?>:</label>
														<section class="col col-8">
															<label class="select">
																<i></i>
																<select name="language">
																	<option value="0">Please select a user language</option>
																	<option value="1"<?= ($language == 1 ? ' selected="selected"' : '') ?>>
																		Hebrew
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

                                                    <footer>
                                                        <button class="btn btn-primary" type="submit"><?=$lang['SAVE']?></button>
                                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['new_csrf_token']?>" />
                                                    </footer>
                                                </fieldset>
                                            </form>

                                        </div>
                                        <!-- end widget content -->

									</div>

								</div>
		
							</div>

						</div>
		
					</div>

			</div>
		
		</div>
		
		<!-- end row -->

	</div>
	<!-- END MAIN CONTENT -->
</div>

<!-- end widget grid -->
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
<script src="..."></script>-->

<script>

    var $orderForm = $("#form_user").validate({
        // Rules for form validation
        rules : {
            first_name: {
                required : true
            },
            last_name: {
                required : true
            },
//            pass: {
//                required : true
//            },
//            image: {
//                required : true
//            },
            language: {
                required : true,
                min: 1
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
//            pass : {
//                required : 'Please enter the password'
//            },
//            image : {
//                required : 'Please enter the password'
//            },
            language : {
                required : 'Please select language',
                min : 'Please select language'
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