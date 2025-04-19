<?php
include("../mpdf.php");
include("../../../include/conf.php");

$css = '
<style>
body {
    font-size: 14px;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}
.form-table {
    width: 100%;
    border-collapse: collapse;
}
.form-table th, .form-table td {
    border: 1px solid #000;
    padding: 5px;
    line-height: 1.4;
    font-size: 14px;
}
</style>';

$mpdf = new mPDF('utf-8', 'A5');
$mpdf->WriteHTML($css, 1);

$rows_per_page = 20;
$row_count = 0;
$page_data = [];
$html = '';

while ($subdata = mysqli_fetch_array($subsql)) {
    if ($row_count % $rows_per_page === 0) {
        // Write previous page and start a new one
        if ($row_count > 0) {
            // Fill remaining blank rows if needed
            $remaining = $rows_per_page - (count($page_data) % $rows_per_page);
            if ($remaining != $rows_per_page) {
                for ($i = 0; $i < $remaining; $i++) {
                    $page_data[] = '<tr><td>&nbsp;</td><td>&nbsp;</td><td></td><td></td><td></td></tr>';
                }
            }

            $html .= implode("", $page_data) . '</tbody></table>';
            $mpdf->WriteHTML($html);
            $mpdf->AddPage();
        }

        // Reset
        $page_data = [];

        // New header
        $header = '
        <table class="order-form" border="0" cellspacing="0" cellpadding="5" width="100%">
            <tbody>
                <tr>
                    <td align="left">
                        <img src="Pravah Logo CDR-11.png" alt="logo" width="100">
                    </td>
                    <td align="right">
                        <div><strong>Date:</strong>' . $date . '</div>
                        <div><strong>Order No:</strong>' . $_GET["order_id"] . '</div>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        <div><strong>M/s:</strong>' . $subdata['user_name'] . '</div>
                        <div><strong>Trans.:</strong>' . $subdata["transport_name"] . '</div>
                    </td>
                    <td align="right">
                        <div><strong>GST No:</strong>' . $subdata["gst_no"] . '</div>
                        <div><strong>City:</strong>' . $subdata["city_name"] . '</div>
                    </td>
                </tr>
            </tbody>
        </table>';

        $html = $header . '
        <table class="form-table">
            <thead>
                <tr>
                    <th>PARTICULARS</th>
                    <th>QTY.</th>
                    <th>PCS</th>
                    <th>C/R</th>
                    <th>QTY.</th>
                </tr>
            </thead>
            <tbody>';
    }

    $product_name = !empty($subdata["product_desc"]) ? $subdata["product_desc"] : $subdata["product_name"];
    $quantity = ($subdata['box_carton'] != '0') ? $subdata['box_carton'] : $subdata['quantity'];

    $page_data[] = '<tr>
        <td>' . $product_name . ' Size: ' . $subdata['size'] . '</td>
        <td style="text-align:center;">' . $quantity . '</td>
        <td></td><td></td><td></td>
    </tr>';

    $row_count++;
}

// Final page (if needed)
if (!empty($page_data)) {
    $remaining = $rows_per_page - (count($page_data) % $rows_per_page);
    if ($remaining != $rows_per_page) {
        for ($i = 0; $i < $remaining; $i++) {
            $page_data[] = '<tr><td>&nbsp;</td><td>&nbsp;</td><td></td><td></td><td></td></tr>';
        }
    }

    $html .= implode("", $page_data) . '</tbody></table>';
    $mpdf->WriteHTML($html);
}

$mpdf->Output();

?>