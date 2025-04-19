<?php
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
.header_hed{float:left; width:85%; font-size:34px; color:#95A3Ab; padding-top:8%; margin-left:3px;}
.header_border{float:left; width:100%; background-image: url(bar_green.png); background-repeat:repeat-x; height:6px;}
.delivrery_detail{float:left; width:100%; margin-top:50px;}
.image_header{float:left; width:100%; padding-top:1px;}
.descript_main{float:left; width:98%; padding-top:8px; padding-left:15px;}
.descript_voucher{float:left; width:100%;}
.voucher_for{float:left; width:20%; font-size:18px; font-weight:bold;}
.voucher_detail{float:left; width:65%; font-size:18px; font-weight:bold;}
.descript_date{float:left; width:100%; font-size:18px; padding-top:20px;}
.description_first{float:left; width:100%; padding-top:20px; font-size:18px;}
.contect_detail{float:left; width:100%; padding-top:40px;}
.contect{float:left; width:20%; font-size:18px;} 
.contect_desc{float:left; width:65%; font-size:18px;}
.footer_boom{float:left; width:100%; text-align:center; font-size:18px; padding-top:40px;}
.purchase_date{float:left; width:20%;}
.purchase_date_desc{float:left; width:50%;}
.value{float:left; width:30%;}
.bac_border_tbl{background-image: url(bar_green.png); background-repeat:repeat-x; height:1px;}
.footer_line{float:left; width:100%; padding-top:20px; }
.footerline_text{ text-align:center; font-size:12px; color:#95A3Ab;}
</style>
</head>
<body>
<div class="main_div">
	<div class="sub_div">
        <div class="image_header">
       	<img src="congratulation.png" height="480" width="1348" />
       </div>
       <div class="descript_main">
       		<div class="descript_voucher">
            	<div class="voucher_for">Voucher for :</div>
                <div class="voucher_detail">'.$title.'</div>
            </div>
            <div class="descript_date">
            	<div class="purchase_date">Purchase date :</div>
                <div class="purchase_date_desc">sdasdadsa</div>
                <div class="value">Value : AED dadsdad</div>
            </div>
            <div class="description_first">
           dgdfgdfg
            </div>
            <div class="contect_detail">
            	<div class="contect">Contact Details :</div>
                <div class="contect_desc">fghfgh</div>
      
                
            </div>
            <div class="footer_boom">An Al Boom representative will contact you shortly to arrange your booking.</div>
       </div>
    </div>
</div>
</body>
</html>
';
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





?>