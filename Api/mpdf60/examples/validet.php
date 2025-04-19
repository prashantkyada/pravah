<?php
include("../mpdf.php");
include("../../../include/conf.php");

$html = "<style>
body {
	font-family: Arial, sans-serif;
	margin: 0;
	padding: 0;
	background-color: #f4f4f4;
	font-size: 12px !important;
}
.order-form {
	width: 148mm; /* A5 width */
	height: 210mm; /* A5 height */
	margin: 20px auto;
	padding: 0;
	background: #fff;
	border: 1px solid #ccc;
//	box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
	width:100%;
}

.logo {
    flex: 0 0 auto; /* Ensures the logo doesnt stretch */
    text-align: left;
	width:40%;
}

.info {
    flex: 1; /* Takes up remaining space */
    text-align: right;
    font-size: 14px;
	width:40%;
	posi
}

.info p {
    margin: 0;
}

.header_name{
    display: inline-block;
    width: 100%;
}

.header_name .infoheder{
    display: inline-block;
    width: 49%;
}

.gst_no, .trans {
	text-align: right;
}
.form-table {
	width: 100%;
	border-collapse: collapse;
}
.form-table th, .form-table td {
	padding: 5px;
	text-align: left;
}
.form-table th {
	background-color: #f0f0f0;
}
.form-table td {
	text-align: center; /* Centers text horizontally */
	vertical-align: middle; /* Centers text vertically */
}
.order-form table th, .order-form table td{
	border: 1px solid #000;
}
.totals {
	margin-top: 20px;
}

.textLayer{
	position: relative;
}
</style>";

/*$result	= mysqli_query($mysqli,"select ord.*,us.name,us.mobile_no,us.name,us.transport_name,us.gst_no from `tbl_order` ord,`tbl_user` us where us.id=ord.user_id and ord.id='".$_GET['id']."'");
if(mysqli_num_rows($result)>0)
{	
	$row=mysqli_fetch_array($result);*/
				$date = date("d-m-Y");

				$order_dt_query = mysqli_query($mysqli, "SELECT * FROM `tbl_order` WHERE `order_id`='".$_GET['order_id']."' ORDER BY `id` DESC");
				$order_dt_row = mysqli_fetch_array($order_dt_query);

				$user_query = "SELECT * FROM `tbl_user` WHERE `id`='" . $order_dt_row['user_id'] . "'";
				$user_sql = mysqli_query($mysqli, $user_query) or die(mysqli_error($mysqli));
				$user_data = mysqli_fetch_array($user_sql); // Fetch all fields as an associative array

				$order_details = array(); // Associative array to group by product name

				$subquery = "SELECT * FROM `tbl_order_detail` WHERE `order_id`='" . $order_dt_row['order_id'] . "' ORDER BY `id` ASC";
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
				}

	
 $html .='
    <link rel="stylesheet" href="style.css">
	
 <!--Body content-->
    <table class="order-form" border="0" cellspacing="0" cellpadding="5" width="100%">
		<tbody>
			<tr>
				<td align="left">
					<img src="Pravah Logo CDR-11.png" alt="logo" width="100">
				</td>
				<td align="right">
					<div>
						<div><strong>Date:</strong> '.$date.'</div>
						<div><strong>Order No:</strong> '.$_GET["order_id"].'</div>
					</div>
				</td>
			</tr>
			<tr>
				<td align="left">
					<div>
						<div><strong>M/s:</strong> '.$user_data['name'].'</div>
						<div><strong>City.:</strong> '.$user_data["city_name"].'</div>
						<div><strong>Trans.:</strong> '.$user_data["transport_name"].'</div>
					</div>
				</td>
				<td align="right">
					<div></div>
					<div><strong>GST No:</strong> '.$user_data["gst_no"].'</div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table cellspacing="0" cellpadding="5" width="100%">
						<thead>
							<tr>
								<th>Particulars</th>
								<th>QTY.</th>
								<th>PCS</th>
								<th>C/R</th>
								<th>QTY.</th>
							</tr>
						</thead>
						<tbody>';
							foreach ($order_details as $product_name => $details)
							{
									for ($i = 0; $i < count($details['sizes']); $i++) { 
								
						  $html .= '
							<tr>
								<td>'.htmlspecialchars($product_name).' Size :'.htmlspecialchars($details['sizes'][$i]).'</td>
								<td style="text-align: center;">'.$details['quantities'][$i].'</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>';
								}
							}
					   $html .=' 
					  </tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
    ';							
ob_clean(); // cleaning the buffer before Output()



$mpdf =new mPDF(); 
$mpdf->WriteHTML($html);
$mpdf->Output(); exit;

$mpdf->stream();
$mpdf->stream("order-form".$_GET['order_id'].".pdf", array("Attachment" => true));




exit;

?>