<?php
	include("../db_connection.php");
	session_start();
	session_destroy();
	$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_GET['userid']}','Logged out of the System','".date('Y-m-d')."','".date('H:i:s')."','','System')";
	mysql_query($auditSql);
	header( 'Location: ../index.php' ); die();
?>
eader