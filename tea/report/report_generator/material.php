<?php
	$objPHPExcel->setActiveSheetIndex(0);
	//Start and end dates
	$start_date=$_GET['start_datepick'];
	$end_date=$_GET['end_datepick'];
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->setCellValue('A2',"MATERIAL NAME")->setCellValue('B2',"MATERIAL TYPE")->setCellValue('C2',"QUANTITY");
	$objPHPExcel->getActiveSheet()->setCellValue('A1',"MATERIAL RETURNED_REPORT");
     $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
	 	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00FFFF00');
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$query = "select name,type,quantity,date from material,material_returned where material.id=material_returned.materialid AND date BETWEEN '{$start_date}' AND '{$end_date}'";
	$Result=mysql_query($query);
	if(!Result)
	{
	die(mysql_error());
	}
	
		$k=3;
		while($row=mysql_fetch_array($Result)){
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$k,$row['name']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$k,$row['type']);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$k,$row['quantity']);
	
		
			$k++;
		}
?>