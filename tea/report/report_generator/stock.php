<?php
	$objPHPExcel->setActiveSheetIndex(0);
	//$sheet = $objPHPExcel->createSheet();
	$sheet = $objPHPExcel->getActiveSheet();
	$sheet->setTitle("Products");
	
	//Start and end dates
	$start_date=$_GET['start_date'];
	$end_date=$_GET['end_date'];
	$branchid=$_GET['branchid'];
	
	//GETTING BRANCH NAME
	$sql="SELECT storename FROM store WHERE storeid='{$branchid}'";
	$rowName=mysql_fetch_array(mysql_query($sql));
	$storeName=$rowName['storename'];
	
	$sheet->setCellValue("A1","KYIMBILA TEA PACKING Co. LIMITED");
	$sheet->setCellValue("A2","STOCK REPORT FOR THE PERIOD STARTING ON ")->setCellValue("E2",date('d-M-Y',strtotime($start_date)))->setCellValue("F2","TO ")->setCellValue("G2",date('d-M-Y',strtotime($end_date)))->setCellValue("H2","FOR ".$storeName);
	
	if($branchid==0){
		include("materials.php");
		//FOR MAIN BRANCH
		$sheet->setCellValue("A3","SKU Name")->setCellValue("B3","Packet Size")->setCellValue("C3","Weight per Carton")->setCellValue("D3","Opening Stock")->setCellValue("F3","Production during the period")->setCellValue("H3","Sales during the period")->setCellValue("J3","Transfered to Branches")->setCellValue("L3","Closing Stock");
		$sheet->setCellValue("D4","Cartons")->setCellValue("E4","Kgs")->setCellValue("F4","Cartons")->setCellValue("G4","Kgs")->setCellValue("H4","Cartons")->setCellValue("I4","Kgs")->setCellValue("J4","Cartons")->setCellValue("K4","Kgs")->setCellValue("L4","Cartons")->setCellValue("M4","Kgs");
		
		//Merging cells
		$sheet->mergeCells("A1:D1");
		$sheet->mergeCells("A2:D2");
		$sheet->mergeCells("D3:E3");
		$sheet->mergeCells("F3:G3");
		$sheet->mergeCells("H3:I3");
		$sheet->mergeCells("J3:K3");
		$sheet->mergeCells("L3:M3");
		$sheet->mergeCells("A3:A4");
		$sheet->mergeCells("B3:B4");
		$sheet->mergeCells("C3:C4");
		
		$excelRow=5;
		$sql="SELECT blend.id AS bid,blend.name AS bname,product.id AS pid,product.name AS pname,wtpercarton FROM blend INNER JOIN product ON blend.id=product.blendid ORDER BY bid";
		$resultBlend=mysql_query($sql) or die(mysql_error());
		while($row=mysql_fetch_array($resultBlend)){
			//Getting opening stock for the product in this period
			$sqlOpening="SELECT openingstock FROM product_stock WHERE productid={$row['pid']} AND date='{$start_date}'";
			$rowOpening=mysql_fetch_array(mysql_query($sqlOpening));
			$openingStock=$rowOpening['openingstock'];
			
			//Getting total production of the product during this period
			$sqlTotalProduction="SELECT SUM(quantity) AS total FROM dailyoutput WHERE productid={$row['pid']} AND date BETWEEN '{$start_date}' AND '{$end_date}'";
			$rowProduction=mysql_fetch_array(mysql_query($sqlTotalProduction));
			$totalProduction=$rowProduction['total'];
			
			//Getting total sales of the product during the period
			$sqlSales="SELECT SUM(quantity) AS total FROM sales WHERE Product_ID={$row['pid']} AND date BETWEEN '{$start_date}' AND '{$end_date}'";
			$rowSales=mysql_fetch_array(mysql_query($sqlSales));
			$totalSales=$rowSales['total'];
			
			//TRANSFERED PRODUCTS TO BRANCHES
			$sqlTransit="SELECT SUM(quantity) AS total FROM on_transit WHERE productid='{$row['pid']}' AND date BETWEEN '{$start_date}' AND '{$end_date}'";
			$totalTransitRow=mysql_fetch_array(mysql_query($sqlTransit));
			$transfered=$totalTransitRow['total'];
			
			//Getting closing stock for the product in this period
			$sqlClosing="SELECT closingstock FROM product_stock WHERE productid={$row['pid']} AND date='{$end_date}'";
			$rowClosing=mysql_fetch_array(mysql_query($sqlClosing));
			$closingStock=$rowClosing['closingstock'];
			
			$sheet->setCellValue("A".$excelRow,$row['bname'])->setCellValue("B".$excelRow,$row['pname'])->setCellValue("C".$excelRow,$row['wtpercarton'])->setCellValue("D".$excelRow,$openingStock)->setCellValue("E".$excelRow,"=+D".$excelRow."*C".$excelRow)->setCellValue("F".$excelRow,$totalProduction)->setCellValue("G".$excelRow,"=+F".$excelRow."*C".$excelRow)->setCellValue("H".$excelRow,$totalSales)->setCellValue("I".$excelRow,"=+H".$excelRow."*C".$excelRow)->setCellValue("J".$excelRow,$transfered)->setCellValue("K".$excelRow,"=+J".$excelRow."*C".$excelRow)->setCellValue("L".$excelRow,$closingStock)->setCellValue("M".$excelRow,"=+".$excelRow."*C".$excelRow);
			$excelRow++;
		}
		$lastRow=$excelRow-1;
		$sheet->setCellValue("D".$excelRow,"=SUM(D5:D".$lastRow.")")->setCellValue("E".$excelRow,"=SUM(E5:E".$lastRow.")")->setCellValue("F".$excelRow,"=SUM(F5:F".$lastRow.")")->setCellValue("G".$excelRow,"=SUM(G5:G".$lastRow.")")->setCellValue("H".$excelRow,"=SUM(H5:H".$lastRow.")")->setCellValue("I".$excelRow,"=SUM(I5:I".$lastRow.")")->setCellValue("J".$excelRow,"=SUM(J5:J".$lastRow.")")->setCellValue("K".$excelRow,"=SUM(K5:K".$lastRow.")")->setCellValue("L".$excelRow,"=SUM(L5:L".$lastRow.")")->setCellValue("M".$excelRow,"=SUM(M5:M".$lastRow.")");
		
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getAlignment()->setWrapText(true);
		}
	else{
		//FOR OTHER BRANCHES
		$sheet->setCellValue("A3","SKU Name")->setCellValue("B3","Packet Size")->setCellValue("C3","Weight per Carton")->setCellValue("D3","Opening Stock")->setCellValue("F3","Received from Main Branch")->setCellValue("H3","Sales during the period")->setCellValue("J3","Closing Stock");
		$sheet->setCellValue("D4","Cartons")->setCellValue("E4","Kgs")->setCellValue("F4","Cartons")->setCellValue("G4","Kgs")->setCellValue("H4","Cartons")->setCellValue("I4","Kgs")->setCellValue("J4","Cartons")->setCellValue("K4","Kgs");
		
		//Merging cells
		$sheet->mergeCells("A1:D1");
		$sheet->mergeCells("A2:D2");
		$sheet->mergeCells("D3:E3");
		$sheet->mergeCells("F3:G3");
		$sheet->mergeCells("H3:I3");
		$sheet->mergeCells("J3:K3");
		$sheet->mergeCells("A3:A4");
		$sheet->mergeCells("B3:B4");
		$sheet->mergeCells("C3:C4");
		
		$excelRow=5;
		$sql="SELECT blend.id AS bid,blend.name AS bname,product.id AS pid,product.name AS pname,wtpercarton FROM blend INNER JOIN product ON blend.id=product.blendid ORDER BY bid";
		$resultBlend=mysql_query($sql) or die(mysql_error());
		while($row=mysql_fetch_array($resultBlend)){
			//Getting opening stock for the product in this period
			$sqlOpening="SELECT openingstock FROM branch_stock_tracking WHERE productid={$row['pid']} AND date='{$start_date}' AND storeid='{$branchid}'";
			$rowOpening=mysql_fetch_array(mysql_query($sqlOpening));
			$openingStock=$rowOpening['openingstock'];
			
			//Getting total products received during this period
			$sqlTotalProduction="SELECT SUM(quantity) AS total FROM branch_stock WHERE productid={$row['pid']} AND branchid='{$branchid}}' AND date BETWEEN '{$start_date}' AND '{$end_date}'";
			$rowProduction=mysql_fetch_array(mysql_query($sqlTotalProduction));
			$totalProduction=$rowProduction['total'];
			
			//Getting total sales of the product during the period
			$sqlSales="SELECT SUM(quantity) AS total FROM branch_sales WHERE productid={$row['pid']} AND branchid='{$branchid}}' AND date BETWEEN '{$start_date}' AND '{$end_date}'";
			$rowSales=mysql_fetch_array(mysql_query($sqlSales));
			$totalSales=$rowSales['total'];
			
			//Getting closing stock for the product in this period
			$sqlClosing="SELECT closingstock FROM branch_stock_tracking WHERE productid={$row['pid']} AND date='{$end_date}' AND storeid='{$branchid}'";
			$rowClosing=mysql_fetch_array(mysql_query($sqlClosing));
			$closingStock=$rowClosing['closingstock'];
			
			$sheet->setCellValue("A".$excelRow,$row['bname'])->setCellValue("B".$excelRow,$row['pname'])->setCellValue("C".$excelRow,$row['wtpercarton'])->setCellValue("D".$excelRow,$openingStock)->setCellValue("E".$excelRow,"=+D".$excelRow."*C".$excelRow)->setCellValue("F".$excelRow,$totalProduction)->setCellValue("G".$excelRow,"=+F".$excelRow."*C".$excelRow)->setCellValue("H".$excelRow,$totalSales)->setCellValue("I".$excelRow,"=+H".$excelRow."*C".$excelRow)->setCellValue("J".$excelRow,$closingStock)->setCellValue("K".$excelRow,"=+J".$excelRow."*C".$excelRow);
			$excelRow++;
		}
		$lastRow=$excelRow-1;
		$sheet->setCellValue("D".$excelRow,"=SUM(D5:D".$lastRow.")")->setCellValue("E".$excelRow,"=SUM(E5:E".$lastRow.")")->setCellValue("F".$excelRow,"=SUM(F5:F".$lastRow.")")->setCellValue("G".$excelRow,"=SUM(G5:G".$lastRow.")")->setCellValue("H".$excelRow,"=SUM(H5:H".$lastRow.")")->setCellValue("I".$excelRow,"=SUM(I5:I".$lastRow.")")->setCellValue("J".$excelRow,"=SUM(J5:J".$lastRow.")")->setCellValue("K".$excelRow,"=SUM(K5:K".$lastRow.")");
		$sheet->getStyle("A1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFF0F090');
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getAlignment()->setWrapText(true);
		}
	
	$sheet->getColumnDimension('A')->setAutoSize(true);
	$sheet->getColumnDimension('B')->setAutoSize(true);
	$sheet->getColumnDimension('C')->setAutoSize(true);
	$sheet->getColumnDimension('D')->setAutoSize(true);
	$sheet->getColumnDimension('E')->setAutoSize(true);
	$sheet->getColumnDimension('F')->setAutoSize(true);
	$sheet->getColumnDimension('G')->setAutoSize(true);
	$sheet->getColumnDimension('H')->setAutoSize(true);
	$sheet->getColumnDimension('I')->setAutoSize(true);
	$sheet->getColumnDimension('J')->setAutoSize(true);
	$sheet->getColumnDimension('K')->setAutoSize(true);
	$sheet->getColumnDimension('L')->setAutoSize(true);
	$sheet->getColumnDimension('M')->setAutoSize(true);
	
	$sheet->getStyle("A1:M1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFF0F090');
	$sheet->getStyle("A2:M2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF209020');
?>