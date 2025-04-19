<?php
include("header.php");
$dateupdate = date("Y-m-d H:i:s");
?>
	<!--Body content-->
	<div id="content" class="clearfix">
		<div class="contentwrapper">
		   <!--Content wrapper-->
			<div class="heading">
				<h3>Pending Order</h3>                    
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
					<li class="active">Pending Order</li>
				</ul>
			</div><!-- End .heading-->
			<!-- Build page from here: Usual with <div class="row-fluid"></div> -->
              <!-- Build page from here: -->
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
                                <div class="panel-body noPad clearfix">
                                    <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                               <th>Order Id</th>
                                               <th>Mobile No</th>
                                               <th>User name</th>
                                               <th>Detail</th>
                                               <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
									<?php
									if(isset($_POST['submit_date']))
									{
										$originalDate = $_POST['start_dt'].'00:00:00';
										$startdt = date("Y-m-d H:i:s", strtotime($originalDate));

										$endDate = $_POST['end_dt'].'23:59:59';
										$enddt = date("Y-m-d H:i:s", strtotime($endDate));
										
										$extrasearch = "AND ord.date >= '".$startdt."' AND ord.date <= '".$enddt."'";
										
									}
									if($_GET['search_submit'] == 'submit')
									{
										$qrysearch = "where CONCAT_WS('',u.name,o.order_id,u.mobile_no) LIKE '%".$_GET['search']."%'";
									}
				
									//Main query
									$pages = new Paginator;
									$pages->default_ipp = 25;
									$sql_forms = mysqli_query($mysqli,"SELECT 
										o.order_id,
										u.name AS customer_name,
										u.mobile_no AS customer_mobile,
										SUM(od.quantity) AS total_ordered_quantity,
										SUM(od.box_carton) AS total_ordered_box_carton,
										COALESCE(SUM(dd.total_dispatched_quantity), 0) AS total_dispatched_quantity,
										COALESCE(SUM(dd.total_dispatched_box_carton), 0) AS total_dispatched_box_carton,
										(SUM(od.quantity) - COALESCE(SUM(dd.total_dispatched_quantity), 0)) AS pending_quantity,
										(SUM(od.box_carton) - COALESCE(SUM(dd.total_dispatched_box_carton), 0)) AS pending_box_carton
									FROM 
										tbl_order o
									JOIN 
										tbl_user u ON o.user_id = u.id  
									JOIN 
										tbl_order_detail od ON o.order_id = od.order_id  
									LEFT JOIN 
										(SELECT 
											order_id, 
											product_id, 
											product_dtid, 
											SUM(quantity) AS total_dispatched_quantity,
											SUM(box_carton) AS total_dispatched_box_carton
										 FROM 
											tbl_dispatch_detail
										
										 GROUP BY 
											order_id, product_id, product_dtid
										) dd 
										ON od.order_id = dd.order_id 
										AND od.product_id = dd.product_id 
										AND od.product_dtid = dd.product_dtid
										$qrysearch
									GROUP BY 
										o.order_id, u.name, u.mobile_no
									HAVING 
										(pending_quantity > 0 OR pending_box_carton > 0)  
									ORDER BY `o`.`order_id` ASC");
									$pages->items_total = $sql_forms->num_rows;
									$pages->mid_range = 9;
									$pages->paginate();	

									$result	= mysqli_query($mysqli,"SELECT 
										o.order_id,
										u.name AS customer_name,
										u.mobile_no AS customer_mobile,
										SUM(od.quantity) AS total_ordered_quantity,
										SUM(od.box_carton) AS total_ordered_box_carton,
										COALESCE(SUM(dd.total_dispatched_quantity), 0) AS total_dispatched_quantity,
										COALESCE(SUM(dd.total_dispatched_box_carton), 0) AS total_dispatched_box_carton,
										(SUM(od.quantity) - COALESCE(SUM(dd.total_dispatched_quantity), 0)) AS pending_quantity,
										(SUM(od.box_carton) - COALESCE(SUM(dd.total_dispatched_box_carton), 0)) AS pending_box_carton
									FROM 
										tbl_order o
									JOIN 
										tbl_user u ON o.user_id = u.id  
									JOIN 
										tbl_order_detail od ON o.order_id = od.order_id  
									LEFT JOIN 
										(SELECT 
											order_id, 
											product_id, 
											product_dtid, 
											SUM(quantity) AS total_dispatched_quantity,
											SUM(box_carton) AS total_dispatched_box_carton
										 FROM 
											tbl_dispatch_detail
											
										 GROUP BY 
											order_id, product_id, product_dtid
										) dd 
										ON od.order_id = dd.order_id 
										AND od.product_id = dd.product_id 
										AND od.product_dtid = dd.product_dtid
										$qrysearch
									GROUP BY 
										o.order_id, u.name, u.mobile_no
									HAVING 
										(pending_quantity > 0 OR pending_box_carton > 0)  
									ORDER BY `o`.`id`  DESC
 									".$pages->limit."");
										if($pages->items_total>0){
											$n = 1;
											while($row = $result->fetch_assoc()){ 

											/*$product_id = $row['product_id'];
											$productdt_id = $row["product_dtid"];

											$get_product = mysqli_query($mysqli, "SELECT * FROM tbl_product WHERE `id`='$product_id'");
											$get_product_row = mysqli_fetch_array($get_product);

											$get_product_dt = mysqli_query($mysqli,"SELECT * FROM tbl_product_detail WHERE `id`='" . $subdata['product_dtid'] . "'");
											$get_productdt_row = mysqli_fetch_array($get_product_dt);

											$product_name = $get_product_row["name"];	
												
												
												
												$get_dispach_dt = mysqli_query($mysqli, "SELECT SUM(quantity) AS total_quantity FROM tbl_dispatch_detail 
												WHERE `order_id`='".$order_dt_row['order_id']."' and product_id='".$product_id."' and 
												`product_dtid`='".$productdt_id."'");
												if(mysqli_num_rows($get_dispach_dt) > 0)
												{	
													$get_dispach_row = mysqli_fetch_array($get_dispach_dt);
													$dispach_quantity = $get_dispach_row['total_quantity'];




												}
											$updateresult = mysqli_query($mysqli,"UPDATE tbl_manage_pending_order SET 
											`view_status`='1'
											where `id`='".$row['id']."'");
												
											$order_details = array(); // Associative array to group by product name
											$product_id = $subdata['product_id'];

											$get_product = mysqli_query($mysqli, "SELECT * FROM tbl_product WHERE `id`='$product_id'");
											$get_product_row = mysqli_fetch_array($get_product);

											$get_product_dt = mysqli_query($mysqli, "SELECT * FROM tbl_product_detail WHERE `id`='" . $subdata['product_dtid'] . "'");
											$get_productdt_row = mysqli_fetch_array($get_product_dt);

											$product_name = $get_product_row["name"];
											$product_id = $get_product_row["id"];

											// If product name is already added, append sizes, prices, quantities, and totals
											if (!isset($order_details[$product_name])) {
												$order_details[$product_name] = array(
													'sizes' => array(),
													'id' => array(),
													'product_id' => array(),
													'prices' => array(),
													'quantities' => array(),
													'totals' => array()
												);
											}

											// Append details
											$order_details[$product_name]['sizes'][] = $get_productdt_row['size'];
											$order_details[$product_name]['product_dtid'][] = $get_productdt_row['id'];
											$order_details[$product_name]['product_id'][] = $get_productdt_row['product_id'];
											$order_details[$product_name]['prices'][] = $subdata['price'];
											$order_details[$product_name]['quantities'][] = $subdata['quantity'];
											$order_details[$product_name]['totals'][] = $subdata['total'];	
											*/
												
										 ?>
                                            <tr class="odd gradeX <?php echo $success;?>">
                                              <td><?php echo $row['order_id']; ?></td>
                                              <td><a href="https://api.whatsapp.com/send/?phone=<?php echo $mobile_no; ?>&text=Hello" target="_blank"><?php echo $row['customer_mobile']; ?></a></td>
                                              <td><?php echo $row['customer_name']; ?></td>
                                              <td> 
                                               <div class="print">
                                              <button class="btn btn-info" data-toggle="modal" data-target="#myModal<?php echo $row['order_id']; ?>">
													 Pending Order Detail
												  </button>
                                          	 <!-- <a href="mpdf60/examples/manage_pending_order.php?id=<?php echo $row['id']; ?>" target="_blank" class="tip" title="Print Order Form"><span class="icon24 icomoon-icon-print"></span></a>-->
                                          	 </td>
										<?php
										$subquery = "SELECT 
											od.order_id,
											od.product_id,
											od.product_dtid,
											p.name AS product_name,
											p.desc AS product_description,
											pd.size AS product_size,
											SUM(od.quantity) AS total_ordered_quantity,
											SUM(od.box_carton) AS total_ordered_box_carton,
											COALESCE(SUM(dd.total_dispatched_quantity), 0) AS total_dispatched_quantity,
											COALESCE(SUM(dd.total_dispatched_box_carton), 0) AS total_dispatched_box_carton,
											(SUM(od.quantity) - COALESCE(SUM(dd.total_dispatched_quantity), 0)) AS pending_quantity,
											(SUM(od.box_carton) - COALESCE(SUM(dd.total_dispatched_box_carton), 0)) AS pending_box_carton
										FROM 
											tbl_order_detail od
										JOIN 
											tbl_product_detail pd ON od.product_dtid = pd.id
										JOIN 
											tbl_product p ON od.product_id = p.id
										JOIN 
											tbl_order o ON od.order_id = o.order_id
										LEFT JOIN 
											(
												SELECT 
													order_id, 
													product_id, 
													product_dtid, 
													SUM(quantity) AS total_dispatched_quantity,
													SUM(box_carton) AS total_dispatched_box_carton
												FROM 
													tbl_dispatch_detail
												GROUP BY 
													order_id, product_id, product_dtid
											) dd 
											ON od.order_id = dd.order_id 
											AND od.product_id = dd.product_id 
											AND od.product_dtid = dd.product_dtid
										WHERE 
											od.order_id = '".$row['order_id']."'
										GROUP BY 
											od.order_id, od.product_id, od.product_dtid, 
											p.name, p.desc, pd.size
										HAVING 
											(pending_quantity > 0 OR pending_box_carton > 0)  
										ORDER BY 
											od.id ASC";
										$subsql = mysqli_query($mysqli, $subquery) or die(mysqli_error($mysqli));
										//Grouping by product name
										$currentProduct = null;
										$first = true;												
										?>
												<!-- Popup Start -->
												<div class="modal fade" id="myModal<?php echo $row['order_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel<?php echo $row['id']; ?>" aria-hidden="true">
												  <div class="modal-dialog" role="document">
													<!-- Modal content -->
													<div class="modal-content">
													  <div class="modal-header">
														<h3 class="modal-title" id="modalLabel<?php echo $row['id']; ?>">Pending Order Details
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															  <span aria-hidden="true">&times;</span>
															</button>
														</h3>
													  </div>
													  <div class="modal-body">
														<div><label>Order ID: </label> <?php echo $row['order_id']; ?></div>
														<hr>
														<?php while ($subrow = mysqli_fetch_assoc($subsql)) {
														// Combine pending quantity logic
														$quantity = ($subrow['pending_box_carton'] != '0') ? $subrow['pending_box_carton'] : $subrow['pending_quantity'];

													  // Check if we’ve moved to a new product
													  if ($currentProduct != $subrow['product_name']) {
															// If not first, close the previous group

															// Set current product
															$currentProduct = $subrow['product_name'];

															?>
														<h4>Product Name: <?php echo htmlspecialchars($currentProduct); ?></h4>
														<?php
														}
														?>
														<div class="product">
														<ul class="list-group">
														<li class="list-group-item">
														<strong style="padding: 10px;">Size:</strong> <?php echo htmlspecialchars($subrow['product_size']); ?>
														<strong style="padding: 10px;">Quantity:</strong> <?php echo $quantity; ?>											
														</li>
														
														</ul>
														<?php
														}
													 // Close last product group
													  if ($currentProduct !== null) {
														  echo "</ul></div><hr>";
													  }
														?>
														</div>
														<hr>
													  <div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
													  </div>
													</div>
												  </div>
												</div>
												<!-- popup end-->
                                              <td> <a href="pending_order_form.php?order_id=<?php echo $row['order_id']; ?>" target="_blank" class="tip" title="Print Order Form"><span class="icon24 icomoon-icon-print"></span></a></td>
                                            </tr>
                                           <?php 
											}
										}
										else
										{
										?>
										<tr>
											<td colspan="9" align="center"><strong>No Record Found!</strong></td>
										</tr>
										<?php 
										} 
										?>
                                          </tbody>
                                           <tfoot>
                                            <tr>
                                               <th>Order Id</th>
                                               <th>Mobile No</th>
                                               <th>User name</th>
                                               <th>Detail</th>
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
		  mysqli_close($mysqli);
		  ?>
            </div><!-- End contentwrapper -->
        </div><!-- End #content -->
    </div><!-- End #wrapper -->
	<!-- Le javascript-->
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
    <script type="text/javascript" src="plugins/misc/jqueryFileTree/jqueryFileTree.js"></script> 
    <script type="text/javascript" src="plugins/misc/pnotify/jquery.pnotify.min.js"></script>
    <script type="text/javascript" src="plugins/forms/select/select2.min.js"></script>
    
    <!-- Search plugin -->
    <script type="text/javascript" src="plugins/misc/search/tipuesearch_set.js"></script>
    <script type="text/javascript" src="plugins/misc/search/tipuesearch_data.js"></script><!-- JSON for searched results -->
    <script type="text/javascript" src="plugins/misc/search/tipuesearch.js"></script>

    <!-- Form plugins -->
    <script type="text/javascript" src="plugins/forms/togglebutton/jquery.toggle.buttons.js"></script>
    <script type="text/javascript" src="plugins/forms/uniform/jquery.uniform.min.js"></script>
    <script type="text/javascript" src="plugins/forms/wizard/jquery.bbq.js"></script>
    <script type="text/javascript" src="plugins/forms/wizard/jquery.form.js"></script>
    <script type="text/javascript" src="plugins/forms/wizard/jquery.form.wizard.js"></script>
    <script type="text/javascript" src="plugins/forms/typeahead/typeahead.min.js"></script>
    
     <!-- Form plugins -->
    <script type="text/javascript" src="plugins/forms/maskedinput/jquery.maskedinput-1.3.min.js"></script>
   
    <!-- Gallery plugins -->
    <script type="text/javascript" src="plugins/gallery/lazy-load/jquery.lazyload.min.js"></script>
    <script type="text/javascript" src="plugins/gallery/jpages/jPages.min.js"></script>
    <script type="text/javascript" src="plugins/gallery/pretty-photo/jquery.prettyPhoto.js"></script>

    <!-- Table plugins -->
    <script type="text/javascript" src="plugins/tables/dataTables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="plugins/tables/responsive-tables/responsive-tables.js"></script><!-- Make tables responsive -->
    
    <!-- Init plugins -->
    <script type="text/javascript" src="js/main.js"></script><!-- Core js functions -->
    <script type="text/javascript" src="js/widgets.js"></script><!-- Init plugins only for page -->
    <script type="text/javascript" src="js/forms.js"></script><!-- Init plugins only for page -->
    
    </body>
</html>
