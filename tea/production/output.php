<?php
	if(isset($_REQUEST['sel_blend']))
	{
		include("../db_connection.php");
		$sql = "SELECT product.name as n,product.id as i FROM product,blend WHERE blendid=blend.id AND blendid='{$_REQUEST['sel_blend']}' AND product.status='1'";
		$result = mysql_query($sql);
		echo "<option value='0'>Select Product</option>";
		if(mysql_num_rows($result) != 0)
		{
			while($row=mysql_fetch_array($result))
			{
				echo "<option value='{$row['i']}'>{$row['n']}</option>";
			}
			
		}
		exit(0);
	}
?>
<script>
function getProducts()
{
	var id = document.getElementById("sel_blend").value;
	if(id == 0)
	{
		document.getElementById("sel_product").innerHTML = "<option value='0'>Select Blend First</option>";
	}else
	{	
		var xmlhttp;
		if (window.XMLHttpRequest){	
			xmlhttp=new XMLHttpRequest();  // Initialize HTTP request
		}
	
		else {
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				document.getElementById("sel_product").innerHTML=xmlhttp.responseText;
			}
		}
	
		xmlhttp.open("GET","production/output.php?sel_blend="+id,true);
		xmlhttp.send();
	}
}
function del(val)
{
	if(confirm("Are you sure you want to delete the product?"))
	{
		document.forms[val].submit();
	}
}
function isValid(val)
{
	if(val.elements['blend'].value == "0")
	{
		alert("Please select a blend.");
		return false;
	}
	if(val.elements['product'].value == "0")
	{
		alert("Please select a product.");
		return false;
	}
	if(val.elements['quantity'].value == "")
	{
		alert("Please enter a valid quantity.");
		return false;
	}
	return true;
}
</script>
<h3>Daily Output</h3>
<?php
	$reply="";
	if(isset($_POST['blend']))
	{
		$result = mysql_query("INSERT INTO dailyoutput VALUES('{$_POST['product']}','".date("Y-m-d")."','{$_POST['quantity']}')");
		if($result)
		{
			$reply = "The product has already been added. You can edit it.<br />";
		}
		header("Location: ?opt={$_GET['opt']}&reply={$reply}");
	}
	if(isset($_POST['delete']))
	{
		$sql = "DELETE FROM dailyoutput WHERE productid='{$_POST['pid']}' AND date='".date("Y-m-d")."'";
		echo $sql;
		$result = mysql_query($sql);
		if($result)
		{
			mysql_query("INSERT INTO event VALUES('{$_SESSION['userid']}','Deleted a product in table','".date("Y-m-d")."','".date("H:i:s")."','dailyoutput','')");
			$reply = "The product has been deleted succesfully.<br />";
		}
		header("Location: ?opt={$_GET['opt']}&reply={$reply}");
	}
	if(isset($_POST['edit']))
	{
		$result = mysql_query("UPDATE dailyoutput SET quantity='{$_POST['quantity']}' WHERE productid='{$_POST['pid']}' AND date='".date("Y-m-d")."'");
		if($result)
		{
			mysql_query("INSERT INTO event VALUES('{$_SESSION['userid']}','Updated the quantity of a product in table','".date("Y-m-d")."','".date("H:i:s")."','dailyoutput','')");
			$reply = "The product has been edited succesfully.<br />";
		}
		header("Location: ?opt={$_GET['opt']}&reply={$reply}");
	}
	if(isset($_GET['reply']))
		echo $_GET['reply'];
	$result = mysql_query("SELECT * FROM blend WHERE status='1'");
	while($row=mysql_fetch_array($result))
	{
		echo "{$row['name']} <br />";
		$result2 = mysql_query("SELECT blend.id as bid,product.id as pid,product.name as pn,quantity FROM dailyoutput,product,blend WHERE blend.id=product.blendid AND product.id=productid AND date='".date("Y-m-d")."' AND product.blendid=blend.id AND product.blendid={$row['id']} AND product.status='1' AND blend.status='1'") or die(mysql_error());
		if(mysql_num_rows($result2) > 0)
		{
			$i = 1;
			echo "<table border='1'><tr class='header'><td>#</td><td width='200px'>Product</td><td width='100px'>Quantity</td><td colspan='2' width='200px'>Action</td></tr>";
			while($row2=mysql_fetch_array($result2))
			{
				if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
				echo "<td>$i</td>";
				echo "<td>{$row2['pn']}</td>";
				echo "<td>{$row2['quantity']}</td>";
				echo "<td><form name='efrm{$row2['pid']}' action='?opt=edit_output' method='post'><input type='hidden' name='bid' value='{$row2['bid']}' />
				<input type='hidden' name='pid' value='{$row2['pid']}' />
				<input type='hidden' name='quantity' value='{$row2['quantity']}' />
				<input type='hidden' value='Edit'/>
				<a href=\"javascript:document.forms['efrm{$row2['pid']}'].submit()\" ><img src='images/edit.png'alt='edit' /> Edit</a>
				</form></td>";
				echo "<td><form name='dfrm{$row2['pid']}' method='post' onsubmit='return del()'>
				<input type='hidden' name='bid' value='{$row2['bid']}' />
				<input type='hidden' name='pid' value='{$row2['pid']}' />
				<input type='hidden' name='delete' value='Delete'/>
				<a href='javascript:del(\"dfrm{$row2['pid']}\")' ><img src='images/delete.png'alt='edit' /> Delete</a>
				</form></td>";
				echo "</tr>";
				$i++;
			}
			echo "</table>";
		}else
		{
			echo "There's no output added for {$row['name']}.<br />";
		}
	}
	echo "<br />Add Output";
	$result = mysql_query("SELECT * FROM blend WHERE status='1'");
	echo "<form method='post' action='' onsubmit='return isValid(this)'><table>";
	echo "<tr><th>Blend:</th><td><select name='blend' id='sel_blend' onchange='getProducts()'><option value='0'>Select Blend</option>";
	if(mysql_num_rows($result) != 0)
	{
		while($row=mysql_fetch_array($result))
		{
			echo "<option value='{$row['id']}'>{$row['name']}</option>";
		}
		
	}
	echo "</select></td>";
	$result = mysql_query("SELECT * FROM product WHERE status='1'");
	echo "<th>Product:</th><td><select name='product' id='sel_product'><option value='0'>Select Product</option></select></td></tr>";
	$result = mysql_query("SELECT * FROM blendingmaterial WHERE status='1'");
	echo "<tr><th>Quantity:</th><td><input name='quantity' /></td><td><input type='submit' /></td></tr>";
	echo "</table></form>";
?>