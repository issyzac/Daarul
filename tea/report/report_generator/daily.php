<?php
	require_once "./get_clmn.php";
	$objPHPExcel->setActiveSheetIndex(0);
	
	$sheet = $objPHPExcel->getActiveSheet();
	
	$date=$start_date;// = "20{$_GET['startdate']}";
	//$end_date = "20{$_GET['enddate']}";
	
	$sdate = explode("-",$start_date);
	$edate = explode("-",$end_date);
	
	$start_day = date("w", mktime(0, 0, 0, $sdate['1'], $sdate['2'], $sdate[0]));
	
	$no_days = mktime(0, 0, 0, $edate['1'], $edate['2'], $edate[0]) - mktime(0, 0, 0, $sdate['1'], $sdate['2'], $sdate[0]);
	
	$no_days = $no_days/(60*60*24);
	
	//echo $no_days;
	//exit;
	$clmn_date = array();
	
	$day = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");

	$mstari = 1;
	$next = $mstari + 1;

	for($x = 0; $x <= $no_days; $x++){
		
		$last_clmn = $x + 67;
		
			
		$clmn = getColumn($last_clmn);
		
		$d = $x+$start_day;
		
		$sheet->setCellValue($clmn.$mstari,$day[$d % 7])
			  ->setCellValue($clmn.$next,($x+1));
			  
		$sheet->getColumnDimension($clmn)->setAutoSize(true);
		
		$sheet->getStyle($clmn.$next)->getAlignment()
			  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
		
		$clmn_date[$date] = $clmn;
		
		$xx = strtotime(date("Y-m-d", strtotime($date)) . " +1 day");
		$date = date('Y-m-d', $xx);
		
	}
	$mstari++;
	//var_dump($clmn_date);
	//exit;
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
	
	while($row = mysql_fetch_assoc($query_aproduct)){
		if(!in_array($row['blendid'],$printed_blend)){
			$mstari++;
			
			$sheet->getStyle("A".$mstari.":".getColumn($last_clmn).$mstari)
				  ->getBorders()->getBottom()
				  ->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

			$sheet->getStyle("A{$mstari}:".getColumn($last_clmn)."{$mstari}")->getFill()
				  ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
				  ->getStartColor()
				  ->setARGB($color[$color_index%3]);

			$printed_blend[] = $row['blendid'];
			
			$sheet->setCellValue('A'.$mstari,"{$row['blendname']}");
			
			$color_index++;
		}
		
		if(!in_array($row['blendid'].$row['productid'],$printed_product)){
			$mstari++;
			
			$printed_product[] = $row['blendid'].$row['productid'];
			
			$product_row[$row['blendid']][$row['productid']] = $mstari;
			
			$sheet->setCellValue('A'.$mstari,$row['productname']);
			
			$sheet->getStyle("C{$mstari}:".getColumn($last_clmn)."{$mstari}")->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('FFFFE4C4');
			$sheet->getStyle("C{$mstari}:".getColumn($last_clmn)."{$mstari}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOTTED);								
				
		}
	}
	//var_dump($product_row);
	//exit;
	$last_clmn++;

	$sql_date = "SELECT distinct(date) FROM dailyoutput WHERE (date >= '".$start_date."' AND date <= '".$end_date."')";
	$query_date = mysql_query($sql_date) or die(mysql_error());

	$product_id = $blend_id = "";	
	$blends = array();
	

	
	while($row_date = mysql_fetch_array($query_date))
	{	
		$sql_products = "SELECT blend.id AS blend_id, product.id AS product_id, blend.name AS blend_name, product.name AS product_name, quantity "
						."FROM dailyoutput,product,blend  "
						."WHERE dailyoutput.productId = product.id "
						."AND product.blendid = blend.id "
						."AND dailyoutput.date = '{$row_date['date']}' "
						."ORDER BY product.blendid,product.id ASC ";
						
		$query_products = mysql_query($sql_products) or die(mysql_error());	
				
		
		while($row_products = mysql_fetch_array($query_products))
		{		
				$working_row = $product_row[$row_products['blend_id']][$row_products['product_id']];

				$sheet->setCellValue($clmn_date[$row_date['date']].$working_row,$row_products['quantity']);
				
				$sheet->getStyle($clmn_date[$row_date['date']].$working_row)->getAlignment()
					  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
				
				$sheet->getStyle("B".$mstari.":".getColumn($last_clmn).$working_row)->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()->setARGB("#FFFFFFCC");			
		}
		
		$total_quantity = 0;
	}
	
	$last_row = $mstari;
	$mstari += 2;
	
	$day_keys = array_keys($clmn_date);
	
	for($z = 0; $z < count($day_keys); $z++){
	
		$sum = "SUM({$clmn_date[$day_keys[$z]]}4:{$clmn_date[$day_keys[$z]]}{$last_row})";
		//echo $sum;
		$sheet->setCellValue(($clmn_date[$day_keys[$z]]).$mstari,"=IF(SUM({$sum}) > 0, {$sum}, \"-\")");

		$sheet->getStyle($clmn_date[$day_keys[$z]].$mstari)->getAlignment()
			  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	}
	
	//exit;

	$sheet->setCellValue(getColumn($last_clmn)."1","GRAND TOTAL");

	$sheet->setCellValue("A".$mstari,"GRAND TOTAL");
	$sheet->getStyle("A".$mstari)->getFont()->setBold(true);
	
	$sheet->getStyle("A".$mstari.":".getColumn($last_clmn).$mstari)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	$sheet->getStyle("A".$mstari.":".getColumn($last_clmn).$mstari)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);			

	$sql_material =  "SELECT material.id AS material_id, material.name AS material_name,blend.id AS blendid, "
					."blend.name AS blend_name, dailyinput.date AS date, dailyinput.quantity, product.name AS product_name, product.id AS productid "
					."FROM dailyinput,material,product,blend "
					."WHERE dailyinput.productid =  product.id "
					."AND dailyinput.materialid = material.id "
					."AND product.blendid = blend.id "
					."AND (dailyinput.date >= '{$start_date}' AND dailyinput.date <= '{$end_date}')"
					."ORDER BY material.id,blend.id, product.id, dailyinput.date ";
					
	$query_material = mysql_query($sql_material) or die(mysql_error());			
	
	$printed_materials = array();

	
	$mstari +=1;
	$color_index = 0;
	$product_row = array();
	
	while($row = mysql_fetch_assoc($query_material)){
		if(!in_array($row['material_id'],$printed_materials)){
				$printed_blend = array();
				$printed_product = array();		
				$mstari+=2;
				
				$printed_materials[] = $row['material_id'];
				$sheet->setCellValue("A".$mstari,$row['material_name']);	
				
				$sql_aproduct =  "SELECT product.id AS productid, blend.id AS blendid, blend.name AS blendname, product.name AS productname "
								."FROM product,blend "
								."WHERE product.blendid = blend.id "
								."ORDER BY blend.id, product.id ";
				
				$query_aproduct = mysql_query($sql_aproduct) or die(mysql_error());
				
				while($rowx = mysql_fetch_assoc($query_aproduct)){

					if(!in_array($rowx['blendid'],$printed_blend)){
						$mstari++;
						$product_row[$row['material_id']][$rowx['blendid']][$rowx['productid']] = $mstari+1;
						$sheet->getStyle("A".$mstari.":".getColumn($last_clmn-1).$mstari)
							  ->getBorders()->getBottom()
							  ->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

						$sheet->getStyle("A{$mstari}:".getColumn($last_clmn-1)."{$mstari}")->getFill()
							  ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							  ->getStartColor()
							  ->setARGB($color[$color_index%3]);

						$printed_blend[] = $rowx['blendid'];
						
						$sheet->setCellValue('A'.$mstari,"{$rowx['blendname']}");
						
						$color_index++;
					}
					
					if(!in_array($rowx['blendid'].$rowx['productid'],$printed_product)){
						$mstari++;
						$printed_product[] = $rowx['blendid'].$rowx['productid'];
						
						$product_row[$rowx['blendid']][$rowx['productid']] = $mstari;
						
						$sheet->setCellValue('A'.$mstari,$rowx['productname']);
						
						$sheet->getStyle("C{$mstari}:".getColumn($last_clmn)."{$mstari}")->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('FFFFE4C4');
						$sheet->getStyle("C{$mstari}:".getColumn($last_clmn)."{$mstari}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOTTED);								
				
					}
				}
		}

		
		/*$sheet->setCellValue("A".$product_row[$row['material_id']][$row['blendid']][$row['productid']],$row['product_name'])
			  ->setCellValue($clmn_date[$row['date']].$mstari,$row['quantity']);

		$sheet->getStyle($clmn_date[$row['date']].$product_row[$row['material_id']][$row['blendid']][$row['productid']])->getAlignment()
			  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
			  
			 
		$sheet->getStyle("B".$mstari.":".getColumn($last_clmn).$product_row[$row['material_id']][$row['blendid']][$row['productid']])->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()->setARGB("#FFFFFFCC");	*/		  
		
	}

	for($i = 4; $i <= $mstari; $i++){	
		$sheet->setCellValue(getColumn($last_clmn)."{$i}","=IF(SUM(B{$i}:".getColumn($last_clmn-1)."{$i}) > 0, SUM(B{$i}:".getColumn($last_clmn-1)."{$i}), \"\")");
	}	
	
	for($x = 0; $x <= $no_days; $x++){		
		$last_clmn = $x + 67;	
		$clmn = getColumn($last_clmn);		
		$d = $x+$start_day;		
		$sheet->getStyle($clmn.'4:'.getColumn($last_clmn+2).$mstari)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	}
	
	$sheet->getStyle(getColumn($last_clmn+1).'3:'.getColumn($last_clmn+1).$mstari)->getFill()
							  ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							  ->getStartColor()
							  ->setARGB('FFDDA0DD');
	
	$sheet->getColumnDimension("A")->setAutoSize(true);														
	$sheet->getColumnDimension("B")->setAutoSize(true);						
	$sheet->getColumnDimension(getColumn($last_clmn))->setAutoSize(true);
		
	$sheet->getDefaultStyle()->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
	$sheet->getDefaultStyle()->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);	
	$sheet->getDefaultStyle()->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);	
	$sheet->getDefaultStyle()->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
	
	$sheet->setTitle("DailyInput");

?>
