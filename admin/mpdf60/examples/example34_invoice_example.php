<?php
include("../../../include/conf.php");
if(isset($_GET['order_no']))
{
	
	$query = mysql_query("select * from `order_header` where `orderID`='".$_GET['order_no']."'");
	$row=mysql_fetch_array($query);

		$orderID = $row['orderID'];
		$title = $row['title'];
		$firstname = $row['firstname'];
		$surname = $row['surname'];
		$area = $row['area'];
		$street1 = $row['street1'];
		$street2 = $row['street2'];
		$mobile = $row['mobile'];
		$email = $row['email'];
		$orderdate = $row['orderdate'];
	
$header = '
<div class="header">
	<div class="logo_img"><img src="logo_gosport.png" height="150" width="150" /></div>
	<div class="header_hed">Order & Delivery</div>
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
		
		$i = 0;
		$query_list = mysql_query("select * from `order_line` where orderID='".$_GET['order_no']."' ORDER BY `id` ASC");
		while($row_list=mysql_fetch_array($query_list))
		{
			$i++;
			$barcode_url = 'barcode.php?text='.$row_list['barocde'].'';
			//<img alt="barcode" src="'.$barcode_url.'" />
			$price = $row_list['itemprice'] * $row_list['quantity'];  
				$php ='<tr class="order_detail_tbl">
                	<td width="3%">'.$i.'</td>
				    <td width="35%"><img alt="barcode" src="'.$barcode_url.'" />
						<div style="text-align:center;">'.$row_list['barocde'].'</div>
					</td>
                    <td width="35%">'.$row_list['name'].'</td>
                    <td width="10%">'.$row_list['itemprice'].'</td>
                    <td width="7%">'.$row_list['quantity'].'</td>
                    <td width="10%">'.$price.'</td>
                </tr>
               ';
			   $php_data = $php_data.$php; 
			   $total = $total+$price;
		}
		
		
$html = '
<html>
<head>
<style>
body{font-family:Arial, Helvetica, sans-serif; margin:0px; padding:0px;}
.main_div{float:left; width:100%; margin:0px; padding:0px;}
.sub_div{float:left; width:100%;}
.header{float:left; width:100%;}
.logo_img{float:left; width:12%;}
.header_hed{float:left; width:85%; font-size:34px; margin-top:50px; color:#95A3Ab; margin-left: 31px;}
.header_border{float:left; width:100%; background-image: url(bar_green.png); background-repeat:repeat-x; height:6px;}
.delivrery_detail{float:left; width:90%; margin-top:50px; text-align:center;}
.delivrery_name{float:left; width:60%;}
.delivery_for{float:left; width:22%; font-size:12px; padding-left:10px;}
.delivery_customer_name{float:left; width:65%; font-size:12px; text-align: left;}
.coustomer_detail{float:left; width:100%; margin-top:15px;}
.order_detail{float:left; width:40%;}
.order_name{float:left; width:100%; font-size:12px;}
.order_no,.order_date{float:left; width:40%; font-size:12px; padding-top:7px;}
.order_no_detail,.order_date_detail{float:left; width:55%; font-size:12px; padding-top:7px;}
.border{border-bottom:0.5px solid #000; width:40%; margin-top:20px;}
.order_delivery_date{float:left; width:100%; margin-top:20px;}
.map_pin{float:left; width:100%; margin-top:30px; text-align:center;}
.table_product{float:left; width:100%; margin-top:30px;}
.tabale_str,.order_detail_tbl{font-size:12px;}
.bac_border_tbl{background-image: url(bar_green.png); background-repeat:repeat-x; height:1px;}
.tbl_total{text-align:right; font-size:20px; padding-right:35px; padding-top:20px;}
.footer_line{float:left; width:100%; padding-top:20px; }
.footerline_text{text-align:center; font-size:12px; color:#95A3Ab;}
.barcode_span{text-align:center; width:100%;}
</style>
</head>
<body>
<div class="main_div">
	<div class="sub_div">
        <div class="delivrery_detail">
        	<div class="delivrery_name">
            	<div class="delivery_for">Delivery for :</div>
                <div class="delivery_customer_name">
                	<div>
                        <div>'.$title.' '.$firstname.' '.$surname.'</div>
                        <div>'.$area.'</div>
                        <div>'.$street1.'</div>
						<div>'.$street2.'</div>
                   </div>
                   <div class="coustomer_detail">
                   	<div>'.$mobile.'</div>
                    <div>'.$email.'</div>
                   </div>    
                </div>
            </div>
            <div class="order_detail">
                <div class="order_name">
                    <div class="order_no">Order No. : </div>
                    <div class="order_no_detail">'.$orderID.'</div>
                	<div class="order_date">Order Date : </div>
                    <div class="order_date_detail">'.$orderdate.'</div>
                </div>
                <div class="order_delivery_date">
                	<div class="order_no">Delivery Date. : </div>
                    <div class="order_no_detail border"></div>
				</div>
				<div class="order_delivery_date">	
                	<div class="order_date">Invoice No. : </div>
                    <div class="order_date_detail border"></div>
               </div>
            </div>
        </div>
    	<div class="map_pin">
        	<img src="../../../image/map/'.$orderID.'.png" />
        </div>
        
        <div class="table_product">
        	<table align="center" width="90%" cellpadding="1" cellspacing="5">
           		<tr class="tabale_str">
                	<td width="5%">#</td>
                    <td width="20%">Barcode</td>
                    <td width="30%">Product</td>
                    <td width="15%">Item Price</td>
                    <td width="10%">Qty</td>
                    <td width="10%">Price</td>
                </tr>
				 <tr class="bac_border_tbl">
                	<td colspan="6"></td>
                </tr>	
				'.$php_data.'
                <tr class="bac_border_tbl">
                	<td colspan="6"></td>
                </tr>	
           		<tr>
                	<td colspan="6" class="tbl_total">Total : '.$total.'</td>
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
$mpdf->SetTitle("GO Sport MOE - Invoice");
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