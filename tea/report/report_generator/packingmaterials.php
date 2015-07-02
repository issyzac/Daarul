<?php
	$sheet = $objPHPExcel->createSheet();
	$start_date = "{$_GET['startdate']}";
	$end_date = "{$_GET['enddate']}";
	$sheet->setTitle("Packingmaterials");
	
	$sheet->setCellValue('C5', 'Wt per primary unit(gms)');
	$sheet->getStyle('C5')->getAlignment()->setWrapText(true);
	$sheet->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$sheet->setCellValue('D5', 'Wt per carton(kgs)');
	$sheet->getStyle('D5')->getAlignment()->setWrapText(true);
	$sheet->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$sheet->setCellValue('E5', 'Primary Units per carton');
	$sheet->getStyle('E5')->getAlignment()->setWrapText(true);
	$sheet->getStyle('E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$sheet->setCellValue('F5', 'pkts/ct');
	$sheet->getStyle('F5')->getAlignment()->setWrapText(true);
	$sheet->getStyle('F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$sheet->setCellValue('H5', 'Quantity Produced');
	$sheet->getStyle('H5')->getAlignment()->setWrapText(true);
	$sheet->getStyle('H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	
	$sheet->getColumnDimension('B')->setWidth(30);							
	$sheet->getStyle('B1:BO1')->getAlignment()->setWrapText(true);
	$sheet->getStyle('B1:BO1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$char = 'C';
	for($col=3; $col<27; $col++){
		$sheet->getColumnDimension($char)->setWidth(15);
		$char++;
	}
	
	$char = 'A';
	for($col=1; $col<27; $col++){
		$sheet->getColumnDimension('A'.$char)->setWidth(15);
		$char++;
	}
	
	$char = 'A';
	for($col=1; $col<14; $col++){
		$sheet->getColumnDimension('B'.$char)->setWidth(15);
		$char++;
	}
	
	$sql = "SELECT * FROM material WHERE type='Packing Material' ORDER BY name";
	$result = mysql_query($sql);
	$i = 'H';
	
	for($j = 0;$j < 5;$j++)
	{
		$i++;
		if($j == 0)
		{
			$sheet->setCellValue('J4', 'Acceptable wastages(except c/boxes)');
		}else if($j == 1)
		{
			$f = $i;
			$f++;
			$sheet->setCellValue("{$f}4", 'Theoretical quantity of packing materials required for the quantity produced');
		}else if($j == 2)
		{
			$f = $i;
			$f++;
			$sheet->setCellValue("{$f}4", 'Actual quantity of packing materials required for the quantity produced');
		}else if($j == 3)
		{
			$f = $i;
			$f++;
			$sheet->setCellValue("{$f}4", 'VARIANCES');
		}else if($j == 4)
		{
			$f = $i;
			$f++;
			$sheet->setCellValue("{$f}4", 'Packing materials cost');
		}
		while($row = mysql_fetch_array($result))
		{
			$i++;
			$sheet->setCellValue($i.'5', $row['name']);
			
			$sheet->getStyle($i.'5')->getAlignment()->setWrapText(true);
			$sheet->getStyle($i.'5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);		
		}
		$i++;
		$sheet->setCellValue($i.'5', "Total");
		mysql_data_seek($result, 0);
	}
	
	$color_index = 0;
	$back_color = array('FFFF0000', 'FFFFA500', 'FF3CB371', 'FF98FB98', 'FF468284', 'FF87CEEB');

	$sql = "SELECT * FROM blend ORDER BY name";
	$result = mysql_query($sql);
	$c = 5;
	
	while($row = mysql_fetch_array($result))
	{
		$c++;
		$sheet->getStyle('B'.$c.':BO'.$c.'')->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setARGB($back_color[$color_index]);
		
		$sheet->getStyle('B'.$c.':BO'.$c.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$sheet->getStyle('B'.$c.':BO'.$c.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	
		$sql = "SELECT * FROM product WHERE blendid='{$row['id']}'";
		$result2 = mysql_query($sql);
		$sheet->setCellValue('B'.$c, $row['name']);
	
		while($row2 = mysql_fetch_array($result2))
		{
			$c++;
			$sheet->getStyle('C'.$c.':BO'.$c)->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setARGB($back_color[$color_index+1]);
			
			$sheet->setCellValue('B'.$c, $row2['name']);
			
			$sheet->setCellValue('C'.$c, $row2['wtperprimaryunit']);
			
			$sheet->setCellValue('D'.$c, $row2['wtpercarton']);
			
			$sheet->setCellValue('E'.$c, $row2['primaryunitpercarton']);
			
			$sheet->setCellValue('F'.$c, $row2['pkts']);
			
			$sql = "SELECT SUM(quantity) as sum FROM dailyoutput WHERE productid='{$row2['id']}' AND date >='$start_date' AND date <='$end_date' GROUP BY productid";
			$ro = mysql_fetch_array(mysql_query($sql));
			$sheet->setCellValue('H'.$c, $ro['sum']);
			
			
			$sql = "SELECT * FROM material WHERE type='Packing Material' ORDER BY name";
			$result3 = mysql_query($sql) or die(mysql_error());
			$i = 'I';

			$pStart = $i;
			$pStart++;
			$theNew = $i;
			while($row3 = mysql_fetch_array($result3))
			{
				//Start here with angels changes removed equantity
				$sql = "SELECT SUM(quantity) as sum FROM dailyinput WHERE productid='{$row2['id']}' AND materialid='{$row3['id']}' AND date >='$start_date' AND date <='$end_date' GROUP BY materialid";
				$sql = "SELECT * FROM blendformula WHERE productid='{$row2['id']}' AND materialid='{$row3['id']}'";
				$row4 = mysql_fetch_array(mysql_query($sql));
				$i++;
				$result4 = mysql_query($sql);
				if(mysql_num_rows($result4) == 1)
				{
					$row4 = mysql_fetch_array($result4);
					$sheet->setCellValue("{$i}{$c}",$row4['percentage']);
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
			$pStart = $i;
			$pStart++;
			
			//Blank Column here
			$sheet->getColumnDimension($i)->setWidth(2);
			$sheet->getStyle($i.'1:'.$i.$c)->getFill()
							  ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							  ->getStartColor()
							  ->setARGB('FFFFFFFF');
			
			mysql_data_seek($result3, 0);
			$theNew2 = $i;
			while($row3 = mysql_fetch_array($result3))
			{
				$i++;
				$theNew++;
				$sheet->setCellValue($i.$c, "={$theNew}{$c}*H{$c}");
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
			$theNew = $i;
			while($row3 = mysql_fetch_array($result3))
			{
				$sql = "SELECT SUM(quantity) as sum FROM dailyinput WHERE productid='{$row2['id']}' AND materialid='{$row3['id']}' AND date >='$start_date' AND date <='$end_date' GROUP BY materialid";
				$row4 = mysql_fetch_array(mysql_query($sql));
				$i++;
				$sheet->setCellValue($i.$c, $row4['sum']);
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
				$theNew++;
				$theNew2++;
				$sheet->setCellValue($i.$c, "={$theNew2}{$c}-{$theNew}{$c}");
			}
			$pEnd = $i;
			$pEnd--;
			$i++;
			$sheet->setCellValue("{$i}{$c}","=SUM({$pStart}{$c}:{$pEnd}{$c})");
		}
		
		$sheet->getColumnDimension('G')->setWidth(2);
		$sheet->getStyle('G1:G'.$c)->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						->getStartColor()
						->setARGB('FFFFFFFF');
						
		$sheet->getColumnDimension('I')->setWidth(2);
		$sheet->getStyle('I1:I'.$c)->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						->getStartColor()
						->setARGB('FFFFFFFF');
			
		$color_index = $color_index + 2;
		if($color_index > 4)  $color_index = 0;
	}
?>