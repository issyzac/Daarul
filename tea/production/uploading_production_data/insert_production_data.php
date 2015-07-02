<?php
	require("report/report_generator/Classes/PHPExcel.php");
	function insert($file){
		$Reader = PHPExcel_IOFactory::createReaderForFile($file);
		$Reader->setReadDataOnly(true); // set this, to not read all excel properties, just data
		$objXLS = $Reader->load($file);
		$workSheet=$objXLS->getSheet(0);
		$rowCount=$workSheet->getHighestRow();
		$highestColumn=$workSheet->getHighestColumn();
		$columnCount=PHPExcel_Cell::columnIndexFromString($highestColumn);
		
		$date=$workSheet->getCellByColumnAndRow(2,1)->getValue();
		for($i=6;$i<=$rowCount;$i++){#ITERATING THROUGH ROWS
			$proId=$workSheet->getCellByColumnAndRow(0,$i)->getValue();
			if($proId!=0){
				$qty=$workSheet->getCellByColumnAndRow(2,$i)->getValue();
				
				if($qty>0&&$qty!=""){
					$productSql="INSERT INTO dailyoutput(productid,quantity,date) VALUES('{$proId}','{$qty}','{$date}')";
					$result1=mysql_query($productSql);
					if($result1){
						for($j=4;$j<=$columnCount;$j++){#ITERATING THROUGH COLUMNS
							$materialId=$workSheet->getCellByColumnAndRow($j,3)->getValue();
							$materialQty=$workSheet->getCellByColumnAndRow($j,$i)->getValue();
							if($materialQty!=0&&$materialQty!=""){#SAVING MATERIALS USED AS DAILY INPUT
								$materialSql="INSERT INTO dailyinput(productid,materialid,quantity,date) VALUES('{$proId}','{$materialId}','{$materialQty}','{$date}')";
								$result=mysql_query($materialSql);
							}
						}
					}
				}
			}
		}
		
	}
?>