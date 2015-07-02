<?php
$sql = "SELECT material.name as mn,material.type as mt,product.name as pn,blend.name as bn FROM dailyinput,material,blend,product WHERE material.id=materialid AND materialid='{$_POST['material']}' AND productid='{$_POST['product']}' AND date='".date("Y-m-d")."' AND blend.id=blendid AND productid=product.id";
$result = mysql_query($sql);
$row=mysql_fetch_array($result);
echo "<h3>Edit {$row['mt']} {$row['mn']} of Product {$row['pn']} in {$row['bn']}</h3>";
echo "<form action='?opt=blend_input&blendid={$_GET['blendid']}&productid={$_GET['productid']}' method='post'>
		<input type='hidden' name='blend' value='{$_POST['blend']}' />
		<input type='hidden' name='product' value='{$_POST['product']}' />
		<input type='hidden' name='material' value='{$_POST['material']}' />
		Quantity:<input type='text' name='quantity' value='{$_POST['quantity']}' />
		<input type='submit' name='edit' value='Edit'/>
	  </form>";
?>