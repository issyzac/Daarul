<?php
	require("../../db_connection.php");
	require("../../report/report_generator/classes/PHPExcel.php");
	$productionList=new PHPExcel();
	$productionList->setActiveSheetIndex(0);
	
	$productionList->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$productionList->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$productionList->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$productionList->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$productionList->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$productionList->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$productionList->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$productionList->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	
	$sqlMaterials1="SELECT * FROM material WHERE status=1 AND type='Packing Material'";
	$sqlMaterials2="SELECT * FROM material WHERE status=1 AND type='Blending Material'";
	$sqlBlend="SELECT * FROM blend WHERE status=1";
	
	$productionList->getActiveSheet()->setCellValueByColumnAndRow(0,2,"PRODUCT ID")->setCellValueByColumnAndRow(1,2,"BLENDS' AND PRODUCTs' NAME")->setCellValueByColumnAndRow(2,2,"PRODUCED QUANTITY")->setCellValueByColumnAndRow(4,2,"PACKING MATERIALS");
	$productionList->getActiveSheet()->setCellValueByColumnAndRow(3,3,"MATERIAL IDs")->setCellValueByColumnAndRow(3,4,"MATERIAL NAMEs");
	$productionList->getActiveSheet()->setCellValueByColumnAndRow(1,1,"DATE OF PRODUCTION(YYYY-mm-dd):")->setCellValueByColumnAndRow(2,1,date('Y-m-d'));
	
	##FETCHING PACKING MATERIALS
	$resultMaterial=mysql_query($sqlMaterials1);
	$j=4;#COLUMN ITERATOR
	while($rowMaterial=mysql_fetch_array($resultMaterial)){
		$productionList->getActiveSheet()->setCellValueByColumnAndRow($j,3,$rowMaterial['id'])->setCellValueByColumnAndRow($j,4,$rowMaterial['name']);
		$j++;
	}
	
	#MERGING AND FORMATTING CELLS
	//$maxParking=$productionList->getActiveSheet()->getHighestColumn();
	$maxParking=PHPExcel_Cell::stringFromColumnIndex($j-1);
	$productionList->getActiveSheet()->mergeCells('E2:'.$maxParking.'2');
	$productionList->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$productionList->getActiveSheet()->getStyle('E2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('0000FFFF');
	
	##FETCHING BLENDING MATERIALS
	$initialBlending=PHPExcel_Cell::stringFromColumnIndex($j);
	$productionList->getActiveSheet()->setCellValueByColumnAndRow($j,2,"BLENDING MATERIALS");
	$resultMaterial=mysql_query($sqlMaterials2);
	while($rowMaterial=mysql_fetch_array($resultMaterial)){
		$productionList->getActiveSheet()->setCellValueByColumnAndRow($j,3,$rowMaterial['id'])->setCellValueByColumnAndRow($j,4,$rowMaterial['name']);
		$j++;
	}
	$highestColumn=$productionList->getActiveSheet()->getHighestColumn();
	$strHighestColumn=PHPExcel_Cell::stringFromColumnIndex($highestColumn);##HIGHEST COLUMN IN STRING
	
	#BLENDS AND PRODUCTS
	$resultBlend=mysql_query($sqlBlend);
	$i=5;
	while($rowBlend=mysql_fetch_array($resultBlend)){
		$productionList->getActiveSheet()->setCellValueByColumnAndRow(1,$i,strtoupper($rowBlend['name']))->setCellValueByColumnAndRow(0,$i,0);
		$productionList->getActiveSheet()->getStyle($i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00779900');
		$productionList->getActiveSheet()->getStyle('B'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00779900');
		$sqlProducts="SELECT * FROM product WHERE status=1 AND blendid='{$rowBlend['id']}'";
		
		$i++;
		$resultProduct=mysql_query($sqlProducts);
		while($rowProduct=mysql_fetch_array($resultProduct)){
			$productionList->getActiveSheet()->setCellValueByColumnAndRow(0,$i,$rowProduct['id'])->setCellValueByColumnAndRow(1,$i,$rowProduct['name']);
			$productionList->getActiveSheet()->getStyle('C'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('0000FF00');
			$productionList->getActiveSheet()->getStyle('E'.$i.':'.$strHighestColumn.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('0000FF00');
			$i++;
		}
	}
	
	#MERGING CELLS
	$productionList->getActiveSheet()->mergeCells($initialBlending.'2:'.$highestColumn.'2');
	$productionList->getActiveSheet()->getStyle($initialBlending.'2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	//$productionList->getActiveSheet()->getStyle($initialBlending.'1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	$productionList->getActiveSheet()->getStyle($initialBlending.'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00FF00FF');
	
	##HIDING COLUMNS AND ROWS
	$productionList->getActiveSheet()->getColumnDimension('A')->setVisible(false);
	$productionList->getActiveSheet()->getRowDimension('3')->setVisible(false);
	
	#FORMATTING COLORS
	--$i;
	$productionList->getActiveSheet()->getStyle('A1:A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00FF0000');
	$productionList->getActiveSheet()->getStyle($initialBlending.'2:'.$highestColumn.'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('007F7F7F');
	
	$productionList->setActiveSheetIndex(0);
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="production.xls"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($productionList, 'Excel5');
	$objWriter->save('php://output');
	exit;
?>