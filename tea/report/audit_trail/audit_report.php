<?php
	require("../../db_connection.php");
	require("../report_generator/classes/PHPExcel.php");
	$auditList=new PHPExcel();
	$auditList->setActiveSheetIndex(0);
	
	$auditList->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$auditList->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$auditList->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$auditList->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$auditList->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$auditList->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$auditList->getActiveSheet()->setCellValue('A2',"ACTOR's NAMES")->setCellValue('B2',"ACTION DONE")->setCellValue('C2',"AFFECTED TABLE")->setCellValue('D2',"AFFECTED ITEM")->setCellValue('E2',"DATE")->setCellValue('F2',"TIME");
	
	#TOP HEADING
	$auditList->getActiveSheet()->setCellValue('A1',"THE AUDIT TRAIL REPORT FOR THIS SYSTEM AS OF BETWEEN ".$_REQUEST['initialdate2']." AND ".$_REQUEST['finaldate2']);
	$auditList->getActiveSheet()->mergeCells('A1:F1');
	$auditList->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00FFFF00');
	$auditList->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	#FUNCTION TO RETRIEVE NAMES OF ITEMS AFFECTED DURING EVENT IN A SYSTEM
	$name="";
	function getName($table,$itemId){
		$resultName="select name from {$table} where id='{$itemId}'";
		$itemResult=mysql_query($resultName);
		if($itemResult){
			$itemRow=mysql_fetch_array($itemResult);
			$name=$itemRow['name'];
		}else $name="";
		return $name;
	}
	
	$auditQuery="SELECT employee_id,date,time,event,table_name,item_id,firstname,middlename,surname FROM event,employee WHERE (date BETWEEN '{$_REQUEST['initialdate2']}' AND '{$_REQUEST['finaldate2']}') AND event.employee_id=employee.id ORDER BY date asc,time ASC";
	$auditResult=mysql_query($auditQuery);
	if($auditResult&&mysql_num_rows($auditResult)>0){
		$k=3;
		while($row=mysql_fetch_array($auditResult)){
		
			####
			try{
				$itemId=(int)$row['item_id'];
			}
			catch(Exception $e){
				$itemId=$row['item_id'];
			}
			
			$table=$row['table_name'];
			$name="";
			if(strtolower($table)=="user"||strtolower($table)=="employee"){
				$resultName="select firstname,middlename,surname from employee where id='{$row['item_id']}'";
				$itemResult=mysql_query($resultName);
				$itemRow=mysql_fetch_array($itemResult);
				$name="{$itemRow['surname']},{$itemRow['firstname']}{$itemRow['middlename']}";
			}
			else if(strtolower($table)!="system"){
				$name=getName($table,$itemId);
			}
			else if(strtolower($table)=="product_stock"){
				$table="product";
				$name=getName($table,$itemId);
			}
			else if(strtolower($table)=="material_stock"){
				$table="material";
				$name=getName($table,$itemId);
			}

			####
			$auditList->getActiveSheet()->setCellValue('A'.$k,$row['surname'].", ".$row['firstname']." ".$row['middlename'])->setCellValue('B'.$k,$row['event'])->setCellValue('C'.$k,$row['table_name'])->setCellValue('D'.$k,$name)->setCellValue('E'.$k,$row['date'])->setCellValue('F'.$k,$row['time']);
			$k++;
		}
	}
		
	$auditList->getActiveSheet()->getStyle('A2:F2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00FF0000');
	$auditList->setActiveSheetIndex(0);
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="audit_report.xls"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($auditList, 'Excel5');
	$objWriter->save('php://output');
	exit;
?>