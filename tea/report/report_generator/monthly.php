<?php
	require_once "get_clmn.php";
	$sheet = $objPHPExcel->createSheet();
	$sheet->getRowDimension('2')->setRowHeight(30);


	$month = date('m');
	$year = date('Y');
	
	$sql_all_material = "SELECT * FROM material ORDER BY type ";
	$query_material = mysql_query($sql_all_material) or die(mysql_error());
	
	$material_clmn = 68;
	$material = array();
	$type = "";
	while($row_material = mysql_fetch_assoc($query_material)){
	
		if($type != $row_material['type']){
			$material_clmn++;
			$sheet->getColumnDimension(getColumn($material_clmn - 1))->setAutoSize(12);			
		}
		//echo $material_clmn." --- ".getColumn($material_clmn)."2"."<br/>";
		$sheet
					->setCellValue(getColumn($material_clmn)."2","{$row_material['name']}");
		
		
		$sheet
					->getStyle(getColumn($material_clmn)."2")->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					
		$material[$row_material['id']]['column'] = $material_clmn;
		$material[$row_material['id']]['total'] = 0;
		
		$material_clmn++;
		$type = $row_material['type'];
	}
	
	$sql_aproduct =  "SELECT product.id AS productid, blend.id AS blendid, blend.name AS blendname, product.name AS productname "
					."FROM product,blend "
					."WHERE product.blendid = blend.id "
					."ORDER BY blend.id, product.id ";
					
	$query_aproduct = mysql_query($sql_aproduct) or die(mysql_error());
	
	$color = array("FFFF0000","FF0D5931","FF336699");
	$printed_blend = array();
	$printed_product = array();
	$product_row= array();
	$color_index = 0;
	$mstari = 2;
	
	while($row = mysql_fetch_assoc($query_aproduct)){
		if(!in_array($row['blendid'],$printed_blend)){
			$mstari++;
		
			$sheet->getStyle("A".$mstari.":".getColumn($material_clmn).$mstari)
				  ->getBorders()->getBottom()
				  ->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

			$sheet->getStyle("A{$mstari}:".getColumn($material_clmn)."{$mstari}")->getFill()
				  ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
				  ->getStartColor()
				  ->setARGB($color[$color_index%3]);

			$printed_blend[] = $row['blendid'];
			
			$sheet->setCellValue('A'.$mstari,"BLEND {$row['blendname']}");
			
			$color_index++;
		}
		
		if(!in_array($row['blendid'].$row['productid'],$printed_product)){
			$mstari++;
			$printed_product[] = $row['blendid'].$row['productid'];
			
			$product_row[$row['blendid']][$row['productid']] = $mstari;
			
			$sheet->setCellValue('A'.$mstari,$row['productname']);
		}
	}

	$sql_product = 	 "SELECT blend.id AS blend_id, product.id AS product_id, blend.name AS blend_name, product.name AS product_name, SUM(quantity) AS quantity "
					."FROM dailyoutput,product,blend  "
					."WHERE dailyoutput.productId = product.id "
					."AND product.blendid = blend.id "
					."AND Month(date) = '".$month."' "
					."AND Year(date) = '".$year."'"
					."GROUP BY Month(date),product.id "
					."ORDER BY product.blendid,product.id, Month(date) ";
					
	
	$query_product = mysql_query($sql_product) or die(mysql_error());
	
	$product_id = $blend_id = "";	
	
	while($row_products = mysql_fetch_array($query_product))
	{	
				#--Printing product of this blend and quantity produced--#
				
				$working_row =	$product_row[$row_products['blend_id']][$row_products['product_id']];
				
				$sheet
							->setCellValue('A'.$working_row,$row_products['product_name']);
				
				$sheet
							->setCellValue("C".$working_row,$row_products['quantity']);

				$sheet
							->getStyle("C".$working_row)->getAlignment()
							->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							
							
				$sheet->getDefaultStyle()->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				
				$sheet->getStyle('C'.$working_row)->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()->setARGB('FFFFE4C4');
				
				$sheet->getStyle('E'.$working_row.':'.getColumn($material_clmn).$working_row)->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()->setARGB('FFFFE4C4');
								
				#--end--#
				
				#--Query material used--#
				
				$sql_material =  "SELECT Sum(quantity) AS quantity,name,materialid "
								."FROM dailyinput,material WHERE dailyinput.productid = '".$row_products['product_id']."' "
								."AND Month(date) = '".$month."' AND Year(date) = '".$year."' "
								."AND material.id = dailyinput.materialid "
								."GROUP BY dailyinput.materialid ";
								
				//echo $sql_material;
				//exit;
					
				$query_material = mysql_query($sql_material) or die(mysql_error());
				
				$material_used = array();
				
				#--Print material used--#	
				
				while($row_material = mysql_fetch_assoc($query_material)){
					$material_used[] = $row_material['materialid'];
					
					$clmn_mx = $material[$row_material['materialid']]['column'];
					
					$material[$row_material['materialid']]['total'] += $row_material['quantity'];
					
					$sheet
								->setCellValue(getColumn($clmn_mx).$working_row,$row_material['quantity']);

					$sheet
								->getStyle(getColumn($clmn_mx).$working_row)->getAlignment()
								->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				}
				
				$keys = array_keys($material);
				
				for($x = 0; $x < count($material); $x++){
					if(!in_array($keys[$x],$material_used)){
							$sheet
										->setCellValue(getColumn($material[$keys[$x]]['column']).$working_row,"-");
							
							$sheet
										->getStyle(getColumn($material[$keys[$x]]['column']).$working_row)->getAlignment()
										->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
										
					}
				}
				
				#--end--#
			
	}
	
	$sheet
				->setCellValue("C2","Quantity Produced");
	
	$sheet
				->getStyle("C")->getAlignment()
				->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	

	$sheet->getColumnDimension("A")->setAutoSize(true);	
	$sheet->getColumnDimension("B")->setAutoSize(true);					
	$sheet->getColumnDimension("C")->setAutoSize(true);					

	$last_row = $mstari;
	
	$mstari += 2;
	//echo "C".$mstari,"=SUM(C4:C{$last_row})";
	$sheet->setCellValue("A".$mstari,"GRAND TOTAL")->setCellValue("C".$mstari,"=SUM(C4:C{$last_row})");
	
	//exit;
	$keys = array_keys($material);		

	for($x = 0; $x< count($keys); $x++){
		$clmnx = getColumn($material[$keys[$x]]['column']);		
		
		$sum = "SUM({$clmnx}4:{$clmnx}{$last_row})";
		
		$sheet->setCellValue(getColumn($material[$keys[$x]]['column']).$mstari,"=IF(SUM({$sum}) > 0, {$sum}, \"-\")");

		$sheet->getStyle(getColumn($material[$keys[$x]]['column']).$mstari)->getAlignment()
			  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
			
	}
	
	$sheet->getDefaultStyle()->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
	$sheet->getDefaultStyle()->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
	$sheet->getDefaultStyle()->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
	$sheet->getDefaultStyle()->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
	
	$sheet->getStyle("A".$mstari.":".getColumn($material_clmn+3).$mstari)->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()->setARGB('FFDDA0DD');
				
	$sheet->getStyle("A".$mstari.":".getColumn($material_clmn+3).$mstari)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	$sheet->getStyle("A".$mstari.":".getColumn($material_clmn+3).$mstari)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);			
		
	$sheet->getStyle('A2:'.getColumn($material_clmn).'2')
				->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				
	$sheet->getStyle('A2:'.getColumn($material_clmn).'2')
				->getAlignment()->setWrapText(true);
				
	$sheet->setTitle("MonthlyDetails");
?>
