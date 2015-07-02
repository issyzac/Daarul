<?php	
$sheet = $objPHPExcel->createSheet();
$sheet->setTitle("VARIANCE REPORT");
$objPHPExcel->getProperties()->setCreator("Kymbila Tea Packing Limited - Chai Tausi")
							 ->setLastModifiedBy("Kymbila Tea Packing Limited - Chai Tausi")
							 ->setTitle("Report - Kymbila Tea Packing Limited")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Kymbila Tea Packing Limited - Chai Tausi Report")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

$sheet->getStyle('A1:I1')->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()->setARGB('FFFFC0CB');
							

$sheet->getColumnDimension('A')->setWidth(30);							
$sheet->getStyle('B1:I1')->getAlignment()->setWrapText(true);
$sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('B1', 'Weight per carton (kilos)');
$sheet->getColumnDimension('B')->setWidth(15);

$sheet->setCellValue('C1', 'Total units produced (tons)');
$sheet->getColumnDimension('C')->setWidth(15);

$sheet->setCellValue('D1', 'Actual packing materials usage (kgs)');
$sheet->getColumnDimension('D')->setWidth(15);

$sheet->setCellValue('E1', 'Theoretical packing material usage (kgs)');						  
$sheet->getColumnDimension('E')->setWidth(15);
							  
$sheet->setCellValue('F1', 'Packing material variance (kgs)');							  
$sheet->getColumnDimension('F')->setWidth(15);
							  
$sheet->setCellValue('G1', 'Actual raw materials usage (kgs)');							  
$sheet->getColumnDimension('G')->setWidth(15);
							  
$sheet->setCellValue('H1', 'Theoretical raw material usage (kgs)');							  
$sheet->getColumnDimension('H')->setWidth(15);
							  
$sheet->setCellValue('I1', 'Total raw tea variance (kgs)');						  
$sheet->getColumnDimension('I')->setWidth(15);

$sheet->getStyle('B1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('C1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('D1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('E1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('F1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('G1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('H1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('I1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('I1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
							  
$sheet->setCellValue('A2', 'PRODUCT COSTING AND PROFITABILITY ANALYSIS:');	

// Loops start here

$row = 2;
$color_index = 0;
$back_color = array('FFFF0000', 'FFFFA500', 'FF3CB371', 'FF98FB98', 'FF468284', 'FF87CEEB');

$get_blends = mysql_query("SELECT * FROM blend ORDER BY name");

while($blends = mysql_fetch_array($get_blends)){
	$row++;
	$blend_id = $blends['id'];
	$blend_name = $blends['name'];	
	
	$sheet->getStyle('A'.$row.':I'.$row)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB($back_color[$color_index]);
									
	$sheet->getStyle('A'.$row.':Z'.$row)->getFont()->setBold(true);		
	$sheet->setCellValue('A'.$row, $blend_name.':');	
	
	$sheet->getStyle('A'.$row.':I'.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	$sheet->getStyle('A'.$row.':I'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	
	$get_products = mysql_query("SELECT *,SUM(quantity) as sum FROM product,dailyoutput WHERE product.blendid = '$blend_id' AND product.id=productid AND date>='$start_date' AND date<='$end_date' GROUP BY product.id ORDER BY name");
	while($products = mysql_fetch_array($get_products)){
		$row++;
		$product_name = $products['name'];
		$id = $products['id'];
		
		$sheet->setCellValue('A'.$row, $product_name);
		$sheet->setCellValue('B'.$row, $products['wtperprimaryunit']);
		$sheet->setCellValue('C'.$row, $products['sum']/1000);
		//packing material row materials
		$sql = "SELECT SUM(quantity) as sum FROM dailyinput,material WHERE productid='{$products['id']}' AND material.id=materialid AND type='Packing Material' AND date>='$start_date' AND date<='$end_date' GROUP BY productid";
		$res = mysql_query($sql);
		$sum_row = mysql_fetch_array($res);
		$sheet->setCellValue('D'.$row, $sum_row['sum']);
		
		$sql = "SELECT * FROM blendformula,material WHERE productid='{$products['id']}' AND material.id=materialid AND type='Packing Material'";
		$res = mysql_query($sql);
		$sum1 = 0;
		while($sum_row = mysql_fetch_array($res))
		{
			$sum1 += (($products['sum'] * $sum_row['percentage'])/100);
		}
		$sheet->setCellValue('E'.$row, $sum1);
		$sheet->setCellValue('F'.$row, '=E'.$row.' - D'.$row);
		//end of packing materials
		//blending material row materials
		$sql = "SELECT SUM(quantity) as sum FROM dailyinput,material WHERE productid='{$products['id']}' AND material.id=materialid AND type='Blending Material' AND date>='$start_date' AND date<='$end_date' GROUP BY productid";
		$res = mysql_query($sql);
		$sum_row = mysql_fetch_array($res);
		$sheet->setCellValue('G'.$row, $sum_row['sum']);
		
		$sql = "SELECT * FROM blendformula,material WHERE productid='{$products['id']}' AND material.id=materialid AND type='Blending Material'";
		$res = mysql_query($sql);
		$sum1 = 0;
		while($sum_row = mysql_fetch_array($res))
		{
			$sum1 += (($products['sum'] * $sum_row['percentage'])/100);
		}
		$sheet->setCellValue('H'.$row, $sum1);
		$sheet->setCellValue('I'.$row, '=H'.$row.' - G'.$row);
		//end of raw materials
		
		
		
		
		$sheet->getStyle('B'.$row.':I'.$row)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB($back_color[$color_index + 1]);
									
		$sheet->getStyle('B'.$row.':I'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOTTED);
						
	}
	
	$sheet->getStyle('B3:B'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('C3:C'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('D3:D'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('E3:E'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('F3:F'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('G3:G'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('H3:H'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('I3:I'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('I3:I'.$row)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
	$color_index = $color_index + 2;
	if($color_index > 4)  $color_index = 0;
}

$sheet->getStyle('B'.$row.':I'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
$sheet->getStyle('B'.$row.':I'.($row+1))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);

$sheet->setCellValue('A'.($row+1), 'Sub Total:');

$sheet->setCellValue('C'.($row+1), '=SUM(C4:C'.$row.')');
$sheet->setCellValue('D'.($row+1), '=SUM(D4:D'.$row.')');
$sheet->setCellValue('E'.($row+1), '=SUM(E4:E'.$row.')');
$sheet->setCellValue('F'.($row+1), '=SUM(F4:F'.$row.')');
$sheet->setCellValue('G'.($row+1), '=SUM(G4:G'.$row.')');
$sheet->setCellValue('H'.($row+1), '=SUM(H4:H'.$row.')');
$sheet->setCellValue('I'.($row+1), '=SUM(I4:I'.$row.')');

$sheet->setCellValue('A'.($row+3), 'Weighted Average: (Excl.Herbals)');
$sheet->setCellValue('A'.($row+4), 'Weighted Average: (Organic & Bags)');

?>
