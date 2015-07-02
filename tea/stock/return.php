<option value="0" >Select product</option>
<?php
	include('../db_connection.php');
	$sqlproduct="SELECT id,name FROM product where status='1' AND blendid='{$_GET['name_blend']}'";
	$sql1=mysql_query($sqlproduct);
	while($Row=mysql_fetch_array($sql1)){
	echo "<option value='{$Row['id']}'>{$Row['name']}</option>";
	}
?>