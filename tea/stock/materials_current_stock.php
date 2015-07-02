<?php
	$today=date('Y-m-d');
	$tomorrow=date('Y-m-d',strtotime('tomorrow'));
	$sales=0;
	
	//CURRENT STOCK FOR MATERIALS
	echo "<h3>Materials currently in the Stock:</h3>";
	
	$sqlType="SELECT type FROM material GROUP BY type";
	$resultType=mysql_query($sqlType);
	if(mysql_num_rows($resultType)>0){
		while($typeRow=mysql_fetch_array($resultType)){
			$type=$typeRow['type'];
			echo "<span style='font-size:20px'>{$type}s</span><br />";
			$sqlProducts="select material.id as id, material.name as name, material_stock.openingstock from material_stock,material where date='{$today}' AND material.id=material_stock.materialid AND type='{$type}'";	//reading today's production for each product
			$result=mysql_query($sqlProducts) or die(mysql_error());
			$i=1;
			echo "<table border='1'>";
			echo "<tr style='color:#ffffff; background-color:#008010' ><th>#</th><th>MATERIAL NAME</th><th>AVAILABLE QUANTITY</th></tr>";
			while($row=mysql_fetch_array($result)){
				$material_id[$i]=$row['id'];
				$dataSet=mysql_query("SELECT SUM(purchaseqty) AS quantity FROM material_purchase,ordered_material WHERE material_purchase.orderid=ordered_material.orderid AND ordered_material.materialid='{$material_id[$i]}' AND material_purchase.date='{$today}'") or die(mysql_error());
				
				while($dataRow=mysql_fetch_array($dataSet)){
					$qty=$dataRow['quantity'];
				}
				
				$sqlProducts="SELECT SUM(quantity) AS quantity FROM dailyinput WHERE materialid='{$material_id[$i]}' AND date='{$today}'";	//reading today's sales
				$total=mysql_query($sqlProducts) or die(mysql_error());
				while($jumla=mysql_fetch_array($total)){
					$sales=$jumla['quantity'];
				}
				
				##MATERIALS RETURNED TO SUPPLIER
				$sqlReturn="SELECT quantity FROM material_returned WHERE materialid='{$material_id[$i]}' AND date='{$today}'";
				$resultReturn=mysql_query($sqlReturn);
				$rowReturn=mysql_fetch_array($resultReturn);
				
				//27th February 2014
				//Materials used for repacking
				$sqlRepack="SELECT SUM(repack) AS repacked FROM material_stock WHERE materialid='{$material_id[$i]}' AND date='{$today}'";
				$resultRepack=mysql_query($sqlRepack);
				$rowRepack=mysql_fetch_array($resultRepack);
				
				##CALCULATIONS
				$amount[$i]=$qty-$sales-$rowReturn['quantity'];
				$current=$amount[$i]+$row['openingstock']-/*27th February 2014*/$rowRepack['repacked'];
				
				///NIMEISHIA HAPA
				if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
				echo "<td>{$i}</td><td>{$row['name']}</td><td>{$current}</td></tr>";
				$i++;
			}
			echo "</table><br />";
		}
	}
?>