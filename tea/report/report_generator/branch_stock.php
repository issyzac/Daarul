<?php
	include("../../db_connection.php");
	include("classes/PHPExcel.php");
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getDefaultStyle()->getFont()->setSize('12');
	$objPHPExcel->setActiveSheetIndex(0);
	
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1',"TRANSIT DETAILS");
	
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
	
	$query_ontransit = mysql_query("SELECT * FROM on_transit,product,store WHERE on_transit.branchid = store.storeid "
								  ."AND on_transit.productid = product.id AND on_transit.delivery = '0' ORDER BY transitid,store.storeid,productid") or die(mysql_error());
	
	$bt = "";
	$mstari = 3;
	if(mysql_num_rows($query_ontransit)){
		while($row =  mysql_fetch_assoc($query_ontransit)){
			
			if($bt != $row['branchid'].$row['transitid']){
				$query_trname = mysql_query("SELECT storename AS transitname FROM store WHERE storeid = '{$row['transitid']}'") or die(mysql_error());
				$trans_row = mysql_fetch_assoc($query_trname);
				
				$mstari += 2;
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$mstari,$trans_row['transitname']." to ".$row['storename'])
							->setCellValue('A'.($mstari+1),"Dispatch date: ".$row['date'])
							->setCellValue('A'.($mstari+2),"Product name")
							->setCellValue('B'.($mstari+2),"Quantity");
						
				$objPHPExcel->getActiveSheet()->getStyle("A".($mstari+2).":C".($mstari+2))->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()->setARGB('FF999999');

						
				$bt = $row['branchid'].$row['transitid'];
				$mstari += 3;
			}
			
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$mstari,$row['name'])
						->setCellValue('B'.$mstari,$row['quantity']);		
			$mstari++;			
		}
		
	}else{
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A3',"No transit details");
	}
	$objPHPExcel->createSheet(1);	
	$sheet1 = $objPHPExcel->setActiveSheetIndex(1);
	
	$sheet1->setCellValue('A1',"Stock on branches");
	$sheet1->getStyle('A1')->getFont()->setBold(true);
	
	$sql = "SELECT *, sum(quantity) AS total FROM branch_stock,store,product "
		  ."WHERE branch_stock.branchid = store.storeid AND branch_stock.productid = product.id GROUP BY branch_stock.branchid,branch_stock.productid ";
		  
	$query = mysql_query($sql) or die(mysql_error());
	$mstari = 1;
	$bid = "";
	
	while($row = mysql_fetch_assoc($query)){
		$query_sales = mysql_query("SELECT sum(quantity) AS total FROM branch_sales WHERE branchid = '{$row['branchid']}' AND productid = '{$row['productid']}'") or die(mysql_error());
		$sales_row = mysql_fetch_assoc($query_sales); 
	
		$remaining = $row['total'] - $sales_row['total'];
		
		if($bid != $row['branchid']){
			$mstari += 2;
			$sheet1->setCellValue('A'.$mstari,$row['storename'])
				   ->setCellValue('A'.($mstari+1),'Product name')
				   ->setCellValue('B'.($mstari+1),'Remaining quantity');
			
			$objPHPExcel->getActiveSheet()->getStyle("A".($mstari+1).":C".($mstari+1))->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						->getStartColor()->setARGB('FF999999');
		
			$objPHPExcel->getActiveSheet()->getStyle('A'.($mstari+1))->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B'.($mstari+1))->getFont()->setBold(true);
					
			$mstari +=2;	 
			$bid = $row['branchid'];
		}
		
		$sheet1->setCellValue('A'.$mstari,$row['name'])
			   ->setCellValue('B'.$mstari,$remaining);
		$mstari++;
	}
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->setActiveSheetIndex(1);
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="report.xls"');
	header('Cache-Control: max-age=0');
			
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
?>