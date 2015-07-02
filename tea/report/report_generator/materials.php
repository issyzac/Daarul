<?php
	$matSheet=$objPHPExcel->createSheet();
	//$objPHPExcel->setActiveSheetIndex(1);
	$matSheet->setTitle("Materials");
	$matSheet->setCellValue("A1","Stock Report for Materials for the period from ".date('d-M-Y',strtotime($start_date))." to ".date('d-M-Y',strtotime($end_date)));
	$matSheet->mergeCells("A1:H1");
	
	$sql="SELECT o.lpo AS lpo,o.quantity AS qty,o.date AS orderdate,o.outstanding AS balance,m.name AS matname,m.type AS mattype,s.name AS supname FROM ordered_material o,material m,supplier s WHERE o.materialid=m.id AND s.supplierid=o.supplierid AND date BETWEEN '{$start_date}' AND '{$end_date}'";
	$result=mysql_query($sql);
	$i=3;
	if(mysql_num_rows($result)>0){
		$matSheet->setCellValue("A2","Supplier")->setCellValue("B2","Material")->setCellValue("C2","Type")->setCellValue("D2","LPO")->setCellValue("E2","Ordered Quantity")->setCellValue("F2","Received Quantity")->setCellValue("G2","Outstanding Order")->setCellValue("H2","Ordering Date");
		while($row=mysql_fetch_array($result)){
			$matSheet->setCellValue("A".$i,$row['supname'])->setCellValue("B".$i,$row['matname'])->setCellValue("C".$i,$row['mattype'])->setCellValue("D".$i,$row['lpo'])->setCellValue("E".$i,$row['qty'])->setCellValue("F".$i,"=E{$i}-G{$i}")->setCellValue("G".$i,$row['balance'])->setCellValue("H".$i,date('d-M-Y',strtotime($row['orderdate'])));
			$i++;
		}
		$matSheet->getStyle("A2:H2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF209020');
	}
	else{
		$matSheet->setCellValue("A2","No order placed during this period");
		$matSheet->mergeCells("A2:H2");
	}
	$matSheet->getStyle("A1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFF0F090');
	
	$i++;
	$matSheet->setCellValue("A{$i}","Details on reception of materials");
	$matSheet->getStyle("A{$i}")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF0000');
	$matSheet->mergeCells("A{$i}:H{$i}");
	$i++;
	
	$sql="SELECT * FROM material WHERE id IN(SELECT materialid FROM ordered_material INNER JOIN material_purchase ON ordered_material.orderid=material_purchase.orderid AND material_purchase.date BETWEEN '{$start_date}' AND '{$end_date}')";
	$result=mysql_query($sql);
	while($row=mysql_fetch_array($result)){
		$matSheet->setCellValue("A{$i}",$row['name'])->setCellValue("B{$i}","LPO")->setCellValue("C{$i}","GRN")->setCellValue("D{$i}","Received Quantity")->setCellValue("E{$i}","Purchase Date");
		$matSheet->getStyle("A{$i}:E{$i}")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF209020');
		$i++;
		$innerSql="SELECT mp.purchaseqty AS received,mp.date AS receptiondate,mp.refno AS grn,om.lpo AS lpo,s.name AS supname FROM supplier s,ordered_material om,material_purchase mp WHERE s.supplierid=om.supplierid AND om.materialid='{$row['id']}' AND om.orderid=mp.orderid AND mp.date BETWEEN '{$start_date}' AND '{$end_date}'";
		$innerResult=mysql_query($innerSql);
		if(!$innerResult){
			$matSheet->setCellValue("J1",mysql_error());
			continue;
		}
		while($innerRow=mysql_fetch_array($innerResult)){
			$matSheet->setCellValue("A{$i}",$innerRow['supname'])->setCellValue("B{$i}",$innerRow['lpo'])->setCellValue("C{$i}",$innerRow['grn'])->setCellValue("D{$i}",$innerRow['received'])->setCellValue("E{$i}",date('d-M-Y',strtotime($innerRow['receptiondate'])));
			$i++;
		}
		$i++;
	}
	$matSheet->getColumnDimension('A')->setAutoSize(true);
	$matSheet->getColumnDimension('B')->setAutoSize(true);
	$matSheet->getColumnDimension('C')->setAutoSize(true);
	$matSheet->getColumnDimension('D')->setAutoSize(true);
	$matSheet->getColumnDimension('E')->setAutoSize(true);
	$matSheet->getColumnDimension('F')->setAutoSize(true);
	$matSheet->getColumnDimension('G')->setAutoSize(true);
	$matSheet->getColumnDimension('H')->setAutoSize(true);
?>