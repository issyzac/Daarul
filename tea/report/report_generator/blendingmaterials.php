<?php
	$sheet = $objPHPExcel->createSheet();
	$sheet->setTitle("Blendingmaterials");
	
	$sheet->getStyle('B6:EJ6')->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()->setARGB('FFDCDCDC');
	
	$sheet->getStyle('A5:EJ5')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	$sheet->getStyle('A6:EJ6')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	
	$sheet->getColumnDimension('A')->setWidth(30);							
	$sheet->getStyle('B1:EJ1')->getAlignment()->setWrapText(true);
	$sheet->getStyle('A1:EJ1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$sql = "SELECT * FROM material WHERE type='Blending Material' ORDER BY name";
	$result = mysql_query($sql);
	$i = 'A';
	$back_color = array('FFFF0000', 'FFFFA500', 'FF3CB371', 'FF98FB98', 'FF468284', 'FF87CEEB');
	$color_index = 0;
	$pColumn;						
	for($j = 0;$j < 5;$j++)
	{
		if($j != 0)
			$i++;
		$m = $i;
		$m++;
		if($j == 0)
			$sheet->setCellValue($m.'5', "Standard Blend Formula");
		else if($j == 1)
			$sheet->setCellValue($m.'5', "Theoretical weight of raw tea required for the quantity produced ");
		else if($j == 2)
			$sheet->setCellValue($m.'5', "Actual weight of raw tea used for the quantity of packed tea produced ");
		else if($j == 3)
			$sheet->setCellValue($m.'5', "VARIANCES");
		else if($j == 3)
			$sheet->setCellValue($m.'5', "TOTAL COSTS");
		while($row = mysql_fetch_array($result))
		{
			$i++;
			$sheet->setCellValue($i.'6', $row['name']);
		}
		$i++;
		$sheet->setCellValue($i.'6', "Total");
		if($j == 0)
		{
			$i++;
			$pColumn = $i;
			$sheet->setCellValue($i.'6', "Production");
		}
		mysql_data_seek($result, 0);
	}
	
	$sql = "SELECT * FROM blend ORDER BY name";
	$result = mysql_query($sql);
	$c = 6;
	while($row = mysql_fetch_array($result))
	{
		$c++;
		$sql = "SELECT * FROM product WHERE blendid='{$row['id']}'";
		$result2 = mysql_query($sql);
		$sheet->setCellValue('A'.$c, $row['name']);
		$sheet->getStyle('A'.$c.':EJ'.$c)->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setARGB($back_color[$color_index]);		
		$sheet->getStyle('A'.$c.':EJ'.$c)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$sheet->getStyle('A'.$c.':EJ'.$c)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	
		while($row2 = mysql_fetch_array($result2))
		{
			$c++;
			$sheet->setCellValue('A'.$c, $row2['name']);
			$sql = "SELECT * FROM material WHERE type='Blending Material' ORDER BY name";
			$result3 = mysql_query($sql) or die(mysql_error());
			$i = 'A';
			$pStart = $i;
			$pStart++;
			
			$sheet->getStyle('B'.$c.':EJ'.$c)->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setARGB($back_color[$color_index+1]);
			
			while($row3 = mysql_fetch_array($result3))
			{
				//Start here with angels changes removed equantity
				$sql = "SELECT * FROM blendformula WHERE productid='{$row2['id']}' AND materialid='{$row3['id']}'";
				$i++;
				$result4 = mysql_query($sql);
				if(mysql_num_rows($result4) == 1)
				{
					$row4 = mysql_fetch_array($result4);
					$sheet->setCellValue("{$i}{$c}",$row4['percentage']."%");
				}else
				{
					$sheet->setCellValue("{$i}{$c}","0");
				}
			}
			
			$pEnd = $i;
			$pEnd--;
			$i++;
			$sheet->setCellValue("{$i}{$c}","=SUM({$pStart}{$c}:{$pEnd}{$c})");
			$i++;
			$sql = "SELECT *,SUM(quantity) as sum FROM dailyoutput WHERE productid='{$row2['id']}' AND date >='$start_date' AND date <='$end_date' GROUP BY productid";
			$row4 = mysql_fetch_array(mysql_query($sql));
			$sheet->setCellValue("{$i}{$c}",$row4['sum']);
			$i++;
			$pStart = $i;
			$pStart++;
			
			//Blank Column here
			$sheet->getColumnDimension($i)->setWidth(2);
			$sheet->getStyle($i.'1:'.$i.$c)->getFill()
							  ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							  ->getStartColor()
							  ->setARGB('FFFFFFFF');
			
			$theoreticalCol = array();
			$actualCol = array();
			mysql_data_seek($result3, 0);
			$f = 'A';
			$tStartColumn = $i;
			while($row3 = mysql_fetch_array($result3))
			{
				$f++;
				$i++;
				$sheet->setCellValue($i.$c, "=\${$pColumn}\${$c}*$f{$c}");
			}
			$pEnd = $i;
			$pEnd--;
			$i++;
			$sheet->setCellValue("{$i}{$c}","=SUM({$pStart}{$c}:{$pEnd}{$c})");
			$i++;
			$aStartColumn = $i;
			$pStart = $i;
			$pStart++;
			
			//Blank Column here
			$sheet->getColumnDimension($i)->setWidth(2);
			$sheet->getStyle($i.'1:'.$i.$c)->getFill()
							  ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							  ->getStartColor()
							  ->setARGB('FFFFFFFF');
			
			mysql_data_seek($result3, 0);
			while($row3 = mysql_fetch_array($result3))
			{
				$sql = "SELECT *,SUM(quantity) as sum FROM dailyinput WHERE productid='{$row2['id']}' AND materialid='{$row3['id']}' AND date >='$start_date' AND date <='$end_date' GROUP BY materialid";
				$row4 = mysql_fetch_array(mysql_query($sql));
				$i++;
				$sheet->setCellValue($i.$c, $row4['sum']);
				$actualCol[] = $row4['sum'];
			}
			$pEnd = $i;
			$pEnd--;
			$i++;
			$sheet->setCellValue("{$i}{$c}","=SUM({$pStart}{$c}:{$pEnd}{$c})");
			$i++;
			$pStart = $i;
			$pStart++;
			
			//Blank Column here
			$sheet->getColumnDimension($i)->setWidth(2);
			$sheet->getStyle($i.'1:'.$i.$c)->getFill()
							  ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							  ->getStartColor()
							  ->setARGB('FFFFFFFF');
			
			mysql_data_seek($result3, 0);
			while($row3 = mysql_fetch_array($result3))
			{
				$i++;
				$aStartColumn++;
				$tStartColumn++;
				$sheet->setCellValue("{$i}{$c}","={$tStartColumn}{$c}-{$aStartColumn}{$c}");
			}
			$pEnd = $i;
			$pEnd--;
			$i++;
			$sheet->setCellValue("{$i}{$c}","=SUM({$pStart}{$c}:{$pEnd}{$c})");
		}
		
		$color_index = $color_index + 2;
		if($color_index > 4)  $color_index = 0;
		
	}
?>