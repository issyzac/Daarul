<?php
	include("../db_connection.php");
	$productid=$_GET['productid'];
	$today=date('Y-m-d');

	require_once "availability_function.php";
	
	echo check_availability($productid,$today);
?>