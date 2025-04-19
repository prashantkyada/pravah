<?php
include("header.php");
$dateupdate = date("Y-m-d H:i:s");


	if(isset($_POST['submit']))
	{
		$updstr = '';
		if($_POST['username']!='' && $_POST['new_username']!='')
		{
			$change_username = mysqli_query($mysqli,"select * from `admin_master` where id='".$_SESSION['admin_id']."' and `username`='".$_POST['username']."'");
			if(mysqli_num_rows($change_username) > 0) 
			{
				$updstr="`username`='".$_POST['new_username']."'";
			}
			else{
				
				echo "<script language='javascript'>window.location='setting.php?wrong_user=wrong_username';</script>";
				die();
			}
		}
		if($_POST['current_password']!='' && $_POST['password']!='' )
		{
			
			$change_password = mysqli_query($mysqli,"select * from `admin_master` where id='".$_SESSION['admin_id']."' and `password`='".$_POST['current_password']."'"); 
			if(mysqli_num_rows($change_password) > 0) 
			{
				if($updstr !='')
					$updstr.=",password='".$_POST['password']."'";			
				else
					$updstr.="password='".$_POST['password']."'";			
			}
			else
			{
				echo "<script language='javascript'>window.location='setting.php?wrong_pass=wrong_pass';</script>";
				die();
			}
		}
		if($updstr!='')
		{
			mysqli_query($mysqli,"update `admin_master` set $updstr where id='".$_SESSION['admin_id']."'");
			echo "<script language='javascript'>window.location='setting.php?msg=upd_msg';</script>";
		}
	}
	/*if(isset($_POST['app_setting']))
	{
		if($_FILES["app_icon"]["error"]==UPLOAD_ERROR_OK)
		{
			$tmp_name = $_FILES["app_icon"]["tmp_name"];
			$name = $_FILES["app_icon"]["name"];		
			$forth_banner_new = getuniqkey($tmp_name).substr($name,strrpos($name,"."));
			copy($_FILES["app_icon"]["tmp_name"], 'image/setting/'.$forth_banner_new);
			//$image_link = ROOT_PATH.$forth_banner_new;
			
		}
		if($_FILES["splash_screen"]["error"]==UPLOAD_ERROR_OK)
		{
			$tmp_name = $_FILES["splash_screen"]["tmp_name"];
			$name = $_FILES["splash_screen"]["name"];		
			$forth_banner = getuniqkey($tmp_name).substr($name,strrpos($name,"."));
			copy($_FILES["splash_screen"]["tmp_name"], 'image/setting/'.$forth_banner);
			//$image_link = ROOT_PATH.$forth_banner_new;
			
		}
		if($forth_banner_new != '')
		{
			$qrystr = ",`icon`='".$forth_banner_new."'";
		}
		if($forth_banner != '')
		{
			$qrystr2 = ",`splash_image`='".$forth_banner."'";
		}
		mysqli_query($mysqli,"update `tbl_app_setting` set `name`='".$_POST['app_name']."' $qrystr $qrystr2");
		echo "<script language='javascript'>window.location='setting.php?msg=msg';</script>";
	}*/
	if(isset($_POST['add_submit']))
	{
		if($_FILES["splash_screen"]["error"]==UPLOAD_ERROR_OK)
		{
			$tmp_name = $_FILES["splash_screen"]["tmp_name"];
			$name = $_FILES["splash_screen"]["name"];		
			$forth_banner = getuniqkey($tmp_name).substr($name,strrpos($name,"."));
			copy($_FILES["splash_screen"]["tmp_name"], 'image/setting/'.$forth_banner);
			
		}
		if($forth_banner != '')
		{
			$qrystr2 = ",`image`='".$forth_banner."'";
		}
		mysqli_query($mysqli,"update `tbl_add_setting` set `name`='".$_POST['name']."',`link`='".$_POST['link']."' $qrystr2");
		echo "<script language='javascript'>window.location='setting.php?msg=msg';</script>";
	}
	if(isset($_POST['policy_submit']))
	{
		mysqli_query($mysqli,"update `tbl_policy` set `privacy_policy`='".$_POST['terms']."'");
		echo "<script language='javascript'>window.location='setting.php?msg=upd_msg';</script>";
	}
	if(isset($_POST['use_submit']))
	{
		if($_FILES["pdf"]["error"]==UPLOAD_ERROR_OK)
		{
			$tmp_name = $_FILES["pdf"]["tmp_name"];
			$name = $_FILES["pdf"]["name"];		
			$forth_banner = getuniqkey($tmp_name).substr($name,strrpos($name,"."));
			copy($_FILES["pdf"]["tmp_name"], '../image/PDF/'.$forth_banner);
			
		}
		if($forth_banner != '')
		{
			mysqli_query($mysqli,"update `tbl_how_to_use` set `file`='".$forth_banner."', `date`='".$dateupdate."'");
		}
		
		
		echo "<script language='javascript'>window.location='setting.php?msg=upd_msg';</script>";
	}

	
	$query = mysqli_query($mysqli,"select * from `admin_master` where id='".$_SESSION['admin_id']."'");
 	$list_row=mysqli_fetch_array($query)
?>
 <!--Body content-->
	<div id="content" class="clearfix">
		<div class="contentwrapper"><!--Content wrapper-->
			<div class="heading">
				<h3>Setting</h3>                    
				<div class="resBtnSearch">
					<a href="#"><span class="icon16 icomoon-icon-search-3"></span></a>
				</div>
				<ul class="breadcrumb">
					<li>You are here:</li>
					<li>
						<a href="#" class="tip" title="back to dashboard">
							<span class="icon16 icomoon-icon-screen-2"></span>
						</a> 
						<span class="divider">
							<span class="icon16 icomoon-icon-arrow-right-3"></span>
						</span>
					</li>
					<li class="active">Setting</li>
				</ul>
			</div><!-- End .heading-->
			<!-- Build page from here: -->
			<div class="row">
				<div class="col-lg-12">
					 <div style="margin-bottom: 20px;">
							<ul id="myTab" class="nav nav-tabs pattern">
								<li class="active"><a href="#user_setting" data-toggle="tab">Admin Setting</a></li>
								<li><a href="#profile" data-toggle="tab">Company Profile</a></li>
								<li><a href="#addmob_setting" data-toggle="tab">Product PDF</a></li>
								<!--<li><a href="#appsetting" data-toggle="tab">App Settings</a></li>
								li><a href="#addmob_setting" data-toggle="tab">Admob Add Settings</a></li>
								<li><a href="#addmob_setting" data-toggle="tab">Add Settings</a></li>
								-->

							</ul>

							 <div class="tab-content">
								<div class="tab-pane fade in active" id="user_setting">
									 <?php
									if($_GET['wrong_pass'] == 'wrong_pass')
									{
									?>	
										<div class='username_exist'><h4><?php echo "Current password you entered is wrong, Please try again.";?></h4></div>
									<?php
									}
									if($_GET['wrong_user'] == 'wrong_username')
									{
									?>
										<div class="username_exist"><h4><?php echo "Current username you entered is wrong, Please try again.";?></h4></div>
									<?php
									}
									?>
									<form class="form-horizontal seperator" action="" method="post" role="form">
										<div class="form-group">
											<label class="col-lg-2 control-label" for="username">Current Username :</label>
											<div class="col-lg-10">
										   <input type="text" name="username" class="form-control" readonly id="username" value="<?php echo $list_row['username']; ?>" />
											</div>
										  </div><!-- End .form-group  -->
										  <div class="form-group">
											<label class="col-lg-2 control-label" for="username">New Username :</label>
											<div class="col-lg-10">
										   <input type="text" name="new_username" class="form-control" id="new_username" value="" />
											</div>
										  </div><!-- End .form-group  -->
										  <div class="form-group">
											<label class="col-lg-2 control-label" for="username">Conform Username :</label>
											<div class="col-lg-10">
										  <input type="text" name="co_username" class="form-control" id="co_username" value="" />
											</div>
										  </div><!-- End .form-group  -->
										  <div class="form-group">
											<label class="col-lg-2 control-label" for="username">Current Password :</label>
											<div class="col-lg-10">
										  <input type="password" name="current_password" class="form-control" id="current_password" value="">
											</div>
										  </div><!-- End .form-group  -->
										  <div class="form-group">
											<label class="col-lg-2 control-label" for="username">New Password :</label>
											<div class="col-lg-10">
											<input type="password" name="password" class="form-control" id="password" value="" />
											</div>
										  </div><!-- End .form-group  -->
										   <div class="form-group">
											<label class="col-lg-2 control-label" for="username">Conform Password :</label>
											<div class="col-lg-10">
											<input type="password" name="co_password" class="form-control" id="co_password" value="" />
											</div>
										  </div><!-- End .form-group  -->
										  
										  <div class="form-group">
											<div class="col-lg-offset-2">
												<button type="submit" name="submit" class="btn btn-info marginR10 marginL10"  onClick="return callonsubmit()">Save changes</button>
												<button type="reset" class="btn btn-danger">Cancel</button>
											</div>
										</div><!-- End .form-group  -->
									</form>
								</div>
								<!--admob app setting start-->
								<div class="tab-pane fade" id="addmob_setting">
									<?php
									$addsettingquery = mysqli_query($mysqli,"select * from `tbl_how_to_use`");
 									$add_row=mysqli_fetch_array($addsettingquery);
									
									$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
									$server_name = $_SERVER['SERVER_NAME'];
									$file_path = ROOT_PATH.'/image/PDF/' . $add_row['file'];
								
									?>
									<div class="row">
										<div class="col-lg-12">
											<form class="form-horizontal seperator" enctype="multipart/form-data"  method="post" role="form">
											 <div class="form-group">
												  <label class="col-lg-2 control-label" for="username">Product PDF:</label>
												  <div class="col-lg-10">
													<input type="file" name="pdf" id="pdf" value="">
													<button type="button" name="pdf_download" onclick="download_pdf('<?php echo $file_path; ?>')" class="btn btn-info marginR10 ">Download PDF</button>
												  </div>
												  
												</div>
						   					<!-- End .form-group -->
											<div class="form-group">
												<div class="col-lg-offset-2">
													<button type="submit" name="use_submit" class="btn btn-info marginR10 marginL10">Save changes</button>
													<button type="reset" class="btn btn-danger">Cancel</button>
												</div>
											</div><!-- End .form-group  -->
										 </form>
										</div><!-- End .row -->
									</div>
								</div> 
								<div class="tab-pane fade" id="appsetting">
								<?php
									$appsettingquery = mysqli_query($mysqli,"select * from `tbl_app_setting`");
 									$app_row=mysqli_fetch_array($appsettingquery)
									?>
									<form class="form-horizontal seperator" method="post" role="form" enctype="multipart/form-data">
										  <div class="form-group">
											<label class="col-lg-2 control-label" for="username">App Name :</label>
											<div class="col-lg-10">
										   <input type="text" name="app_name" class="form-control" id="app_name_id" value="<?php echo $app_row['name'];?>" />
											</div>
										  </div><!-- End .form-group  -->
										   <div class="form-group">
											<label class="col-lg-2 control-label" for="username">App icon :</label>
											<div class="col-lg-10">
										 		 <input type="file" name="app_icon" id="app_icon_id" value="">
										 		 <span><img src="image/setting/<?php echo $app_row['icon']; ?>"
												class='img-thumbnail' alt='No Image' width='60' height='60'/></span>
											</div>
										  </div><!-- End .form-group  -->
										  <div class="form-group">
											<label class="col-lg-2 control-label" for="username">Splash Screen :</label>
											<div class="col-lg-10">
										 		 <input type="file" name="splash_screen" id="splash_screen_id" value="">
										 		  <span><img src="image/setting/<?php echo $app_row['splash_image']; ?>"
												class='img-thumbnail' alt='No Image' width='60' height='60'/></span>
											</div>
										  </div><!-- End .form-group  -->

										  <div class="form-group">
											<div class="col-lg-offset-2">
												<button type="submit" name="app_setting" class="btn btn-info marginR10 marginL10">Save changes</button>
												<button type="reset" class="btn btn-danger">Cancel</button>
											</div>
										</div><!-- End .form-group  -->
									</form>
								</div>
								<!--privercy policy start-->
								<div class="tab-pane fade" id="profile">
								<?php
									$policysettingquery = mysqli_query($mysqli,"select * from `tbl_policy`");
 									$policy_row=mysqli_fetch_array($policysettingquery)
									?>
									<div class="row">
										<div class="col-lg-12">
											<form class="form-horizontal seperator"  method="post" role="form">
											<div class="form-group">
												<label class="col-lg-2 control-label" for="fullname">Company Profile :</label>
												<div class="col-lg-10">
													 <textarea class="tinymce" name="terms"><?php echo $policy_row['privacy_policy']; ?></textarea>
												</div>
											</div><!-- End .form-group  -->
											<div class="form-group">
												<div class="col-lg-offset-2">
													<button type="submit" name="policy_submit" class="btn btn-info marginR10 marginL10">Save changes</button>
													<button type="reset" class="btn btn-danger">Cancel</button>
												</div>
											</div><!-- End .form-group  -->
										 </form>
										</div><!-- End .row -->
									</div>
								</div> 
							</div>       
						</div>
					 </div>
			<script language="javascript">
	function download_pdf(pdfUrl) {
    // Specify the URL of the PDF file
  //const pdfUrl1 = pdfUrl; // Replace with the actual file path or URL

    // Open the PDF in a new tab
    window.open(pdfUrl, '_blank');
		
}
			function callonsubmit()
				{		
					if(document.getElementById("new_username").value != '')
					{
						if(document.getElementById("co_username").value=="" || document.getElementById("co_username").value == null)
						{
								alert("Please enter your confirm username.");
								document.getElementById("co_username").focus();
								return false;
						}
						if(document.getElementById("username").value=="" || document.getElementById("username").value == null)
						{
								alert("Please enter your current username.");
								document.getElementById("username").focus();
								return false;
						}
						if(document.getElementById("co_username").value!="" && document.getElementById("co_username").value != '' && (document.getElementById("new_username").value != document.getElementById("co_username").value))
						{
							alert("Confirm username does not match with new username.");
							document.getElementById("new_username").focus();
							return false;
						}
					}
					if(document.getElementById("password").value != '')
					{
						if(document.getElementById("co_password").value=="" || document.getElementById("co_password").value == null)
						{
								alert("Please enter your confirm password.");
								document.getElementById("co_password").focus();
								return false;
						}
						if(document.getElementById("current_password").value=="" || document.getElementById("current_password").value == null)
						{
								alert("Please enter your current password.");
								document.getElementById("current_password").focus();
								return false;
						}
						if(document.getElementById("co_password").value!="" && document.getElementById("co_password").value != '' && (document.getElementById("password").value != document.getElementById("co_password").value))
						{
							alert("Confirm password does not match with password.");
							document.getElementById("password").focus();
							return false;
						}
					}

				return true;
			}
			</script>
			
			<?php
				mysqli_close($mysqli);
				include("footer.php");
			?>