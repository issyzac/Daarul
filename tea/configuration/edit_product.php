<script language="javascript" type="text/javascript">
	function confirmUpdate(){
		if(confirm("Save changes made?")) return true;
		else return false;
	}
</script>
<?php
	$query = mysql_query("SELECT * FROM product WHERE id='{$_GET['editid']}'");
	$row= mysql_fetch_array($query);
	echo "<form method='post' action='?opt=product&editid={$_GET['editid']}&viewid={$_GET['viewid']}' onsubmit='return confirmUpdate()'>";
	echo '<table>';
	echo "<tr><td>Product Name:</td><td><input name='name' value='{$row['name']}' /></td></tr>";
	echo "<tr><td>Critical Level:</td><td><input type='text' value='{$row['criticallevel']}' name='critical' id='critical' /></td></tr>";
	echo "<tr><td colspan='2' align='right' ><input type='submit' name='edit' value='Update' /></td></tr>";
	echo "<input type='hidden' name='editid' value='{$_GET['editid']}' />";
	echo '</table>';
	echo "</form>";
?>

