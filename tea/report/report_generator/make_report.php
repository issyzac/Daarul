<?php
error_reporting(0);
set_time_limit(5 * 60);
$report_type = $_REQUEST['report_type'];

include("../../db_connection.php");
include("classes/PHPExcel.php");
$objPHPExcel = new PHPExcel();

if($report_type == 'Production'){
	$start_date = "{$_GET['startdate']}";
	$end_date = "{$_GET['enddate']}";
	include("daily.php");
	include("monthly.php");
	include("blendingmaterials.php");
	include("packingmaterials.php");
	include("kpi.php");
	include("variance.php");
}else if($report_type == 'Attendance')
{
	include("man_power.php");
}
elseif($report_type =="material"){
	include("material.php");}else if($report_type == 'Stock')
{
	include("stock.php");
}

$datetime = date('Y-m-d_h-i-s');
$report_link = '../report_files/'.$report_type.'_Report_'.$datetime.'.xls';

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($report_link);


echo'
<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />
Report successfully generated, <a href="report/report_files/'.$report_type.'_Report_'.$datetime.'.xls"> Click here to download </a>
</center>';
