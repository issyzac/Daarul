<?php
$result = mysql_query("SELECT blend.name as bn,blend.id as bid,product.id as pid,product.name as pn,quantity FROM dailyoutput,product,blend WHERE blend.id=blendid AND product.id=productid AND date='".date("Y-m-d")."' AND blendid={$_POST['bid']}") or die(mysql_error());
$row=mysql_fetch_array($result);
echo "<h3>Edit Output of Product {$row['pn']} in {$row['bn']}</h3>";
echo "<form action='?opt=output' method='post'>
		<input type='hidden' name='bid' value='{$_POST['bid']}' />
		<input type='hidden' name='pid' value='{$_POST['pid']}' />
		Quantity:<input type='text' name='quantity' value='{$_POST['quantity']}' />
		<input type='submit' name='edit' value='Edit'/>
	  </form>";
?>