<?php
	require("../../db_connection.php");
	require("../../report/report_generator/classes/PHPExcel.php");
	$matList=new PHPExcel();
	$matList->setActiveSheetIndex(0);
	$matList->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$matList->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$matList->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$matList->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$matList->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	
	$sql="select id,name,type from material where status=1";
	$result=mysql_query($sql);
	
	if($_GET['operation']=="purchase"){//Excel to be used during material purchase
		$matList->getActiveSheet()->setCellValue('B1',"DATE(YYYY-mm-dd)")->setCellValue('C1',date('Y-m-d'))->setCellValue('B2',"GRN:");
		$matList->getActiveSheet()->setCellValue("B3","LPO:");
		$matList->getActiveSheet()->setCellValue('A4',"MATERIAL ID")->setCellValue('B4',"MATERIAL TYPE")->setCellValue('C4',"MATERIAL NAME")->setCellValue('D4',"RECEIVED QUANTITY");
		$i=5;
		while($row=mysql_fetch_array($result)){
			$matList->getActiveSheet()->setCellValue('A'.$i,strtoupper($row['id']))->setCellValue('B'.$i,strtoupper($row['type']))->setCellValue('C'.$i,strtoupper($row['name']));
			$i++;
		}
		$j=$i-1;
		$matList->getActiveSheet()->getStyle('A4:Z4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00008800');
		$matList->getActiveSheet()->getStyle('A1:A'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00FF0000');
		$matList->getActiveSheet()->getStyle('C2:C3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('0000FF00');
	}
	elseif($_GET['operation']=="place_order"){//Excel to be used during ordering of materials
		$matList->getActiveSheet()->setCellValue('B1',"DATE(YYYY-mm-dd)")->setCellValue('C1',date('Y-m-d'))->setCellValue('B2',"LPO:");
		$matList->getActiveSheet()->setCellValue('A3',"MATERIAL ID")->setCellValue('B3',"MATERIAL TYPE")->setCellValue('C3',"MATERIAL NAME")->setCellValue('D3',"ORDERED QUANTITY");
		$i=4;
		while($row=mysql_fetch_array($result)){
			$matList->getActiveSheet()->setCellValue('A'.$i,strtoupper($row['id']))->setCellValue('B'.$i,strtoupper($row['type']))->setCellValue('C'.$i,strtoupper($row['name']));
			$i++;
		}
		$j=$i-1;
		$matList->getActiveSheet()->getStyle('A4:Z4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00008800');
		$matList->getActiveSheet()->getStyle('A1:A'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00FF0000');
		$matList->getActiveSheet()->getStyle('C2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('0000FF00');
	}
	#HIDING A COLUMN
	$matList->getActiveSheet()->getColumnDimension('A')->setVisible(false);
	$matList->setActiveSheetIndex(0);
	
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="materials.xls"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($matList, 'Excel5');
	$objWriter->save('php://output');
	exit;
?> 