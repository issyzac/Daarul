<?php
	$today=date('Y-m-d');
	$tomorrow=date('Y-m-d',strtotime('tomorrow'));
	$sales=0;
	$qty=0;
	
	//CURRENT STOCK FOR PRODUCTS
	echo "<h3>Products currently in the Stock:</h3>";
	
	$sqlBlend="SELECT id,name FROM blend WHERE status=1";
	$resultBlend=mysql_query($sqlBlend);
	if(mysql_num_rows($resultBlend)>0){
		while($blendRow=mysql_fetch_array($resultBlend)){
			$bid=$blendRow['id'];
			$bname=$blendRow['name'];
			echo "<span style='font-size:20px'>{$bname}</span><br />";
			$sqlProducts="SELECT product.id AS id,product.name AS name,openingstock FROM product_stock,product WHERE date='{$today}' AND product.id=product_stock.productid AND blendid='{$bid}'";	//reading today's production for each product
			$result=mysql_query($sqlProducts) or die(mysql_error());
			$i=1;
			echo "<table border='1'>";
			echo "<tr style='color:#ffffff; background-color:#008010' ><th>#</th><th>PRODUCT NAME</th><th>AVAILABLE QUANTITY</th></tr>";
			while($row=mysql_fetch_array($result)){
				$product_id[$i]=$row['id'];
				$dataSet=mysql_query("select quantity FROM dailyoutput WHERE productid='{$product_id[$i]}' AND date='{$today}'") or die(mysql_error());
				
				while($dataRow=mysql_fetch_array($dataSet)){
					$qty=$dataRow['quantity'];
				}
				
				$sqlProducts="SELECT sum(quantity) FROM sales WHERE product_id='{$product_id[$i]}' AND date='{$today}'";	//reading today's sales
				$total=mysql_query($sqlProducts) or die(mysql_error());
				while($jumla=mysql_fetch_array($total)){
					$sales=$jumla['sum(quantity)'];
				}
				
				##FOR PRODUCTS IN TRANSIT
				$sqlTransit="SELECT SUM(quantity) AS total FROM on_transit WHERE productid='{$product_id[$i]}' AND date='{$today}'";
				$resultTransit=mysql_query($sqlTransit);
				$rowTransit=mysql_fetch_array($resultTransit);
				
				##FOR PRODUCTS IN SALES VANS OR SALES PERSONNELS
				$sqlSVSP="SELECT SUM(quantity) AS total FROM offstock_product WHERE productid='{$product_id[$i]}' AND date='{$today}'";
				$resultSVSP=mysql_query($sqlSVSP);
				$rowSVSP=mysql_fetch_array($resultSVSP);
				
				$amount[$i]=$qty-$sales-$rowTransit['total']-$rowSVSP['total'];	//CALCULATIONS	
				$qty=0;
				$current=$amount[$i]+$row['openingstock'];
				
				///NIMEISHIA HAPA
				if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
				echo "<td>{$i}</td><td>{$row['name']}</td><td>{$current}</td></tr>";
				$i++;
			}
			echo "</table><br />";
		}
	}
?>