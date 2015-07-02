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
		$lpo=$workSheet->getCellByColumnAndRow(2,3)->getValue();
		if($refno=="")
			echo "<span style='font-size:25px;color:#ff0000' >Upload failed, You must fill the GRN Number</span>";
		elseif($lpo=="")
			echo "<span style='font-size:25px;color:#ff0000' >Upload failed, You must fill the LPO Number</span>";
		else{
			mysql_query("SET AUTOCOMMIT=0");//start transaction
			$commit=true;
			$nuf=0; #number of unuploaded Entries
			for($i=5;$i<=$rowCount;$i++){
				//READ FROM UPLOADED EXCEL FILE
				$id = $workSheet->getCellByColumnAndRow(0, $i)->getValue();
				$qty = $workSheet->getCellByColumnAndRow(3, $i)->getValue();
				if($qty==0) continue;
				
				//GET orderid FROM ordered_material
				$sql="SELECT orderid FROM ordered_material WHERE materialid={$id} AND lpo={$lpo}";
				$result=mysql_query($sql);
				/*if(mysql_num_rows($result)<=0){
					echo "<span style='font-size:25px;color:#ff0000' >Upload failed, You entered invalid LPO Number</span>";
					$commit=false;
					break;
				}*/
				$orderRow=mysql_fetch_array($result);
				$orderid=$orderRow['orderid'];
				
				//INSERT INTO DATABASE
				$sql="INSERT INTO material_purchase(orderid,purchaseqty,date,refno) VALUES('{$orderid}','{$qty}','{$date}','{$refno}')";
				$qry=mysql_query($sql);
				if($qry){
					$sql="UPDATE ordered_material SET outstanding=outstanding-{$qty} WHERE orderid='{$orderid}'";
					$result1=mysql_query($sql);
					if(!$result1)$commit=false;
					else{
						$sql="SELECT outstanding FROM ordered_material WHERE orderid='{$orderid}'";
						$rst=mysql_query($sql);
						$rw=mysql_fetch_array($rst);
						if($rw['outstanding']<0)$commit=false;
					}
				}
				/*if(!$qry){
					$sql2="UPDATE material_purchase SET purchaseqty=purchaseqty+{$qty} WHERE materialid={$id}";
					mysql_query($sql2);
				}*/
			}
			$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Uploaded Excel file containing purchase Data','".date('Y-m-d')."','".date('H:i:s')."','','sales')";
			mysql_query($auditSql);
			if($commit){
				mysql_query("COMMIT");
				echo "<span style='font-size:25px' >Data successfully uploaded</span>";
			}
			else{
				mysql_query("ROLLBACK");
				echo "<span style='font-size:15px;color:#ff0000' >Upload failed, <br />please correct all information in your excel file and upload again</span>";
			}
		} 
	}
?>