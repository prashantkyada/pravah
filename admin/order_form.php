<?php
include("../include/conf.php");
require_once '../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$css = '
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
   
}
.order-form {
    margin: 0 auto;
   	padding: 5px; 
    background: #fff;
    border: 1px solid #ccc;
}
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    width: 100%;
}
.logo {
    width: 30%;
}
.info {
    width: 65%;
    text-align: right;
  
}
.form-table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
}
.form-table th, .form-table td {
    padding: 3px;
    width:100;
    border: 1px solid #000;
    
}
.form-table th {
    background-color: #f0f0f0;
}
.totals {
    margin-top: 20px;
}
</style>';


function generateHeader($date, $order_id, $subdata,$gst_no,$city_name){
     return '<table class="order-form" border="0" cellspacing="0" cellpadding="5" width="100%">

        <tr>
            <td align="left"><img src="images/Pravah_Logo.png" alt="logo" width="100"></td>
            <td align="right"><div><strong>Date:</strong> ' . $date . '</div><div><strong>Order No:</strong> ' . $order_id . '</div></td>
        </tr>
        <tr>
            <td align="left"><div><strong>M/s:</strong> ' . $subdata['user_name'] . '</div><div><strong>Trans.:</strong> ' . $subdata["transport_name"] . '</div></td>
            <td align="right"><div><strong>City:</strong> ' . $city_name . '</div><div><strong>GST No:</strong> ' . $gst_no . '</div></td>
        </tr>
    </table>';
}

$date = date("d-m-Y");
$order_id = $_GET["order_id"];
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
ORDER BY od.id ASC";

$subsql = mysqli_query($mysqli, $subquery);

$html = ''; // Main HTML content
$page_rows = 25;//ows per page
$row_count = 0;

//$mpdf = new \Mpdf\Mpdf()
//$mpdf = new \Mpdf\Mpdf('utf-8', 'A5', 10, 'dejavusans', 5, 5, 5, 3);

/*$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A5',
    'default_font_size' => 10,
    'default_font' => 'dejavusans',
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
    'margin_bottom' => 3
]);*/

//$mpdf = new mPDF(); // Classic syntax
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A5',
    'default_font_size' => 10,
    'default_font' => 'dejavusans',
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
    'margin_bottom' => 3,
    'tempDir' => '../vendor/tmp/mpdf'
]);


//$mpdf->default_font = 'dejavusans';
//$mpdf->default_font_size = 14;
//$mpdf->WriteHTML($css,1); // Apply the CSS

while ($subdata = mysqli_fetch_array($subsql)) {
	
    if ($row_count % $page_rows === 0) {
        // If we have any rows on the page, close the previous table and create a new page
        if ($row_count > 0) {
            $html .= '</tbody></table>';
            $mpdf->WriteHTML($html);
            $mpdf->AddPage(); // Add a new page when 20 rows are completed
        }
         if($subdata["gst_no"]!="")
		{
			$gst_no = $subdata["gst_no"];
		}
		else
		{
			$gst_no = '__________________';
		}

		if($subdata["city_name"] !="")
		{
			$city_name = $subdata["city_name"];
		}
		else
		{
			$city_name = "__________________";
		}
		
		
        
        $product_name = "";
        
        $product_id = $subdata['product_id'];

		$get_product = mysqli_query($mysqli, "SELECT * FROM tbl_product WHERE `id`='$product_id'");
		$get_product_row = mysqli_fetch_array($get_product);
		
	
		if (!empty($subdata["product_desc"])) {
			$product_name = $subdata["product_desc"];
		} else {
			$product_name = $subdata["product_name"];
		}
		
        // Generate the header for the new page
        $html = generateHeader($date, $order_id, $subdata,$gst_no,$city_name);
        $html .= '<table class="form-table" border="1" style="border-collapse: collapse; border-radius: 10px; width:100%; ">
            <thead>
                <tr>
                    <th style="width: 60%;">PARTICULARS</th>  <!-- Increased width for first column -->
                    <th style="width: 10%;">QTY.</th>
                    <th style="width: 10%;">PCS</th>
                    <th style="width: 10%;">C/R</th>
                    <th style="width: 10%;">QTY.</th>
                </tr>
            </thead>
            <tbody>';
    }
      $product_name = !empty($subdata["product_desc"]) ? $subdata["product_desc"] : $subdata["product_name"];
   // $product_name = htmlspecialchars($subdata['product_name']);
    $quantity = ($subdata['box_carton'] != '0') ? $subdata['box_carton'] : $subdata['quantity'];
   $html .= '<tr style="height: 25px; line-height: 1.4;">
    <td style="line-height: 1.4;">' . $product_name . ' Size : ' . $subdata['size'] . '</td>
    <td style="text-align:center; line-height: 1.4;">' . $quantity . '</td>
    <td style="line-height: 1.4;"></td>
    <td style="line-height: 1.4;"></td>
    <td style="line-height: 1.4;"></td>
</tr>';
    $row_count++;
	
}

// If we have fewer than 20 rows, fill the remaining rows with empty ones
$remaining_rows = $page_rows - ($row_count % $page_rows);
if ($remaining_rows > 0 && $remaining_rows < $page_rows) {
    for ($i = 0; $i < $remaining_rows; $i++) {
        $html .= '<tr>style="height: 25px; line-height: 1.4;"<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
        $row_count++;
    }
}

$html .= '</tbody></table>';
$mpdf->WriteHTML($html);
$mpdf->Output();
//$pdf->Output('order-form'.$order_id.'.pdf', 'D');
?>
