<?php
	@session_start();
	require("../../db_connection.php");
	require("../../report/report_generator/classes/PHPExcel.php");
	$proList=new PHPExcel();
	$proList->setActiveSheetIndex(0);
	
	$proList->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$proList->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$proList->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$proList->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	
	$proList->getActiveSheet()->getStyle('C2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('0000FF00');
	
	$proList->getActiveSheet()->setCellValue('B1',"DATE(YYYY-mm-dd)")->setCellValue('C1',date('Y-m-d'))->setCellValue('B2',"Reference Number:");
	
	$proList->getActiveSheet()->setCellValue('A3',"PRODUCT ID")->setCellValue('B3',"PRODUCT NAME")->setCellValue('C3',"QUANTITY");
	$i=4;
	if(!isset($_SESSION['rejected'])){
		$sql="select product.name as name,product.id as id from product,blend where product.status=1 and blend.id=product.blendid and blend.status=1";
		$result=mysql_query($sql);
		while($row=mysql_fetch_array($result)){
			$proList->getActiveSheet()->setCellValue('A'.$i,strtoupper($row['id']))->setCellValue('B'.$i,strtoupper($row['name']));
			$i++;
		}
	}else{
		$proList->getActiveSheet()->setCellValue('D3',"AVAILABLE QUANTITY");
	
		$keys = array_keys($_SESSION['rejected']);
		
		for($x = 0; $x < count($keys); $x++){
			$proList->getActiveSheet()->setCellValue('A'.$i,$keys[$x])->setCellValue('B'.$i,strtoupper($_SESSION['rejected'][$keys[$x]]['name']))
									->setCellValue('D'.$i,$_SESSION['rejected'][$keys[$x]]['available']);
			$i++;
		}
		unset($_SESSION['rejected']);
	}
	
	$proList->setActiveSheetIndex(0);
	
	#HIDING COLUMN
	$proList->getActiveSheet()->getColumnDimension('A')->setVisible(false);
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="products.xls"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($proList, 'Excel5');
	$objWriter->save('php://output');
	exit;
?>