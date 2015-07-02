<?php
	function check_availability($productid,$today){
		$available=0;
		$sqlProducts="SELECT openingstock FROM product_stock WHERE date='{$today}' AND productid='{$productid}'";
		$result=mysql_query($sqlProducts);
		while($row=mysql_fetch_array($result)){
			$dataSet=mysql_query("SELECT SUM(quantity) AS sumqty FROM dailyoutput WHERE productid='{$productid}' AND date='{$today}'");
			
			while($dataRow=mysql_fetch_array($dataSet)){
				$qty=$dataRow['sumqty'];
			}
			
			$sqlProducts="SELECT sum(quantity) FROM sales WHERE product_id='{$productid}' AND date='{$today}'";	//reading today's sales
			$total=mysql_query($sqlProducts);
			while($jumla=mysql_fetch_array($total)){
				$sales=$jumla['sum(quantity)'];
			}
			
			##FOR PRODUCTS IN TRANSIT
			$sqlTransit="SELECT SUM(quantity) AS total FROM on_transit WHERE productid='{$productid}' AND date='{$today}'";
			$resultTransit=mysql_query($sqlTransit);
			$rowTransit=mysql_fetch_array($resultTransit);
			
			$amount=$qty-$sales-$rowTransit['total'];	//CALCULATIONS	
			$available=$amount+$row['openingstock'];
		}
	
		return $available;
	}
		
?>