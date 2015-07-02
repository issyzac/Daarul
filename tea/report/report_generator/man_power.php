<?php
	require_once "get_clmn.php";
	
	$today = "{$_GET['date']}";
	
	#change 1
	if(!isset($_GET['date']) || empty($_GET['date'])){
		$today = date('Y-m-d');
		//exit;
	}	
	#end c1--#
	
	$objPHPExcel = new PHPExcel();
	
	$sheet = $objPHPExcel->setActiveSheetIndex(0);	
	
	$objPHPExcel->getDefaultStyle()->getFont()->setSize('12');
	$mstari = 1;
	
	#change 2
	$date_arr = explode("-",$today);
	$sheet->setCellValue('A'.$mstari,"Date: ".$date_arr[2]."-".$date_arr[1]."-".$date_arr[0]);
	#end c2			
				
	$shift_clmn = array();
	$shifts = array();
	
	$sql_shift = "SELECT * FROM shift";
	$query_shift = mysql_query($sql_shift) or die(mysql_error());
	
	$max_clmn = 66;
	
	$color_index = 0;
	$back_color = array('FFFF0000', 'FF3CB371', 'FF0000F9');
	
	while($row = mysql_fetch_array($query_shift)){
		$sheet->setCellValue(getColumn($max_clmn).$mstari,$row['name']);
		
		$shift_clmn[$row['id']] = $max_clmn;
		$shifts[$row['id']] = $row['name'];
		
		$max_clmn += 5;
	}
	$mstari++;
	
	$sql_att_status = "SELECT * FROM attendance_status";
	$query_att_status = mysql_query($sql_att_status) or die(mysql_error());
	$att_status_row = array();
	
	$trow = mysql_num_rows($query_att_status) + $mstari + 2;
	
	$keys = array_keys($shift_clmn);
		
	for($x = 0; $x < count($keys); $x++){
		$tmp_clmn = $shift_clmn[$keys[$x]];
		
		$sum1 = "=SUM(".getColumn($tmp_clmn)."3".":".getColumn($tmp_clmn).($trow-1).")";
		$sum2 = "=SUM(".getColumn($tmp_clmn+1)."3".":".getColumn($tmp_clmn+1).($trow-1).")";
		$sum3 = "=SUM(".getColumn($tmp_clmn+2)."3".":".getColumn($tmp_clmn+2).($trow-1).")";
		$sum4 = "=SUM(".getColumn($tmp_clmn+3)."3".":".getColumn($tmp_clmn+3).($trow-1).")";
		
		$sheet->setCellValue(getColumn($tmp_clmn).$mstari,"MG");
		$sheet->getStyle(getColumn($tmp_clmn).($mstari-1).':'.getColumn($tmp_clmn).$mstari)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB($back_color[$color_index]);
		
		$sheet->getStyle(getColumn($tmp_clmn).($mstari+1).':'.getColumn($tmp_clmn).($mstari+4))->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('FFFFE4C4');
									
		$sheet->getStyle(getColumn($tmp_clmn).($mstari-1).":".getColumn($tmp_clmn).($mstari+5))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
									
		$sheet->setCellValue(getColumn($tmp_clmn+1).$mstari,"SG");
		$sheet->getStyle(getColumn($tmp_clmn+1).($mstari-1).':'.getColumn($tmp_clmn+1).$mstari)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB($back_color[$color_index]);
									
		$sheet->getStyle(getColumn($tmp_clmn+1).($mstari+1).':'.getColumn($tmp_clmn+1).($mstari+4))->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('FFFFE4C4');
		
		$sheet->getStyle(getColumn($tmp_clmn+1).($mstari-1).":".getColumn($tmp_clmn+1).($mstari+5))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				
		$sheet->setCellValue(getColumn($tmp_clmn+2).$mstari,"Casual");
		$sheet->getStyle(getColumn($tmp_clmn+2).($mstari-1).':'.getColumn($tmp_clmn+2).$mstari)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB($back_color[$color_index]);
									
		$sheet->getStyle(getColumn($tmp_clmn+2).($mstari+1).':'.getColumn($tmp_clmn+2).($mstari+4))->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('FFFFE4C4');
		
		$sheet->getStyle(getColumn($tmp_clmn+2).($mstari-1).":".getColumn($tmp_clmn+2).($mstari+5))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		
		$sheet->setCellValue(getColumn($tmp_clmn+3).$mstari,"Totals");
		$sheet->getStyle(getColumn($tmp_clmn+3).($mstari-1).':'.getColumn($tmp_clmn+3).$mstari)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB($back_color[$color_index]);
									
		$sheet->getStyle(getColumn($tmp_clmn+3).($mstari+1).':'.getColumn($tmp_clmn+3).($mstari+4))->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('FFFFE4C4');
									
		$sheet->getStyle(getColumn($tmp_clmn+3).($mstari-1).":".getColumn($tmp_clmn+3).($mstari+5))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		
		$sheet->getStyle(getColumn($tmp_clmn+3).($mstari-1).":".getColumn($tmp_clmn+3).($mstari+5))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		
		$sheet->setCellValue(getColumn($tmp_clmn).$trow,$sum1);
		$sheet->getStyle(getColumn($tmp_clmn).$trow)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('FFDDA0DD');
		
		$sheet->setCellValue(getColumn($tmp_clmn+1).$trow,$sum2);
		$sheet->getStyle(getColumn($tmp_clmn+1).$trow)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('FFDDA0DD');
		
		$sheet->setCellValue(getColumn($tmp_clmn+2).$trow,$sum3);
		$sheet->getStyle(getColumn($tmp_clmn+2).$trow)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('FFDDA0DD');
		
		$sheet->setCellValue(getColumn($tmp_clmn+3).$trow,$sum4);
		$sheet->getStyle(getColumn($tmp_clmn+3).$trow)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('FFDDA0DD');
		
		
					
		$color_index++;
		if($color_index > 2)  $color_index = 0;
		
		for($trows=2; $trows<6; $trows++){
			$sheet->getStyle(getColumn($tmp_clmn).$trows.":".getColumn($tmp_clmn+3).$trows)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
		}
		
		$sheet->getStyle(getColumn($tmp_clmn).$trow.":".getColumn($tmp_clmn+3).$trow)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$sheet->getStyle(getColumn($tmp_clmn).$trow.":".getColumn($tmp_clmn+3).$trow)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	}
	
	$sheet->getStyle("A".$trow)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	$sheet->getStyle("A".$trow)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	$sheet
			->setCellValue("A".$trow,"Totals");
			
	$mstari+=2;
	while($row =  mysql_fetch_assoc($query_att_status)){
		$sheet
			 ->setCellValue("A".$mstari,ucfirst($row['name']));
		$att_status_row[$row['id']] = $mstari;
		$mstari++;
	}
	$mstari = $trow;
	

	
	$sql_att = 	"SELECT attendance_status.id AS attid, count(employeeid) AS total, employee.toe, attendance_status.name As status, shift.name AS shiftname, shift.id AS shiftid "
			   ."FROM attendance,employee,attendance_status,shift "
			   ."WHERE attendance.employeeid = employee.id "
			   ."AND attendance.status = attendance_status.id "
			   ."AND attendance.shiftid = shift.id "
			   ."AND date = '{$today}' "
			   ."GROUP BY employee.toe, attendance.status, attendance.shiftid ";
	#echo $sql_att;exit;	   
	$query_att = mysql_query($sql_att) or die(mysql_error());
	$num_rows = mysql_num_rows($query_att);
	

	$mstari++;
	
	$printed_status = array();
	
	
	while($row = mysql_fetch_assoc($query_att)){
		$clm;
		if(!in_array($row['status'],$printed_status)){		
			$printed_status[$row['status']] = $mstari;
			
			$sheet->setCellValue("A".$att_status_row[$row['attid']],$row['status']);		
		}
		
		$toe = strtolower($row['toe']);
		//echo $toe."<br>";
		switch($toe){
			case "casual":
				$clm = "2";
				break;
			
			case "mg":
				$clm = "0";
				break;
				
			case "sg":
				$clm = "1";
				break;
			
			default:
				$clm = 'kicheche';
		}
		//echo $clm."<br/>";
		if($clm != "kicheche"){
		
			$tclmn = getColumn($shift_clmn[$row['shiftid']]+3);
			$clm = getColumn($clm+$shift_clmn[$row['shiftid']]);
			$s = $shift_clmn[$row['shiftid']];
			$e = $s + 2;
		
			$sum = "=SUM(".getColumn($s).$att_status_row[$row['attid']].":".getColumn($e).$att_status_row[$row['attid']].")";
			$sheet
					->setCellValue($tclmn.$att_status_row[$row['attid']],$sum);

			$sheet
					->setCellValue($clm.$att_status_row[$row['attid']],$row['total']);

			$sheet
						->setCellValue($clm.$att_status_row[$row['attid']],$row['total']);
		}
	}
	$mstari ++;
	$sheet
		->setCellValue("A".$mstari,"Allocation:");
	$mstari ++;
	$sheet
			->setCellValue("A".$mstari,"Job/Task");
	
	$shift_keys = array_keys($shifts);
	$shift_aclmn = array();

	$temp_clmn = 66;

	for($x = 0; $x < count($shift_keys); $x++){
		$sheet->setCellValue(getColumn($temp_clmn).$mstari."",$shifts[$shift_keys[$x]]);
		$sheet->getStyle(getColumn($temp_clmn).($mstari-1).':'.getColumn($temp_clmn+1).($mstari))->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('FFFF0000');
		
		$shift_aclmn[$shift_keys[$x]] = $temp_clmn;
		
		$temp_clmn++;
	}
	
	#change 3
	$sql_all_task = "SELECT * FROM allocation WHERE enabled = '1'";
	#end c3
	
	$query_all_task = mysql_query($sql_all_task) or die(mysql_error());
	
	$task_total = getColumn($temp_clmn-1);
	$t_task = getColumn($temp_clmn);	
	
	$sheet->setCellValue($t_task.$mstari,"Totals");
	
	#change 4
	if(mysql_num_rows($query_all_task) == 0){
		$trow = mysql_num_rows($query_all_task) + 2 +$mstari;
	}else{
		$trow = mysql_num_rows($query_all_task) + 1 +$mstari;
	}
	#end c4
	
	//echo $trow; exit;
	$sheet
		  ->setCellValue("A".$trow,"Totals")
		  ->setCellValue($t_task.$trow,"=SUM(".$t_task.($mstari+1).":".$t_task.($trow-1).")");
	
	$sheet->getStyle("A".$trow.":".$t_task.$trow)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	$sheet->getStyle("A".$trow.":".$t_task.$trow)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	
	$sheet->getStyle("B".$trow.":".$t_task.$trow)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('FFDDA0DD');
	$mstari++;
	
	$task_row = array();
	while($row = mysql_fetch_array($query_all_task)){
		$sheet
				->setCellValue("A".$mstari,ucfirst($row['name']));
		$task_row[$row['id']] = $mstari;		
		$sum = "=IF(SUM(B".$mstari.":".$task_total.$mstari.") >0, SUM(B".$mstari.":".$task_total.$mstari."),\"-\")";	
		$sheet->setCellValue($t_task.$mstari,$sum);
		$sheet->getStyle('B'.$mstari.':'.$t_task.$mstari)->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('FFFFE4C4');
		$mstari++;
	}

	#exit;
	
	#change 5
	$sql_m = "SELECT manpower.job_id, manpower.manpower, shift.name AS shift_name, shift.id AS shift_id, allocation.name AS job_name "
			."FROM manpower,allocation,shift "
			."WHERE date = '{$today}' "
			."AND manpower.job_id = allocation.id "
			."AND manpower.shift_id = shift.id AND allocation.enabled = '1'";
	#end c5		
	$query_m = mysql_query($sql_m) or die(mysql_error());

	$printed_jobs = array();
	$all_jobs = array();
	
	while($row = mysql_fetch_array($query_m))
	{	
		if(!in_array($row['job_id'],$printed_jobs)){
			$printed_jobs[] = $row['job_id'];
		}
		
		$column = getColumn($shift_aclmn[$row['shift_id']]);
		
		$sheet->setCellValue($column.$task_row[$row['job_id']],$row['manpower']);
	}
	
	$mstari++;
	
	#change 6
	if(mysql_num_rows($query_all_task) == 0){
		$mstari++;
	}
	#end c6
	$sheet
			->setCellValue("B".$mstari,"NB: This total must equal the number of present");
	
	$mstari+=2;	
	$sheet
					->setCellValue("A".$mstari,"Names of Absentees");
	$mstari++;
	
	$sql_abs = 	"SELECT * "
			   ."FROM attendance,employee,attendance_status "
			   ."WHERE attendance_status.name = 'absentees' "
			   ."AND attendance.status = attendance_status.id "
			   ."AND attendance.employeeid = employee.id "
			   ."AND date = '{$today}'";
	
	$query_abs = mysql_query($sql_abs) or die(mysql_error());
	
	while($row = mysql_fetch_array($query_abs)){
			$sheet
						->setCellValue("A".$mstari,$row['firstname']." ".$row['middlename']." ".$row['surname']);
			$mstari++;
	}
	
	for($x = 66; $x < 80; $x++){
		$objPHPExcel->getActiveSheet()
					->getStyle(chr($x)."1:".chr($x)."50")->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	}
	//*/
	
	
	$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);		
	$sheet;	


?>
