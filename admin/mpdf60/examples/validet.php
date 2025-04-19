<?php
include("../mpdf.php");
include("../../../include/conf.php");

$html = "<style>
body {
	font-family: Arial, sans-serif;
	margin: 0;
	padding: 0;
	background-color: #f4f4f4;
	font-size: 14px !important;
}
.order-form {
	width: 148mm; /* A5 width */
	height: 210mm; /* A5 height */
	margin: 0 auto; /* Center without top margin */
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
.tbl_head{

  background-color: #f0f0f0;
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
$order_id = $_GET["order_id"];
$date = date("d-m-Y");

	$subquery = "SELECT 
    o.*, 
    u.name AS user_name, 
    u.mobile_no, 
    u.transport_name, 
    u.gst_no, 
    u.city_name, 
    od.*, 
    pr.name AS product_name, 
    pr.desc AS product_desc, 
    pd.size 
FROM `tbl_order` o
JOIN `tbl_user` u ON o.user_id = u.id
JOIN `tbl_order_detail` od ON o.order_id = od.order_id
JOIN `tbl_product` pr ON od.product_id = pr.id
JOIN `tbl_product_detail` pd ON od.product_dtid = pd.id
WHERE o.order_id = '$order_id'
ORDER BY `pd`.`product_id`,`pd`.`id` ASC";
$subsql = mysqli_query($mysqli, $subquery);

			
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
						<div><strong>Date:</strong>'.$date.'</div>
						<div><strong>Order No:</strong>'.$_GET["order_id"].'</div>
					</div>
				</td>
			</tr>
			<tr>
				<td align="left">
					<div>
						<div><strong>M/s:</strong>'.$user_data['name'].'</div>
						<div><strong>Trans.:</strong>'.$user_data["transport_name"].'</div>
					</div>
				</td>
				<td align="right">
					<div><strong>GST No:</strong>'.$gst_no.'</div>
					<div><strong>City.:</strong>'.$city_name.'</div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table cellspacing="0" cellpadding="5" width="100%">
						<thead class"tbl_head">
							<tr>
								<th>PARTICULARSE</th>
								<th>QTY.</th>
								<th>PCS</th>
								<th>C/R</th>
								<th>QTY.</th>
							</tr>
						</thead>
						<tbody>';
						$max_rows = 20; // Total rows to display on the page
						$current_row_count = 0;

while ($subdata = mysqli_fetch_array($subsql)) {
	
	if($subdata["gst_no"] )
	{
		$gst_no = $subdata["gst_no"];
	}
	else
	{
		$gst_no = '___________________';
	}

	if($subdata["city_name"] !='')
	{
		$city_name = $subdata["city_name"];
	}
	else
	{
		$city_name = '___________________';
	}

		
							/*foreach ($order_details as $product_name => $details)
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
									$current_row_count++; // Increment row count*/
								
								$current_row_count++; // Increment row count*/
	 $quantity = ($subdata['box_carton'] != '0') ? $subdata['box_carton'] : $subdata['quantity'];
						  $html .= '
							<tr>
								 <td>' . $product_name . ' Size: ' . $subdata['size'] . '</td>
                <td>' . $quantity . '</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>';
								
							}
							// Add blank rows if the current count is less than max_rows
							while ($current_row_count < $max_rows) {
								$html .= '
								<tr>
									<td>&nbsp;</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>';
								$current_row_count++;
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
$mpdf->Output(); 

$mpdf->stream();
$mpdf->stream("user-bill".$_REQUEST['id'].".pdf", array("Attachment" => true));
exit;
?>