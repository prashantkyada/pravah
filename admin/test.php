<?php
include("../include/conf.php");


	if(isset($_POST['submit']))
	{
		
		
		
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

	
				
			$json_output = json_encode($data, JSON_PRETTY_PRINT);
		echo $json_output;
			die();

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
				
				mysqli_query($mysqli,"insert into `tbl_cancel_order` set 
				`status`='".$_POST['status']."'
				where 
				`id`='".$_POST['id']."'
				");
				
			}
			mysqli_query($mysqli,"INSERT INTO `tbl_dispatch_order` (`order_id`, `user_id`, `data`, `total`, `discount_rate`, `discount_total`, `status`, `date`) VALUES ( '".$_POST['order_id']."', '".$_POST['user_id']."', '".$json_output."', '".$_POST['user_id']."', '".$_POST['user_id']."', '".$_POST['user_id']."', '".$_POST['status']."', '".$dateupdate."')");

			echo "<script language='javascript'>window.location='test.php';</script>";
		
		
		
	}	






	$edit_query=mysqli_query($mysqli,"select * from `tbl_order` where id='".$_GET['detailid']."'");				
		$upd_ord_row=mysqli_fetch_array($edit_query);
		
		//Parse the database fields
		$data = $upd_ord_row['data'];
		$jsonData = json_decode($data, JSON_PRETTY_PRINT);


?>

    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>
                    <span class="icon16 icomoon-icon-pencil"></span>
                    <span>Order Form</span>
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

                      <?php foreach ($jsonData as $index => $product) { ?>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Product Name:</label>
                            <div class="col-lg-9">
                                <?php echo htmlspecialchars($product["product"]); ?>
                                <input type="text" name="product_name[<?php echo $index; ?>]" value="<?php echo htmlspecialchars($product["product"]); ?>" />
                                <input name="product_id[<?php echo $index; ?>]" class="form-control" type="hidden" value="<?php echo htmlspecialchars($product["product_id"]); ?>" />
                            </div>
                        </div>
                        <?php foreach ($product["items"] as $itemIndex => $item) { ?>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Size:</label>
                                <div class="col-lg-3">
                                    <input name="size[<?php echo $index; ?>][<?php echo $itemIndex; ?>][size]" class="form-control" type="text" value="<?php echo htmlspecialchars($item['size']); ?>" />
                                </div>
                                <label class="col-lg-3 control-label">Price:</label>
                                <div class="col-lg-3">
                                    <input class="form-control" name="size[<?php echo $index; ?>][<?php echo $itemIndex; ?>][price]" type="text" value="<?php echo htmlspecialchars($item["price"]); ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Quantity:</label>
                                <div class="col-lg-3">
                                    <input name="size[<?php echo $index; ?>][<?php echo $itemIndex; ?>][quantity]" class="form-control" type="text" value="<?php echo $item['quantity']; ?>" />
                                </div>
                                <label class="col-lg-3 control-label">Total:</label>
                                <div class="col-lg-3">
                                    <input class="form-control" name="size[<?php echo $index; ?>][<?php echo $itemIndex; ?>][total]" type="text" value="<?php echo $item["total"]; ?>" />
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>

                     
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Order Status:</label>
                            <div class="col-lg-9">
                                <select name="status" class="form-control">
                                    <option value="2" <?php echo ($upd_ord_row['status'] == '2') ? 'selected' : ''; ?>>Dispatch</option>
                                    <option value="3" <?php echo ($upd_ord_row['status'] == '3') ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="4" <?php echo ($upd_ord_row['status'] == '4') ? 'selected' : ''; ?>>Cancel</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Buttons Outside the Form, Centered -->
<div class="text-center mt-4">
    <button type="submit" name="submit" form="form-validate" class="btn btn-info">Save changes</button>
    <button type="reset" class="btn btn-danger" onclick="window.location='manage_order.php';">Cancel</button>
</div>
