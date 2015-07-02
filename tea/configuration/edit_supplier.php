<?php
$query = mysql_query("SELECT * FROM supplier WHERE supplierid='{$_GET['edit']}'");

	$row= mysql_fetch_array($query);
	echo "<form method='post' action='?opt=supplier&edit={$_GET['edit']}'  name='editsupplier' id='editsupplier'>";
	

	echo"<table>";
	echo "<tr><td>Supplier FirstName:</td><td><input name='name2' id='name2' value='{$row['name']}' /></td></tr>";
	
	echo "<tr><td>Contact:</td><td><input name='contact2' id='contact2' value='{$row['contact']}' /></td></tr>";
	echo "<tr><td><input type='submit' name='edit' value='Update' /></td></tr>";
	echo"</table>";
	
	echo "</form>";
?>