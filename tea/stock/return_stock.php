<?php
	if(isset($_POST['add']))
	{
		$result = mysql_query("SELECT * FROM material_returned WHERE materialid='{$_POST['materialid']}' AND date='".date("Y-m-d")."'");
		if(mysql_num_rows($result) == 0)
		{
			$sql = "INSERT INTO material_returned VALUES('{$_POST['materialid']}','".date("Y-m-d")."','{$_POST['quantity']}')";
			$result = mysql_query($sql);
			if($result)
			{
				mysql_query("INSERT INTO event VALUES('{$_SESSION['userid']}','Inserted a material to be returned.','".date("Y-m-d")."','".date("H:i:s")."','material_returned','')");
				$reply = '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />The material returned has already been added.</p><br /></center>';
			}
		}else
		{
			$row=mysql_fetch_array($result);
			$sum = $row['quantity'] + $_POST['quantity'];
			$sql = "UPDATE material_returned set quantity='$sum' WHERE materialid='{$_POST['materialid']}' AND date='".date("Y-m-d")."'";
			$result = mysql_query($sql);
			if($result)
			{
				mysql_query("INSERT INTO event VALUES('{$_SESSION['userid']}','Added a material to be returned.','".date("Y-m-d")."','".date("H:i:s")."','material_returned','')");
				$reply = '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />The material returned has already been added.</p><br /></center>';
			}
		}
		header("Location: ?opt=return_stock&reply={$reply}");
	}else if(isset($_POST['edit']))
	{
			$sql = "UPDATE material_returned set quantity='{$_POST['quantity']}' WHERE materialid='{$_POST['materialid']}' AND date='".date("Y-m-d")."'";
			$result = mysql_query($sql);
			if($result)
			{
				mysql_query("INSERT INTO event VALUES('{$_SESSION['userid']}','Changed the material to be returned.','".date("Y-m-d")."','".date("H:i:s")."','material_returned','')");
				$reply = '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />The material returned has already been changed.</p><br /></center>';
			}
		header("Location: ?opt=return_stock&reply={$reply}");
	}else if(isset($_POST['delete']))
	{
		$sql = "DELETE FROM material_returned WHERE materialid='{$_POST['materialid']}' AND date='".date("Y-m-d")."'";
		$result = mysql_query($sql);
		if($result)
		{
			mysql_query("INSERT INTO event VALUES('{$_SESSION['userid']}','Deleted a returned material','".date("Y-m-d")."','".date("H:i:s")."','material_returned','')");
			$reply = '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />The entry for returned Material has been Deleted Sucessfully.</p><br /></center>';
		}
		header("Location: ?opt=return_stock&reply={$reply}");
	}
	if(isset($_GET['reply']))
	{
		echo $_GET['reply'];
	}
?>
<script>
function del(val)
{
	if(confirm("Are you sure you want to delete this entry?"))
	{
		document.forms[val].submit();
		return true;
	}
	//return false;
}
function isFormValid(val)
{
	if(val.elements['material'].value == 0)
	{
		alert("Please select a material.");
		return false;
	}
	if(!isFinite(val.elements['quantity'].value) || val.elements['quantity'].value == "")
	{
		alert("Please enter a valid quantity.");
		return false;
	}
	return true;
}
function validateData(){
	var qty=document.forms['frmreturn']['quantity'].value.toString();
	if(qty.length==0){
		alert("You must enter a valid Quantity");
		return false;
	}
	else return true;
}
</script>
<center><h2>Returned Stock</h2>
<?php
	$result = mysql_query("SELECT * FROM material_returned,material WHERE material.id=materialid AND date='".date("Y-m-d")."'") or die(mysql_error());
	if(mysql_num_rows($result) > 0)
	{
	echo "<table>";
	echo "<tr style='color:#ffffff; background-color:#008010' ><td>Material Name</td><td>Material Type</td><td>Returned Quantity</td><td colspan='2'>Action</td></tr>";
	$i=1;
		while($row=mysql_fetch_array($result))
		{
			if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
			echo "<td width='150px' align='center' valign='top' >";
			echo "<h4>{$row['name']}</h4>";
			echo "</td>";
			echo "<td>";
			echo "<h4>{$row['type']}</h4>";
			echo "</td>";
			echo "<td>";
			echo "<h4>{$row['quantity']}</h4>";
			echo "</td>";
			echo "<td><form name='efrm{$row['materialid']}' action='?opt=edit_return_stock&materialid={$row['materialid']}' method='post'>
						<input type='hidden' name='materialid' value='{$row['materialid']}' />
						<input type='hidden' name='material' value='{$row['name']}' />
						<input type='hidden' name='quantity' value='{$row['quantity']}' />
						<input type='hidden' name='edit' value='edit' />
						<a href=\"javascript:document.forms['efrm{$row['materialid']}'].submit()\" >
						<img src='images/edit.png'alt='edit' /> Edit</a>
						</form></td>";
						echo "<td>
						<form name='dfrm{$row['materialid']}' onsubmit='return del(this)' method='post'>
						<input type='hidden' name='materialid' value='{$row['materialid']}' />
						<input type='hidden' name='delete' value='delete' />
						<a href='javascript:del(\"dfrm{$row['materialid']}\")' ><img src='images/delete.png'alt='edit' /> Delete</a>
						</form></td>";
			echo "</tr>";
			$i++;
		}
	echo "</tr></table>";
	}
?>
<form method="post" action="#" onsubmit="return validateData()" name="frmreturn" id="frmreturn" >
<select name="materialid">
<?php
	$result = mysql_query("SELECT * FROM material WHERE status='1'") or die(mysql_error());
	while($row=mysql_fetch_array($result))
	{
		echo "<option value='{$row['id']}'>{$row['name']}</option>";
	}
?>
</select>

Quantity:<input type='text' name='quantity'/>
<input type='submit' name='add' value="Submit"/>
</form></center>