<?php
include("../../../include/conf.php");
if(isset($_GET['location']))
{
	
	$query = mysql_query("select * from `outdoorsublocation` where `outdoorSubLocationPK`='".$_GET['location']."'");
	$row=mysql_fetch_array($query);

		$outdoorLocationFK = $row['outdoorLocationFK'];
		$outdoorSubLocationName = $row['outdoorSubLocationName'];
		$GPS = $row['GPS'];
		$Description = $row['Description'];
		$Image = $row['Image'];
	
	$setting = mysql_query("select * from `setting` where `description`='ActivityImageLocation'");
	$setting_row=mysql_fetch_array($setting);
	$image_path = $setting_row['setting'];
	

	$file_url = $image_path.$Image.'.jpg';
	if(file_exists($file_url)) 
	{
		$dynamic_data = 
		'<div class="order_name">
			<div class="order_no"><img src="'.$file_url.'" width="700" height="600" /></div>
		</div>';
		
	} 
	else 
	{
		$dynamic_data = '';
	}
		
	$sub_img_url_1 = $image_path.$Image.'_a.jpg';
	if(file_exists($sub_img_url_1)) 
	{
		$sub_img_1[] = $sub_img_url_1;
	}
	else
	{
		$sub_img_1[] = '';
	}
	
	$sub_img_url_2 = $image_path.$Image.'_b.jpg';
	if(file_exists($sub_img_url_2)) 
	{
		$sub_img_2[] = $sub_img_url_2;
	}
	else
	{
		$sub_img_2[] = '';
	}

	$sub_img_url_3 = $image_path.$Image.'_c.jpg';
	if(file_exists($sub_img_url_3)) 
	{
		$sub_img_3[] = $sub_img_url_3;
	}
	else
	{
		$sub_img_3[] = '';
	}

	
	$linksArray =  array_merge($sub_img_1, $sub_img_2,$sub_img_3);
	$sub_img_list = array_filter($linksArray);
	$php_data ='';
	foreach($sub_img_list as $value) 
	{
		$sub_map_url = '<div class="sub_map_1"><img src="'.$value.'" width="100" height="100" /></div>';
		$php_data = $php_data.$sub_map_url; 
	}
		
/*$php_data = '';
$image_2[] = '../../../image/CampBeachFuj.jpg';
$image_3[] = '../../../image/CampBeachFuj.jpg';
$image_1[] = '../../../image/CampBeachFuj.jpg';
$sub_img_list =  array_merge($image_1,$image_2,$image_3);
$i = 0;
foreach($sub_img_list as $value) 
{
	$i++;
	$sub_map_url = 
	'<div class="sub_map">
		<div class="sub_map_'.$i.'"><img src="'.$value.'" width="100" height="100" /></div>
	</div>';
	
	$php_data = $php_data.$sub_map_url; 
}*/

$header = '
<div class="header">
        	<div class="logo_img"><img src="logo_gosport.png" height="150" width="150" /></div>
            <div class="header_hed">GO EXPLORE</div>
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
.logo_img{float:left; width:12%;}
.header_hed{float:left; width:85%; font-size:34px; margin-top:50px; color:#95A3Ab; margin-left: 31px; }
.header_border{float:left; width:100%; background-image: url(bar_green.png); background-repeat:repeat-x; height:6px;}
.delivrery_detail{float:left; width:90%; margin-top:50px;}
.delivrery_name{float:left; width:48%; margin-left:15px;}
.map_marker{float:left; width:100%; font-size:12px; }
.jabal_names{float:left; width:100%; font-size:18px; color:#B2D33C; padding-top:20px;}
.description{float:left; width:100%; font-size:12px; color:#000; margin-top:15px;}
.order_detail{float:right; width:46%; margin-left:25px;}
.order_name{float:left; width:100%;}
.order_no{float:left; width:100%; font-size:12px; padding-top:7px;}
.sub_map{float:left; width:100%; margin-top:15px;}
.sub_map_1{float:left; width:20%; padding:5px;}
.sub_map_2{float:left; width:20%;}
.sub_map_3{float:left; width:20%;}
.bac_border_tbl{background-image: url(bar_green.png); background-repeat:repeat-x; height:1px;}
.footer_line{float:left; width:100%; padding-top:20px; }
.footerline_text{ text-align:center; font-size:12px; color:#95A3Ab;}
</style>
</head>
<body>
<div class="main_div">
	<div class="sub_div">
        <div class="delivrery_detail">
        	<div class="delivrery_name">
            	<div class="map_marker"><img src="../../../image/Maps-Marker-icon.png" />'.$GPS.'</div>
           		<div class="jabal_names">'.$outdoorSubLocationName.'</div>
                <div class="description">
                '.$Description.'
                </div>
            </div>
            <div class="order_detail">
				'.$dynamic_data.'
				<div class="sub_map">
                	'.$php_data.'
				</div>
            </div>
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