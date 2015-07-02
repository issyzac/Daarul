<?php
	include("../db_connection.php");
	$sql1="SELECT username FROM user WHERE id='{$_GET['empid']}'";
	$result1=mysql_query($sql1);
	$row=mysql_fetch_array($result1);
	$username=$row['username'];
	$paswd=MD5($username);
	$sql2="UPDATE user SET password='{$paswd}' WHERE id='{$_GET['empid']}'";
	$result2=mysql_query($sql2);
	if($result2){
		echo "<fieldset><span>Password Update Successful, new Password is <span style='color:red'>{$username}</span><br />Please Inform this user to change it during a first successful login<br /></span></fieldset>";
	}
	else echo "<fieldset>Failed to Update Password for this User</fieldset>";
?>