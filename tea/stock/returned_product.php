<script type="text/javascript" language="javascript">
function getProducts()
{
	var id = document.getElementById("name_blend").value;
	if(id == 0)
	{
		document.getElementById("name_product").innerHTML = "<option value='0'>Select Blend First</option>";
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
				document.getElementById("name_product").innerHTML=xmlhttp.responseText;
			}
		}
	
		xmlhttp.open("GET","stock/return.php?name_blend="+id,true);
		xmlhttp.send();
	}
}
</script>
<form action="#" method="post">
<fieldset>
<legend align="center">Returned_Product</legend>
<table>
<tr><td>Blend:</td>
<td><select id="name_blend" name="name_blend" onchange='getProducts()'  >
	<option value="0" >Select Blend </option>
	<?php
		$sql="SELECT * FROM blend where status='1'";
		$result=mysql_query($sql);
		while($Row=mysql_fetch_array($result)){
			echo "<option value='{$Row['id']}'>{$Row['name']}</option>";
		}
	?>
</select>
</td>
<td>Product:</td>
<td><select id="name_product" name="name_product"  >
	<option value="0" >Select product</option>
</select></td>

<td>Quantity:</td><td><input type="text" name="Qty" size="10" id="Qty"/></td></tr>
<tr><td>From:</td>
<td>
	<select id="from" name="from" >
		<option value="0" >--Select One--</option>
		<option value="0" >--Direct Sales--</option>
		<?php
			$sql="SELECT storeid,storename FROM store";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
				echo "<option value='{$row['storeid']}' >{$row['storename']}</option>";
			}
		?>
	</select>
</td>
<td>Date:</td><td><input type="text"  name="ADate" id="ADate" placeholder="Select Date" size="10" style="cursor:pointer"></td>
<td align="right" colspan="2"><input type="submit" value="submit" name="submit" id="submit"/></td></tr>
<link rel="stylesheet" href="jquery/jquery.datepick.css"/>
<script type="text/javascript" language="javascript" src="jquery/jquery.js" ></script>
<script type="text/javascript" language="javascript" src="jquery/jquery.datepick.js" ></script>
<script type="text/javascript" language="javascript" >
	$('#ADate').datepick({dateFormat: 'yyyy-mm-dd', maxDate: new Date()});
</script>
</table>
</fieldset>

</form>
<?php
if(isset($_POST['submit'])){
	$blend=$_POST['name_blend'];
	$product=$_POST['name_product'];
	$qty=$_POST['Qty'];
	$store=$_POST['from'];
	$date=$_POST['ADate'];

	$query="INSERT INTO returned_product(blendid,productid,storeid,quantity,date) VALUES('{$blend}','{$product}','{$store}','{$qty}','{$date}')";
	$result=mysql_query($query) or die(mysql_error());
	
	##BADILISHA TREND YA OPENING STOCK NA CLOSING STOCK
	if($result){
		$sql="UPDATE product_stock SET closingstock=closingstock+{$qty} WHERE productid='{$product}' AND date='{$date}'";
		$res=mysql_query($sql) or die(mysql_error());
		if($res){
			$sql="UPDATE product_stock SET closingstock=closingstock+{$qty},openingstock=openingstock+{$qty} WHERE productid='{$product}' AND date>'{$date}'";
			$res=mysql_query($sql) or die(mysql_error());
		}
	}
}
if(isset($_GET['delete'])){

	###FIRST UPDATE CLOSING AND OPENING STOCK
	$sql="SELECT quantity,date,productid FROM returned_product WHERE retid='{$_GET['delid']}'";
	$res=mysql_query($sql);
	$row=mysql_fetch_array($res);
	$date=$row['date'];
	$qty=$row['quantity'];
	$product=$row['productid'];
	$sql="UPDATE product_stock SET closingstock=closingstock-{$qty} WHERE productid='{$product}' AND date='{$date}'";
	$res=mysql_query($sql) or die(mysql_error());
	if($res){
		$sql="UPDATE product_stock SET closingstock=closingstock-{$qty},openingstock=openingstock-{$qty} WHERE productid='{$product}' AND date>'{$date}'";
		$res=mysql_query($sql) or die(mysql_error());
	}
	
	###DELETING
	if($res){
		$query = mysql_query("DELETE FROM returned_product WHERE retid='{$_GET['delid']}'") or die(mysql_error());
	}
}
if(isset($_GET['edit'])){
	###FIRST UPDATE CLOSING AND OPENING STOCK
	$sql="SELECT quantity,date,productid FROM returned_product WHERE retid='{$_GET['editid']}'";
	$res=mysql_query($sql);
	$row=mysql_fetch_array($res);
	$date=$row['date'];
	$qty=$_POST['qty1']-$row['quantity'];
	$product=$row['productid'];
	$sql="UPDATE product_stock SET closingstock=closingstock+{$qty} WHERE productid='{$product}' AND date='{$date}'";
	$res=mysql_query($sql) or die(mysql_error());
	if($res){
		$sql="UPDATE product_stock SET closingstock=closingstock+{$qty},openingstock=openingstock+{$qty} WHERE productid='{$product}' AND date>'{$date}'";
		$res=mysql_query($sql) or die(mysql_error());
	}
	###UPDATING
	if($res){
		$query = mysql_query("UPDATE returned_product SET quantity='{$_POST['qty1']}' WHERE retid='{$_GET['editid']}'");
	}
}
	$sel=mysql_query("select returned_product.retid,returned_product.blendid as bid,returned_product.productid as pid,blend.name as bname,product.name as pname,quantity from blend,product,returned_product where blend.id=returned_product.blendid AND product.id=productid ") or die(mysql_error());
	echo "<table border='1'><tr style='color:#ffffff; background-color:#008010'><td>#</td><td style='width:150px'>Blend Name</td><td style='width:200px'>Product Name</td> <td style='width:150px'>Returned Quantity</td><td colspan='2' align = 'center' style='width:200px'>Action</td></tr>";
	$i = 1;
	while($row= mysql_fetch_array($sel)){
		if($i% 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
		echo "<td>$i</td>";
		echo "<td>{$row['bname']}</td>";
		echo "<td>{$row['pname']}</td>";
		echo "<td>{$row['quantity']}</td>";
		echo "<td><a href='?opt=edit_return_product&edit&editid={$row['retid']}'><img src='images/edit.png'alt='edit' />Edit</a></td>";
		echo "<td><a href='?opt=returned_product&delete&delid={$row['retid']}' ><img src='images/delete.png' alt=delete/> Delete</a></td>";
		echo "</tr>";
		$i++;
	}
	echo "</table>";	
?>