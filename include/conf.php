<?php
error_reporting(0);
session_start();

header("Content-Type: text/html;charset=UTF-8");
	
	
	if($_SERVER['HTTP_HOST']=="localhost" or $_SERVER['HTTP_HOST']=="192.168.1.125")
	{	
		//local
		 DEFINE ('DB_USER', 'root');
		 DEFINE ('DB_PASSWORD','');
		 DEFINE ('DB_HOST', 'localhost'); 
		 DEFINE ('DB_NAME', 'shop_order_app');
	}
	else
	{
		//local live
		 DEFINE ('DB_USER', 'dabsterapps_order_app');
		 DEFINE ('DB_PASSWORD', 'NC*X!Tts87Qg');
		 DEFINE ('DB_HOST', 'localhost'); //host name depends on server
		 DEFINE ('DB_NAME', 'dabsterapps_order_app');
	}

	$mysqli =mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

	if ($mysqli->connect_errno) 
	{
    	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}

	mysqli_query($mysqli,"SET NAMES 'utf8'");	 

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
define("ROOT_PATH",$protocol."pravahapp.dabstersolution.com/");

?>