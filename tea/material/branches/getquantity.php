<?php
	include("../db_connection.php");
	$proid=$_GET['pro'];
	$branchid=$_GET['bra'];
	$sql1="SELECT SUM(quantity) AS total FROM branch_stock  WHERE productid='{$proid}' AND branchid='{$branchid}'";
	$sql2="SELECT SUM(quantity) AS total FROM branch_sales WHERE productid='{$proid}' AND branchid='{$branchid}'";
	$totalResult=mysql_query($sql1);
	$row1=mysql_fetch_array($totalResult);
	$totalReceived=$row1['total'];
	$totalSold=mysql_query($sql2);
	$row2=mysql_fetch_array($totalSold);
	$totalSold=$row2['total'];
	$available=$totalReceived-$totalSold;
	echo $available;
?>