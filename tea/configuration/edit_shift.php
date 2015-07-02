<script language="javascript" type="text/javascript">
	function confirmUpdate(){
		var conf=confirm("Do you Really want to Save the changes made?");
		if(conf) return true;
		else return false;
	}
</script>
<?php
	$query = mysql_query("SELECT * FROM shift WHERE id='{$_GET['editid']}'");
	$row= mysql_fetch_array($query);
	echo "<form method='post' action='?opt=shift&editid={$_GET['editid']}' onsubmit='return confirmUpdate()'>";
	echo "<table>";
	echo "<tr><td>Shift Name:</td><td><input name='name' value='{$row['name']}' /></td></tr>";
	echo "<tr><td>Start Time:</td><td><input name='starttime' id='starttime' value='{$row['starttime']}' style='cursor:pointer' /></td></tr>";
	echo "<tr><td>End Time:</td><td><input name='endtime' id='endtime' value='{$row['endtime']}' style='cursor:pointer' /></td></tr>";
	echo "<tr><td>Number of Employees:</td><td><input name='noemployee' value='{$row['noemployee']}' /></td></tr>";
	echo "<tr><td colspan='2' align='right'><input type='submit' name='edit' value='Update' /></td></tr>";
	echo "</table></form>";
?>
<script type="text/javascript" language="javascript" src="jquery/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="jquery/jquery.timepicker.css" />
<script type="text/javascript" language="javascript" src="jquery/jquerytimepicker.js"></script>
<script type="text/javascript" language="javascript">
	$('#starttime').timepicker();
	$('#endtime').timepicker();
</script>
