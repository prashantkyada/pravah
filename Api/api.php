<?php
include("../include/conf.php");
include("../include/function.php");
require_once '../vendor/autoload.php';
$request = new HTTP_Request2();

if(isset($_SERVER['HTTPS'])) 
{  
   $file_path = 'https://'.$_SERVER[ 'SERVER_NAME' ].'/image/';
   $link = 'https://';
   $order_form_api_path = 'https://'.$_SERVER[ 'SERVER_NAME' ].'/Api/';
	
  //live change
   /*$file_path = 'https://'.$_SERVER[ 'SERVER_NAME' ].'/pravah/image/';
   $link = 'https://';
   $order_form_api_path = 'https://'.$_SERVER[ 'SERVER_NAME' ].'/pravah/Api/';*/
}
else
{
	 $file_path = 'http://'.$_SERVER[ 'SERVER_NAME' ].'/image/';
	 $link = 'http://';
	 $order_form_api_path = 'http://'.$_SERVER[ 'SERVER_NAME' ].'/Api/';
	
  //live site url
  /*$file_path = 'http://'.$_SERVER[ 'SERVER_NAME' ].'/pravah/image/';
  $link = 'http://';
  $order_form_api_path = 'http://'.$_SERVER[ 'SERVER_NAME' ].'/pravah/Api/';*/
	
}
$dateupdate = date("Y-m-d H:i:s");

//user login check and otp send & forgate password check
if(isset($_POST['user_otp_send']) && isset($_POST['mobile_no'])){
	
	$query_user = mysqli_query($mysqli,"SELECT * FROM `tbl_user` WHERE `mobile_no`='".$_POST['mobile_no']."'");
	if(mysqli_num_rows($query_user)>0)
	{
		$data = mysqli_fetch_array($query_user);
		
		$user_id = $data['id'];
		
		if($data['status'] == '0')
		{
			$set['login_status'] = 'false';
			$set['error'] = 'Your login is disable by admin please contact admin.';
			header('Content-Type: application/json; charset=utf-8');
			echo $val = str_replace( '\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
			curl_close($ch);
			die();
		}
	}
	
	$mobile = $_POST['mobile_no'];
	//$sms = rand(1000, 9999);
	//SMS SEND API CALL START
	
	$request->setUrl('https://cpaas.messagecentral.com/verification/v3/send?countryCode=91&customerId=C-F82C45941C94454&senderId=UTOMOB&type=OTP&flowType=SMS&mobileNumber='.$mobile.'&message=Welcome to sms service otp 5055');
	$request->setMethod(HTTP_Request2::METHOD_POST);
	$request->setConfig(array(
	'follow_redirects' => TRUE
	));
	$request->setHeader(array(
	'authToken' => 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJDLUY4MkM0NTk0MUM5NDQ1NCIsImlhdCI6MTc0MTY2MjYzMywiZXhwIjoxODk5MzQyNjMzfQ.78UluDFZZdK_0RsNIj2hY-uXFz1mVDp-eLynrStpoyvURqOybOdcLrdsdcRk_awQb0Ymw4pdFTLQo_UYGOu-0g'
	));
	try {
	$response = $request->send();
	if ($response->getStatus() == 200) {
		
	$responseArray = json_decode($response->getBody(), true);
	$verificationId = $responseArray['data']['verificationId'];
	$transactionId = $responseArray['data']['transactionId'];	
		
		$query = mysqli_query($mysqli,"SELECT * FROM `tbl_mobile_otp` WHERE `mobile_no`='".$_POST['mobile_no']."'");
		if(mysqli_num_rows($query)>0)
		{	
			$result = mysqli_query($mysqli,"UPDATE tbl_mobile_otp SET 
			`status`='1',
			`verificationId`='".$verificationId."',
			`transactionId`='".$transactionId."',
			`update_on`='".$dateupdate."'
			where `mobile_no`='".$_POST['mobile_no']."'");

		}
		else
		{
			$result = mysqli_query($mysqli,"insert into `tbl_mobile_otp` SET 
			`mobile_no`='".$_POST['mobile_no']."',
			`verificationId`='".$verificationId."',
			`transactionId`='".$transactionId."',
			`created_on`='".$dateupdate."',
			`update_on`='".$dateupdate."'
			");

		}
		
		$set['status'] = 'true';
		$set['data'] = $response->getBody();
		
	}
	else 
	{
		echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
		$response->getReasonPhrase();

	}
	}
		catch(HTTP_Request2_Exception $e) {
		//echo 'Error: ' . $e->getMessage();
		$set['status'] = 'false';	
		$set['Error'] = $e->getMessage();	
	}

	
	//$set['err_code'] = http_response_code();
	header( 'Content-Type: application/json; charset=utf-8' );
	echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	curl_close($ch);
	die();

}
 //user check otp mobile no
if(isset($_POST['otp_check']) && isset($_POST['mobile_no']) && isset($_POST['otp'])){
	$jsonObj = array();
	$verificationId = $_POST['verificationId'];
	$otp = $_POST['otp'];
	
		$query = mysqli_query($mysqli,"SELECT * FROM `tbl_mobile_otp` WHERE `mobile_no`='".$_POST['mobile_no']."' and `verificationId`='".$verificationId."'");
		if(mysqli_num_rows($query)>0)
		{	
			$maindata = mysqli_fetch_array($query);
			
				$query_user = mysqli_query($mysqli,"SELECT * FROM `tbl_user` WHERE `mobile_no`='".$_POST['mobile_no']."'");
				if(mysqli_num_rows($query_user)>0)
				{	
					$data = mysqli_fetch_array($query_user);
					$user_id = $data['id'];   

					$result = mysqli_query($mysqli,"UPDATE tbl_mobile_otp SET 
					`status`='2',
					`user_id`='".$user_id."',
					`update_on`='".$dateupdate."'
					where `mobile_no`='".$_POST['mobile_no']."'");

				}
				else
				{
					$user_id = '0';  
				}
			
				$request->setUrl('https://cpaas.messagecentral.com/verification/v3/validateOtp?&verificationId='.$verificationId.'&code='.$otp);
				$request->setMethod(HTTP_Request2::METHOD_GET);
				$request->setConfig(array(
				  'follow_redirects' => TRUE
				));
				$request->setHeader(array(
				  'authToken' => 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJDLUY4MkM0NTk0MUM5NDQ1NCIsImlhdCI6MTc0MTY2MjYzMywiZXhwIjoxODk5MzQyNjMzfQ.78UluDFZZdK_0RsNIj2hY-uXFz1mVDp-eLynrStpoyvURqOybOdcLrdsdcRk_awQb0Ymw4pdFTLQo_UYGOu-0g'
				));
				try {
				  $response = $request->send();
				  if ($response->getStatus() == 200) {
					//echo $response->getBody();
					 $set['status'] = 'true'; 
					 $set['error'] = 'OTP verified successfully.'; 
					 $set['user_id'] = $user_id;  
					 $set['data'] = $response->getBody(); 
				  }
				  else {
					//echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
					 $set['status'] = 'false'; 
					 $set['user_id'] = $user_id; 
					 $set['error'] = 'Invalid Otp'; 
					 $set['data'] = $response->getStatus(); 	
						
						
					$response->getReasonPhrase();
				  }
				}
				catch(HTTP_Request2_Exception $e) {
				$set['status'] = 'false';
				$set['user_id'] = $user_id;  	
				$set['error'] = $e->getMessage();	
				  //echo 'Error: ' . $e->getMessage();
				}
			
				//$set['status'] = 'true';
				//$set['user_id'] = $user_id;
				//$set['error'] = 'OTP verified successfully.';
			
				//$set['status'] = 'false';
				//$set['error'] = 'User not registered.';
			

		}
		else
		{
			$set['status'] = 'false';
			$set['error'] = 'Invalid Mobile number.';
		}
			
			//$set['err_code'] = http_response_code();
			header( 'Content-Type: application/json; charset=utf-8' );
			echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
			die();
	
}

//user send  otp password
if(isset($_POST['forgot_password']) && isset($_POST['mobile_no'])){
	$jsonObj = array();
	
	$maindata = mysqli_fetch_array($query);
	$query_user = mysqli_query($mysqli,"SELECT *
	FROM `tbl_user` WHERE `mobile_no`='".$_POST['mobile_no']."'");
	if(mysqli_num_rows($query_user)>0)
	{
		$data = mysqli_fetch_array($query_user);

		$password =  '1234';

		$result = mysqli_query($mysqli,"UPDATE tbl_mobile_otp SET 
		`status`='1',
		`otp`='".$password."',
		`user_id`='".$data['id']."',
		`update_on`='".$dateupdate."'
		where `mobile_no`='".$_POST['mobile_no']."'");
		//otp send  password
		
		//"Your OTP for reset the password on Pravah App is {{OTP}}. This OTP is valid for {{EXPIRY_TIME}} minutes. Do not share it with anyone. Pravah Team."
		$set['status'] = 'true';
		$set['message'] = 'OTP has been sent successfully.';


	}
	else
	{
		$set['status'] = 'false';
		$set['message'] = 'Invalid Mobile Number.';
	}

	$set['err_code'] = http_response_code();
	header( 'Content-Type: application/json; charset=utf-8' );
	echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();
	
}
//user register
if(isset($_POST['user_register']) && isset($_POST['mobile_no'])){

	$jsonObj = array();
	$mainquery = "SELECT * FROM `tbl_user` where `mobile_no`='".$_POST['mobile_no']."'";
	$mainsql = mysqli_query( $mysqli, $mainquery)or die( mysql_error() );
	if(mysqli_num_rows($mainsql)>0)
	{	
		$set['status'] = false;
		$set['error'] = 'Your mobile number is already exist.';
		
	}
	else
	{
		mysqli_query($mysqli,"insert into `tbl_user` set 
		`name`='".$_POST['name']."',
		`email`='".$_POST['email']."',
		`password`='".password_hash($_POST['password'], PASSWORD_DEFAULT)."',
		`mobile_no`='".$_POST['mobile_no']."',
		`address`='".$_POST['address']."',
		`city_name`='".$_POST['city_name']."',
		`transport_name`='".$_POST['transport_name']."',
		`transport_add`='".$_POST['transport_address']."',
		`gst_no`='".$_POST['gst_no']."',
		`status`='0',
		`device`='".$_POST['device']."',
		`device_token`='".$_POST['device_token']."',
		`update_date`='".$dateupdate."',
		`date`='".$dateupdate."'
		");
		$set['status'] = true;
		
	}
	$set['err_code'] = http_response_code();

	header( 'Content-Type: application/json; charset=utf-8' );
	echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();
	
	
}

//user login check
if(isset($_POST['login']) && isset($_POST['mobile_no']) && isset($_POST['password'])){

	$jsonObj = array();
	$mainquery = "SELECT * FROM `tbl_user` where `mobile_no`='".$_POST['mobile_no']."' and `password`='".$_POST['password']."'";
	$mainsql = mysqli_query( $mysqli, $mainquery)or die( mysql_error() );
	if(mysqli_num_rows($mainsql)>0)
	{	
		$maindata = mysqli_fetch_array($mainsql);
		
		if($maindata['status'] == '1')
		{	
			
			$set['login_status'] = true;
			$set['id'] = $maindata['id'];
			$set['name'] = $maindata['name'];
			$set['email'] = $maindata['email'];
			$set['mobile_no'] = $maindata['mobile_no'];
			$set['discount'] = $maindata['discount'];
			$set['user_status'] = $maindata['user_status'];
			
			$set['date'] = $maindata['date'];
			
			//update device token product_list
			mysqli_query($mysqli,"UPDATE `tbl_user` SET  
				`device_token`='".$_POST['device_token']."',
				`device`='".$_POST['device']."'
				WHERE 
				`id`='".$maindata['id']."'");
		}
		else
		{
			$set['login_status'] = false;
			$set['error'] = 'Your Mobile no is inactive contact admin to active.';
		}

		
	}
	else
	{
		$set['login_status'] = false;
		$set['error'] = 'Your login & password is wrong.';
		
	}
	$set['err_code'] = http_response_code();
	//$set['data'] = $jsonObj;

	header( 'Content-Type: application/json; charset=utf-8' );
	echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();
	
}
//category list
if(isset($_POST['category_list'])){

	$jsonObj = array();
	$sublanguagejson = array();
	
	$userquery = "SELECT * FROM `tbl_user` where `id`='".$_POST['user_id']."'";
	$usersql = mysqli_query( $mysqli, $userquery)or die( mysql_error() );
	$userdata = mysqli_fetch_array($usersql);
	
	$query = "SELECT * FROM tbl_category where `status`='1' ORDER BY `display_order` ASC";
	$sql = mysqli_query( $mysqli, $query )or die( mysql_error() );
	while($data = mysqli_fetch_array( $sql ) ) 
	{
		$row['id'] = $data['id'];
		$row['category_name'] = $data['category_name'];
		if($data['image'] !='')
		{
			$row['category_image'] = ROOT_PATH.'image/category/'.$data['image'];
		}
		else
		{
			$row['category_image'] = ROOT_PATH.'image/dummy.png';
		}
		
		array_push($jsonObj,$row);
	}
	$set['status'] = true;
	$set[ 'user_status' ] = $userdata['status'];
	$set['data'] = $jsonObj;

	header( 'Content-Type: application/json; charset=utf-8' );
	echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();
}
//product list by category id & subcategory id
if(isset($_POST['product_list']) && isset($_POST['category_id']) && isset($_POST['user_id']))
{
	$jsonObj = array();
	$jsonObj1 = array();
	
	$userquery = "SELECT * FROM `tbl_user` where `id`='".$_POST['user_id']."'";
	$usersql = mysqli_query( $mysqli, $userquery)or die( mysql_error() );
	$userdata = mysqli_fetch_array($usersql);
	$discount = $userdata['discount'];
	
	$query = "select * from `tbl_product` where `status`='1' and category_id='".$_POST['category_id']."'";
	$sql = mysqli_query($mysqli,$query)or die(mysqli_error());
	while($data = mysqli_fetch_array($sql)) 
	{
		$row[ 'id' ] = $data[ 'id' ];
		$row['name'] = $data[ 'name' ];
		$row['desc'] = $data[ 'desc' ];
		if($data['image'] !='')
		{
			$row['image'] = ROOT_PATH.'image/product/'.$data['image'];
		}
		else
		{	
			$row['image'] = ROOT_PATH.'image/pravah.png';
		}
		if($data['image'] !='')
		{
			$row['thumb_image'] = ROOT_PATH.'image/product/thumb/'.$data['image'];
		}
		else
		{	
			$row['thumb_image'] = ROOT_PATH.'image/pravah.png';
		}
		
		//$sizes = isset($data['size']) ? explode("{*}", $data['size']) : [];
		$sizes = explode("{*}",$data[ 'size' ]);
		$prices = explode("{*}",$data[ 'price' ]);
		$std_paking = explode("{*}",$data['std_paking']);
		$min_qtys = explode("{*}",$data[ 'min_qty' ]);
		
		/*$sizes = array_map('trim', explode("{*}", $data['size']));
		$prices = array_map('trim', explode("{*}", $data['price']));
		$std_paking = array_map('trim', explode("{*}", $data['std_paking']));
		$min_qtys = array_map('trim', explode("{*}", $data['min_qty']));*/
		//$offer_prices = explode("{*}",$data[ 'offer_price' ]);
		//$offer_prices = isset($data['offer_price']) ? explode("{*}", $data['offer_price']) : [];
		
	
		$jsonObj1 = []; // Initialize the array
		
		// Iterate through all sizes and combine data
		foreach ($sizes as $index => $size) {

			$discount_price = $prices[$index] - ($prices[$index] * ($discount/100));
			

			$row1 = [
				//'size' =>   trim(str_replace('"', '', $size)),
				'size' =>    $size,
				'price' => $prices[$index],
				'discount_price' => $discount_price,
				'master_pack' => $std_paking[$index],
				'avg_weight' => $min_qtys[$index]
			];
			array_push($jsonObj1, $row1);
		}
		$row['multi_size'] = $jsonObj1;
		//price
		//	min_qty
		//	offer_price
		
		$row['date'] = $data['date'];
		array_push($jsonObj,$row);

	}
	$set[ 'status' ] = true;	
	$set[ 'data' ] = $jsonObj;
		
	header( 'Content-Type: application/json; charset=utf-8' );
	echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();

}

//new product list by category id
if(isset($_POST['new_product_list']) && isset($_POST['category_id']) && isset($_POST['user_id']))
{
	$jsonObj = array();
	$jsonObj1 = array();
	
	$userquery = "SELECT * FROM `tbl_user` where `id`='".$_POST['user_id']."'";
	$usersql = mysqli_query( $mysqli, $userquery)or die( mysql_error() );
	$userdata = mysqli_fetch_array($usersql);
	$discount = $userdata['discount'];
	
	$query = "select * from `tbl_product` where `status`='1' and category_id='".$_POST['category_id']."'";
	$sql = mysqli_query($mysqli,$query)or die(mysqli_error());
	while($data = mysqli_fetch_array($sql)) 
	{
		$row[ 'id' ] = $data[ 'id' ];
		$row['name'] = $data[ 'name' ];
		$row['desc'] = $data[ 'desc' ];
		if($data['image'] !='')
		{
			$row['image'] = ROOT_PATH.'image/product/'.$data['image'];
		}
		else
		{	
			$row['image'] = ROOT_PATH.'image/pravah.png';
		}
		if($data['image'] !='')
		{
			$row['thumb_image'] = ROOT_PATH.'image/product/thumb/'.$data['image'];
		}
		else
		{	
			$row['thumb_image'] = ROOT_PATH.'image/pravah.png';
		}
		
		$jsonObj1 = []; // Initialize the array
		$query_fetch = "select * from `tbl_product_detail` where product_id='".$data['id']."'";
		$sql_fetch = mysqli_query($mysqli,$query_fetch)or die(mysqli_error());
		while($fetch_data = mysqli_fetch_array($sql_fetch)) 
		{
			$discount_price = $fetch_data['price'] - ($fetch_data['price'] * ($discount/100));
			
			if (empty($fetch_data['std_paking'])) 
			{
				$std_paking = '1';
				
			}
			else
			{
				$std_paking = $fetch_data['std_paking'];
			}
			

				$row1 = [
					'product_dt_id' => $fetch_data['id'],
					'size' =>    $fetch_data['size'],
					'price' => $fetch_data['price'],
					'discount_price' => $discount_price,
					'master_pack' => $std_paking,
					'avg_weight' => $fetch_data['min_qty']
				];
				array_push($jsonObj1, $row1);
			
		}
		$row['multi_size'] = $jsonObj1;
		
		$row['date'] = $data['date'];
		array_push($jsonObj,$row);

	}
	$set[ 'status' ] = true;
	$set[ 'user_status' ] = $userdata['status'];
	$set[ 'data' ] = $jsonObj;
		
	header( 'Content-Type: application/json; charset=utf-8' );
	echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();
}

//cart enter data
if(isset($_POST['cart_add']) && isset($_POST['user_id'])) {
//$data = json_decode($_POST['cart_data'],true);
	
	$user_id = mysqli_real_escape_string($mysqli,$_POST['user_id']);
	$cart_data = mysqli_real_escape_string($mysqli, json_encode($_POST['cart_data']));
	$total = mysqli_real_escape_string($mysqli, $_POST['total']);
	
	$mainquery = "SELECT * FROM `tbl_cart` where `user_id`='".$_POST['user_id']."'";
	$mainsql = mysqli_query($mysqli,$mainquery) or die( mysql_error());
	if(mysqli_num_rows($mainsql)>0)
	{	
		$maindata = mysqli_fetch_array($mainsql);
		
		mysqli_query($mysqli,"update `tbl_cart` set 
				`user_id`='".$user_id."',
				`data`='".$cart_data."',
				`total`='".$total."',
				`date`='".$dateupdate."'
				where 
				`id`='".$maindata['id']."'
				");
		
		$last_id = $maindata['id'];
		
	}
	else
	{
		mysqli_query($mysqli,"insert into `tbl_cart` set 
				`user_id`='".$user_id."',
				`data`='".$cart_data."',
				`total`='".$total."',
				`date`='".$dateupdate."'
				");
		$last_id = mysqli_insert_id($mysqli);
		
	}
	$set[ 'status' ] = true;	
	$set[ 'cart_id' ] = $last_id;
	//$set[ 'cart_data' ] = $_POST['cart_data'];
	header( 'Content-Type: application/json; charset=utf-8');
	echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();
}
//new cart add
if(isset($_POST['new_cart_add']) && isset($_POST['user_id'])) {

	$user_id = mysqli_real_escape_string($mysqli,$_POST['user_id']);
	$cart_data = mysqli_real_escape_string($mysqli, json_encode($_POST['cart_data']));
	$total = mysqli_real_escape_string($mysqli, $_POST['total']);
	
	$mainquery = "SELECT * FROM `tbl_cart` where `user_id`='".$_POST['user_id']."'";
	$mainsql = mysqli_query($mysqli,$mainquery) or die( mysql_error());
	if(mysqli_num_rows($mainsql)>0)
	{	
		$maindata = mysqli_fetch_array($mainsql);
		
		mysqli_query($mysqli,"update `tbl_cart` set 
				`user_id`='".$_POST['user_id']."',
				`discount`='".$_POST['discount']."',
				`discount_rate`='".$_POST['discount_rate']."',
				`final_total`='".$_POST['final_total']."',
				`data`='".$cart_data."',
				`total`='".$total."',
				`date`='".$dateupdate."'
				where 
				`id`='".$maindata['id']."'
				");
		
		$last_id = $maindata['id'];
		
	}
	else
	{
		mysqli_query($mysqli,"insert into `tbl_cart` set 
				`user_id`='".$_POST['user_id']."',
				`discount`='".$_POST['discount']."',
				`discount_rate`='".$_POST['discount_rate']."',
				`final_total`='".$_POST['final_total']."',
				`data`='".$cart_data."',
				`total`='".$total."',
				`date`='".$dateupdate."'
				");
		$last_id = mysqli_insert_id($mysqli);
		
	}
	$set[ 'status' ] = true;	
	$set[ 'cart_id' ] = $last_id;
	//$set[ 'cart_data' ] = $_POST['cart_data'];
	header( 'Content-Type: application/json; charset=utf-8');
	echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();
}

//cart list data
if(isset($_POST['cart_list']) && isset($_POST['user_id'])) 
{
	$jsonObj = array();
	$mainquery = "SELECT * FROM `tbl_cart` where `user_id`='".$_POST['user_id']."' and `id`='".$_POST['cart_id']."'";
	$mainsql = mysqli_query( $mysqli, $mainquery)or die( mysql_error() );
	if(mysqli_num_rows($mainsql)>0)
	{	
		while($maindata = mysqli_fetch_array($mainsql))
		{	
			$row['id'] = $maindata['id'];
			$row['data'] = json_decode($maindata['data']);
			$row['total'] = $maindata['total'];
			array_push($jsonObj,$row);
		}
	}
	else
	{
		$set['status'] = false;
		$set['mesage'] = 'User Cart is empty.';
	}
	
	$set['err_code'] = http_response_code();
	$set['status'] = true;	
	$set['data'] = $jsonObj;
	
	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();

}

// new cart list page
if (isset($_POST['new_cart_list']) && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $jsonObj = array();

    // Fetch cart data for user
    $mainquery = "SELECT * FROM `tbl_cart` WHERE `user_id`='$user_id'";
    $mainsql = mysqli_query($mysqli, $mainquery) or die(mysqli_error($mysqli));

    if (mysqli_num_rows($mainsql) > 0) {
        while ($maindata = mysqli_fetch_assoc($mainsql)) {
            $row = array();
			$row1 = array();
            $row['id'] = $maindata['id'];
            $row['discount'] = $maindata['discount'];
			$row['discount_rate'] = $maindata['discount_rate'];
            $row['total'] = $maindata['total'];
            $row['final_total'] = $maindata['final_total'];
            $row['date'] = $maindata['date'];

            // Decode JSON cart data
          $cart_items = json_decode($maindata['data'], true);

         //$cart_items_array = json_decode($cart_items, true);

            if ($cart_items === null) {
                $set['status'] = false;
                $set['message'] = 'Invalid JSON format';
                echo json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                die();
            }
			
           // $jsonObj1 = []; // Reset for each main row

            $jsonObj = []; // Final JSON array
			$jsonObj1 = []; // Array for storing product details

			
			foreach ($cart_items as $cart) {
			
				
				$row1 = [];  // Initialize for each product
				$product_query = "SELECT * FROM `tbl_product` WHERE `id`='".$cart['product_id']."'";
				
				
    			$product_sql = mysqli_query($mysqli, $product_query) or die(mysqli_error($mysqli));
				$roduct_data = mysqli_fetch_assoc($product_sql);
				
				$row1['product_id'] = $cart['product_id'];
				$row1['name'] = $roduct_data['name'];
				$row1['number'] = $roduct_data['number'];
				$row1['items'] = []; // Initialize items array inside product

				// Iterate through items within each product
				foreach ($cart['items'] as $item) {
					$product_dt_query = "SELECT * FROM `tbl_product_detail` WHERE `id`='".$item['product_dt_id']."'";
					$product_dt_sql = mysqli_query($mysqli, $product_dt_query) or die(mysqli_error($mysqli));
					$product_dt_data = mysqli_fetch_assoc($product_dt_sql);
					
					$row1['items'][] = [
						"product_dt_id" => $item['product_dt_id'],
						"size" => $product_dt_data['size'],
						"std_paking" => $product_dt_data['std_paking'],
						"price" => $item['price'],
						"quantity" => $item['quantity'],
						"total" => $item['total']
					];
				}

				// Push to main array
				$jsonObj1[] = $row1;
			}
	

            $row['product_detail'] = $jsonObj1;
            array_push($jsonObj, $row);
        
        }

        $set['status'] = true;
        $set['message'] = 'Cart fetched successfully.';
        $set['data'] = $jsonObj;
    } else {
        $set['status'] = false;
        $set['message'] = 'User Cart is empty.';
        $set['data'] = [];
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    die();

}


//cart update
if(isset($_POST['cart_update']) && isset($_POST['cart_id'])) {
//$data = json_decode($_POST['cart_data'], true);
	
	$user_id = mysqli_real_escape_string($mysqli, $_POST['user_id']);
	$cart_data = mysqli_real_escape_string($mysqli, json_encode($_POST['cart_data']));
	$total = mysqli_real_escape_string($mysqli, $_POST['total']);
	$discount = mysqli_real_escape_string($mysqli, $_POST['discount']);
	$discount_rate = mysqli_real_escape_string($mysqli, $_POST['discount_rate']);
	$final_total = mysqli_real_escape_string($mysqli, $_POST['final_total']);
	
	mysqli_query($mysqli,"update `tbl_cart` set 
			`data`='".$cart_data."',
			`total`='".$total."',
			`discount`='".$discount."',
			`discount_rate`='".$discount_rate."',
			`final_total`='".$final_total."',
			`date`='".$dateupdate."'
			where 
			`id`='".$_POST['cart_id']."'
			");
	
	$set[ 'status' ] = true ;
	$set[ 'cart_id' ] = $_POST['cart_id'];
	header( 'Content-Type: application/json; charset=utf-8' );
	echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();
}

//page company profile page
if (isset($_POST['company_profile'])){
	$jsonObj = array();
	$query = "select * from `tbl_policy`";
	$sql = mysqli_query( $mysqli, $query )or die( mysqli_error() );
	$data = mysqli_fetch_array( $sql );
	$set[ 'status' ] = true;
	$set[ 'id' ] = $data['id'];
	$set[ 'company_profile' ] = $data['privacy_policy'];
	
	header( 'Content-Type: application/json; charset=utf-8' );
	echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();


}
//page how to use app 
if(isset($_POST['how_to_use'])){
	
	$jsonObj = array();
	$query = "select * from `tbl_how_to_use`";
	$sql_how_use = mysqli_query($mysqli,$query) or die( mysqli_error() );
	$data_how_use = mysqli_fetch_array($sql_how_use);
	$set['status'] = true;
	$set['id'] = $data_how_use['id'];
	$set['how_use'] = $data_how_use['privacy_policy'];
	
	header( 'Content-Type: application/json; charset=utf-8' );
	echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();


}
//banner list
if(isset($_POST['banner_list'])) {
	$jsonObj = array();
	$query = "select * from `tbl_banner` where `status`='1' ORDER BY `tbl_banner`.`display_order` ASC";
	$sql_how_use = mysqli_query( $mysqli, $query )or die( mysqli_error() );
	while($data = mysqli_fetch_array( $sql_how_use ))
	{
		$row['id'] = $data['id'];
		$row['category_id'] = $data['category_id'];
		$row['image'] = ROOT_PATH.'image/banner/'.$data['image'];
		array_push($jsonObj,$row);
		
	}
	$set['status'] = true ;
	$set['data'] = $jsonObj;
	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	die();


}
//order add 
if(isset($_POST['order_add']) && isset($_POST['user_id'])) {
	
	$get_curr_ord = mysqli_query($mysqli,"select * from tbl_order order by id desc limit 0,1");
	$curr_ord_row = mysqli_fetch_array($get_curr_ord);
	
	$curr_order = $curr_ord_row['order_id'];
	
	$newOrderNumber = getNextOrderNumber($curr_order);
	
	mysqli_query($mysqli,"insert into `tbl_order` set 
			`user_id`='".$_POST['user_id']."',
			`order_id`='".$newOrderNumber."',
			`data`='".$_POST['cart_data']."',
			`total`='".$_POST['total']."',
			`discount_rate`='".$_POST['discount_rate']."',
			`discount_total`='".$_POST['discount_total']."',
			`date`='".$dateupdate."'
			");
	
	$order_id = mysqli_insert_id($mysqli);
	
	//order place after cart id temove
	mysqli_query($mysqli,"DELETE FROM `tbl_cart` WHERE `id`='".$_POST['cart_id']."'");
	
	
	
	/* $url = $pdf_path.'validet.php?'.$order_id;
	 $file_name = basename($url);
	
		if (file_put_contents($file_name, file_get_contents($url)))
		{
			echo "File downloaded successfully";
		}
		else
		{
			echo "File downloading failed.";
		}*/
	
	$set['status'] = true;	
	$set['order_id'] = $newOrderNumber;	
	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();
}
//new_order add
if(isset($_POST['new_order_add']) && isset($_POST['cart_id'])) {
	
	$get_curr_ord = mysqli_query($mysqli,"select * from tbl_order order by id desc limit 0,1");
	$curr_ord_row = mysqli_fetch_array($get_curr_ord);
	
	$curr_order = $curr_ord_row['order_id'];
	
	$newOrderNumber = getNextOrderNumber($curr_order);

	$get_cart_ord = mysqli_query($mysqli,"select * from tbl_cart where id='".$_POST['cart_id']."'");
	 if(mysqli_num_rows($get_cart_ord) > 0) {
	
		$row_cart_ord = mysqli_fetch_array($get_cart_ord);

		mysqli_query($mysqli,"insert into `tbl_order` set 
				`user_id`='".$row_cart_ord['user_id']."',
				`order_id`='".$newOrderNumber."',
				`total`='".$row_cart_ord['total']."',
				`discount_rate`='".$row_cart_ord['discount_rate']."',
				`discount_total`='".$row_cart_ord['discount']."',
				`final_total`='".$row_cart_ord['final_total']."',
				`date`='".$dateupdate."'
				");

			$order_id = mysqli_insert_id($mysqli);

			$cart_items_array = json_decode($row_cart_ord['data'], true);
		 		// null found blank
				if ($cart_items_array === null) {
					$set['status'] = false;	
					$set['mesage'] = 'Error: Invalid JSON format.';

				}
		 
					$cart_items_array = json_decode($row_cart_ord['data'], true);


		foreach ($cart_items_array as $cart) {

			$product_id = $cart['product_id'];
			
				foreach ($cart['items'] as $item) {
					
					$mainquery = "SELECT * FROM `tbl_product_detail` where `product_id`='".$product_id."' and `id`='".$item['product_dt_id']."'";
					$mainsql = mysqli_query( $mysqli, $mainquery) or die( mysql_error());
					$maindata = mysqli_fetch_array($mainsql);
					
					if($maindata['std_paking'] !='')
					{
						$box_carton = $item['quantity'] / $maindata['std_paking'];
					}
					else
					{
					   $box_carton = '0';
					}
					
					mysqli_query($mysqli,"insert into `tbl_order_detail` set 
							`order_id`='".$newOrderNumber."',
							`product_id`='".$product_id."',
							`product_dtid`='".$item['product_dt_id']."',
							`price`='".$item['price']."',
							`quantity`='".$item['quantity']."',
							`box_carton`='".$box_carton."',
							`total`='".$item['total']."',
							`date`='".$dateupdate."'
							");
					
				}
		}
		//order place after cart id remove
		mysqli_query($mysqli,"DELETE FROM `tbl_cart` WHERE `id`='".$_POST['cart_id']."'");

		$set['status'] = true;	
		$set['order_id'] = $newOrderNumber;	
	 }
	else
	{
		$set['status'] = false;	
		$set['mesage'] = 'Cart id is wrong.';
	}
		 
	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace('\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();
}

//order list data
if(isset($_POST['order_list']) && isset($_POST['user_id'])) 
{
	$jsonObj = array();
	$mainquery = "SELECT * FROM `tbl_order` where `user_id`='".$_POST['user_id']."' ORDER BY `id` DESC";
	$mainsql = mysqli_query( $mysqli, $mainquery)or die( mysql_error() );
	if(mysqli_num_rows($mainsql)>0)
	{	
		while($maindata = mysqli_fetch_array($mainsql))
		{	
			//$fixed_json = preg_replace('/(?<=:)"(.*?)""(.*?)"/', '"$1\\\"$2"', $maindata['data']);
			//$fixed_json = str_replace('"1/2""', '"1/2\\""', $maindata['data']);
			$row['id'] = $maindata['order_id'];
			$row['data'] = $maindata['data'];
			$row['total'] = $maindata['total'];
			$row['discount_total'] = $maindata['discount_total'];
			$row['discount_rate'] = $maindata['discount_rate'];
			$row['status'] = $maindata['status'];
			$row['date'] = $maindata['date'];
			array_push($jsonObj,$row);
			
		}
	}
	else
	{
		$set['status'] = false;
		$set['mesage'] = 'User Cart is empty.';
		
	}
	$order_result = mysqli_query($mysqli,"SELECT * FROM tbl_order");
	$order_num_rows = mysqli_num_rows($order_result);
	
	$set['err_code'] = http_response_code();
	$set['status'] = true;	
	$set['total_order'] = $order_num_rows;
	$set['data'] = $jsonObj;
	

	header( 'Content-Type: application/json; charset=utf-8' );
	echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();

}
//new order list 
if(isset($_POST['new_order_list']) && isset($_POST['user_id'])) 
{
		$jsonObj1 = array();
		$jsonObj = array();
	
		$get_curr_user = mysqli_query($mysqli,"select * from tbl_user WHERE `id`='".$_POST['user_id']."'");
		$curr_user_row = mysqli_fetch_array($get_curr_user);

		if($curr_user_row['user_status'] == '1')
		{	
			$mainquery = "SELECT * FROM `tbl_order` WHERE `user_id`='".$_POST['user_id']."' ORDER BY `id` DESC";	
		}
		if($curr_user_row['user_status'] == '2')
		{
			$mainquery = "SELECT * FROM `tbl_order` ORDER BY `id` DESC";
		}
	
		$mainsql = mysqli_query($mysqli, $mainquery) or die(mysqli_error($mysqli));
		$order_num_rows = mysqli_num_rows($mainsql);

		if (mysqli_num_rows($mainsql) > 0) 
		{  
			while ($maindata = mysqli_fetch_array($mainsql)) 
			{  
				
				$row['id'] = $maindata['id'];
				$row['order_id'] = $maindata['order_id'];
				$row['total'] = $maindata['total'];
				$row['discount_rate'] = $maindata['discount_rate'];
				$row['discount_total'] = $maindata['discount_total'];
				$row['final_total'] = $maindata['final_total'];
				$row['date'] = $maindata['date'];	
				if($curr_user_row['user_status'] == '2')
				{
					$order_form_link = ROOT_PATH.'Api/order_form.php?order_id='.$maindata['order_id'];
				}
				else
				{
					$order_form_link = '';
				}
		$order_details = [];
        $order_id = $maindata['order_id'];

        $subquery = "SELECT * FROM `tbl_order_detail` WHERE `order_id`='$order_id' ORDER BY `id` ASC";
        $subsql = $mysqli->query($subquery);

        while ($subdata = $subsql->fetch_assoc()) {
            $product_id = $subdata['product_id'];
            $product_dtid = $subdata['product_dtid'];

            $get_product = $mysqli->query("SELECT * FROM tbl_product WHERE `id`='$product_id'");
            $get_product_row = $get_product->fetch_assoc();

            $get_product_dt = $mysqli->query("SELECT * FROM tbl_product_detail WHERE `id`='$product_dtid'");
            $get_productdt_row = $get_product_dt->fetch_assoc();

            $product_name = $get_product_row["name"];
            $number = $get_product_row["number"];
			

            if (!isset($order_details[$product_id])) {
                $order_details[$product_id] = [
                    "product_id" => $product_id,
                    "name" => $product_name,
                    "number" => $number,
                    "items" => []
                ];
            }
			
			if($subdata['box_carton'] != '0')
			{
				$quantity = $subdata['box_carton'];
			}
			else
			{
				$quantity = $subdata['quantity'];
			}

            $order_details[$product_id]['items'][] = [
                "product_dt_id" => $product_dtid,
                "size" => $get_productdt_row['size'],
                "std_paking" => $get_productdt_row['std_paking'],
                "price" => $subdata['price'],
                "quantity" => $quantity,
                "total" => $subdata['total']
            ];
        }

        $jsonObj[] = [
            "id" => $maindata['id'],
			"order_id" => $maindata['order_id'],
            "discount" => $maindata['discount_total'],
            "discount_rate" => $maindata['discount_rate'],
            "total" => $maindata['total'],
            "final_total" => $maindata['final_total'],
            "date" => $maindata['date'],
			"order_form_link" => $order_form_link,
            "product_detail" => array_values($order_details)
        ];
				
			
				
    }
			
    $response = [
        "status" => true,
		"total_order" => $order_num_rows,
        "message" => "Order fetched successfully.",
        "data" => $jsonObj
    ];
} else {
    $response = [
        "status" => true,
        "message" => "This user has no orders.",
        "data" => []
    ];
}
	echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	//echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();

}
//order dispach detail
if(isset($_POST['dispatch_order_list']) && isset($_POST['user_id'])) 
{
		$jsonObj1 = array();
	
		$get_curr_user = mysqli_query($mysqli,"select * from tbl_user WHERE `id`='".$_POST['user_id']."'");
		$curr_user_row = mysqli_fetch_array($get_curr_user);

		if($curr_user_row['user_status'] == '1')
		{	
			$mainquery = "SELECT * FROM `tbl_dispatch_order` WHERE `user_id`='".$_POST['user_id']."' and `status`='2' ORDER BY `id` DESC";
			
		}
		if($curr_user_row['user_status'] == '2')
		{
			
			$mainquery = "SELECT * FROM `tbl_dispatch_order` WHERE `status`='2' ORDER BY `id` DESC";
		}
	
		$mainsql = mysqli_query($mysqli, $mainquery) or die(mysqli_error($mysqli));
	
		$order_num_rows = mysqli_num_rows($mainsql);

		if (mysqli_num_rows($mainsql) > 0) {  
			while ($maindata = mysqli_fetch_array($mainsql)) {  
				$order = array();
				$order['order_id'] = $maindata['order_id'];
				/*$order['total'] = $maindata['total'];
				$order['discount_rate'] = $maindata['discount_rate'];
				$order['discount_total'] = $maindata['discount_total'];
				$order['final_total'] = $maindata['final_total'];*/
				if($curr_user_row['user_status'] == '2')
				{
					$order_form_link = ROOT_PATH.'Api/dispatch_order_form.php?id='.$maindata['id'];
				}
				else
				{
					$order_form_link = '';
				}
				
				$order_details = array(); // Associative array to group by product_id

				// Fetch order details
				$subquery = "SELECT * FROM `tbl_dispatch_detail` WHERE `dispatch_id`='" . $maindata['id'] . "' ORDER BY `product_id`, `product_dtid` ASC";
				$subsql = mysqli_query($mysqli, $subquery) or die(mysqli_error($mysqli));

				while($subdata = mysqli_fetch_array($subsql)) {  
					$product_id = $subdata['product_id'];

					$get_product = mysqli_query($mysqli, "SELECT * FROM tbl_product WHERE `id`='$product_id'");
					$get_product_row = mysqli_fetch_array($get_product);

					$get_product_dt = mysqli_query($mysqli, "SELECT * FROM tbl_product_detail WHERE `id`='" . $subdata['product_dtid'] . "'");
					$get_productdt_row = mysqli_fetch_array($get_product_dt);

			  		$product_name = $get_product_row["name"];
					$number = $get_product_row["number"];
					
					if($subdata['box_carton'] != '0')
					{
						$quantity = $subdata['box_carton'];
					}
					else
					{
						$quantity = $subdata['quantity'];
					}
					

					if (!isset($order_details[$product_id])) {
						$order_details[$product_id] = [
							"product_id" => $product_id,
							"name" => $product_name,
							"number" => $number,
							"items" => []
						];
					}

					$order_details[$product_id]['items'][] = [
						"product_dt_id" => $get_productdt_row['id'],
						"size" => $get_productdt_row['size'],
						"std_paking" => $get_productdt_row['std_paking'],
						"price" => $subdata['price'],
						"quantity" => $quantity,
						"total" => $subdata['total']
					];
				}

				$jsonObj[] = [
					"id" => $maindata['id'],
					"order_id" => $maindata['order_id'],
					"discount" => $maindata['discount_total'],
					"discount_rate" => $maindata['discount_rate'],
					"total" => $maindata['total'],
					"final_total" => $maindata['final_total'],
					"date" => $maindata['date'],
					"order_form_link" => $order_form_link,
					"product_detail" => array_values($order_details)
				];

            }
			
	$response = [
        "status" => true,
		"total_order" => $order_num_rows,
        "message" => "Order Dispach successfully.",
        "data" => $jsonObj
    ];
} else {
    $response = [
        "status" => true,
        "message" => "This user has no Dispach Order.",
        "data" => []
    ];
}

	echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	//echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();
	
} 
//order pending detail
if(isset($_POST['pending_order_list']) && isset($_POST['user_id'])) 
{
		$jsonObj1 = array();
		$jsonObj = array();
		$get_curr_user = mysqli_query($mysqli,"select * from tbl_user WHERE `id`='".$_POST['user_id']."'");
		$curr_user_row = mysqli_fetch_array($get_curr_user);
	
	
		if($curr_user_row['user_status'] == '1')
		{
			$extra = "o.user_id = '".$_POST['user_id']."' AND";
				
		}
		else
		{
			$extra = '';
		}
	
	
	$mainquery = "SELECT 
    o.order_id,
	o.id,
    o.user_id,
	o.date,
    od.product_id,
    od.product_dtid,
    p.name AS product_name,
    p.desc AS product_description,
    p.number,
    od.product_dtid,
    pd.size AS product_size,
    pd.price AS product_price,
    pd.std_paking AS product_std_paking,
    
    SUM(od.quantity) AS total_ordered_quantity,
    SUM(od.box_carton) AS total_ordered_box_carton,
    
    COALESCE(SUM(dd.total_dispatched_quantity), 0) AS total_dispatched_quantity,
    COALESCE(SUM(dd.total_dispatched_box_carton), 0) AS total_dispatched_box_carton,
    
    (SUM(od.quantity) - COALESCE(SUM(dd.total_dispatched_quantity), 0)) AS pending_quantity,
    (SUM(od.box_carton) - COALESCE(SUM(dd.total_dispatched_box_carton), 0)) AS pending_box_carton

FROM 
    tbl_order o
JOIN 
    tbl_order_detail od ON o.order_id = od.order_id  
JOIN 
    tbl_product p ON od.product_id = p.id
JOIN 
    tbl_product_detail pd ON od.product_dtid = pd.id

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
        WHERE status = 2
        GROUP BY 
            order_id, product_id, product_dtid
    ) dd 
    ON od.order_id = dd.order_id 
    AND od.product_id = dd.product_id 
    AND od.product_dtid = dd.product_dtid

-- ❌ EXCLUDE any order/product/variant that has status = 4 dispatch
WHERE $extra NOT EXISTS (
    SELECT 1 
    FROM tbl_dispatch_detail d
    WHERE 
        d.order_id = od.order_id
        AND d.product_id = od.product_id
        AND d.product_dtid = od.product_dtid
        AND d.status = 4
)

GROUP BY 
    o.order_id, 
    od.product_id, 
    od.product_dtid, 
    p.name, 
    p.desc, 
    pd.size, 
    pd.price, 
    pd.std_paking

HAVING 
    (pending_quantity > 0 OR pending_box_carton > 0)

ORDER BY 
    o.id DESC";	

		/*if($curr_user_row['user_status'] == '1')
		{
			$extra = "Where o.user_id = '".$_POST['user_id']."'";
		}
		else
		{
			$extra = '';
		}
	
	
	$mainquery = "SELECT 
    o.order_id,
	o.id,
    o.user_id,
	o.date,
    od.product_id,
    od.product_dtid,
    p.name AS product_name,
    p.desc AS product_description,
	p.number,
    pd.size AS product_size,
	pd.price AS product_price,
	pd.std_paking AS product_std_paking,
    SUM(od.quantity) AS total_ordered_quantity,
    SUM(od.box_carton) AS total_ordered_box_carton,
    COALESCE(SUM(dd.total_dispatched_quantity), 0) AS total_dispatched_quantity,
    COALESCE(SUM(dd.total_dispatched_box_carton), 0) AS total_dispatched_box_carton,
    (SUM(od.quantity) - COALESCE(SUM(dd.total_dispatched_quantity), 0)) AS pending_quantity,
    (SUM(od.box_carton) - COALESCE(SUM(dd.total_dispatched_box_carton), 0)) AS pending_box_carton
FROM 
    tbl_order o
JOIN 
    tbl_order_detail od ON o.order_id = od.order_id
JOIN 
    tbl_product p ON od.product_id = p.id
JOIN 
    tbl_product_detail pd ON od.product_dtid = pd.id
LEFT JOIN 
    (SELECT 
        d.order_id, 
        dd.product_id, 
        dd.product_dtid, 
        SUM(dd.quantity) AS total_dispatched_quantity,
        SUM(dd.box_carton) AS total_dispatched_box_carton
     FROM 
        tbl_dispatch_order d
     JOIN 
        tbl_dispatch_detail dd ON d.id = dd.dispatch_id
     WHERE 
        d.status != 4  
     GROUP BY 
        d.order_id, dd.product_id, dd.product_dtid
    ) dd 
    ON o.order_id = dd.order_id 
    AND od.product_id = dd.product_id 
    AND od.product_dtid = dd.product_dtid
	$extra
    AND NOT EXISTS (
        SELECT 1 FROM tbl_dispatch_order d 
        WHERE d.order_id = o.order_id 
        AND d.status = 4
    ) 
GROUP BY 
    o.order_id, o.user_id, od.product_id, od.product_dtid, p.name, p.desc, pd.size
HAVING 
    (pending_quantity > 0 OR pending_box_carton > 0)  
ORDER BY `o`.`id`  DESC";	*/
/*$mainquery = "SELECT 
    o.order_id,
    o.id,
    o.user_id,
    o.date,
    od.product_id,
    od.product_dtid,
    p.name AS product_name,
    p.desc AS product_description,
    p.number,
    pd.size AS product_size,
    pd.price AS product_price,
    pd.std_paking AS product_std_paking,
    SUM(od.quantity) AS total_ordered_quantity,
    SUM(od.box_carton) AS total_ordered_box_carton,
    COALESCE(SUM(dd.total_dispatched_quantity), 0) AS total_dispatched_quantity,
    COALESCE(SUM(dd.total_dispatched_box_carton), 0) AS total_dispatched_box_carton,
    (SUM(od.quantity) - COALESCE(SUM(dd.total_dispatched_quantity), 0)) AS pending_quantity,
    (SUM(od.box_carton) - COALESCE(SUM(dd.total_dispatched_box_carton), 0)) AS pending_box_carton
FROM 
    tbl_order o
JOIN 
    tbl_order_detail od ON o.order_id = od.order_id
JOIN 
    tbl_product p ON od.product_id = p.id
JOIN 
    tbl_product_detail pd ON od.product_dtid = pd.id
LEFT JOIN 
    (SELECT 
        d.order_id, 
        dd.product_id, 
        dd.product_dtid, 
        SUM(dd.quantity) AS total_dispatched_quantity,
        SUM(dd.box_carton) AS total_dispatched_box_carton
     FROM 
        tbl_dispatch_order d
     JOIN 
        tbl_dispatch_detail dd ON d.id = dd.dispatch_id
     WHERE 
        d.status != 4  
     GROUP BY 
        d.order_id, dd.product_id, dd.product_dtid
    ) dd 
    ON o.order_id = dd.order_id 
    AND od.product_id = dd.product_id 
    AND od.product_dtid = dd.product_dtid
WHERE 
$extra
    NOT EXISTS (
        SELECT 1 
        FROM tbl_dispatch_order d2 
        WHERE d2.order_id = o.order_id 
        AND d2.status = 4
    )
GROUP BY 
    o.order_id, o.id, o.user_id, o.date,
    od.product_id, od.product_dtid,
    p.name, p.desc, p.number,
    pd.size, pd.price, pd.std_paking
HAVING 
    (pending_quantity > 0 OR pending_box_carton > 0)  
ORDER BY 
    od.id DESC";*/
	
		$response_data = [];
		$result = mysqli_query($mysqli, $mainquery);
		$i='0';	
		while ($row = mysqli_fetch_array($result)) {
			$order_id = $row['order_id'];
			$product_id = $row['product_id'];
			$product_dtid = $row['product_dtid'];

			if($row['pending_box_carton'] != '0')
			{
				$quantity = $row['pending_box_carton'];
			}
			else
			{
				$quantity = $row['pending_quantity'];
			}
			if($curr_user_row['user_status'] == '2')
			{
				$order_form_link = ROOT_PATH.'Api/pending_order_form.php?order_id='.$order_id;
			}
			else
			{
				$order_form_link = '';
			}

			// Initialize order if not exists
			if (!isset($response_data[$order_id])) {
				$response_data[$order_id] = [
					"order_id" => $order_id,
					"discount" => '0',
					"discount_rate" => "0",
					"total" => "0",
					"final_total" => "0",
					"date" => $row['date'],
					"order_form_link" => $order_form_link,
					"product_detail" => []
				];
				$i++;
			}

			// Initialize product if not exists
			if (!isset($response_data[$order_id]['product_detail'][$product_id])) {
				$response_data[$order_id]['product_detail'][$product_id] = [
					"product_id" => $product_id,
					"name" => $row['product_name'],
					"number" => $row['number'],
					"items" => []
				];
			}

			// Add product detail items
			$response_data[$order_id]['product_detail'][$product_id]['items'][] = [
				"product_dt_id" => $product_dtid,
				"size" => $row['product_size'],
				"std_paking"=>  $row['product_std_paking'],
				"price"=> $row['product_price'],
				"quantity" => $quantity,
				"total" =>  "0"
			];
		}	

		// Convert 'product_detail' from associative array to indexed array
		foreach ($response_data as &$order) {
			$order['product_detail'] = array_values($order['product_detail']);
		}	

		if($i >= 1)
		{	
			// Convert array values to index-based array for JSON formatting
			$json_response = [
				"status" => true,
				"total_order" => $i,
				"message" => "This user Order Pending.",
				"data" => array_values($response_data)
			];

		}
		else
		{
			// Convert array values to index-based array for JSON formatting
			$json_response = [
				"status" => false,
				"message" => "This user no Order Pending.",
				"data" => array_values($response_data)
			];
		}
		
	// Output JSON response
	echo json_encode($json_response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} 
//order cancel detail
if(isset($_POST['cancel_order_list']) && isset($_POST['user_id'])) 
{
		$jsonObj1 = array();
	
		$mainquery = "SELECT * FROM `tbl_dispatch_order` WHERE `user_id`='".$_POST['user_id']."' and `status`='4' ORDER BY `id` DESC";
		$mainsql = mysqli_query($mysqli, $mainquery) or die(mysqli_error($mysqli));
	
		$order_num_rows = mysqli_num_rows($mainsql);

		if (mysqli_num_rows($mainsql) > 0) {  
			while ($maindata = mysqli_fetch_array($mainsql)) {  
				$order = array();
				$order['order_id'] = $maindata['order_id'];
				/*$order['total'] = $maindata['total'];
				$order['discount_rate'] = $maindata['discount_rate'];
				$order['discount_total'] = $maindata['discount_total'];
				$order['final_total'] = $maindata['final_total'];*/

				$order_details = array(); // Associative array to group by product_id

				// Fetch order details
				$subquery = "SELECT * FROM `tbl_dispatch_detail` WHERE `dispatch_id`='" . $maindata['id'] . "' ORDER BY `id` DESC";
				$subsql = mysqli_query($mysqli, $subquery) or die(mysqli_error($mysqli));

				while($subdata = mysqli_fetch_array($subsql)) {  
					$product_id = $subdata['product_id'];

					$get_product = mysqli_query($mysqli, "SELECT * FROM tbl_product WHERE `id`='$product_id'");
					$get_product_row = mysqli_fetch_array($get_product);

					$get_product_dt = mysqli_query($mysqli, "SELECT * FROM tbl_product_detail WHERE `id`='" . $subdata['product_dtid'] . "'");
					$get_productdt_row = mysqli_fetch_array($get_product_dt);

			  		$product_name = $get_product_row["name"];
					$number = $get_product_row["number"];
					
					
					if($subdata['box_carton'] != '0')
					{
						$quantity = $subdata['box_carton'];
					}
					else
					{
						$quantity = $subdata['quantity'];
					}
					

					if (!isset($order_details[$product_id])) {
						$order_details[$product_id] = [
							"product_id" => $product_id,
							"name" => $product_name,
							"number" => $number,
							"items" => []
						];
					}

					$order_details[$product_id]['items'][] = [
						"product_dt_id" => $get_productdt_row['id'],
						"size" => $get_productdt_row['size'],
						"std_paking" => $get_productdt_row['std_paking'],
						"price" => $subdata['price'],
						"quantity" => $quantity,
						"total" => $subdata['total']
					];
				}

				$jsonObj[] = [
					"id" => $maindata['id'],
					"order_id" => $maindata['order_id'],
					"discount" => $maindata['discount_total'],
					"discount_rate" => $maindata['discount_rate'],
					"total" => $maindata['total'],
					"final_total" => $maindata['final_total'],
					"date" => $maindata['date'],
					"product_detail" => array_values($order_details)
				];

            }
			
	$response = [
        "status" => true,
		"total_order" => $order_num_rows,
        "message" => "Order Cancel.",
        "data" => $jsonObj
    ];
} else {
    $response = [
        "status" => false,
        "message" => "This User has no Order Cancel.",
        "data" => []
    ];
}

	echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	//echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();

}

//user notification list
if(isset($_POST['notification_list']) && isset($_POST['user_id'])){
	$jsonObj = array();
	$query_cart = mysqli_query($mysqli,"SELECT * FROM `tbl_notification` WHERE (`user_id`='".$_POST['user_id']."' or `user_id`='0') ORDER BY `id` DESC");
	if(mysqli_num_rows($query_cart)>0)
	{
		while ($data = mysqli_fetch_array($query_cart)) {
			$row[ 'id' ] = $data[ 'id' ];
			$row[ 'title' ] = $data['title'];
			if($data['image'] !='')
			{
				$row['image'] = ROOT_PATH.'image/notification/'.$data['image'];
			}
			else
			{
				$row[ 'image' ] = '';
			}
			
			$row[ 'message' ] = $data['message'];
			$row[ 'create_date' ] = $data['create_date'];
			array_push($jsonObj,$row );
			
			if($data['user_view'] !='' || $data['user_view'] !='NULL')
			{
				$user_id = ','.$_POST['user_id'];
			}
			else
			{
				$user_id = $_POST['user_id'];
			}
			
			$check_user = mysqli_query($mysqli,"SELECT * FROM `tbl_notification` WHERE FIND_IN_SET('".$_POST['user_id']."',user_view) and `id`='".$data['id']."'");
			if(mysqli_num_rows($check_user)>0)
			{}
			else
			{
				$updateresult = mysqli_query($mysqli,"UPDATE tbl_notification SET 
				user_view = CONCAT(user_view, '$user_id')
				where `id`='".$data['id']."'");
			}

		}
		$set[ 'status' ] = true;	
		$set['err_code'] = http_response_code();
		$set[ 'data' ] = $jsonObj;
	}
	else
	{
		$set[ 'status' ] = false;	
		$set['err_code'] = http_response_code();
		$set[ 'message' ] = 'This user no any notification.';	
		
	}
	header( 'Content-Type: application/json; charset=utf-8' );
	echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();
}
//user profile get data
if(isset($_POST['profile_list']) && isset($_POST['user_id'])) 
{
	$mainquery = "SELECT `id`, `name`, `email`, `username`, `mobile_no`, `address`, `city_name`, `transport_name`, `transport_add`, `gst_no`, `discount`, `user_status`, `device`, `device_token`, `date`, `update_date` FROM `tbl_user` WHERE `id`='" . $_POST['user_id'] . "'";
	$mainsql = mysqli_query($mysqli, $mainquery) or die(mysqli_error($mysqli));

		if (mysqli_num_rows($mainsql) > 0) {  
			$maindata = mysqli_fetch_assoc($mainsql); // Fetch all fields as an associative array

			// Merge fetched data into response array
			$set = array_merge(['status' => true], $maindata);
		} else {
			$set = [
				'status' => false,
				'message' => 'User not found'
			];
		}


	header( 'Content-Type: application/json; charset=utf-8' );
	echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();

}
//user profile password change
if(isset($_POST['profile_change']) && isset($_POST['user_id'])){

		if (!empty($_POST['old_password']) && $_POST['old_password'] != "''") 
		{
			$extra = "and `password`='".$_POST['old_password']."'";
		}
		
		$change_password = mysqli_query($mysqli,"select * from `tbl_user` where id='".$_POST['user_id']."' $extra"); 
		if(mysqli_num_rows($change_password) > 0) 
		{	
			mysqli_query($mysqli,"update `tbl_user`set `password`='".$_POST['new_password']."',`update_date`='".$dateupdate."' where id='".$_POST['user_id']."'");	
			$set[ 'status' ] = true;	
			$set['message'] = 'User Passeord change successfully.';
			
		}
		else
		{
			$set[ 'status' ] = false;	
			$set[ 'message' ] = 'Current Passsword is wrong.';
			
		}
		
	header('Content-Type: application/json; charset=utf-8');
	echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();	
	
}
//profile data update data
if(isset($_POST['profile_update']) && isset($_POST['user_id'])){

	mysqli_query($mysqli,"update `tbl_user`set 
	`name`='".$_POST['name']."',
	`email`='".$_POST['email']."',
	`address`='".$_POST['address']."',
	`city_name`='".$_POST['city_name']."',
	`transport_name`='".$_POST['transport_name']."',
	`transport_add`='".$_POST['transport_add']."',
	`gst_no`='".$_POST['gst_no']."',
	`update_date`='".$dateupdate."'
	where id='".$_POST['user_id']."'");	
	$set[ 'status' ] = true;	
	$set['message'] = 'User Profile change successfully.';
	
	header( 'Content-Type: application/json; charset=utf-8' );
	echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();	
	
}

//product catlog pdf list
if(isset($_POST['product_pdf'])){
	
    // Database query to fetch PDF details
    $mainquery = "SELECT * FROM `tbl_how_to_use`";
    $mainsql = mysqli_query($mysqli, $mainquery) or die(mysqli_error($mysqli)); // Updated error function for mysqli

    if (mysqli_num_rows($mainsql) > 0) {
        $maindata = mysqli_fetch_array($mainsql);

        // Construct the response
        $set = [
            'status' => true,
            'pdf_file' => ROOT_PATH.'image/PDF/' . $maindata['file'],
            'date' => $maindata['date'],
        ];
    } else {
        // Response if no PDF file is found
        $set = [
            'status' => false,
            'message' => 'No PDF file found.',
        ];
    }

    // Return JSON response
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit(); // Ensures no further processing
}

//delete account
if($_POST['delete_account'] && isset($_POST['user_id']) )
{

	$update = mysqli_query($mysqli,"UPDATE tbl_user SET `status`='2',`update_date`='".$dateupdate."' where `id`='".$_POST['user_id']."'");
	$set['status'] = true;
	$set['err_code'] = http_response_code();
	
	header( 'Content-Type: application/json; charset=utf-8' );
	echo $val = str_replace( '\\/', '/', json_encode( $set, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
	die();
	
}

?>