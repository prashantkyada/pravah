<?php
include("../mpdf.php");
include("../../../include/conf.php");

	$date = date("d-m-Y");

		$order_dt_query = mysqli_query($mysqli, "SELECT * FROM `tbl_order` WHERE `order_id`='".$_GET['order_id']."' ORDER BY `id` DESC");
		$order_dt_row = mysqli_fetch_array($order_dt_query);

		$user_query = "SELECT * FROM `tbl_user` WHERE `id`='".$order_dt_row['user_id']."'";
		$user_sql = mysqli_query($mysqli, $user_query) or die(mysqli_error($mysqli));
		$user_data = mysqli_fetch_array($user_sql); // Fetch all fields as an associative array

		$order_details = array(); // Associative array to group by product name

		$subquery = "SELECT * FROM `tbl_order_detail` WHERE `order_id`='" . $order_dt_row['order_id'] . "' ORDER BY `id` ASC";
		$subsql = mysqli_query($mysqli, $subquery) or die(mysqli_error($mysqli));

		while ($subdata = mysqli_fetch_array($subsql)) {
			$product_id = $subdata['product_id'];

			$get_product = mysqli_query($mysqli, "SELECT * FROM tbl_product WHERE `id`='$product_id'");
			$get_product_row = mysqli_fetch_array($get_product);

			$get_product_dt = mysqli_query($mysqli, "SELECT * FROM tbl_product_detail WHERE `id`='".$subdata['product_dtid']."'");
			$get_productdt_row = mysqli_fetch_array($get_product_dt);

			if($get_product_row["desc"] !='')
			{	
				$product_name = $get_product_row["desc"];
			}
			else
			{
				$product_name = $get_product_row["name"];
			}

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


		if($user_data["gst_no"] )
		{
			$gst_no = $user_data["gst_no"];
		}
		else
		{
			$gst_no = '___________________';
		}

		if($user_data["city_name"] !='')
		{
			$city_name = $user_data["city_name"];
		}
		else
		{
			
			$city_name = '___________________';
		}

// Define Header
$header = '
<table width="100%" style="border-bottom: 1px solid #000; padding-bottom: 5px;">
    <tr>
        <td width="50%" align="left">
            <img src="Pravah Logo CDR-11.png" width="100">
        </td>
        <td width="50%" align="right">
            <div><strong>Date:</strong> ' . $date . '</div>
            <div><strong>Order No:</strong> ' . $_GET["order_id"] . '</div>
        </td>
    </tr>
	
</table>';

// Define Footer
$footer = '
<table width="100%" style="border-top: 1px solid #000; padding-top: 5px;">
    <tr>
        <td width="50%" align="left">© ' . date("Y") . ' Pravah. All rights reserved.</td>
    </tr>
</table>';

$html = '<style>
body { font-family: Arial, sans-serif; font-size: 14px; background-color: #f4f4f4; }
.order-form { margin: 20px auto; padding: 0; background: #fff; border: 1px solid #000; }
.form-table { width: 100%; border-collapse: collapse; }
.form-table th, .form-table td { padding: 5px; text-align: center; border: 1px solid #000; }
.form-table th { background-color: #f0f0f0; }
</style>';

$html .= '<table class="order-form" border="0" cellspacing="0" cellpadding="5" width="100%">
	<tr>
        <td colspan="2">
            <table width="100%">
                <tr>
                    <td align="left"><strong>M/s:</strong> ' . $user_data['name'] . '</td>
                    <td align="right"><strong>GST No:</strong> ' . $gst_no . '</td>
                </tr>
                <tr>
                    <td align="left"><strong>Trans.:</strong> ' . $user_data["transport_name"] . '</td>
                    <td align="right"><strong>City:</strong> ' . $city_name . '</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table class="form-table">
                <thead>
                    <tr>
                       <th width="40%">Particulars</th>
						<th width="10%">QTY.</th>
						<th width="10%">PCS</th>
						<th width="10%">C/R</th>
						<th width="10%">QTY.</th>
                    </tr>
                </thead>
                <tbody>';

foreach ($order_details as $product_name => $details)
							{
									for ($i = 0; $i < count($details['sizes']); $i++) { 
										
									if($details['box_cartons'][$i] != '0')
									{
										$quantity = $details['box_cartons'][$i];
									}
									else
									{
										$quantity = $details['quantities'][$i];
									}	
    $html .= '<tr>
      <td>'.htmlspecialchars($product_name).' Size :'.htmlspecialchars($details['sizes'][$i]).'</td>
								<td style="text-align: center;">'.$quantity.'</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>';
}
							}	

$html .= '</tbody>
            </table>
        </td>
    </tr>
</table>';


// Generate PDF
ob_clean(); // Prevent buffer issues

$mpdf =new mPDF(); 
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($footer);
$mpdf->WriteHTML($html);
$mpdf->Output("order_" . $order_id . ".pdf", "I"); // Open in browser

exit;
?>
