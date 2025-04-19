<?php
include("header.php");
$dateupdate = date("Y-m-d H:i:s");

if(isset($_SERVER['HTTPS'])) 
{  
   $file_path = 'https://'.$_SERVER[ 'SERVER_NAME' ].dirname( $_SERVER[ 'REQUEST_URI' ] ) . '/';
   $link = 'https://';
}
else
{
  $file_path = 'http://'.$_SERVER[ 'SERVER_NAME' ].dirname( $_SERVER[ 'REQUEST_URI' ] ) . '/';
  $link = 'http://';

}

	if($_GET['action'] == 'delete')
	{
		mysqli_query($mysqli,"delete from `tbl_user` Where `id`='".$_GET['id']."'");
		echo "<script language='javascript'>window.location='manage_user.php?msg=deleted';</script>";
	}
	if($_GET['upd_status'] == 'act')
	{
		mysqli_query($mysqli,"update `tbl_user` set status='1' where `id`='".$_GET['id']."'");
		echo "<script language='javascript'>window.location='manage_user.php';</script>";
		die;
	}
	if($_GET['upd_status'] == 'dact')
	{
		mysqli_query($mysqli,"update `tbl_user` set status='0' where  `id`='".$_GET['id']."'");
		echo "<script language='javascript'>window.location='manage_user.php';</script>";
		die;
	}
	if(isset($_POST['submit']))
	{
		
		
		 $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		 $check_query = mysqli_query($mysqli, "SELECT * FROM `tbl_user` WHERE `mobile_no`='".$_POST['mobile_no']."' AND `id`!= '$id'");
			if (mysqli_num_rows($check_query) > 0) {
				// Mobile number already exists
				echo "<script>alert('Mobile number already exists. Please use a different number.');</script>";
			} else {

			if($_POST['action'] == 'add')
			{	
				mysqli_query($mysqli,"insert `tbl_user` set 
				`name`='".$_POST['name']."',
				`password`='".$_POST['password']."',
				`email`='".$_POST['email']."',
				`mobile_no`='".$_POST['mobile_no']."',
				`address`='".$_POST['address']."',
				`city_name`='".$_POST['city_name']."',
				`transport_name`='".$_POST['transport_name']."',
				`transport_add`='".$_POST['transport_add']."',
				`gst_no`='".$_POST['gst_no']."',
				`discount`='".$_POST['discount']."',
				`status`='".$_POST['status']."',
				`user_status`='".$_POST['user_status']."',
				`date`='".$dateupdate."',
				`update_date`='".$dateupdate."'
				");
				echo "<script language='javascript'>window.location='manage_user.php?msg=updated';</script>";
			}
			if($_POST['action'] == 'edit')
			{	
				if($_POST['password'] != '')
				{
					$password = ",`password`='".$_POST['password']."'";
				}

				mysqli_query($mysqli,"UPDATE `tbl_user` set 
				`name`='".$_POST['name']."',
				`email`='".$_POST['email']."',
				`mobile_no`='".$_POST['mobile_no']."',
				`address`='".$_POST['address']."',
				`city_name`='".$_POST['city_name']."',
				`transport_name`='".$_POST['transport_name']."',
				`transport_add`='".$_POST['transport_add']."',
				`gst_no`='".$_POST['gst_no']."',
				`discount`='".$_POST['discount']."',
				`status`='".$_POST['status']."',
				`user_status`='".$_POST['user_status']."',
				`update_date`='".$dateupdate."'
				$password
				where `id`='".$_GET['id']."'");
				echo "<script language='javascript'>window.location='manage_user.php?msg=updated';</script>";
			}
		}
	}			
?>
       <!--Body content-->
      <div id="content" class="clearfix">
           <div class="contentwrapper"><!--Content wrapper-->
               <div class="heading">
                    <h3>Manage User</h3>                    
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
                        <li class="active">Manage User</li>
                    </ul>
                </div><!-- End .heading-->
                <!-- Build page from here: Usual with <div class="row-fluid"></div> -->
                
               
    <?php
	if($_GET['action'] == "edit")
	{
		$btn="Edit";
		$act="edit";
		$edit_query=mysqli_query($mysqli,"select * from `tbl_user` where id='".$_GET['id']."'");				
		$upd_row=mysqli_fetch_array($edit_query);
	}
	else
	{
		$act="add";
		$btn="Add";
	}
     if($_GET['action'] == "add" || $_GET['action'] == "edit")
	{
	?>	
     <!-- Build page from here: -->
                <div class="row">
                    <div class="col-lg-12">
                        <form class="form-horizontal seperator" id="form-validate"  method="post" role="form" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="<?php echo $act?>" />
						<input type="hidden" name="id" value="<?php echo $_GET['id']?>" />
                            <div class="form-group">
                                 <label class="col-lg-2 control-label" for="required">Party Name:</label>
                                <div class="col-lg-10">
                                  <input type="text" class="form-control" id="required" name="name" 
                                  value="<?php echo $upd_row['name'];?>"> 
                                </div>
                            </div><!-- End .form-group  -->
                            <div class="form-group">
                                 <label class="col-lg-2 control-label" for="required">Email:</label>
                                <div class="col-lg-10">
                                  <input type="text" class="form-control" id="required" name="email" 
                                  value="<?php echo $upd_row['email'];?>"> 
                                </div>
                            </div><!-- End .form-group  -->
                            <div class="form-group">
                                 <label class="col-lg-2 control-label" for="required">Mobile No:</label>
                                <div class="col-lg-10">
                                  <input type="text" class="form-control" id="required" name="mobile_no" 
                                  value="<?php echo $upd_row['mobile_no'];?>"> 
                                </div>
                            </div><!-- End .form-group  -->
                             <div class="form-group">
                                 <label class="col-lg-2 control-label" for="required">Password:</label>
                                <div class="col-lg-10">
                                  <input type="password" class="form-control" id="required" name="password" 
                                  value=""> 
                                </div>
                            </div><!-- End .form-group  -->
                              <div class="form-group">
                                 <label class="col-lg-2 control-label" for="required">Address:</label>
                                <div class="col-lg-10">
                                 <textarea name="address" id="address" rows="3" class="form-control"><?php echo $upd_row['address'];?></textarea>
                                </div>
                            </div><!-- End .form-group  -->
                             <div class="form-group">
                                 <label class="col-lg-2 control-label" for="required">City:</label>
                                <div class="col-lg-10">
                                  <input type="text" class="form-control" id="required" name="city_name" 
                                  value="<?php echo $upd_row['city_name'];?>"> 
                                </div>
                            </div><!-- End .form-group  -->
                              <div class="form-group">
                                 <label class="col-lg-2 control-label" for="required">Transport Name:</label>
                                <div class="col-lg-10">
                                  <input type="text" class="form-control" id="required" name="transport_name" 
                                  value="<?php echo $upd_row['transport_name'];?>"> 
                                </div>
                            </div><!-- End .form-group  -->
                             <div class="form-group">
                                 <label class="col-lg-2 control-label" for="required">Transport Address:</label>
                                <div class="col-lg-10">
                                  <input type="text" class="form-control" id="required" name="transport_add" 
                                  value="<?php echo $upd_row['transport_add'];?>"> 
                                </div>
                            </div><!-- End .form-group  -->
                             <div class="form-group">
                                 <label class="col-lg-2 control-label" for="required">GST No:</label>
                                <div class="col-lg-10">
                                  <input type="text" class="form-control" id="required" name="gst_no" 
                                  value="<?php echo $upd_row['gst_no'];?>"> 
                                </div>
                            </div><!-- End .form-group  -->
                             <div class="form-group">
                                 <label class="col-lg-2 control-label" for="required">Discount (%):</label>
                                <div class="col-lg-10">
                                  <input type="text" class="form-control" id="required" name="discount" 
                                  value="<?php echo $upd_row['discount'];?>"> 
                                </div>
                            </div><!-- End .form-group  -->
                            <div class="form-group">
                                 <label class="col-lg-2 control-label" for="required">User Type:</label>
                                <div class="col-lg-10">
                                 <select name="status" id="flat_type_id" class="form-control" >
                                 	<option value="1" <?php if($upd_row['status'] == '1') echo"selected"; ?>>Active</option>
                                 	<option value="0" <?php if($upd_row['status'] == '0') echo"selected"; ?>>Block</option>
                                 </select>
                                </div>
                            </div><!-- End .form-group  -->
                            <div class="form-group">
                                 <label class="col-lg-2 control-label" for="required">Super Admin:</label>
                                <div class="col-lg-10">
                                 <select name="user_status" id="user_status" class="form-control" >
                                 	<option value="1" <?php if($upd_row['user_status'] == '1') echo"selected"; ?>>Normal</option>
                                 	<option value="2" <?php if($upd_row['user_status'] == '2') echo"selected"; ?>>Master Admin</option>
                                 </select>
                                </div>
                            </div><!-- End .form-group  -->
                           <div class="form-group">
                                <div class="col-lg-offset-2">
                                    <button type="submit" name="submit" class="btn btn-info marginR10 marginL10">Save changes</button>
                                    <button type="reset" class="btn btn-danger" onclick="javascript:window.location='manage_user.php';">Cancel</button>
                                </div>
                            </div><!-- End .form-group  -->
    					   </form>
                    </div><!-- End .span12 -->
                </div><!-- End .row -->
			<?php
            }
			else
			{
            ?>
              <div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default gradient">
						<div class="panel-heading">
							<h4>
								<span>Manage User</span>
							</h4>
							<!-- <div class="print">
                                    <a href="<?php echo $file_path; ?>/user_pdf.php" target="_blank" class="tip" title="Print invoice"><span class="icon24 icomoon-icon-print"></span></a>
                                </div>-->
						</div>
						<div class="panel-body noPad clearfix">
							<table cellpadding="0" cellspacing="0" border="0" class="dynamicTable display table table-bordered" width="100%">
								<thead>
									 <tr>
									  	  <th>ID</th>
										  <th>Name</th>
										  <th>Mobile No.</th>
										  <th>Password</th>
										  <th>Email</th>
										  <th>Transport Name</th>
										  <th>Discount</th>
										  <th>Status</th>
										  <th>Action</th>
									</tr>
								</thead>

								 <tbody>
								<?php
								$query = mysqli_query($mysqli,"select * from `tbl_user` ORDER BY `tbl_user`.`id` DESC");
								while($row=mysqli_fetch_array($query))
								{
									if($row['status'] == '1')
									{
										$new_status_kwd = "dact";
										$new_status ="Inactive";
									}
									else
									{		
										$new_status_kwd = "act";
										$new_status ="Active";	
									}
								 ?>
									<tr class="odd gradeX">
										<td><?php echo $row['id']; ?></td>
										<td><?php echo $row['name']; ?></td>
										<td><?php echo $row['mobile_no']; ?></td>
										<td><?php echo $row['password']; ?></td>	
										<td><?php echo $row['email']; ?></td>		
										<td><?php echo $row['transport_name']; ?></td>		
										<td><?php echo $row['discount']; ?>%</td>									
										<td class="center">
										<?php
										if($row['user_status'] == '1')
										{
										?>
										<a class="listinglingk" href="manage_user.php?upd_status=<?php echo $new_status_kwd;?>&id=<?php echo $row['id'];?>"><strong><?php echo $new_status;?></strong></a>
										<?php
										}
										?>
										</td>
										<td class="controls center">
										<a class="tip" href="manage_user.php?action=edit&amp;id=<?php echo $row['id'];?>" 
										title="Edit task" data-hasqtip="true" aria-describedby="qtip-7">
									   <span class="icon12 icomoon-icon-pencil"></span>
									   </a>&nbsp;&nbsp;
									   <?php
										if($row['user_status'] == '1')
										{
										?>
									   <a class="tip" href="manage_user.php?action=delete&amp;id=<?php echo $row['id'];?>" onClick="return confirmdelete();"
									   title="Delete task" data-hasqtip="true" aria-describedby="qtip-7">
									   <span class="icon12 icomoon-icon-remove"></span>
									   </a>
									    <?php
										}
										?>
									   </td>
									</tr>
								   <?php
								   }
								   ?> 
								  </tbody>
									<tfoot>
									<tr>
									 	 <th>ID</th>
										 <th>Name</th>
										  <th>Mobile No.</th>
										  <th>Password</th>
										  <th>Email</th>
										  <th>Transport Name</th>
										  <th>Discount</th>
										  <th>Status</th>
										  <th>Action</th>
									</tr>
									<tr>
										<th colspan="9" style="text-align:center;">
											<button class="btn btn-info" onclick="javascript:window.location='manage_user.php?action=add';">
											Add User
											</button>
										 </th>
									</tr>
								</tfoot>

							</table>
						</div>
                      </div><!-- End .panel -->
                  </div><!-- End .span12 -->
              </div><!-- End .row -->
			<!-- Page end here -->
          <?php
		  }
		  mysqli_close($mysqli);
		  ?>
       </div><!-- End contentwrapper -->
    </div><!-- End #content -->
 </div><!-- End #wrapper -->
<script language="javascript">
var action = '<?php echo $_GET['action'] ?>';

function confirmdelete()
{
	var agree=confirm("Would you like to continue?");
	if (agree)	
		return true ;
	else
		return false ;
}
</script>
     <!-- Le javascript -->
    <!-- Important plugins put in all pages -->
    <script  type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap/bootstrap.js"></script>  
    <script type="text/javascript" src="js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="js/libs/jRespond.min.js"></script>

    <!-- Charts plugins -->
    <script type="text/javascript" src="plugins/charts/sparkline/jquery.sparkline.min.js"></script><!-- Sparkline plugin -->
   
    <!-- Misc plugins -->
    <script type="text/javascript" src="plugins/misc/nicescroll/jquery.nicescroll.min.js"></script>
    <script type="text/javascript" src="plugins/misc/qtip/jquery.qtip.min.js"></script><!-- Custom tooltip plugin -->
    <script type="text/javascript" src="plugins/misc/totop/jquery.ui.totop.min.js"></script> 

    <!-- Search plugin -->
    <script type="text/javascript" src="plugins/misc/search/tipuesearch_set.js"></script>
    <script type="text/javascript" src="plugins/misc/search/tipuesearch_data.js"></script><!-- JSON for searched results -->
    <script type="text/javascript" src="plugins/misc/search/tipuesearch.js"></script>

    <!-- Form plugins -->
    <script type="text/javascript" src="plugins/forms/elastic/jquery.elastic.js"></script>
    <script type="text/javascript" src="plugins/forms/inputlimiter/jquery.inputlimiter.1.3.min.js"></script>
    <script type="text/javascript" src="plugins/forms/maskedinput/jquery.maskedinput-1.3.min.js"></script>
    <script type="text/javascript" src="plugins/forms/togglebutton/jquery.toggle.buttons.js"></script>
    <script type="text/javascript" src="plugins/forms/uniform/jquery.uniform.min.js"></script>
    <script type="text/javascript" src="plugins/forms/globalize/globalize.js"></script>
    <script type="text/javascript" src="plugins/forms/color-picker/colorpicker.js"></script>
    <script type="text/javascript" src="plugins/forms/timeentry/jquery.timeentry.min.js"></script>
    <script type="text/javascript" src="plugins/forms/select/select2.min.js"></script>
    <script type="text/javascript" src="plugins/forms/dualselect/jquery.dualListBox-1.3.min.js"></script>
    <script type="text/javascript" src="plugins/forms/tiny_mce/tinymce.min.js"></script>
    <script type="text/javascript" src="js/supr-theme/jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="js/supr-theme/jquery-ui-sliderAccess.js"></script>
    
    <!-- Table plugins -->
    <script type="text/javascript" src="plugins/tables/dataTables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="plugins/tables/dataTables/TableTools.min.js"></script>
    <script type="text/javascript" src="plugins/tables/dataTables/ZeroClipboard.js"></script>
    <script type="text/javascript" src="plugins/tables/responsive-tables/responsive-tables.js"></script><!-- Make tables responsive -->

    <!-- Init plugins -->
    <script type="text/javascript" src="js/main.js"></script><!-- Core js functions -->
    <script type="text/javascript" src="js/datatable.js"></script><!-- Init plugins only for page -->
    <script type="text/javascript" src="js/forms.js"></script><!-- Init plugins only for page -->
    </body>
</html>
