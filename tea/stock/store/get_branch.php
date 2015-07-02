<?php
	include("../../db_connection.php");
	$sqlbt="SELECT * FROM store WHERE type='1'";
	$resultbt=mysql_query($sqlbt);
	while($row=mysql_fetch_array($resultbt)){
		echo "<option value='{$row['storeid']}' >{$row['storename']}</option>";
	}
?>