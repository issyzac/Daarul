<?php
	require("report/report_generator/Classes/PHPExcel.php");
	function insert($file){
		$Reader = PHPExcel_IOFactory::createReaderForFile($file);
		$Reader->setReadDataOnly(true); // set this, to not read all excel properties, just data
		$objXLS = $Reader->load($file);
		$workSheet=$objXLS->getSheet(0);
		$rowCount=$workSheet->getHighestRow();
		
		#DATE
		$date=$workSheet->getCellByColumnAndRow(2,1)->getValue();
		$refno=$workSheet->getCellByColumnAndRow(2,2)->getValue();
		if($refno=="")
			echo "<span style='font-size:25px' >Upload failed, You must fill the LPO</span>";
		else{
			$nuf=0; #number of unuploaded Entries
			for($i=4;$i<=$rowCount;$i++){
				$matid = $workSheet->getCellByColumnAndRow(0, $i)->getValue();
				$qty = $workSheet->getCellByColumnAndRow(3, $i)->getValue();
				if($qty==0) continue;
				$sql="INSERT INTO ordered_material(lpo,supplierid,materialid,quantity,date,outstanding) VALUES('{$refno}','{$_POST['supplieru']}','{$matid}','{$qty}','{$date}','{$qty}')";
				$qry=mysql_query($sql);
			}
			$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Uploaded Excel file containing materials order Data','".date('Y-m-d')."','".date('H:i:s')."','','sales')";
			mysql_query($auditSql);
		} 
	}
?>