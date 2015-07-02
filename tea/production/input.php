<script>
function del(val)
{
	if(confirm("Are you sure you want to delete the Material"))
	{
		return true;
	}
	return false;
}
function isFormValid(val)
{
	if(val.elements['material'].value == 0)
	{
		alert("Please select a material to estimate.");
		return false;
	}
	if(!isFinite(val.elements['quantity'].value) || val.elements['quantity'].value == "")
	{
		alert("Please enter a valid quantity.");
		return false;
	}
	return true;
}
</script>
<?php
	$result = mysql_query("SELECT * FROM blend WHERE status='1'") or die(mysql_error());
	if(mysql_num_rows($result) > 0)
	{
	echo "<table>";
	echo "<tr>";
		while($row=mysql_fetch_array($result))
		{
			echo "<td width='150px' align='center' valign='top' >";
			echo "<h4>{$row['name']}</h4>";
			$result2 = mysql_query("SELECT product.name as pname,product.id as pid,blend.id as bid FROM product,blend WHERE product.blendid=blend.id AND blend.id='{$row['id']}' AND product.status='1'");
			while($row2=mysql_fetch_array($result2))
			{
				echo "<a href='?opt=blend_input&blendid={$row2['bid']}&productid={$row2['pid']}'>{$row2['pname']}</a><br />";
			}
			echo "</td>";
		}
	echo "</tr></table>";
	}
?>