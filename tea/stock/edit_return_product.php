<?php
	$jibu = mysql_query("SELECT  quantity from returned_product WHERE retid='{$_GET['editid']}'");
	$row= mysql_fetch_array($jibu);
	//secho s$qty;
	echo "<form method='post' action='?opt=returned_product&edit&editid={$_GET['editid']}'  name='edit_returned_product' id='returnedproduct'>";
	echo "Returned Quantity:<input name='qty1' id='qty1' value='{$row['quantity']}' />";
	echo "<input type='submit' name='edit' value='Update' />";
	echo "</form>";
?>
