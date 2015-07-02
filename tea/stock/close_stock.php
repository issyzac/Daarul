<?php
	$sqlDate="select max(date) as lastDate from product_stock";
	$resultDate=mysql_query($sqlDate) or die(mysql_error());
	$dateRow=mysql_fetch_array($resultDate);
	$yesterday=$dateRow['lastDate'];
	
	$today=date('Y-m-d');
	$sales=0;
	
	##TRANSACTION
	$commit=true;
	$transact=mysql_query("SET AUTOCOMMIT=0") or die(mysql_error());
	if($transact){
		mysql_query("BEGIN") or die(mysql_error());
		//CLOSING STOCK FOR PRODUCTS
		$sqlProduct="SELECT id FROM product WHERE status='1'";
		$resultProduct=mysql_query($sqlProduct) or die(mysql_error());
		
		while($productRow=mysql_fetch_array($resultProduct)){
			$product_id=$productRow['id'];
			$sqlProducts="select SUM(quantity) AS tqty from dailyoutput where date='{$yesterday}' AND productid='{$product_id}'";	//reading yesterday's production for the product
			$result=mysql_query($sqlProducts) or die(mysql_error());
			
			if(mysql_num_rows($result)>0){
				$row=mysql_fetch_array($result);
				$qty=$row['tqty'];
			}
			else $qty=0;
			
			$sqlProducts="select sum(quantity) from sales where product_id='{$product_id}' AND date='{$yesterday}'";	//reading yesterday's sales
			$total=mysql_query($sqlProducts) or die(mysql_error());
			while($jumla=mysql_fetch_array($total)){
				$sales=$jumla['sum(quantity)'];
			}
			
			##FOR PRODUCTS IN TRANSIT
			$sqlTransit="SELECT SUM(quantity) AS total FROM on_transit WHERE productid='{$product_id}' AND date='{$yesterday}'";
			$resultTransit=mysql_query($sqlTransit) or die(mysql_error());
			$totalTransitRow=mysql_fetch_array($resultTransit);
			
			##FOR RETURNED PRODUCTS (modified in 10/07/2013)
			$sql="SELECT SUM(quantity) AS tqty FROM returned_product WHERE productid='{$product_id}' AND date='{$yesterday}'";##here is where there was a problem
			$resultReturn=mysql_query($sql) or die(mysql_error());
			$returnRow=mysql_fetch_array($resultReturn);
			
			$amount=$qty+$returnRow['tqty']-$sales-$totalTransitRow['total'];	//CALCULATIONS
			
			$sql="update product_stock set closingstock=openingstock+'{$amount}' where date='{$yesterday}' AND productid='{$product_id}'";
			$succ1=mysql_query($sql) or die(mysql_error());
			if(!$succ1) $commit=false;
		}
		
		//SETTING OPENING STOCK FOR TODAY
		$result=mysql_query("select distinct(productid) from product_stock") or die(mysql_error());
		while($datarow=mysql_fetch_array($result)){
			$product_id=$datarow['productid'];
			$mySql="select closingstock from product_stock where productid='{$product_id}' AND date='{$yesterday}'";
			$tokeo=mysql_query($mySql) or die(mysql_error());
			while($data=mysql_fetch_array($tokeo)){
				$todayOpening=$data['closingstock'];
				$close="insert into product_stock(productid,closingstock,openingstock,date) values('{$product_id}','{$todayOpening}','{$todayOpening}','{$today}')";
				$succ2=mysql_query($close) or die(mysql_error());
				if(!$succ2) $commit=false;
			}
		}
		
		//CLOSING STOCK FOR MATERIALS
		$sql="select distinct(materialid) from material_stock";
		$Result=mysql_query($sql) or die(mysql_error());
		while($resultRow=mysql_fetch_array($Result)){
			$usedQty=0;
			$purchasedQty=0;
			$id=$resultRow['materialid'];
			
			$strQuery="select sum(quantity) from dailyinput where date='{$yesterday}' AND materialid='{$id}'";
			$sqlResult=mysql_query($strQuery) or die(mysql_error());
			while($data=mysql_fetch_array($sqlResult)){
				$usedQty=$data['sum(quantity)'];
			}
			
			$strQuery2="select sum(purchaseqty) from material_purchase where date='{$yesterday}' AND materialid='{$id}'";
			$sqlResult=mysql_query($strQuery) or die(mysql_error());
			while($data=mysql_fetch_array($sqlResult)){
				$purchasedQty=$data['sum(quantity)'];
			}
			####READING MATERIALS RETURNED TO SUPPLIERS
				$returned=0;
				$strQuery3="SELECT quantity  from material_returned WHERE materialid='{$id}' AND date='{$yesterday}'";
				$rst=mysql_query($strQuery3) or die(mysql_error());
				if(mysql_num_rows($rst)>0){
					$row2=mysql_fetch_array($rst);
					$returned=$row2['quantity'];
				}
				
			//27th February 2014
			//Materials used for repacking
			$sqlRepack="SELECT SUM(repack) AS repacked FROM material_stock WHERE materialid='{$material_id[$i]}' AND date='{$yesterday}'";
			$resultRepack=mysql_query($sqlRepack);
			$rowRepack=mysql_fetch_array($resultRepack);
			
			$qty=$purchasedQty-$usedQty-$returned-/*27th February 2014*/$rowRepack['repacked'];
			
			$closeSql="update material_stock set closingstock=openingstock+'{$qty}' where date='{$yesterday}' and materialid='{$id}'";
			$succ3=mysql_query($closeSql) or die(mysql_error());
			if(!$succ3) $commit=false;
			
			//SETTING OPENING STOCK FOR TODAY
			$mySql="select closingstock from material_stock where materialid='{$id}' and date='{$yesterday}'";
			$tokeo=mysql_query($mySql) or die(mysql_error());
			while($data=mysql_fetch_array($tokeo)){
				$todayOpening=$data['closingstock'];
				$close="insert into material_stock(materialid,closingstock,openingstock,date) values('{$id}','{$todayOpening}','{$todayOpening}','{$today}')";
				$succ4=mysql_query($close) or die(mysql_error());
				if(!$succ4) $commit=false;
			}
		}
		
		##FOR PRODUCTS IN STORES & BRANCHES
		$sqlStore="SELECT * FROM branch_stock_tracking WHERE date='{$yesterday}'";
		$result=mysql_query($sqlStore);
		while($row=mysql_fetch_array($result)){
			
			$sqlOut1="SELECT SUM(quantity) as total FROM returned_product WHERE date='{$yesterday}' AND productid='{$row['productid']}' AND storeid='{$row['storeid']}'";
			$resultOut1=mysql_query($sqlOut1) or die(mysql_error());
			$totalOut1=mysql_fetch_array($resultOut1);
			
			$sqlOut2="SELECT SUM(quantity) as total FROM branch_sales WHERE date='{$yesterday}' AND productid='{$row['productid']}' AND branchid='{$row['storeid']}'";
			$resultOut2=mysql_query($sqlOut2) or die(mysql_error());
			$totalOut2=mysql_fetch_array($resultOut2);
			
			$sqlIn1="SELECT SUM(quantity) as total FROM branch_stock WHERE branchid='{$row['storeid']}' AND productid='{$row['productid']}' AND date='{$yesterday}'";
			$resultIn1=mysql_query($sqlIn1) or die(mysql_error());
			$totalIn1=mysql_fetch_array($resultIn1);
			
			$sqlIn2="SELECT SUM(quantity) as total FROM offstock_product WHERE storeid='{$row['storeid']}' AND productid='{$row['productid']}' AND date='{$yesterday}'";
			$resultIn2=mysql_query($sqlIn2) or die(mysql_error());
			$totalIn2=mysql_fetch_array($resultIn2);
			
			##CALCULATIONS
			$netTotal=$row['openingstock']+$totalIn1['total']+$totalIn2['total']-$totalOut1['total']-$totalOut2['total'];
			
			##SAVING TO DATABASE
			$sqlOpen="UPDATE branch_stock_tracking SET closingstock='{$netTotal}' WHERE productid='{$row['productid']}' AND storeid='{$row['storeid']}'";
			$resultClose=mysql_query($sqlOpen) or die(mysql_error());
			if(!$resultClose) $commit=false;
			
			##OPENING STOCK
			$sqlOpen="INSERT INTO branch_stock_tracking(storeid,productid,openingstock,closingstock,date) VALUES('{$row['storeid']}','{$row['productid']}','{$netTotal}','{$netTotal}','{$today}')";
			$resultOpen=mysql_query($sqlOpen) or die(mysql_error());
			if(!$resultOpen) $commit=false;
		}
		if($commit){
			mysql_query("COMMIT") or die(mysql_error());
			echo "<h1>Stock Successfully closed;</h1><br /> <h2>Good Luck</h2>";
		}
		else mysql_query("ROLLBACK") or die(mysql_error());
	}
?>