<?php
	require("../db_connection.php");
	$orderid=$_GET['orderid'];
	$sql="SELECT outstanding FROM ordered_material WHERE orderid='{$orderid}'";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	echo $row['outstanding'];
?>