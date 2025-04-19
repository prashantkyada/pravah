<?php
include("header.php");
$dateupdate = date("Y-m-d H:i:s");

	if($_GET['action'] == 'delete')
	{
		mysqli_query($mysqli,"delete from `tbl_banner` Where `id`='".$_GET['id']."'");
		echo "<script language='javascript'>window.location='manage_banner.php?msg=deleted';</script>";
	}
	if($_GET['upd_status'] == 'act')
	{
		mysqli_query($mysqli,"update `tbl_banner` set status='1' where `id`='".$_GET['id']."'");
		echo "<script language='javascript'>window.location='manage_banner.php';</script>";
		die;
	}
	if($_GET['upd_status'] == 'dact')
	{
		mysqli_query($mysqli,"update `tbl_banner` set status='0' where `id`='".$_GET['id']."'");
		echo "<script language='javascript'>window.location='manage_banner.php';</script>";
		die;
	}
	if($_GET['action'] == "order")
	{
		if($_GET['dir'] == 'up')
		{
			$get_curr_ord=mysqli_query($mysqli,"select * from tbl_banner where id='".$_GET['id']."'");
			while($curr_ord_row = mysqli_fetch_array($get_curr_ord))
			{
				$curr_order = $curr_ord_row['display_order'];
				$curr_id = $curr_ord_row['id'];
			}

			$get_curr_ord=mysqli_query($mysqli,"select * from tbl_banner where display_order<'".$curr_order."' order by display_order desc limit 0,1");
			while($curr_ord_row = mysqli_fetch_array($get_curr_ord))
			{
				$prev_order = $curr_ord_row['display_order'];
				$prev_id = $curr_ord_row['id'];
			}
			mysqli_query($mysqli,"update tbl_banner set display_order='".$prev_order."' where id='".$curr_id."'");
			mysqli_query($mysqli,"update tbl_banner set display_order='".$curr_order."' where id='".$prev_id."'");
		}
		if($_GET['dir'] == 'dw')
		{
			$get_curr_ord=mysqli_query($mysqli,"select * from tbl_banner where  id='".$_GET['id']."'");
			while($curr_ord_row = mysqli_fetch_array($get_curr_ord))
			{
				$curr_order = $curr_ord_row['display_order'];
				$curr_id = $curr_ord_row['id'];
			}
			$get_curr_ord=mysqli_query($mysqli,"select * from tbl_banner where  display_order>'".$curr_order."' order by display_order asc limit 0,1");
			while($curr_ord_row = mysqli_fetch_array($get_curr_ord))
			{
				$next_order = $curr_ord_row['display_order'];
				$next_id = $curr_ord_row['id'];
			}
			mysqli_query($mysqli,"update tbl_banner set display_order='".$next_order."' where id='".$curr_id."'");
			mysqli_query($mysqli,"update tbl_banner set display_order='".$curr_order."' where id='".$next_id."'");
		}
		echo "<script language='javascript'>window.location='manage_banner.php';</script>";
		die;
	}
	if(isset($_POST['submit']))
	{
		if($_FILES["image"]["error"]==UPLOAD_ERROR_OK)
		{
			$tmp_name = $_FILES["image"]["tmp_name"];
			$name = $_FILES["image"]["name"];		
			$forth_banner_new = getuniqkey($tmp_name).substr($name,strrpos($name,"."));
			copy($_FILES["image"]["tmp_name"], '../image/banner/'.$forth_banner_new);
			//$image_link=ROOT_PATH.$forth_banner_new;
		}
		
		if($_POST['action'] == 'add')
		{	
			$get_curr_ord=mysqli_query($mysqli,"select display_order from tbl_banner order by display_order  DESC limit 1");
			while($curr_ord_row = mysqli_fetch_array($get_curr_ord))
			{
				$next_order = (int)$curr_ord_row['display_order']+1;
			}
			
			$category_name = mysqli_real_escape_string($mysqli, $_POST['category_name']);
			mysqli_query($mysqli,"insert `tbl_banner` set 
			`category_id`='".$_POST['category_id']."',
			`image`='".$forth_banner_new."',
			`display_order`='".$next_order."'
			");
			echo "<script language='javascript'>window.location='manage_banner.php?msg=updated';</script>";
		}
		if($_POST['action'] == 'edit')
		{	
			if($forth_banner_new != '')
			{
				$qrystr = ",`image`='".$forth_banner_new."'";
			}
			mysqli_query($mysqli,"UPDATE `tbl_banner` set 
			`category_id`='".$_POST['category_id']."'
			$qrystr
			where `id`='".$_GET['id']."'");
			echo "<script language='javascript'>window.location='manage_banner.php?msg=updated';</script>";
		}
	}	
	if($_GET['action'] == 'add')
	{
		$display = 'Add';
	}
	elseif($_GET['action'] == 'edit')
	{
		$display = 'Edit';
	}
	else
	{
		$display = 'Manage';
	}


?>
       
        <!--Body content-->
        <div id="content" class="clearfix">
            <div class="contentwrapper"><!--Content wrapper-->
                <div class="heading">
                    <h3><?php echo $display;?> Banner</h3>                    
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
                        <li class="active"><?php echo $display;?> Banner</li>
                    </ul>
                </div><!-- End .heading-->
                <!-- Build page from here: Usual with <div class="row-fluid"></div> -->
    <?php
	if($_GET['action'] == "edit")
	{
		$btn="Edit";
		$act="edit";
		$edit_query=mysqli_query($mysqli,"select * from `tbl_banner` where id='".$_GET['id']."'");				
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
                         <!--<div class="form-group">
							<label class="col-lg-2 control-label" for="fullname">Category Name:</label>
							<div class="col-lg-10">
							   <select name="category_id" id="category_id" class="nostyle form-control" style="cursor:pointer;">
								<?php 
								$idproduct= "select * from `tbl_category` ORDER BY `id` DESC";
								$str = mysqli_query($mysqli,$idproduct);				
								$select="";
								$issel = '';
								if(mysqli_num_rows($str)>0)
								{	
									while($viewrow=mysqli_fetch_array($str))
									{
										if($upd_row['category_id'] == $viewrow['id']) 
										{
											$select = "selected";
											$issel = 'yes';
										}else
											$select ="";
											$optsne .= "<option value='".$viewrow['id']."'".$select." >".$viewrow['category_name']."</option>";
									}
								}
								if($issel == '')
									$optsne = '<option value="" selected>--Select--</option>'.$optsne;
								else
									$optsne = '<option value="" >--Select--</option>'.$optsne;

								 echo $optsne;
								 ?>     
							  </select>
							</div>
						</div> End .form-group  -->
                           <div class="form-group">
							  <label class="col-lg-2 control-label" for="username">Images:</label>
							  <div class="col-lg-10">
								<input type="file" name="image" id="image_id" value="">
							  </div>
							</div>
						   <!-- End .form-group -->
                           <div class="form-group">
                                <div class="col-lg-offset-2">
                                    <button type="submit" name="submit" class="btn btn-info marginR10 marginL10">Save changes</button>
                                    <button type="reset" class="btn btn-danger" onclick="javascript:window.location='manage_banner.php';">Cancel</button>
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
                                <div class="row">
									<div class="col-lg-12">
										<div class="well well-small">

											<form id="" class="form form-horizontal" method="get" action="">
												<div class="form-row row-fluid">
													<div class="col-lg-12">
														<div class="row">
															<div class="col-lg-3">
																<input type="text" name="search"  class="form-control" id="tipue_search_input" placeholder="Search Here" />
															</div>
															<div class="col-lg-9">
																<button class="btn btn-default" name="search_submit" value="submit" type="submit" id="tipue_search_button"><span class="icon16 icomoon-icon-search-3"></span> Search</button>
															</div>
														</div>
													</div>
												</div>
											</form>
										</div>
									</div><!-- End .span12 -->
								</div><!-- End .row -->
                                <div class="panel-heading">
                                    <h4>
                                        <span>Manage Banner</span>
                                    </h4>
                                </div>
                                
                                <div class="panel-body noPad clearfix">
                                    <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                               <!-- <th>Category Name</th>-->
                                                <th>Order List</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        
                                         <tbody>
                                        <?php
				
										if($_GET['search_submit'] == 'submit')
										{
											$qrysearch = "where CONCAT_WS('',id) LIKE '%".$_GET['search']."%'";
										}
										//Main query
										$tmpc=0;
										$pages = new Paginator;
										$pages->default_ipp = 25;
										$sql_forms = mysqli_query($mysqli,"select * from `tbl_banner` $qrysearch");
										$pages->items_total = $sql_forms->num_rows;
										$pages->mid_range = 9;
										$pages->paginate();	

										$result	=	mysqli_query($mysqli,"select * from `tbl_banner`  $qrysearch ORDER BY display_order ASC ".$pages->limit."");

											if($pages->items_total>0){
												$n  =   1;
												$numrows = mysqli_num_rows($result);
												while($row = $result->fetch_assoc()){ 
											 $tmpc++;
				
										/*$tmpc=0;
										$query = mysqli_query($mysqli,"select ca.*,la.name from `tbl_category` ca , `tbl_language` la where la.id=ca.language_id  ORDER BY ca.display_order ASC");
										$numrows = mysqli_num_rows($query);
										while($row=mysqli_fetch_array($query))
										{*/
											//$tmpc++;
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
                                                <td>
                                                <?php
												if($row['image'] !='')
												{
												?>
                                                <a href="../image/banner/<?php echo $row['image']; ?>" rel="prettyPhoto" title="Title for image">
                                                <img src="../image/banner/<?php echo $row['image']; ?>"
												class='img-thumbnail' alt='No Image' width='60' height='60'/>
                                                </a>
                                                <?php
												}
												else
												{
												?>
												 <img src="http://placehold.it/60x60" alt="" class="image marginR10"/>
												<?php
												}
												?>
												</td>
                                                <!--<td><?php echo $row['category_name']; ?></td>-->
                                                <td>
                                                <?php
											if($tmpc != 1)
											{
											
											?>
                                                <a class="tip" href="manage_banner.php?action=order&id=<?php echo $row['id']?>&dir=up" 
                                                title="Up Order" data-hasqtip="true" aria-describedby="qtip-7">
                                               <span class="icon12 icomoon-icon-arrow-up"></span>
                                               </a>
                                           	<?php
											}
											if($tmpc != $numrows)
											{
												if($tmpc == 1)
													echo "&nbsp;&nbsp;&nbsp;";
												?>			
												<a class="tip" href="manage_banner.php?action=order&id=<?php echo $row['id']?>&dir=dw" title="Down Order" data-hasqtip="true" aria-describedby="qtip-7"> <span class="icon12 icomoon-icon-arrow-down"></span></a>
												<?php
											}
											?>    
                                               </td>
                                                 <td class="center"><a class="listinglingk" href="manage_banner.php?upd_status=<?php echo $new_status_kwd;?>&id=<?php echo $row['id'];?>"><strong><?php echo $new_status;?></strong></a></td>
                                                <td class="controls center">
                                                <a class="tip" href="manage_banner.php?action=edit&amp;id=<?php echo $row['id'];?>" 
                                                title="Edit task" data-hasqtip="true" aria-describedby="qtip-7">
                                               <span class="icon12 icomoon-icon-pencil"></span>
                                               </a>&nbsp;&nbsp;
                                               <a class="tip" href="manage_banner.php?action=delete&amp;id=<?php echo $row['id'];?>" onClick="return confirmdelete();"
                                               title="Delete task" data-hasqtip="true" aria-describedby="qtip-7">
                                               <span class="icon12 icomoon-icon-remove"></span>
                                               </a>
                                               </td>
                                            </tr>
                                           <?php 
												}
											}
											else
											{
											?>
											<tr>
												<td colspan="5" align="center"><strong>No Record Found!</strong></td>
											</tr>
											<?php 
											} 
											?>
                                          </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>Image</th>
                                             <!--   <th>Category Name</th>-->
                                                <th>Order List</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                           <tr>
										<th colspan="4" style="text-align:center;">
											<button class="btn btn-info" onclick="javascript:window.location='manage_banner.php?action=add';">
											Add Banner
											</button>
										 </th>
									</tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div><!-- End .panel -->
                        </div><!-- End .span12 -->
 						<div class="clearfix"></div>
							<div class="row marginTop">
								<div class="col-sm-12 paddingLeft pagerfwt">
									<?php if($pages->items_total > 0) { ?>
										<?php echo $pages->display_pages();?>
										<?php echo $pages->display_items_per_page();?>
										<?php echo $pages->display_jump_menu(); ?>
									<?php }?>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
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
   <!-- Le javascript-->
    <link href="css/pop.css" rel='stylesheet'  type='text/css'/>
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
	<script type="text/javascript" src="plugins/forms/wizard/jquery.form.wizard.js"></script>
    <script type="text/javascript" src="plugins/forms/wizard/jquery.bbq.js"></script>
    <script type="text/javascript" src="plugins/forms/wizard/jquery.form.js"></script>
    <script type="text/javascript" src="plugins/forms/typeahead/typeahead.min.js"></script>
    
    <!-- Gallery plugins -->
    <script type="text/javascript" src="plugins/gallery/lazy-load/jquery.lazyload.min.js"></script>
    <script type="text/javascript" src="plugins/gallery/jpages/jPages.min.js"></script>
    <script type="text/javascript" src="plugins/gallery/pretty-photo/jquery.prettyPhoto.js"></script>

    <!-- Table plugins -->
    <script type="text/javascript" src="plugins/tables/dataTables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="plugins/tables/dataTables/TableTools.min.js"></script>
    <script type="text/javascript" src="plugins/tables/dataTables/ZeroClipboard.js"></script>
    <script type="text/javascript" src="plugins/tables/responsive-tables/responsive-tables.js"></script><!-- Make tables responsive -->

    <!-- Init plugins -->
    <script type="text/javascript" src="js/main.js"></script><!-- Core js functions -->
    <script type="text/javascript" src="js/datatable.js"></script><!-- Init plugins only for page -->
    <script type="text/javascript" src="js/forms.js"></script><!-- Init plugins only for page -->
    <script type="text/javascript" src="js/widgets.js"></script><!-- Init plugins only for page -->
    </body>
</html>
