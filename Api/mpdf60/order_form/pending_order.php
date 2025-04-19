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
	font-size: 12pt;
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
    padding: 8px;
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
            <td align="left"><img src="Pravah Logo CDR-11.png" alt="logo" width="100"></td>
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
    od.order_id,
    od.product_id,
    od.product_dtid,
    p.name AS product_name,
    p.desc AS product_description,
    pd.size AS product_size,
    u.name AS user_name,
    u.transport_name,
    u.gst_no,
    u.city_name,
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
JOIN 
    tbl_user u ON o.user_id = u.id
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
    od.order_id = '".$order_id."'
GROUP BY 
    od.order_id, od.product_id, od.product_dtid, 
    p.name, p.desc, pd.size, 
    u.name, u.transport_name, u.gst_no, u.city_name
HAVING 
    (pending_quantity > 0 OR pending_box_carton > 0)  
ORDER BY `pd`.`product_id`,`pd`.`id` ASC";


$subsql = mysqli_query($mysqli, $subquery);


$html = ''; // Main HTML content
$page_rows = 27;//rows per page
$row_count = 0;

// Set A5 size for the PDF
//$mpdf = new mPDF('utf-8', 'A5'); 
/*$mpdf = new mPDF([
    'format' => [148, 210], // A5 size in mm (width x height)
    'margin_left' => 0,     // No left margin
    'margin_right' => 0,    // No right margin
    'margin_top' => 5,      // Optional: small top margin for header
    'margin_bottom' => 5,   // Optional: small bottom margin
	'default_font_size' => 14,
    'default_font' => 'dejavusans',
]);
*/
$mpdf = new mPDF('utf-8', 'A5', 10, 'dejavusans', 5, 5, 5, 3);
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
		
        // Generate the header for the new page
        $html = generateHeader($date, $order_id, $subdata,$gst_no,$city_name);
        $html .= '<table class="form-table" border="1" style="border-collapse: collapse; border-radius: 10px; width:100%; ">
            <thead>
                <tr>
                    <th style="width: 60%;">PARTICULARS</th>  <!-- Increased width for first column -->
                    <th style="width: 10%;">QTY.</th>
                    <th style="width: 12%;">PCS</th>
                    <th style="width: 12%;">C/R</th>
                    <th style="width: 12%;">QTY.</th>
                </tr>
            </thead>
            <tbody>';
    }
      $product_name = !empty($subdata["product_description"]) ? $subdata["product_description"] : $subdata["product_name"];

    $quantity = ($subdata['pending_box_carton'] != '0') ? $subdata['pending_box_carton'] : $subdata['pending_quantity'];
   $html .= '<tr style="height: 25px; line-height: 1.4;">
    <td>' . $product_name . ' Size : ' . $subdata['product_size'] . '</td>
    <td style="text-align:center;">' . $quantity . '</td>
    <td></td>
    <td></td>
    <td></td>
</tr>';
    $row_count++;
	
}

// If we have fewer than 20 rows, fill the remaining rows with empty ones
$remaining_rows = $page_rows - ($row_count % $page_rows);
if ($remaining_rows > 0 && $remaining_rows < $page_rows) {
    for ($i = 0; $i < $remaining_rows; $i++) {
        $html .= '<tr style="height: 25px; line-height: 1.4;"><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
        $row_count++;
    }
}

$html .= '</tbody></table>';
$mpdf->WriteHTML($html);
$mpdf->Output();

?>