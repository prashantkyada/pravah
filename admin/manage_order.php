<?php
include("header.php");
$dateupdate = date("Y-m-d H:i:s");
$noti_date = date("Y-m-d");

	if($_GET['action'] == 'delete')
	{
		mysqli_query($mysqli,"delete from `tbl_order` Where `order_id`='".$_GET['order_id']."'");
		mysqli_query($mysqli,"delete from `tbl_order_detail` Where `order_id`='".$_GET['order_id']."'");
		
		mysqli_query($mysqli,"delete from `tbl_dispatch_order` Where `order_id`='".$_GET['order_id']."'");
		mysqli_query($mysqli,"delete from `tbl_dispatch_detail` Where `order_id`='".$_GET['order_id']."'");
		echo "<script language='javascript'>window.location='manage_order.php?msg=deleted';</script>";
		
	}
	if(isset($_POST['submit']))
	{
		
		if($_POST['action'] == 'edit')
		{
			$order_id = $_POST['order_id'];
			mysqli_query($mysqli,"INSERT INTO `tbl_dispatch_order` (`order_id`, `user_id`,`status`, `date`) VALUES ( '".$order_id."', '".$_POST['user_id']."','".$_POST['status']."', '".$dateupdate."')");

			$dispatch_order_id = mysqli_insert_id($mysqli);

			// Loop through each submitted product
			foreach ($_POST['product_id'] as $index => $product_id) {

				$product_dtid = $_POST['product_dtid'][$index];
				$price = $_POST['price'][$index];
				$quantity = $_POST['quantity'][$index];
				$total = $_POST['total'][$index];
				$status = $_POST['status'];
				$date = date("Y-m-d H:i:s"); // Current date and time

					// Skip if quantity is 0 or empty
					if ($quantity == '0' || empty($quantity)) {
						continue; // Skip this iteration, no query will be fired
					}
				
					$mainquery = "SELECT * FROM `tbl_order_detail` where `product_id`='".$product_id."' and `product_dtid`='".$product_dtid."' and `order_id`='".$order_id."'";
					$mainsql = mysqli_query( $mysqli, $mainquery) or die( mysql_error());
					$maindata = mysqli_fetch_array($mainsql);
					
					//$quantity = '';
					$box_carton = '0';
					if(!empty($maindata['box_carton']) && $maindata['box_carton'] > 0) 
					{
						$devide_quntity = $maindata['quantity'] / $maindata['box_carton'];
						$quantity1 = $quantity * $devide_quntity;
						
						$box_carton = $quantity;
						//$box_carton = $maindata['box_carton'] - $devide_quntity;
						
					}
					else
					{
						$quantity1 = $quantity;
						$box_carton = '0';
						  //$box_carton = $quantity;	
						 // $quantity = '0';
					}
				
					// Insert into dispatch_order table
					$query = "INSERT INTO tbl_dispatch_detail (order_id,dispatch_id, product_id, product_dtid, price, quantity,box_carton, total, status, date) 
							  VALUES ('$order_id','$dispatch_order_id','$product_id', '$product_dtid', '$price', '$quantity1','$box_carton', '$total', '$status', '$date')";
					if (!$mysqli->query($query)) {
						echo "Error: " . $mysqli->error;
					}
				
			}
		/*		
		$data = [];
				$order_id = $_POST['order_id'];
				$user_id = $_POST['user_id'];

				foreach ($_POST['product_name'] as $index => $product_name) {
					$product_id = $_POST['product_id'][$index];
					$items = [];

					if (isset($_POST['size'][$index])) {
						foreach ($_POST['size'][$index] as $item) {
							if (!empty($item['size'])) {
								$items[] = [
									"size" => addslashes($item['size']),
									"price" => $item['price'],
									"quantity" => $item['quantity'],
									"total" => $item['total']
								];
							}
						}
					}

					$data[] = [
						"product" => $product_name,
						"product_id" => $product_id,
						"items" => $items
					];
				}

			
			$json_output = json_encode($data, JSON_PRETTY_PRINT);*/
			
		/*foreach ($_POST['product_name'] as $index => $product_name) {
    		$data = [];

				foreach ($_POST['size'][$index] as $item_index => $size) {
					$items[] = [
						"size" => addslashes($size), // Escape double quotes
						"quantity" => $_POST['quantity'][$index][$item_index],
						"price" => $_POST['price'][$index][$item_index],
						"total" => $_POST['total'][$index][$item_index]
					];
				}

				$data[] = [
					"product" => $product_name,
					"items" => $items
				];
			}*/

			//$data = [];
			// Convert array to JSON
			
			
				
			
			/*foreach ($_POST['product_name'] as $index => $product_name) {
			 $size = $_POST['size'][$index];
			$quantity = $_POST['quantity'][$index];
			$price = $_POST['price'][$index];
			$total = $_POST['total'][$index];
			}
			
			// Convert array to JSON
			$json_output = json_encode($data,JSON_PRETTY_PRINT);*/
			
				
			if($_POST['status'] == '1')
			{
				$status = 'Pending';
			}
			elseif($_POST['status'] == '2')
			{
				$status = 'Dispatch';
			}
			elseif($_POST['status'] == '3')
			{
				$status = 'Delivered';
			}
			elseif($_POST['status'] == '4')
			{
				$status = 'Cancel';
				
			}
			
		/*	mysqli_query($mysqli,"INSERT INTO `tbl_dispatch_order` (`order_id`, `user_id`, `data`, `total`, `discount_rate`, `discount_total`, `status`, `date`) VALUES ( '".$_POST['order_id']."', '".$_POST['user_id']."', '".$json_output."', '".$_POST['user_id']."', '".$_POST['user_id']."', '".$_POST['user_id']."', '".$_POST['status']."', '".$dateupdate."')");
			
			
			mysqli_query($mysqli,"UPDATE `tbl_order` set 
			`status`='".$_POST['status']."'
			where 
			`id`='".$_POST['id']."'
			");*/
			
		//one signal notification start
		$order_id = $_POST['order_id'];
			
		$title = 'Your Orders Update.';
		$noti = 'Order id :'.$order_id.' Status is '.$status.' on '.$noti_date;

		$main_cat = mysqli_query($mysqli,"SELECT * FROM `tbl_user` where `id`='".$_POST['user_id']."'");
		$find_row = mysqli_fetch_array($main_cat);

		//insert database notificatiopn table
		mysqli_query($mysqli,"insert `tbl_notification` set 
		`user_id`='".$_POST['user_id']."',
		`title`='".$title."',
		`image`='".$emb."',
		`message`='".$noti."',
		`user_view`='0',
		`create_date`='".$dateupdate."'
		");

		//one signal notification send start
		$ONESIGNAL_APP_ID = '4612585a-0e20-41d6-941f-daf4bdc8c416';
		$ONESIGNAL_REST_KEY = 'os_v2_app_iyjfqwqoeba5nfa73l2l3sgecyc6yvql3rbeh4v5hve7wib6t3qhurabr7fffq3cknhh7jvdky6vxhzzq6dvs4oamanj3laes2xcary';
		
		$content = array(
		  "en" => $noti                                                 
		  );

		 $fields = [
				"app_id" => $ONESIGNAL_APP_ID,
				"included_segments" => ["Active Users"],
				"data" => ["event_id" => $event_id, "status" => "Cancelled"],
				"filters" => [
					[
						"field" => "tag",
						"key" => "mobile",
						"relation" => "=",
						"value" => $find_row['mobile_no'],
					],
				],
				"contents" => $content,
				'headings'=> array("en" => $title),
				'big_picture' => $file_path.$source_img
			];

			$fields = json_encode($fields);
			//print($fields);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
													   'Authorization: Basic '.$ONESIGNAL_REST_KEY));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			$response = curl_exec($ch);
			curl_close($ch);
			//one signal notification end

			echo "<script language='javascript'>window.location='manage_order.php';</script>";
			
		}
		
	}	

?>
	<!--Body content-->
	<div id="content" class="clearfix">
		<div class="contentwrapper">
		   <!--Content wrapper-->
			<div class="heading">
				<h3>Manage Order</h3>                    
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
					<li class="active">Manage Order</li>
				</ul>
			</div><!-- End .heading-->
			<!-- Build page from here: Usual with <div class="row-fluid"></div> -->
               
   <?php		
	if($_GET['action'] == "edit")
	{
		$btn="Edit";
		$act="edit";
		$edit_query=mysqli_query($mysqli,"select * from `tbl_order` where id='".$_GET['detailid']."'");				
		$upd_ord_row=mysqli_fetch_array($edit_query);
		
	}
	else
	{
		$act="add";
		$btn="Add";
		$order_id = $_GET['order_id'];
	}			
				
				
    if($_GET['action'] == "add" || $_GET['action'] == "edit")
	{
		$read = 'readonly="readonly"';
	?>	
     <!-- Build page from here: --> 
     
	 <div class="row">
    <!-- Left Panel: Order Data -->
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>
                    <span class="icon16 icomoon-icon-pencil"></span>
                    <span>Order Data</span>
                </h4>
                <a href="#" class="minimize">Minimize</a>
            </div>
            <div class="panel-body">
                <div class="col-lg-12">
                    <form class="form-horizontal seperator">
                        <?php 
						$order_dt_query = mysqli_query($mysqli, "SELECT * FROM `tbl_order` WHERE `id`='".$_GET['detailid']."'");
						$order_dt_row = mysqli_fetch_array($order_dt_query);

						$order_details = array(); // Associative array to group by product name
						$pending_order_details = array();

						$subquery = "SELECT * FROM `tbl_order_detail` WHERE `order_id`='" . $order_dt_row['order_id'] . "' ORDER BY `id` ASC";
						$subsql = mysqli_query($mysqli, $subquery) or die(mysqli_error($mysqli));

						while ($subdata = mysqli_fetch_array($subsql)) 
						{
							$product_id = $subdata['product_id'];
							$productdt_id = $subdata["product_dtid"];

							$get_product = mysqli_query($mysqli, "SELECT * FROM tbl_product WHERE `id`='$product_id'");
							$get_product_row = mysqli_fetch_array($get_product);

							$get_product_dt = mysqli_query($mysqli,"SELECT * FROM tbl_product_detail WHERE `id`='" . $subdata['product_dtid'] . "'");
							$get_productdt_row = mysqli_fetch_array($get_product_dt);

							$product_name = $get_product_row["name"];
							

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
							
							if($subdata['box_carton'] != '0')
							{
								$quantity = $subdata['box_carton'];
							}
							else
							{
								$quantity = $subdata['quantity'];
							}

							// Append details
							$order_details[$product_name]['sizes'][] = $get_productdt_row['size'];
							$order_details[$product_name]['product_dtid'][] = $productdt_id;
							$order_details[$product_name]['product_id'][] = $product_id;
							$order_details[$product_name]['prices'][] = $subdata['price'];
							$order_details[$product_name]['quantities'][] = $quantity;
							$order_details[$product_name]['totals'][] = $subdata['total'];
							
							
							$get_dispach_dt = mysqli_query($mysqli, "SELECT SUM(quantity) AS total_quantity ,SUM(box_carton) AS total_carton FROM tbl_dispatch_detail 
							WHERE `order_id`='".$order_dt_row['order_id']."' and product_id='".$product_id."' and 
							`product_dtid`='".$productdt_id."'");
							if(mysqli_num_rows($get_dispach_dt) > 0)
							{	
								$get_dispach_row = mysqli_fetch_array($get_dispach_dt);
								//$dispach_quantity = $get_dispach_row['total_carton'];
								
								
								if($subdata['box_carton'] != '0')
								{
									$pending_quantity = $subdata['box_carton'];
								}
								else
								{
									$pending_quantity = $subdata['quantity'];
								}	
								
								if($get_dispach_row['total_carton'] != '0')
								{
									$dispach_quantity = $get_dispach_row['total_carton'];;
								}
								else
								{
									$dispach_quantity = $get_dispach_row['total_quantity'];;
								}	
								
								
								
								$quantities = $pending_quantity - $dispach_quantity;
								//echo $quantities;
								
								if($quantities >= 1)
								{
									// If product name is already added, append sizes, prices, quantities, and totals
									if (!isset($pending_order_details[$product_name])) {
										$pending_order_details[$product_name] = array(
											'pending_sizes' => array(),
											'pending_id' => array(),
											'pending_product_id' => array(),
											'pending_prices' => array(),
											'pending_quantities' => array(),
											'pending_totals' => array()
										);
									}
									//echo $quantities;

									// Append details
									$pending_order_details[$product_name]['pending_sizes'][] = $get_productdt_row['size'];
									$pending_order_details[$product_name]['pending_product_dtid'][] = $productdt_id;
									$pending_order_details[$product_name]['pending_product_id'][] = $product_id;
									$pending_order_details[$product_name]['pending_prices'][] = $subdata['price'];
									$pending_order_details[$product_name]['pending_quantities'][] = $quantities;
									$pending_order_details[$product_name]['pending_totals'][] = $subdata['total'];
								}
							}
							
						}
						//print_r($pending_order_details);
						?>
                       <div class="form-group">
                                <label class="col-lg-3 control-label">Order ID:</label>
                                <div class="col-lg-8">
                                    <?php echo $order_dt_row['order_id']; ?>
                                </div>
                            </div>
                        <?php 
							foreach ($order_details as $product_name => $details) { 
							// Check if we’ve moved to a new product	
								
						
						?>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Product Name:</label>
                                <div class="col-lg-8">
                                    <?php echo htmlspecialchars($product_name); ?>
                                </div>
                            </div>
                        <?php
							for ($i = 0; $i < count($details['sizes']); $i++) { 
						?>
							<div class="form-group">
								<label class="col-lg-3 control-label">Size:</label>
								<div class="col-lg-3">
									<input class="form-control" type="text" disabled value="<?php  echo htmlspecialchars($details['sizes'][$i]); ?>" />
								</div>
								<label class="col-lg-3 control-label">Quantity:</label>
								<div class="col-lg-3">
									<input class="form-control" type="text" disabled value="<?php echo $details['quantities'][$i]; ?>" />
								</div>
                                </div>
                               <!-- <div class="form-group">
                                   <label class="col-lg-3 control-label">Price:</label>
										<div class="col-lg-3">
											<input class="form-control" type="text" disabled value="<?php echo $details['prices'][$i]; ?>" />
										</div>
										<label class="col-lg-3 control-label">Total:</label>
										<div class="col-lg-3">
											<input class="form-control" type="text" disabled value="<?php echo $details['totals'][$i]; ?>" />
										</div>
                                </div>-->
                            
                        <?php
						 }
						}
						?>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Total:</label>
                            <div class="col-lg-8">
                                <input id="total"  class="form-control" type="text" disabled value="<?php echo htmlspecialchars($upd_ord_row["total"]); ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Discount (<?php echo $upd_ord_row["discount_rate"]; ?>%):</label>
                            <div class="col-lg-8">
                                <input id="discount_total" class="form-control" type="text" disabled value="<?php echo htmlspecialchars($upd_ord_row["discount_total"]); ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Final Total:</label>
                            <div class="col-lg-8">
                                <input id="final_total" class="form-control" type="text" disabled value="<?php echo $upd_ord_row['total'] - $upd_ord_row['discount_total']; ?>" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Right Panel: Order Form -->
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>
                    <span class="icon16 icomoon-icon-pencil"></span>
                    <span>Pending Order</span>
                   <!-- <span><a href="mpdf60/examples/pending_order.php?id=<?php echo $_GET['detailid']; ?>&order_id=<?php echo $_GET['order_id'];?>" target="_blank" class="tip" title="Print Order Form"><span class="icon24 icomoon-icon-print"></span></a></span>-->
                </h4>
                <a href="#" class="minimize">Minimize</a>
            </div>
            <div class="panel-body">
                <div class="col-lg-12">
                    <form class="form-horizontal seperator" id="form-validate" method="post" role="form" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="<?php echo $act ?>" />
                        <input type="hidden" name="user_id" value="<?php echo $_GET['user_id'] ?>" />
                        <input type="hidden" name="id" value="<?php echo $_GET['detailid'] ?>" />
                        <input type="hidden" name="order_id" value="<?php echo $_GET['order_id'] ?>" />
                        <div class="form-group">
							<label class="col-lg-3 control-label">Order ID:</label>
							<div class="col-lg-8">
								<?php echo $order_dt_row['order_id']; ?>
							</div>
                       </div>
                       <?php
						foreach ($pending_order_details as $pending_product_name => $details) { 
								//print_r($pending_product_name);
						?>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Product Name:</label>
                            <div class="col-lg-9">
                                 <?php echo htmlspecialchars($pending_product_name); ?>
            					 <input type="hidden" name="product_name[<?php echo $index; ?>]" value="<?php echo htmlspecialchars($pending_product_name); ?>" />
                            </div>
                        </div>
                      <?php
								
							for($j = 0; $j < count($details['pending_sizes']); $j++) {
							/*$get_dispach_dt = mysqli_query($mysqli, "SELECT SUM(quantity) AS total_quantity FROM tbl_dispatch_detail 
							WHERE `order_id`='".$_GET['order_id']."' and product_id='".$details['product_id'][$i]."' and 
							`product_dtid`='".$details['product_dtid'][$i]."'");
							if(mysqli_num_rows($get_dispach_dt) > 0)
							{	
								$get_dispach_row = mysqli_fetch_array($get_dispach_dt);
						
								$dispach_quantity = $get_dispach_row['total_quantity'];

								//echo  $dispach_quantity;
								$quantities = $details['quantities'][$i] - $dispach_quantity;

								if (empty($quantities) || $quantities == '0')  
								{
									$box_view= 'disabled';
									
								}
								else
								{
									$box_view= '';	
									$product_id_form =  $details['product_id'][$i];
									//echo $details['product_id'][$i];
								}
								if($quantities == '0')
								{
								//echo $details['product_id'][$i];
									$product_id_form ='0';
								}
							}
								
							//echo $product_id_form;
							//echo $details['product_dtid'][$i];
							if($quantities <= 0) {
								continue; // Skip this product entry
							}	*/
						?>
                         <input type="hidden" name="product_id[]" value="<?php echo $details['pending_product_id'][$j]; ?>" />
               			 <input type="hidden" name="product_dtid[]" value="<?php echo $details['pending_product_dtid'][$j]; ?>" />
                        <!-- <input type="hidden" name="price[]" id="price-<?php echo $j; ?>" value="" />
                         <input type="hidden" name="total[]" id="total-<?php echo $j; ?>" value=""/> -->
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Size:</label>
                                <div class="col-lg-3">
                                    <input name="size[]" class="form-control" type="text" disabled value="<?php echo htmlspecialchars($details['pending_sizes'][$j]); ?>" />
                                </div>
                                <label class="col-lg-3 control-label">Quantity:</label>
                                <div class="col-lg-3">
                                    <input name="quantity[]"  class="form-control" <?php echo $box_view;?> type="text" value="<?php echo $details['pending_quantities'][$j]; ?>" />
                                </div>
                            </div>
                   <?php
						 }
						}
		  				$count_order_details =  count($pending_order_details);
						if($count_order_details !='0')
						{
						?>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Order Status:</label>
                            <div class="col-lg-9">
                                <select name="status" class="form-control">
                                    <option value="2" <?php echo ($upd_ord_row['status'] == '2') ? 'selected' : ''; ?>>Dispatch</option>
                                    <!--<option value="3" <?php echo ($upd_ord_row['status'] == '3') ? 'selected' : ''; ?>>Delivered</option>-->
                                    <option value="4" <?php echo ($upd_ord_row['status'] == '4') ? 'selected' : ''; ?>>Cancel</option>
                                </select>
                            </div>
                        </div>
                        <!-- Buttons Outside the Form, Centered -->
						<div class="text-center mt-4">
							<button type="submit" name="submit" form="form-validate" class="btn btn-info">Save changes</button>
							<button type="reset" class="btn btn-danger" onclick="window.location='manage_order.php';">Cancel</button>
						</div>	
						<?php
						}
						?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if($count_order_details =='0')
{
?>
	<div class="text-center mt-4">
		<button type="reset" class="btn btn-danger" onclick="window.location='manage_order.php';">Back</button>
	</div>	
	<?php
}
	?>

<script>
/* 
document.addEventListener("DOMContentLoaded", function () {
   function updateTotal(index) {
	 
        let quantityInput = document.querySelector(`input[name="quantity[]"][data-index='${index}']`);
        let priceInput = document.querySelector(`#price-${index}`);
        let totalInput = document.querySelector(`#total-${index}`);
	    alert(priceInput);
        let quantity = parseInt(quantityInput.value) || 0;
        let price = parseFloat(priceInput.value) || 0;
        let total = quantity * price;
	 
        totalInput.value = total.toFixed(2); // Update total field with formatted value
	     
    }

    // Attach event listener to each quantity input
    document.querySelectorAll("input[name='quantity[]']").forEach(input => {
        input.addEventListener("input", function () {
            let index = this.getAttribute("data-index");
            updateTotal(index);
        });
    });
});*/
</script>		
		
			<?php
            }
			else
			{
            ?>
              <!-- Build page from here: -->
                <div class="row">
                    <div class="col-lg-12">
                        <form class="form-horizontal seperator" id="form-validate"  method="post" role="form">
                            <div class="form-group">
                                 <label class="col-lg-2 control-label" for="required">Start Date:</label>
                                <div class="col-lg-10">
                                  <input id="datepicker" name="start_dt" required="required" class="form-control" type="text" value="<?php echo $_POST['start_dt'];?>"> 
                                </div>
                            </div><!-- End .form-group  --> 
                            <div class="form-group">
                                 <label class="col-lg-2 control-label" for="required">End Date:</label>
                                <div class="col-lg-10">
                                  <input id="datepicker_2" name="end_dt" required="required" class="form-control" type="text" value="<?php echo $_POST['end_dt'];?>">
                                </div>
                            </div><!-- End .form-group  --> 
                           <div class="form-group">
                                <div class="col-lg-offset-2">
                                    <button type="submit" name="submit_date" class="btn btn-info marginR10 marginL10">Submit</button>
                                </div>
                            </div><!-- End .form-group  -->
    					   </form>
                    </div><!-- End .span12 -->
                </div><!-- End .row -->
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
                                               <th>Id</th>
                                               <th>Order Id</th>
                                               <th>Mobile No</th>
                                                <th>Party name</th>
                                                <th>Total</th>
                                                
                                                <th>Date</th>
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
										$qrysearch = "AND CONCAT_WS('',ord.id,ord.order_id,us.mobile_no,us.name) LIKE '%".$_GET['search']."%'";
									}
									//Main query
									$pages = new Paginator;
									$pages->default_ipp = 25;
									$sql_forms = mysqli_query($mysqli,"select ord.*,us.name,us.mobile_no,us.name,us.user_status from `tbl_order` ord,`tbl_user` us where us.id=ord.user_id $extrasearch $qrysearch");
									$pages->items_total = $sql_forms->num_rows;
									$pages->mid_range = 9;
									$pages->paginate();

									$result	= mysqli_query($mysqli,"select ord.*,us.name,us.mobile_no,us.name,us.user_status from `tbl_order` ord,`tbl_user` us where us.id=ord.user_id $extrasearch $qrysearch ORDER BY ord.id DESC ".$pages->limit."");

										if($pages->items_total>0){
											$n  =   1;
											while($row = $result->fetch_assoc()){ 

											// (1 is pending 2 is Dispatch 3 is delivered)
											if($row['status'] == '1')
											{
												$status = '<span style="color: blue;">Pending</span>';
							
											}
											elseif($row['status'] == '2')
											{
												$status = '<span style="color: red;">Dispatch</span>';
											}
											elseif($row['status'] == '3')
											{
												$status = ' <span style="color: black;"><span style="color: black;">Delivered</span>';
											}
											$mobile_no = '91'.$row['mobile_no'];
												
											if($row['view_status'] == '0')
											{
												$success = 'success';
											}
											else
											{
												$success = '';
											}	
											
											$updateresult = mysqli_query($mysqli,"UPDATE tbl_order SET 
											`view_status`='1'
											where `id`='".$row['id']."'");

											$order_details = array(); // Associative array to group by product name

											$subquery = "SELECT * FROM `tbl_order_detail` WHERE `order_id`='" . $row['order_id'] . "' ORDER BY `id` ASC";
											$subsql = mysqli_query($mysqli, $subquery) or die(mysqli_error($mysqli));

											while ($subdata = mysqli_fetch_array($subsql)) {
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
														'box_cartons' => array(),
														'totals' => array()
													);
												}

												// Append details
												$order_details[$product_name]['sizes'][] = $get_productdt_row['size'];
												$order_details[$product_name]['product_dtid'][] = $get_productdt_row['id'];
												$order_details[$product_name]['product_id'][] = $get_productdt_row['product_id'];
												$order_details[$product_name]['prices'][] = $subdata['price'];
												$order_details[$product_name]['quantities'][] = $subdata['quantity'];
												$order_details[$product_name]['box_cartons'][] = $subdata['box_carton'];
												$order_details[$product_name]['totals'][] = $subdata['total'];	


											}
												
												
											$order_id = $row['order_id'];

											$check_pending = mysqli_query($mysqli, "
												SELECT 
													SUM(od.quantity) AS total_qty,
													SUM(od.box_carton) AS total_box,
													COALESCE(SUM(dd.total_dispatched_quantity), 0) AS dispatched_qty,
													COALESCE(SUM(dd.total_dispatched_box_carton), 0) AS dispatched_box
												FROM 
													tbl_order_detail od
												LEFT JOIN (
													SELECT 
														order_id, 
														product_id, 
														product_dtid, 
														SUM(quantity) AS total_dispatched_quantity,
														SUM(box_carton) AS total_dispatched_box_carton
													FROM tbl_dispatch_detail
													GROUP BY order_id, product_id, product_dtid
												) dd 
												ON od.order_id = dd.order_id 
												AND od.product_id = dd.product_id 
												AND od.product_dtid = dd.product_dtid
												WHERE od.order_id = '$order_id'
											");

											$pending_data = mysqli_fetch_assoc($check_pending);

											// Calculate pending
											$pending_qty = $pending_data['total_qty'] - $pending_data['dispatched_qty'];
											$pending_box = $pending_data['total_box'] - $pending_data['dispatched_box'];

											$has_pending = ($pending_qty > 0 || $pending_box > 0);
										 ?>
                                            <tr class="odd gradeX <?php echo $success;?>">
                                              <td><?php echo $row['id']; ?></td>
                                              <td><?php echo $row['order_id']; ?></td>
                                              <td><a href="https://api.whatsapp.com/send/?phone=<?php echo $mobile_no; ?>&text=Hello" target="_blank"><?php echo $row['mobile_no']; ?></a></td>
                                              <td><?php echo $row['name']; ?></td>                                          
											  <td><?php echo number_format($row['total'], 2, '.', ','); ?></td>
											 
                                              <td><?php echo $row['date']; ?></td>
                                              <td> 
                                               <div class="print">
                                              <button class="btn btn-info" data-toggle="modal" data-target="#myModal<?php echo $row['id']; ?>">
													 Order Detail
											  </button>
                                         		
                                          		<a href="order_form.php?id=<?php echo $row['id']; ?>&order_id=<?php echo $row['order_id'];?>" target="_blank" class="tip" title="Print Order Form"><span class="icon24 icomoon-icon-print"></span></a>
                                          		
                                       <!-- <a href="generate_pdf.php?id=<?php echo $row['id']; ?>&order_id=<?php echo $row['order_id'];?>" target="_blank" class="tip" title="Print Order Form"><span class="icon24 icomoon-icon-print"></span></a>-->
                                          	 </td>
                                           	 
                                            	<!-- Popup Start -->
												<div class="modal fade" id="myModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel<?php echo $row['id']; ?>" aria-hidden="true">
												  <div class="modal-dialog" role="document">
													<!-- Modal content -->
													<div class="modal-content">
													  <div class="modal-header">
														<h3 class="modal-title" id="modalLabel<?php echo $row['id']; ?>">Order Details
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															  <span aria-hidden="true">&times;</span>
															</button>
														</h3>
													  </div>
													  <div class="modal-body">
														
														<div><label>Order ID: </label> <?php echo $row['order_id']; ?></div>
														<hr>
														<?php
														foreach ($order_details as $product_name => $details) {
															
														?>
														<h4>Product Name: <?php echo htmlspecialchars($product_name); ?></h4>
														<?php
														for ($i = 0; $i < count($details['sizes']); $i++) { 
															
															if($details['box_cartons'][$i] != '0')
															{
																$quantity = $details['box_cartons'][$i];
															}
															else
															{
																$quantity = $details['quantities'][$i];
															}
															
															
														?>
														<div class="product">
														<ul class="list-group">
														<li class="list-group-item">
														<strong style="padding: 10px;">Size:</strong> <?php echo htmlspecialchars($details['sizes'][$i]); ?>
														<strong style="padding: 10px;">Quantity:</strong> <?php echo $quantity; ?>
														<strong style="padding: 10px;">Price:</strong> <?php echo $details['prices'][$i]; ?>
														<strong style="padding: 10px;">Total:</strong> <?php echo $details['totals'][$i]; ?>
														</li>
														<?php } ?>
														</ul>
														<?php } ?>
														</div>
														<hr>
														<div><label>Total =</label> <?php echo $row['total']; ?></div>
														<div><label>Discount <span><?php echo $row["discount_rate"]; ?>% =</span></label> <?php echo $row['discount_total']; ?></div>
														<div><label>Final Total =</label> <?php echo $row['total'] - $row['discount_total']; ?></div>	
													  <div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
													  </div>
													</div>
												  </div>
												</div>
												<!-- popup end-->     
                                              <td><a class="tip" href="manage_order.php?action=edit&amp;user_id=<?php echo $row['user_id'];?>&detailid=<?php echo $row['id'];?>&order_id=<?php echo $row['order_id'];?>" 
                                                title="Edit task" data-hasqtip="true" aria-describedby="qtip-7">
                                               <span class="icon12 icomoon-icon-pencil"></span>
                                               <?php

												if(!$has_pending) 
												{ 
												  ?>
                                               </a>&nbsp;&nbsp;<a class="tip" href="manage_order.php?action=delete&amp;id=<?php echo $row['id'];?>&order_id=<?php echo $row['order_id'];?>" onClick="return confirmdelete();"
                                               title="Delete task" data-hasqtip="true" aria-describedby="qtip-7"><span class="icon12 icomoon-icon-remove"></span>
                                               </a>
                                               <?php } ?>
                                               </td>
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
                                               <th>Id</th>
                                               <th>Order Id</th>
                                               <th>Mobile No</th>
                                               <th>User name</th>
                                               <th>Total</th>
                                               
                                               <th>Date</th>
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
			if(agree)	
				return true ;
			else
				return false ;
		}
	</script>
	<!-- Le javascript-->
    <!-- Important plugins put in all pages -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
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
