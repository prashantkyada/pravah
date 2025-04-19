<!-- Main Style -->
<link rel="stylesheet" href="style.css">
<?php
$html = '
<html>
<head>
<style>
.main_div{float:left; width:100%; margin:0px; padding:0px;}
.sub_div{float:left; width:100%;}
.header{float:left; width:100%;}
.logo_img{float:left; width:12%;}
.header_hed{float:left; width:85%; font-size:34px; margin-top:40px;}
.header_border{float:left; width:100%; background-image: url(../image/bar_green.png); background-repeat:repeat-x; height:6px;}
.delivrery_detail{float:left; width:100%; margin-top:50px;}
.delivrery_name{float:left; width:50%;}
.delivery_for{float:left; width:20%; font-size:22px;}
.delivery_customer_name{float:left; width:80%; font-size:22px;}
.coustomer_detail{float:left; width:100%; margin-top:15px;}
.order_detail{float:left; width:50%;}
.order_name{float:left; width:80%; font-size:22px;}
.order_no,.order_date{float:left; width:25%; font-size:22px;}
.order_no_detail,.order_date_detail{float:left; width:75%; font-size:22px;}
.order_delivery_date{float:left; width:100%; margin-top:20px;}
.map_pin{float:left; width:100%; margin-top:30px; text-align:center;}
.table_product{float:left; width:100%; margin-top:30px;}
.tabale_str,.order_detail_tbl{font-size:20px;}
.bac_border_tbl{background-image: url(../image/bar_green.png); background-repeat:repeat-x; height:2px;}
.tbl_total{text-align:right; font-size:20px; padding-right:72px; padding-top:20px;}
.footer_line{float:left; width:100%; padding-top:150px;}
.footerline_text{ text-align:center; font-size:18px;}
</style>
</head>
<body>
<div class="main_div">
	<div class="sub_div">
    	<div class="header">
        	<div class="logo_img"><img src="../image/logo_gosport.png" height="150" width="150" /></div>
            <div class="header_hed">Order & Delivery</div>
            <div class="header_border"></div>
        </div>
        <div class="delivrery_detail">
        	<div class="delivrery_name">
            	<div class="delivery_for">Delivery for :</div>
                <div class="delivery_customer_name">
                	<div>
                        <div>Mr. Prashant Kyada</div>
                        <div>Al Majara 804</div>
                        <div>Dubai Marina</div>
                   </div>
                   <div class="coustomer_detail">
                   	<div>05-26238646</div>
                    <div>dfm.stevens@hotmail.com</div>
                   </div>    
                </div>
            </div>
            <div class="order_detail">
                <div class="order_name">
                    <div class="order_no">Order No. : </div>
                    <div class="order_no_detail">MOE-ORD20151016001</div>
                	<div class="order_date">Order Date : </div>
                    <div class="order_date_detail">13 OCT 2015</div>
                </div>
                <div class="order_delivery_date">
                	<div class="order_no">Delivery Date. : </div>
                    <div class="order_no_detail">16 OCT 2015</div>
                	<div class="order_date">Invoice No. : </div>
                    <div class="order_date_detail">0001</div>
               </div>
            </div>
        </div>
    	<div class="map_pin">
        	<img src="../image/map2.jpg" width="1080" height="250" />
        </div>
        
        <div class="table_product">
        	<table align="center" width="80%" cellpadding="1" cellspacing="5">
           		<tr class="tabale_str">
                	<td width="10%">#</td>
                    <td width="20%">Barcode</td>
                    <td width="40%">Product</td>
                    <td width="10%">Item Price</td>
                    <td width="10%">Qty</td>
                    <td width="10%">Price</td>
                </tr>
                <tr class="bac_border_tbl">
                	<td colspan="6"></td>
                </tr>
                <tr class="order_detail_tbl">
                	<td>1</td>
                    <td>7331569320159</td>
                    <td>Boblee GT 20L  - SPITFIRE - MATT SILVER METALLIC</td>
                    <td>770.00</td>
                    <td>1</td>
                    <td>770.00</td>
                </tr>
                  <tr class="order_detail_tbl">
                	<td>2</td>
                    <td>7331569320159</td>
                    <td>Alice Suede Blk/Blk</td>
                    <td>130.00</td>
                    <td>1</td>
                    <td>130.00</td>
                </tr>
                  <tr class="order_detail_tbl">
                	<td>3</td>
                    <td>7331569320159</td>
                    <td>Sanding Blue U</td>
                    <td>5.00</td>
                    <td>3</td>
                    <td>15.00</td>
                </tr>
                 <tr class="bac_border_tbl">
                	<td colspan="6"></td>
                </tr>
           		<tr>
                	<td colspan="6" class="tbl_total">Total : 915</td>
                </tr>
           
            </table> 
        </div>
        <div class="footer_line">
        	<table align="center" width="80%" cellpadding="1" cellspacing="5">
            	<tr class="bac_border_tbl">
                	<td></td>
                </tr>
                <tr>
                	<td class="footerline_text">GO Sport MOE - Level 2, Mall of the Emirates </td>
                </tr>
                <tr>
                	<td class="footerline_text">tel: 02-5650812 | fax: 02-5651817 | e-mail: gosport_moe@almana.com</td>
                </tr>
            </table>
        </div>
        
    </div>
</div>

</body>
</html>
';
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================

define('_MPDF_PATH','../');
include("../mpdf.php");

$mpdf=new mPDF('c','A4','','',20,15,48,25,10,10); 
$mpdf->SetProtection(array('print'));
$mpdf->SetTitle("Acme Trading Co. - Invoice");
$mpdf->SetAuthor("Acme Trading Co.");
$mpdf->SetWatermarkText("Paid");
$mpdf->showWatermarkText = true;
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->watermarkTextAlpha = 0.1;
$mpdf->SetDisplayMode('fullpage');



$mpdf->WriteHTML($html);


$mpdf->Output(); exit;

exit;

?>