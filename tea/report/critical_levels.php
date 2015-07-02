<?php
set_time_limit(5 * 60);
$report_type = 'Critical_Levels';

include("db_connection.php");
include("report_generator/classes/PHPExcel.php");
$objPHPExcel = new PHPExcel();


////////////////////////////////////////////////////////////PACKING MATERIALS////////////////////////////////////////////////////////////////
$sheet = $objPHPExcel->createSheet();
$sheet->setTitle("Packing Materials");
$sheet->getColumnDimension('A')->setWidth(5);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);

$sqlBlending = "SELECT * FROM material, material_stock WHERE material.type = 'Packing Material' AND material.id = material_stock.materialid AND material.status = 1 AND material_stock.closingstock < material.criticallevel";
$resultBlending = mysql_query($sqlBlending);

$sheet->setCellValue('A1', '');
$sheet->setCellValue('B1', 'Material Name');
$sheet->setCellValue('C1', 'Critical Level');
$sheet->setCellValue('D1', 'Available');
$sheet->setCellValue('E1', 'Difference');
$sheet->setCellValue('F1', 'Date Added');

$row = 3;

$sheet->getStyle('A1:F1')->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()->setARGB('FF209020');

$sheet->getStyle('B2:F2')->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()->setARGB('FFF0F090');
							
while($blending = mysql_fetch_array($resultBlending)){
	$sheet->setCellValue('A'.$row, ($row-2));
	$sheet->setCellValue('B'.$row, $blending['name']);
	$sheet->setCellValue('C'.$row, $blending['criticallevel']);
	$sheet->setCellValue('D'.$row, $blending['closingstock']);
	$sheet->setCellValue('E'.$row, ($blending['criticallevel'] - $blending['closingstock']) );
	$sheet->setCellValue('F'.$row, $blending['date']);
	
	$sheet->getStyle('B'.$row.':'.'F'.$row)->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()->setARGB('FFF0F090');
	$row++;	
}

////////////////////////////////////////////////////////////BLENDING MATERIALS////////////////////////////////////////////////////////////////
$sheet = $objPHPExcel->createSheet();
$sheet->setTitle("Blending Materials");
$sheet->getColumnDimension('A')->setWidth(5);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);

$sqlBlending = "SELECT * FROM material, material_stock WHERE material.type = 'Blending Material' AND material.id = material_stock.materialid AND material.status = 1 AND material_stock.closingstock < material.criticallevel";
$resultBlending = mysql_query($sqlBlending);

$sheet->setCellValue('A1', '');
$sheet->setCellValue('B1', 'Material Name');
$sheet->setCellValue('C1', 'Critical Level');
$sheet->setCellValue('D1', 'Available');
$sheet->setCellValue('E1', 'Difference');
$sheet->setCellValue('F1', 'Date Added');

$row = 3;

$sheet->getStyle('A1:F1')->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()->setARGB('FF209020');

$sheet->getStyle('B2:F2')->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()->setARGB('FFF0F090');
							
while($blending = mysql_fetch_array($resultBlending)){
	$sheet->setCellValue('A'.$row, ($row-2));
	$sheet->setCellValue('B'.$row, $blending['name']);
	$sheet->setCellValue('C'.$row, $blending['criticallevel']);
	$sheet->setCellValue('D'.$row, $blending['closingstock']);
	$sheet->setCellValue('E'.$row, ($blending['criticallevel'] - $blending['closingstock']) );
	$sheet->setCellValue('F'.$row, $blending['date']);
	
	$sheet->getStyle('B'.$row.':'.'F'.$row)->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()->setARGB('FFF0F090');
	$row++;	
}

////////////////////////////////////////////////////////////PRODUCTS////////////////////////////////////////////////////////////////
$sheet = $objPHPExcel->createSheet();
$sheet->setTitle("Products At Critical Level");
$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);

$sqlBlend = "SELECT * FROM blend WHERE status = 1";
$resultBlend = mysql_query($sqlBlend);

$back_color = array('FFFF0000', 'FFFFA500', 'FF3CB371', 'FF98FB98', 'FF468284', 'FF87CEEB');
$color_index = 0;

$row = 3;

$sheet->setCellValue('A1', 'Blend');
$sheet->setCellValue('B1', 'Product Name');
$sheet->setCellValue('C1', 'Critical Level');
$sheet->setCellValue('D1', 'Available');
$sheet->setCellValue('E1', 'Difference');
$sheet->setCellValue('F1', 'Production Date');

while($blends = mysql_fetch_array($resultBlend)){
	$blendid = $blends['id'];
	
	$productNumber = 1;
	$sheet->getStyle('A'.$row.':'.'F'.$row)->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()->setARGB('FF209020');
							
	$sheet->setCellValue('A'.$row, $blends['name']);
	$row++;
	$color_index++;
	
	$sqlProduct = "SELECT * FROM product, product_stock WHERE product.blendid = $blendid AND product.id = product_stock.productid AND product_stock.closingstock < product.criticallevel";
	$resultProduct = mysql_query($sqlProduct);

	while($product = mysql_fetch_array($resultProduct)){
		$sheet->setCellValue('A'.$row, $productNumber);
		$sheet->setCellValue('B'.$row, $product['name']);
		$sheet->setCellValue('C'.$row, $product['criticallevel']);
		$sheet->setCellValue('D'.$row, $product['closingstock']);
		$sheet->setCellValue('E'.$row, ($product['criticallevel'] - $product['closingstock']) );
		$sheet->setCellValue('F'.$row, $product['date']);
		$sheet->getStyle('B'.$row.':'.'F'.$row)->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()->setARGB('FFF0F090');
	$row++;	
	$productNumber++;
	}
	$row = $row+2;
}
$datetime = date('Y-m-d_h-i-s');
$report_link = 'report/report_files/'.$report_type.'_Report_'.$datetime.'.xls';

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($report_link);

echo'<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />
Report generated succeefully, <a href="report/report_files/'.$report_type.'_Report_'.$datetime.'.xls"> Click here to download </a>
</center>';
