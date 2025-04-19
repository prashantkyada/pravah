<?php
include("../../../include/conf.php");
if(isset($_GET['image']))
{

$currentDateTime=date('m/d/Y H:i:s');
$newDateTime = date('His', strtotime($currentDateTime));

$insertdate = date('Ymd');
$result = 'MOE-CS' . $insertdate . $newDateTime;

$date = date('d F Y', strtotime($currentDateTime));
$image = $_GET['image'];
$header = '
<div class="header">
        	<div class="logo_img"><img src="logo_gosport.png" height="150" width="150" /></div>
            <div class="header_hed">Customization - Look like a pro</div>
            <div class="header_border"></div>
        </div>
';
$footer = ' <div class="footer_line">
        	<table align="center" width="100%" cellpadding="1" cellspacing="5">
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
		';
		
$html = '
<html>
<head>
<style>
body{font-family:Arial, Helvetica, sans-serif; margin:0px; padding:0px;}
.main_div{float:left; width:100%; margin:0px; padding:0px;}
.sub_div{float:left; width:100%;}
.header{float:left; width:100%;}
.logo_img{float:left; width:11%;}
.header_hed{float:left; width:89%; font-size:34px; color:#95A3Ab; padding-top:3%; margin-left:1px;}
.header_border{float:left; width:100%; background-image: url(bar_green.png); background-repeat:repeat-x; height:6px;}
.delivrery_detail{float:left; width:100%; margin-top:50px;}
.delivrery_name{float:left; width:50%;}
.delivery_for2{background-image: url(bar_green.png); background-repeat:repeat-x; height:1px;}
.customization_opt{float:left; width:100%;}
.option_sub{text-decoration: underline;}
.tshirt_text{padding-left:11%;}
.order_name{float:left; width:100%; font-size:18px;}
.delivery_customer_name{float:left; width:100%; font-size:12px; text-align: left;}
.checkbox_tbl{padding-top:19px; vertical-align: text-bottom;}
.order_no,.order_date{float:left; width:40%; font-size:12px;  padding-top: 6px;}
.order_no_left{float:left; width:12%; font-size:12px; padding-top:7px;}
.order_no_detail{float:left; width:55%; font-size:13px; padding-top:5px; font-weight:bold;}
.order_no_detail_date{float:left; width:55%; font-size:13px; padding-top:2px; font-weight:bold; border-bottom:0.5px solid #000; margin-top:20px;}
.order_no_detail_left{float:left; width:85%; font-size:13px; padding-top:2px; font-weight:bold; border-bottom:0.5px solid #000; margin-top:20px; }
.border{border-bottom:0.5px solid #000; width:40%; margin-top:20px;}
.option_sub_span{float:left; width:100%;}
.table_product{float:left; width:100%; font-size:12px; margin-top:30px;}
.map_marker{float:left; width:100%; font-size:12px; }
.tabale_str,.order_detail_tbl{font-size:12px;}
.order_notes{font-size:12px; font-style:italic;}
.bac_border_tbl{background-image: url(bar_green.png); background-repeat:repeat-x; height:1px;}
.tbl_total{text-align:right; font-size:20px; padding-right:60px; padding-top:20px;}
.jabal_names{float:left; width:100%; font-size:18px; color:#B2D33C; padding-top:20px;}
.description{float:left; width:100%; font-size:12px; color:#000; margin-top:15px;}
.order_detail{float:right; width:50%; padding-top: 25px;}
.order_name{float:left; width:100%;}
.sub_map{float:left; width:100%; margin-top:15px;}
.sub_map_1{float:left; width:20%;}
.sub_map_2{float:left; width:20%;}
.bac_border_tbl{background-image: url(bar_green.png); background-repeat:repeat-x; height:1px;}
.footer_line{float:left; width:100%; padding-top:7px; }
.footerline_text{ text-align:center; font-size:12px; color:#95A3Ab;}
</style>
</head>
<body>
<div class="main_div">
	<div class="sub_div">
        <div class="delivrery_detail" >
        	<div class="delivrery_name">
            	<div class="delivery_for"><strong style="border-bottom:0.5px solid #000; width:40%; margin-top:20px;">Customer details :</strong></div>
                <div class="delivery_customer_name">
                	<div>
                        <div class="order_no_left">Name :</div>
                        <div class="order_no_detail_left"></div>
                        <div class="order_no_left">Email :</div>
                        <div class="order_no_detail_left"></div>
                        <div class="order_no_left">Phone :</div>
                        <div class="order_no_detail_left"></div>
                   </div>
                </div>
            </div>
            <div class="order_detail">
                <div class="order_name">
                     <div class="order_no">Customization No. : </div>
                    <div class="order_no_detail">'.$result.'</div>
                    <div class="order_no">Order Date : </div>
                    <div class="order_no_detail">'.$date.'</div>
                </div>
              <div class="order_delivery_date">
                	<div class="order_date">Collection Date & Time : </div>
                    <div class="order_no_detail_date"></div>
                </div>
                <div class="order_delivery_date">
                	<div class="order_date">Invoice No. : </div>
                    <div class="order_no_detail_date"></div>
               </div>
            </div>
        </div>
        
        <div class="table_product">
        	<table align="center" width="100%" cellpadding="1" cellspacing="5" style="font-size:12px;">
            <tr>
            	<td colspan="6"><strong style="border-bottom:0.5px solid #000; font-size:14px; width:40%; margin-top:20px;">Customization options:</strong></td>
            </tr>
           		<tr class="tabale_str" style="font-size:12px;">
                	<td width="50%">Item purchased at:</td>
                    <td width="25%">GO Sport</td>
                    <td width="25%">Elsewhere</td>
                </tr>
                <tr class="bac_border_tbl">
                	<td colspan="6"></td>
                </tr>
                <tr class="order_detail_tbl" style="font-size:12px;">
                	<td><div class="option_sub">Laser Printing:</div>
                    Ready after 2 hours
					</td>
                    <td class="checkbox_tbl"><input type="checkbox" name="product" />
               		160 aed	
                    </td>
                     <td class="checkbox_tbl"><input type="checkbox" name="product" />
               		200 aed	
                    </td>
                </tr>
                  <tr class="order_detail_tbl" style="font-size:12px;">
                	<td><div class="option_sub">Embroidery:</div>
                    Ready after 2 hours
					</td>
                    <td class="checkbox_tbl"><input type="checkbox" name="product" />
               		160 aed	
                    </td>
                     <td class="checkbox_tbl"><input type="checkbox" name="product" />
               		200 aed	
                    </td>
                </tr>
                 <tr class="order_detail_tbl">
                	<td><div class="option_sub">Heat Printing:</div>
                    Ready after 1 hours
                    <span class="option_sub_span">Ready after 2 hours</span>
					</td>
                    <td class="checkbox_tbl"><input type="checkbox" name="product" />
               		40 aed	
                  <br /><input type="checkbox" name="product" />
               		80 aed	
                    </td>
                     <td class="checkbox_tbl"><input type="checkbox" name="product" />
               		80 aed	
                     <br /><input type="checkbox" name="product" />
               		120 aed	
                    </td>
                </tr>
                <tr>
                	<td colspan="3" class="tshirt_text"><img src="'.$image.'" height="470" width="500" /></td>
                </tr>
              <tr class="order_notes">
                	<td colspan="3">Notes:</td>
                </tr>
                <tr class="order_notes">
                	<td colspan="3">- Laser printing can only be done on metal and leather. Colour of the execution cant be guaranteed.</td>
                </tr>
                <tr class="order_notes">
                	<td colspan="3">- Embroidery can only be done on footwear.</td>
                </tr>
                <tr class="order_notes">
                	<td colspan="3">- Heat printing can only be done on jerseys and shirts.</td>
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

$mpdf=new mPDF('c','A4','','',10,10,40,20,10,10); 
$mpdf->SetProtection(array('print'));
$mpdf->SetTitle("GO EXPLORE");
$mpdf->SetAuthor("GO Sport MOE");
//$mpdf->SetWatermarkText("Paid");
$mpdf->showWatermarkText = true;
$mpdf->watermark_font = 'GO Sport MOE';
$mpdf->watermarkTextAlpha = 0.1;
$mpdf->SetDisplayMode('fullpage');

$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($footer);

$mpdf->WriteHTML($html);


$mpdf->Output(); exit;
exit;

}
else
{
	exit;
}

?>