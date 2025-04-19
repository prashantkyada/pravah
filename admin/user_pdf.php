<?php
require_once('../include/TCPDF-main/tcpdf.php'); // Include TCPDF Library

// Create new PDF document
$pdf = new TCPDF('P', 'mm', 'A5', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Company');
$pdf->SetTitle('Order Invoice');
$pdf->SetSubject('Invoice');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set margins
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 10);

// Sample JSON Data (Replace with dynamic database data)
$json_data = '[{"product":"Nipple","product_id":"8","items":[{"size":"1","price":"60","quantity":"1","total":"60"},{"size":"1.5","price":"70","quantity":"1","total":"70"}]},{"product":"Pipe","product_id":"4","items":[{"size":"50 FT","price":"350","quantity":"1","total":"350"}]}]';
$order_data = json_decode($json_data, true);

// PDF Title
$html = '<h2 style="text-align:center;">Order Invoice</h2>';

// Generate table
/*$html .= '<table border="1" cellspacing="0" cellpadding="5">';
$html .= '<tr style="background-color:#f2f2f2;">
            <th>Product</th>
            <th>Size</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
          </tr>';

// Populate Table with Data
foreach ($order_data as $product) {
    foreach ($product['items'] as $item) {
        $html .= '<tr>
                    <td>' . $product["product"] . '</td>
                    <td>' . $item["size"] . '</td>
                    <td>' . $item["price"] . '</td>
                    <td>' . $item["quantity"] . '</td>
                    <td>' . $item["total"] . '</td>
                  </tr>';
    }
}

$html .= '</table>';*/


$html .='<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .order-form {
            width: 148mm; /* A5 width */
            height: 210mm; /* A5 height */
            margin: 20px auto;
            padding: 0px;
            background: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

}
		.gst_no {
            text-align: right;
			margin-right: 50px;
        }
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .form-table th, .form-table td {
            border: 1px solid #000;
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
        .totals {
            margin-top: 20px;
        }
    </style>
	
       <!--Body content-->
      <div class="order-form">
      <div class="header">
			<div class="logo">	
				<img src="images/logo.png" alt="logo">
			</div>
			<div class="info">
				<p><strong>Date:</strong> 07-03-2025</p>
				<p><strong>Order Form No:</strong> order no</p>
			</div>
		</div>
		<div class="header_name">
			<div class="party_name"><strong>M/s:</strong> party name </div>
			<div class="infoheder">
				<div class="trans"><strong>Trans.:</strong>ABCDFG </div>
				<div class="gst_no"><strong>GST No:</strong>123456789 </div>
			</div>
		</div>
        <table class="form-table">
            <thead>
                <tr>
                    <th>Particulars</th>
                    <th>Order Qty</th>
                    <th>Pcs</th>
                    <th>C/R</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
            	<tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
        
          </tbody>
        </table>
    </div>';


// Add content to PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF to browser
$pdf->Output('order_invoice.pdf', 'D');
?>