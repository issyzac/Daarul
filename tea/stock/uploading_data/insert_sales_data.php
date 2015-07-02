<?php
	@session_start();
	require("report/report_generator/Classes/PHPExcel.php");
	function insert($file,$type){
		$today = date('Y-m-d');
		$Reader = PHPExcel_IOFactory::createReaderForFile($file);
		$Reader->setReadDataOnly(true); // set this, to not read all excel properties, just data
		$objXLS = $Reader->load($file);
		$workSheet=$objXLS->getSheet(0);
		$rowCount=$workSheet->getHighestRow();
		
		$date=$workSheet->getCellByColumnAndRow(2,1)->getValue();
		$receipt=$workSheet->getCellByColumnAndRow(2,2)->getValue();
		
		if($receipt=="")
			echo "<span style='font-size:25px' >Upload failed, You must fill the Reference Number</span>";
		else{
			for($i=4;$i<=$rowCount;$i++){
				$id = $workSheet->getCellByColumnAndRow(0, $i)->getValue();
				$qty = $workSheet->getCellByColumnAndRow(2, $i)->getValue();
				$name = $workSheet->getCellByColumnAndRow(1, $i)->getValue();
				$available = check_availability($id,$today);
				if($available >= $qty){
					$sql="INSERT INTO sales(product_id,quantity,date,action,receiptnumber) VALUES('{$id}','{$qty}','{$date}','{$type}','{$receipt}')";
					if($qty==0) continue;
					$qry=mysql_query($sql);
				}else{
					$_SESSION['rejected'][$id]['name'] = $name;
					$_SESSION['rejected'][$id]['available'] = $available;
				}
				
			}
			$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Uploaded Excel file containing sales Data','".date('Y-m-d')."','".date('H:i:s')."','','material_purchase')";
			mysql_query($auditSql);
		}
	}
?>