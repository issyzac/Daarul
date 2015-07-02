<?php
session_start();
include('../db_connection.php');
$username = $_POST['username'];
$password = $_POST['password'];

//CHECK IF YESTERDAY'S STOCK HAS BEEN CLOSED IF NOT CLOSE IT AND OPEN TODAY'S STOCK!!!
$sql="select max(product_stock.date),max(material_stock.date) from product_stock,material_stock";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$today=date('Y-m-d');
$product_date=$row['max(product_stock.date)'];
$material_date=$row['max(material_stock.date)'];
if($today!=$product_date && $today!=$material_date){ require("../stock/close_stock.php");}

//DELETE OLDER REPORT FILES
$dir = '../report/report_files';
$dirHandle = opendir($dir);

while ($file = readdir($dirHandle)) {
	$filename = '../report/report_files/'.$file;
	$last_modified = filemtime($filename);
	$timedeff = time() - $last_modified;
	if(!is_dir($filename) && $timedeff > 86400) {	
		unlink($filename);
	}
}

//CONTINUE WITH AUTHENTICATION
$sql = "SELECT * FROM user,employee WHERE user.id = employee.id AND username = '$username' AND password = MD5('$password') ";
$query = mysql_query($sql);
if(mysql_num_rows($query) > 0){	
	while($data = mysql_fetch_array($query)){
		$_SESSION['firstname'] = $data['firstname'];
		$_SESSION['surname'] = $data['surname'];
		$_SESSION['privilege'] = $data['privilege'];
		$_SESSION['employees'] = $data['employees'];
		$_SESSION['production'] = $data['production'];
		$_SESSION['allocation'] = $data['allocation'];
		$_SESSION['stock'] = $data['stock'];
		$_SESSION['branches'] = $data['branches'];
		$_SESSION['reports'] = $data['reports'];
		$_SESSION['configurations'] = $data['configurations'];
		$_SESSION['userid']=$data['id'];
		$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Logged into the System','".date('Y-m-d')."','".date('H:i:s')."','','System')";
		mysql_query($auditSql);
	}	
	$_SESSION['username'] = $username;	
	header( 'Location: ../index.php' ); die();
} else {
	header( 'Location: ../index.php?login_status=failed' ); die();
}
?>