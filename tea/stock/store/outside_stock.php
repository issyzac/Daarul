<?php
	$date=date('Y-m-d');
	$sid=$_GET['storeid'];
	$sql="SELECT storename FROM store WHERE storeid='{$sid}'";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	echo '<span style="font-size:23px;font-weight:bold">Stock in '.$row['storename'].':</span><br />';
	$sqlStock="SELECT * FROM product";
	$resultStock=mysql_query($sqlStock);
	echo "<table>"; ###TABLE
	echo "<tr style='color:#ffffff; background-color:#008010' ><th>#</th><th>Product Name</th><th>Blend</th><th>Available Quantity</th></tr>";
	$i=1;
	while($rowStock=mysql_fetch_array($resultStock)){
		$proid=$rowStock['id'];
		
		###GETTING BLEND NAME FOR THE PRODUCT
		$sql="SELECT name FROM blend WHERE id='{$rowStock['blendid']}'";
		$resultBlend=mysql_query($sql);
		$rowBlend=mysql_fetch_array($resultBlend);
		$blend=$rowBlend['name'];
		
		###DELIVERED PRODUCTS ---INPUT
		//$sql1="SELECT SUM(quantity) AS tqty FROM branch_stock WHERE date='{$date}' AND productid='{$proid}' AND branchid='{$sid}'";
		//Correction as of 24th February 2014
		$sql1="SELECT SUM(quantity) AS tqty FROM branch_stock WHERE productid='{$proid}' AND branchid='{$sid}'";
		$result1=mysql_query($sql1);
		$row1=mysql_fetch_array($result1);
		$bstock=$row1['tqty'];
		
		###OPENING STOCK ---INPUT
		//By the way for now this doesn't work... Its here just for future use.
		$sql2="SELECT openingstock FROM branch_stock_tracking WHERE date='{$date}' AND productid='{$proid}' AND storeid='{$sid}'";
		$result2=mysql_query($sql2);
		$row2=mysql_fetch_array($result2);
		$openingstock=$row2['openingstock'];
		
		###SOLD PRODUCTS ---OUTPUT
		//$sql3="SELECT SUM(quantity) AS tqty FROM branch_sales WHERE date='{$date}' AND productid='{$proid}' AND branchid='{$sid}'";
		//Correction as of 24th February 2014
		$sql3="SELECT SUM(quantity) AS tqty FROM branch_sales WHERE productid='{$proid}' AND branchid='{$sid}'";
		$result3=mysql_query($sql3);
		$row3=mysql_fetch_array($result3);
		$soldproducts=$row3['tqty'];
		
		###ON TRANSIT PRODUCTS ---INPUT
		//$sql4="SELECT SUM(quantity) AS tqty FROM on_transit WHERE date='{$date}' AND productid='{$proid}' AND transitid='{$sid}' AND delivery='0'";
		//Correction as of 24th February 2014
		$sql4="SELECT SUM(quantity) AS tqty FROM on_transit WHERE productid='{$proid}' AND transitid='{$sid}' AND delivery='0'";
		$result4=mysql_query($sql4);
		$row4=mysql_fetch_array($result4);
		$transit=$row4['tqty'];
		
		###FOR VANS AND SALES PERSONNEL ---INPUT
		//$sql5="SELECT SUM(quantity) AS tqty FROM offstock_product WHERE date='{$date}' AND productid='{$proid}' AND storeid='{$sid}'";
		//Correction as of 24th February 2014
		$sql5="SELECT SUM(quantity) AS tqty FROM offstock_product WHERE productid='{$proid}' AND storeid='{$sid}'";
		$result5=mysql_query($sql5);
		$row5=mysql_fetch_array($result5);
		$offstock=$row5['tqty'];
		
		###CALCULATIONS
		$available=$bstock+$transit+$offstock-$soldproducts+$openingstock;
		
		if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
		echo "<td>{$i}</td><td>{$rowStock['name']}</td><td>{$blend}</td><td>{$available}</td></tr>";
		$i++;
	}
	echo "</table>";
?>