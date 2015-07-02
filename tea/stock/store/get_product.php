<?php
	include("../../db_connection.php");
	$blendId=$_GET['blendid'];
	if($blendId!=0){
		$sqlProduct="SELECT name,id FROM product WHERE blendid='{$blendId}' AND status='1'";
		$resultProduct=mysql_query($sqlProduct);
		$i=1;
		echo "<table>";
		echo "<tr style='color:#ffffff; background-color:#008010' ><th>#</th><th>Product Name</th><th>Transfer Amount</th></tr>";
		while($row=mysql_fetch_array($resultProduct)){
			if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
			echo "<td>{$i}</td><td>{$row['name']}</td><td><input type='text' name='qty{$i}' id='qty{$i}' /></td></tr>";
			echo "<input type='hidden' id='id{$i}' name='id{$i}' value='{$row['id']}' />";
			$i++;
		}
		echo "<input type='hidden' name='counter' id='counter' value='{$i}' />";
		if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
		echo '<td colspan="3" align="right" ><input type="submit" name="submit" value="Transfer" id="submit" /></td></tr>';
		echo "</table>";
	}
?>