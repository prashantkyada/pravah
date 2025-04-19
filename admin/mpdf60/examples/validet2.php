<?php
include("../mpdf.php");
include("../../../include/conf.php");

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
	font-size: 16px !important;
}
.order-form {
	width: 148mm; /* A5 width */
	height: 210mm; /* A5 height */
	margin: 0 auto;
	padding: 0;
	background: #fff;
	border: 1px solid #ccc;
}
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
	width:100%;
}

.logo {
    flex: 0 0 auto;
    text-align: left;
	width:40%;
}

.info {
    flex: 1;
    text-align: right;
    font-size: 14px;
	width:40%;
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
.tbl_head {
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
	text-align: center;
	vertical-align: middle;
}
.order-form table th, .order-form table td {
	border: 1px solid #000;
}
.totals {
	margin-top: 20px;
}

.textLayer {
	position: relative;
}
</style>';

function generateHeader($date, $order_id, $subdata) {
    return '<table border="0" cellspacing="0" cellpadding="5" width="100%">
        <tr>
            <td align="left"><img src="Pravah Logo CDR-11.png" alt="logo" width="100"></td>
            <td align="right"><div><strong>Date:</strong> ' . $date . '</div><div><strong>Order No:</strong> ' . $order_id . '</div></td>
        </tr>
        <tr>
            <td align="left"><div><strong>M/s:</strong> ' . $subdata['user_name'] . '</div><div><strong>Trans.:</strong> ' . $subdata["transport_name"] . '</div></td>
            <td align="right"><div><strong>GST No:</strong> ' . $subdata["gst_no"] . '</div><div><strong>City:</strong> ' . $subdata["city_name"] . '</div></td>
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
$html = '';
$page_rows = 22;
$row_count = 0;

$mpdf =new mPDF(); 
//$mpdf = new mPDF('utf-8', 'A5');
$mpdf->WriteHTML($css, 1); // Apply the CSS

while ($subdata = mysqli_fetch_array($subsql)) {
    if ($row_count % $page_rows === 0) {
        if ($row_count > 0) {
            $html .= '</tbody></table>';
            $mpdf->WriteHTML($html);
            $mpdf->AddPage();
        }

        $product_name = !empty($subdata["product_desc"]) ? $subdata["product_desc"] : $subdata["product_name"];

        $html = generateHeader($date, $order_id, $subdata);
        $html .= '<table class="form-table" border="1" style="border-collapse: collapse; border-radius: 10px; width:100%;">
            <thead>
                <tr>
                    <th style="width: 60%;">PARTICULARS</th>
                    <th style="width: 10%;">QTY.</th>
                    <th style="width: 10%;">PCS</th>
                    <th style="width: 10%;">C/R</th>
                    <th style="width: 10%;">QTY.</th>
                </tr>
            </thead>
            <tbody>';
    }

    $quantity = ($subdata['box_carton'] != '0') ? $subdata['box_carton'] : $subdata['quantity'];
    $html .= '<tr>
                <td>' . $product_name . ' Size: ' . $subdata['size'] . '</td>
                <td>' . $quantity . '</td>
                <td></td>
                <td></td>
                <td></td>
              </tr>';
    $row_count++;
}

$remaining_rows = $page_rows - ($row_count % $page_rows);
if ($remaining_rows > 0 && $remaining_rows < $page_rows) {
    for ($i = 0; $i < $remaining_rows; $i++) {
        $html .= '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
        $row_count++;
    }
}

$html .= '</tbody></table>';
$mpdf->WriteHTML($html);
$mpdf->Output();
?>
