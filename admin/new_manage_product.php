<?php
include("header.php");
$dateupdate = date("Y-m-d H:i:s");

$file_path = 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI']).'/';

	if($_GET['action'] == 'delete')
	{
		mysqli_query($mysqli,"delete from `tbl_product` Where `id`='".$_GET['id']."'");
		echo "<script language='javascript'>window.location='manage_product.php?msg=deleted';</script>";
	}
	if($_GET['upd_status'] == 'act')
	{
		mysqli_query($mysqli,"update `tbl_product` set status='1' where `id`='".$_GET['id']."'");
		echo "<script language='javascript'>window.location='manage_product.php';</script>";
		die;
	}
	if($_GET['upd_status'] == 'dact')
	{
		mysqli_query($mysqli,"update `tbl_product` set status='0' where  `id`='".$_GET['id']."'");
		echo "<script language='javascript'>window.location='manage_product.php';</script>";
		die;
	}
	if(isset($_POST['updateprice']))
	{
		$update_product=mysqli_query($mysqli,"update tbl_product set  discount='".$_POST['user_disc']."' where id='".$_POST['designer_id']."'");
		echo "<script language='javascript'>window.location='manage_product.php';</script>";
		die;
	}
	if(isset($_POST['submit']))
	{
		 if($_FILES["design_image"]["error"] == UPLOAD_ERROR_OK)
		 {
			$tmp_name = $_FILES["design_image"]["tmp_name"];
			$name = $_FILES["design_image"]["name"];		
			$forth_banner_new = getuniqkey($tmp_name).substr($name,strrpos($name,"."));
			move_uploaded_file($_FILES["design_image"]["tmp_name"], '../image/product/'.$forth_banner_new);
			$source_img = "../image/product/".$forth_banner_new;
			$destination_img = "../image/product/thumb/".$forth_banner_new;
			$imgData = resize_image($source_img,150,100);
			imagepng($imgData, $destination_img);
			$compress_portrait = compress($destination_img, $destination_img, 60);
		 }
		
		$sizeArray = array_filter($_POST['size'], function($value) {
				return trim($value) !== ''; // Exclude blank and whitespace-only values
			});
		
		$priceArray = array_filter($_POST['price'], function($value) {
				return trim($value) !== ''; // Exclude blank and whitespace-only values
			});
		
		$min_qtyArray = array_filter($_POST['min_qty'], function($value) {
				return trim($value) !== ''; // Exclude blank and whitespace-only values
			});
		
			
			// Check if the filtered size array is empty
			if (!empty($sizeArray)) {
			
			$size = implode('{*}', $sizeArray);
			$price = implode('{*}', $priceArray);
			$min_qty = implode('{*}', $min_qtyArray);
			$offer_price = implode('{*}', $_POST['offer_price']);
			$std_paking = implode('{*}', $_POST['std_paking']);
			
			}
		
		
		if($_POST['action'] == 'add')
		{	
			
			
			mysqli_query($mysqli,"insert `tbl_product` set 
			`category_id`='".$_POST['category_id']."',
			`name`='".$_POST['name']."',
			`number`='".$_POST['number']."',
			`desc`='".$_POST['desc']."',
			`image`='".$forth_banner_new."',
			`size`='".$size."',
			`price`='".$price."',
			`std_paking`='".$std_paking."',
			`min_qty`='".$min_qty."',
			`offer_price`='".$offer_price."',
			`date`='".$dateupdate."'
			");
				
			echo "<script language='javascript'>window.location='manage_product.php?action=added';</script>";
		}
		if($_POST['action'] == 'edit')
		{	

			if($forth_banner_new != '')
			{
				$qrystr = ",`image`='".$forth_banner_new."'";
			}
			
			
			mysqli_query($mysqli,"UPDATE `tbl_product` set 
			`category_id`='".$_POST['category_id']."',
			`number`='".$_POST['number']."',
			`name`='".$_POST['name']."',
			`desc`='".$_POST['desc']."',
			`size`='".$size."',
			`price`='".$price."',
			`std_paking`='".$std_paking."',
			`min_qty`='".$min_qty."',
			`offer_price`='".$offer_price."'
			$qrystr
			where `id`='".$_GET['id']."'");
			echo "<script language='javascript'>window.location='manage_product.php?msg=updated';</script>";

		}
	}		

?>
	<!--Body content-->
	<div id="content" class="clearfix">
		<div class="contentwrapper"><!--Content wrapper-->
			<div class="heading">
				<h3>Manage Product</h3>                    
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
					<li class="active">Manage Product</li>
				</ul>
			</div><!-- End .heading-->
		<!-- Build page from here: Usual with <div class="row-fluid"></div> -->
    <?php
	if($_GET['action'] == "edit")
	{
		$btn="Edit";
		$act="edit";
		$edit_query = mysqli_query($mysqli, "SELECT * FROM `tbl_product` WHERE id='".$_GET['id']."'");
		$upd_row = mysqli_fetch_array($edit_query);

		// Parse the database fields+
		$sizes = explode("{*}", $upd_row['size']);
		$prices = explode("{*}", $upd_row['price']);
		$std_packings = explode("{*}", $upd_row['std_paking']);
		$min_qtys = explode("{*}", $upd_row['min_qty']);
		$offer_prices = explode("{*}", $upd_row['offer_price']);
		
		$arrayCount = count($sizes);
		
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
                            </div><!-- End .form-group  -->
                             <div class="form-group">
							<label class="col-lg-2 control-label" for="tags">Product Number:</label>
								<div class="col-lg-10">
									<input id="product_name" name="number" class="form-control" type="text" value="<?php echo $upd_row['number']; ?>" />
								</div>
							</div><!-- End .form-group  -->
                           <div class="form-group">
							<label class="col-lg-2 control-label" for="tags">Product Name:</label>
								<div class="col-lg-10">
									<input id="product_name" name="name" class="form-control" type="text" value="<?php echo $upd_row['name']; ?>" />
								</div>
							</div><!-- End .form-group  -->
                          <div class="form-group">
							<label class="col-lg-2 control-label" for="tags">Product Detail:</label>
								<div class="col-lg-10">
									<input id="desc" name="desc" class="form-control" type="text" value="<?php echo $upd_row['desc']; ?>" />
								</div>
							</div><!-- End .form-group  -->
                          <div class="form-group">
							  <label class="col-lg-2 control-label" for="username">Image:</label>
							  <div class="col-lg-10">
								<input type="file" name="design_image" id="design_image" value="">
							  </div>
							</div>
						   <!-- End .form-group -->
                       	 <?php
						//Loop through and generate the fields for editing
						$count = '0';
if (is_array($sizes) && !empty($sizes)) {    
    foreach ($sizes as $key => $size) {
        $count++;
        if (!empty($size)) { // Additional check for valid size data
?>
<div class="row-item form-group" id="row-<?php echo $count; ?>">
    <label class="col-lg-2 control-label" for="tags">Size:</label>
    <div class="col-lg-4">
        <input name="size[]" class="form-control" type="text" value="<?php echo $size; ?>" />
    </div>

    <label class="col-lg-2 control-label" for="tags">Price:</label>
    <div class="col-lg-4">
        <input name="price[]" class="form-control" type="text" value="<?php echo $prices[$key]; ?>" />
    </div>

    <div class="form-group">
        <label class="col-lg-2 control-label" for="tags">Master Pack (Pcs. / Cartoon):</label>
        <div class="col-lg-4">
            <input name="std_paking[]" class="form-control" type="text" value="<?php echo $std_packings[$key]; ?>" />
        </div>
        <label class="col-lg-2 control-label" for="tags">Avg. Weight (Kg. / Pcs.):</label>
        <div class="col-lg-4">
            <input name="min_qty[]" class="form-control" type="text" value="<?php echo $min_qtys[$key]; ?>" />
        </div>
    </div>
    <!-- Add and Delete buttons -->
    <?php if ($count == count($sizes)) { ?>
        <button type="button" id="add_btn" name="add" onClick="OnButtonClick()" class="btn btn-info marginR10 marginL10">Add</button>
    <?php }?>
    <button type="button" class="btn btn-danger delete-btn" onClick="deleteRow(this)">Delete</button>
</div>
<?php
		
        }
    }
}
							else
						{
							   ?>
								 
							<div class="form-group" id="inputContainer1">
								<label class="col-lg-2 control-label" for="tags">Size:</label>
								<div class="col-lg-4">
								  <input name="size[]" class="form-control" type="text" value="<?php echo $size; ?>" />
								</div>

								<label class="col-lg-2 control-label" for="tags">Price:</label>
								<div class="col-lg-4">
								  <input name="price[]" class="form-control" type="text" value="<?php echo $prices[$key]; ?>" />
								</div>
						   </div><!-- End .form-group -->	
							<div class="form-group">		
								<label class="col-lg-2 control-label" for="tags">Master Pack (Pcs. / Cartoon):</label>
								<div class="col-lg-4">
								  <input name="std_paking[]" class="form-control" type="text" value="<?php echo $std_packings[$key]; ?>" />
								</div>
								<div>
								<label class="col-lg-2 control-label" for="tags">Avg. Weight (Kg. / Pcs.):</label>
								<div class="col-lg-4">
								  <input name="min_qty[]" class="form-control" type="text" value="<?php echo $min_qtys[$key];  ?>" />
								</div>
							</div><!-- End .form-group -->	
								<button type="button" id="add_btn" name="add" onClick="OnButtonClick()" class="btn btn-info marginR10 marginL10">Add</button>
								
							
														
							  </div><!-- End .form-group -->	
    
						
                          <?php
					}
						?>
                          <div class="form-group" id="inputContainer">
                          
                          </div><!-- End .form-group -->
                           <div class="form-group">
                                <div class="col-lg-offset-2">
                                    <button type="submit" name="submit" class="btn btn-info marginR10 marginL10">Save changes</button>
                                    <button type="reset" class="btn btn-danger" onclick="javascript:window.location='manage_product.php';">Cancel</button>
                                </div>
                            </div><!-- End .form-group  -->
    					   </form>
                    </div><!-- End .span12 -->
                </div><!-- End .row -->
<script language="javascript">
/*var current_act	= "<?php echo $_GET['action']; ?>";
if(current_act == "edit")
{
    get_country();
	
}
	
function get_country()
{
	
	var maincat = "<?php echo $upd_row['sub_category_id'];?>";
	$.post("ajex.php", "get=data&category_id="+$("#category_id").val()+"&sel="+maincat, function(data)
	{
		$("#sub_category_id").html(data);
	});
	
}	*/
	
function OnButtonClick() {
  // Create a new div for the appended input fields
  var newDiv = document.createElement("div");
  newDiv.classList.add("form-group", "row-item");

  // Set the inner HTML for the new div
  newDiv.innerHTML = `
    <div class="form-group">  
      <label class="col-lg-2 control-label" for="tags">Size:</label>
      <div class="col-lg-4">
        <input name="size[]" class="form-control" type="text" />
      </div>
      <label class="col-lg-2 control-label" for="tags">Price:</label>
      <div class="col-lg-4">
        <input name="price[]" class="form-control" type="text" value="" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-2 control-label" for="tags">Master Pack (Pcs. / Cartoon):</label>
      <div class="col-lg-4">
        <input name="std_paking[]" class="form-control" type="text" value="" />
      </div>
      <label class="col-lg-2 control-label" for="tags">Avg. Weight (Kg. / Pcs.):</label>
      <div class="col-lg-4">
        <input name="min_qty[]" class="form-control" type="text" value="" />
      </div>
    </div>
    <div class="col-lg-2">
      <button type="button" class="btn btn-info add-btn" onClick="OnButtonClick()">Add</button>
    </div>
    <div class="col-lg-2">
      <button type="button" class="btn btn-danger delete-btn" onClick="deleteRow(this)">Delete</button>
    </div>
  `;

  // Append the new div to the input container
  document.getElementById("inputContainer").appendChild(newDiv);
//hide button first add 	
  $('#add_btn').hide();
  $('#add_first').hide();	
	
  // Update button visibility
  updateButtonVisibility();
}

function deleteRow(button) {
  // Identify the parent row-item container
  const row = button.closest(".row-item");

  if (row) {
    // Remove the row from the DOM
    row.remove();

    // Update button visibility for remaining rows
    updateButtonVisibility();
  }
}

function updateButtonVisibility() {
 // Get all rows
  const rows = document.querySelectorAll(".row-item");

  rows.forEach((row, index) => {
    const addButton = row.querySelector(".add-btn");
    const deleteButton = row.querySelector(".delete-btn");

    // Show "Add" button only for the last row
    if (addButton) {
      addButton.style.display = index === rows.length - 1 ? "inline-block" : "none";
    }

    // Hide "Delete" button for the last row
    if (deleteButton) {
      deleteButton.style.display = index === rows.length - 1 ? "none" : "inline-block";
    }
  });
}

// Run the visibility check when the page loads
document.addEventListener("DOMContentLoaded", updateButtonVisibility);

</script>
			<?php
            }
			else
			{
            ?>
               <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default gradient">
                               <!-- Build page from here: -->
								<div class="row">
									<div class="col-lg-12">
										<div class="well well-small">
											<form id="" class="form form-horizontal" method="get" action="">
												<div class="form-row row-fluid">
													<div class="col-lg-12">
														<div class="row">
															<div class="col-lg-3">
																<input type="text" name="search" class="form-control" id="tipue_search_input" placeholder="Search Here" />
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
                                   <h4 style="text-align: center;">
                                       <button class="btn btn-info" onclick="javascript:window.location='manage_product.php?action=add';">
                                         Add Product
                                     </button>
                                    </h4>
                                </div>
                                
                                <?php
								switch ($_GET['col']) 
								{
									case 'st':
										$order_by = 'tq.status'; 
									break;
									default:
										$order_by = 'tq.id ';
								}
								$order_by = ' Order by '.$order_by;
								$aow = ($_GET['ow'] == 'asc') ? "desc" : "asc" ;
								$order_way = ($_GET['ow'] == '') ? "desc" : $_GET['ow'];
								?>
                                
                                <div class="panel-body noPad clearfix">
                                    <table cellpadding="0" cellspacing="0" id="checkAll" class="display table table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                               <th>Id</th>
                                               <th>Number</th>
                                               <th>Name</th>
                                               <th>Category Name</th>
                                               <th>Description</th>
                                               <th>Image</th>
                                               <th>Create Date</th>
                                               <th><a href="?col=st&amp;ow=<?php echo ($_GET['col'] == 'st') ? $aow : "asc"; ?>">Status</a></th>
                                               <th>Action</th>
                                            </tr>
                                        </thead>
                                         <?php
				
										if($_GET['search_submit'] == 'submit')
										{
											$qrysearch = "and CONCAT_WS('',tq.name,tq.number,ca.category_name) LIKE '%".$_GET['search']."%'";
										}
										//Main query
										$tmpc=0;
										$pages = new Paginator;
										$pages->default_ipp = 25;
										$sql_forms = mysqli_query($mysqli,"select * from `tbl_category` $qrysearch");
										$pages->items_total = $sql_forms->num_rows;
										$pages->mid_range = 9;
										$pages->paginate();	

											$result	= mysqli_query($mysqli,"select tq.*,ca.category_name
											from `tbl_product` tq,`tbl_category` ca where ca.id=tq.category_id
											$qrysearch $order_by $order_way ".$pages->limit."");

											if($pages->items_total>0){
												$n  =   1;
												$numrows = mysqli_num_rows($result);
												while($row = $result->fetch_assoc()){ 
											 $tmpc++;
				
									
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
                                              	<td><?php echo $row['number']; ?></td>
                                              	<td><?php echo $row['name']; ?></td>
                                              	<td><?php echo $row['category_name']; ?></td>
                                              
                                                <td><?php echo $row['desc']; ?></td>
                                                 <td>
                                                <?php
												if($row['image'] !='')
												{
												?>
                                                <a href="../image/product/<?php echo $row['image']; ?>" rel="prettyPhoto" title="<?php echo $row['name']; ?>">
                                                <img src="../image/product/thumb/<?php echo $row['image']; ?>"
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
                                                <td><?php echo $row['date']; ?></td>
                                                <td class="center"><a class="listinglingk" href="manage_product.php?upd_status=<?php echo $new_status_kwd;?>&id=<?php echo $row['id'];?>"><strong><?php echo $new_status;?></strong></a></td>
                                                <td class="controls center">
                                                <a class="tip" href="manage_product.php?action=edit&amp;id=<?php echo $row['id'];?>" 
                                                title="Edit task" data-hasqtip="true" aria-describedby="qtip-7">
                                               <span class="icon12 icomoon-icon-pencil"></span>
                                               </a>&nbsp;&nbsp;
                                               <a class="tip" href="manage_product.php?action=delete&amp;id=<?php echo $row['id'];?>" onClick="return confirmdelete();"
                                               title="Delete task" data-hasqtip="true" aria-describedby="qtip-7">
                                               <span class="icon12 icomoon-icon-remove"></span>
                                               </a>
                                               </td>
                                              </tr>
                                         <?php 
												}
											}
										?>
                                         <tbody>
                                        
                                          </tbody>
                                            <tfoot>
                                            <tr>
                                             <th>Id</th>
                                              <th>Number</th>
                                                <th>Name</th>
                                               <th>Category Name</th>
                                               <th>Description</th>
                                               <th>Image</th>
                                               <th>Create Date</th>                                              
                                               <th>Status</th>
                                               <th>Action</th>
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
