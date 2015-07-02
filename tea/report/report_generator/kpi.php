<?php
$sheet = $objPHPExcel->createSheet();

$sheet->getStyle('A1:AJ1')->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()->setARGB('FFFFC0CB');							

$sheet->getColumnDimension('A')->setWidth(30);							
$sheet->getStyle('B1:AJ1')->getAlignment()->setWrapText(true);
$sheet->getStyle('A1:AJ1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('B1', 'Weight per carton (kilos)');
$sheet->getColumnDimension('B1')->setWidth(15);

$sheet->setCellValue('C1', 'Total units produced (tons)');
$sheet->getColumnDimension('C')->setWidth(15);

$sheet->setCellValue('D1', 'Unit Packing material cost per Kg (Tshs)');
$sheet->getColumnDimension('D')->setWidth(15);

$sheet->setCellValue('E1', 'Unit Blending material costs per Kgs (Tshs)');						  
$sheet->getColumnDimension('E')->setWidth(15);
							  
$sheet->setCellValue('F1', 'Unit Processing costs (Tshs)');							  
$sheet->getColumnDimension('F')->setWidth(15);
							  
$sheet->setCellValue('G1', 'Total unit cost per kg (Tshs)');							  
$sheet->getColumnDimension('G')->setWidth(15);
							  
$sheet->setCellValue('H1', 'Selling price per kg (Tshs)');							  
$sheet->getColumnDimension('H')->setWidth(15);
							  
$sheet->setCellValue('I1', 'Discount allowed (at 17.33%)');						  
$sheet->getColumnDimension('I')->setWidth(15);

$sheet->setCellValue('J1', 'Net selling price per kg (Tshs)');						  
$sheet->getColumnDimension('J')->setWidth(15);

$sheet->setCellValue('K1', 'Carriage outwards costs per KG');						  
$sheet->getColumnDimension('K')->setWidth(15);

$sheet->setCellValue('L1', 'Net contribution per kg (Tshs)');						  
$sheet->getColumnDimension('L')->setWidth(15);

$sheet->setCellValue('M1', 'Net contribution per carton (Tshs)');						  
$sheet->getColumnDimension('M')->setWidth(15);

$sheet->setCellValue('N1', 'Net contribution margin  per kg');						  
$sheet->getColumnDimension('N')->setWidth(15);

$sheet->setCellValue('O1', 'Selling & Distribution costs per KG');						  
$sheet->getColumnDimension('O')->setWidth(15);

$sheet->setCellValue('P1', 'Admin epenses(excl depn) per Kg');						  
$sheet->getColumnDimension('P')->setWidth(15);

$sheet->setCellValue('Q1', 'Group Admin expenses per Kg');						  
$sheet->getColumnDimension('Q')->setWidth(15);

$sheet->setCellValue('R1', 'Net operating profit / (loss) per KG');						  
$sheet->getColumnDimension('R')->setWidth(15);

$sheet->setCellValue('S1', 'Net operating profit / (loss) margin per Kg');						  
$sheet->getColumnDimension('S')->setWidth(15);

$sheet->setCellValue('T1', '');						  
$sheet->getColumnDimension('T')->setWidth(5);

$sheet->setCellValue('U1', 'Weight per carton (kilos)');
$sheet->getColumnDimension('U1')->setWidth(15);

$sheet->setCellValue('V1', 'Total units produced (tons)');
$sheet->getColumnDimension('V')->setWidth(15);

$sheet->setCellValue('W1', 'Unit Packing material cost per Kg (Tshs)');
$sheet->getColumnDimension('W')->setWidth(15);

$sheet->setCellValue('X1', 'Unit Blending material costs per Kgs (Tshs)');						  
$sheet->getColumnDimension('X')->setWidth(15);
							  
$sheet->setCellValue('Y1', 'Unit Processing costs (Tshs)');							  
$sheet->getColumnDimension('Y')->setWidth(15);
							  
$sheet->setCellValue('Z1', 'Total unit cost per kg (Tshs)');							  
$sheet->getColumnDimension('Z')->setWidth(15);
							  
$sheet->setCellValue('AA1', 'Selling price per kg (Tshs)');							  
$sheet->getColumnDimension('AA')->setWidth(15);
							  
$sheet->setCellValue('AB1', 'Discount allowed (at 17.33%)');						  
$sheet->getColumnDimension('AB')->setWidth(15);

$sheet->setCellValue('AC1', 'Net selling price per Carton (Tshs)');
$sheet->getColumnDimension('AC')->setWidth(15);

$sheet->setCellValue('AD1', 'Net contribution per Carton (Tshs)');
$sheet->getColumnDimension('AD')->setWidth(15);

$sheet->setCellValue('AE1', 'Net contribution margin  per unit');						  
$sheet->getColumnDimension('AE')->setWidth(15);
							  
$sheet->setCellValue('AF1', '');							  
$sheet->getColumnDimension('AF')->setWidth(5);
							  
$sheet->setCellValue('AG1', 'Total contribution from SKU (Tshs\'000)');							  
$sheet->getColumnDimension('AG')->setWidth(15);
							  
$sheet->setCellValue('AH1', '');							  
$sheet->getColumnDimension('AH')->setWidth(5);
							  
$sheet->setCellValue('AI1', 'Proportion of total sales Quantity');						  
$sheet->getColumnDimension('AI')->setWidth(15);

$sheet->setCellValue('AJ1', 'Proportion of total Contribution');						  
$sheet->getColumnDimension('AJ')->setWidth(15);


$sheet->getStyle('A1:AJ1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('B1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('C1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('D1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('E1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('F1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('G1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('H1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('I1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('J1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('K1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('L1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('M1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('N1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('O1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('P1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('Q1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('R1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('S1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('T1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('U1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('V1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('W1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('X1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('Y1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('Z1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('AA1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('AB1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('AC1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('AD1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('AE1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('AF1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('AG1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('AH1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('AI1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('AJ1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
								  
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
	
	$sheet->getStyle('A'.$row.':AJ'.$row)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB($back_color[$color_index]);
									
	$sheet->getStyle('A'.$row.':Z'.$row)->getFont()->setBold(true);		
	$sheet->setCellValue('A'.$row, $blend_name.':');	
	
	$sheet->getStyle('A'.$row.':AJ'.$row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	$sheet->getStyle('A'.$row.':AJ'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	$sql = "SELECT *,SUM(quantity) as sum FROM product,dailyoutput WHERE product.blendid = '$blend_id' AND product.id=productid AND date>='$start_date' AND date<='$end_date' GROUP BY product.id ORDER BY name";
	$get_products = mysql_query($sql);
	while($products = mysql_fetch_array($get_products)){
		$row++;
		$product_name = $products['name'];
		$id = $products['id'];
		
		$sheet->setCellValue('A'.$row, $product_name);
		$sheet->setCellValue('B'.$row, $products['wtperprimaryunit']);
		$sheet->setCellValue('C'.$row, $products['sum']);
		$sheet->setCellValue('G'.$row, '=SUM(D'.$row.':F'.$row.')');
		$sheet->setCellValue('I'.$row, '=H'.$row.'*17.33%');
		$sheet->setCellValue('J'.$row, '=H'.$row.' - I'.$row);		
		$sheet->setCellValue('L'.$row, '=J'.$row.' - G'.$row.' - K'.$row);
		$sheet->setCellValue('M'.$row, '=L'.$row.' * B'.$row);
		$sheet->setCellValue('N'.$row, '=L'.$row.' / G'.$row);
		$sheet->setCellValue('R'.$row, '=L'.$row.' -SUM(O'.$row.':Q'.$row.')');
		$sheet->setCellValue('S'.$row, '=R'.$row.' / G'.$row);
		$sheet->setCellValue('U'.$row, '=B'.$row);
		$sheet->setCellValue('V'.$row, '=C'.$row);
		$sheet->setCellValue('W'.$row, '=$U'.$row.' * D'.$row);
		$sheet->setCellValue('X'.$row, '=$U'.$row.' * E'.$row);
		$sheet->setCellValue('Y'.$row, '=$U'.$row.' * F'.$row);
		$sheet->setCellValue('Z'.$row, '=SUM(W'.$row.':Y'.$row.')');
		$sheet->setCellValue('AA'.$row, '=$U'.$row.' * H'.$row);
		$sheet->setCellValue('AB'.$row, '=AA'.$row.'*17.33%');
		$sheet->setCellValue('AC'.$row, '=AA'.$row.' - AB'.$row);
		$sheet->setCellValue('AD'.$row, '=AC'.$row.' - Z'.$row);
		$sheet->setCellValue('AE'.$row, '=AD'.$row.' / Z'.$row);
		$sheet->setCellValue('AG'.$row, '=L'.$row.' * C'.$row);
		
		$sheet->getStyle('B'.$row.':AJ'.$row)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB($back_color[$color_index + 1]);
									
		$sheet->getStyle('B'.$row.':AJ'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOTTED);
						
	}
	
	$sheet->getStyle('B3:B'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('C3:C'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('D3:D'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('E3:E'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('F3:F'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('G3:G'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('H3:H'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('I3:I'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('J3:J'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('K3:K'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('L3:L'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('M3:M'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('N3:N'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('O3:O'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('P3:P'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('Q3:Q'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('R3:R'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('S3:S'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('T3:T'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('U3:U'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('V3:V'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('W3:W'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('X3:X'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('Y3:Y'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('Z3:Z'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('AA3:AA'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('AB3:AB'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('AC3:AC'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('AD3:AD'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('AE3:AE'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('AF3:AF'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('AG3:AG'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('AH3:AH'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('AI3:AI'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getStyle('AJ3:AJ'.$row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
	$color_index = $color_index + 2;
	if($color_index > 4)  $color_index = 0;
}

$sheet->getStyle('N4:N'.$row)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('FFFF1493');
									
$sheet->getStyle('S4:S'.$row)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('FFFF1493');
									
$sheet->getStyle('AE4:AE'.$row)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('FFFF1493');
									
$sheet->getStyle('T1:T'.$row)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('FFFFFFFF');
									
$sheet->getStyle('AF1:AF'.$row)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('FFFFFFFF');
									
$sheet->getStyle('AH1:AH'.$row)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('FFFFFFFF');
									
$sheet->getStyle('A'.$row.':AJ'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
$sheet->getStyle('A'.$row.':AJ'.($row+1))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);

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
				  
$sheet->setTitle('KPI');
?>