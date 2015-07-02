<?php
	@session_start();
	require_once "../db_connection.php";
	$today = date('Y-m-d');
		
	if(isset($_POST['cTotal'])){
		$id = $_POST['id'];
		$total = $_POST['total'];
		
		$_SESSION['total'][$id] = $total;
		exit;
	}
	
	if(isset($_POST['remove'])){
		$id = $_POST['id'];
	
		if(!empty($_SESSION['attendance']) && in_array($id,$_SESSION['attendance'][$_SESSION['last']])){
			$key = array_search($id,$_SESSION['attendance'][$_SESSION['last']]);
			array_splice($_SESSION['attendance'][$_SESSION['last']],$key,1);
		}else{
			$sql_remove = "DELETE FROM attendance WHERE date = '{$today}' AND employeeid = '{$id}'";
			mysql_query($sql_remove) or die(mysql_error());
		}
		echo $_SESSION['last'];
		exit;
	}
	
	if(isset($_POST['shift'])){
		if(!isset($_POST['id'])){
			echo "<strong style = 'color:red'>Error: No shift found, configure shift first</strong>";
			exit;
		}
		$id = $_POST['id'];
		
		if(!isset($_SESSION['emp_shift'])){
			$_SESSION['emp_shift'] = array();
		}
		
		if(!isset($_SESSION['attendance'])){
			$_SESSION['attendance'] = array();
		}

		if(!isset($_SESSION['last'])){
			$queryxx = mysql_query('SELECT id FROM attendance_status LIMIT 0,1') or die(mysql_error());
			$resultxx = mysql_fetch_array($queryxx);
			$_SESSION['last'] = $resultxx['id'];
		}
		
		$_SESSION['shift'] = $id;
		
		if(!isset($_SESSION['last'])){
			$query_p = mysql_query("SELECT id FROM attendance_status WHERE name = 'present'");
			$result = mysql_fetch_assoc($query_p);
			$_SESSION['last'] = $result['id'];
		}
		$query = mysql_query("SELECT name FROM shift WHERE id = '{$id}'") or die(mysql_error());
		
		$name_arr = mysql_fetch_assoc($query);
		
		$name = $name_arr['name'];
		
		load();
		if(isset($_SESSION['total'][$id])){
			$total = $_SESSION['total'][$id];
		}else{
			$sql_emp = "SELECT * FROM employee_shift WHERE shift_id = '{$id}' AND date = '{$today}'";
			$query_emp = mysql_query($sql_emp) or die(mysql_error());
			if(mysql_num_rows($query_emp) > 0){
				$emp_total = mysql_fetch_array($query_emp);
				$total = $emp_total['total_emp'];	
			}else{
				$total = "";		
			}
			
		}
		echo "::".$name;
		$query_noemployee = mysql_query("SELECT noemployee FROM shift WHERE id = '{$id}'") or die(mysql_error());
		$noemployee = mysql_fetch_assoc($query_noemployee);
		$noemployee = $noemployee['noemployee'];
		
		$query_status = mysql_query("SELECT id,name FROM attendance_status") or die(mysql_error());
		
		$xxx = "<table><tr><td align = 'right'><i><strong style = 'color:black; font-size:13px'>Total emp: {$noemployee}</strong></i></td></tr>";
		
		while($rowx = mysql_fetch_assoc($query_status)){
			$query_att = mysql_query("SELECT count(employeeId) AS noemployee FROM attendance "
								  ."WHERE date = '{$today}' AND shiftid = '{$id}' AND status = '{$rowx['id']}'") or die(mysql_error());
			
			$att = mysql_fetch_assoc($query_att);
			$att = $att['noemployee'];
			if($att == NULL)
				$att = 0;
			
			$xxx .="<tr><td align = 'right'><i><strong style = 'color:black; font-size:13px'>Total {$rowx['name']}: {$att}</strong></i></td></tr>";
		}
		$query_mpower = mysql_query("SELECT sum(manpower) AS manpower FROM manpower WHERE shift_id = '{$id}' AND date = '{$today}'") or die(mysql_error());
		$manpower = mysql_fetch_assoc($query_mpower);
		$manpower = $manpower['manpower'];
		if($manpower == NULL){
			$manpower = 0;
		}
		$xxx .= "<tr><td align = 'right'><i><strong style = 'color:black; font-size:13px'>Total employee on tasks: {$manpower}</strong></i></td></tr>";
		$xxx .= "</table>";
		echo "::".$xxx;
		exit;
 	}
	if($_POST['type'] == "empty"){
		echo "<b style='color:red'>Error: No attendace status found, configure attendance first</b>";
		exit;
	}else{
		$type = $_POST['type'];
	}
	if(!isset($_SESSION['attendance']))
	{
		$_SESSION['attendance']=array();
	}
	
	if(!isset($_SESSION['attendance'][$type]))
	{	
		$_SESSION['attendance'][$type]=array();	
		$_SESSION['last'] = $type;
	}else{
			$_SESSION['last'] = $type;
	}		
	
	if(!isset($_SESSION['shift'])){
		$sql = "SELECT * FROM shift WHERE status = 1";
		$query = mysql_query($sql) or die(mysql_error());
		if(mysql_num_rows($query) > 0)
			echo "<strong style = 'color:red' style ='margin-top:10px'>Plese select shift first</strong>";
		exit;
	}
	
	if(isset($_POST['id']))
	{
		$id = $_POST['id'];
		$_SESSION['attendance'][$_SESSION['last']][] = $id;
		$_SESSION['emp_shift'][$id] = $_SESSION['shift'];		
		//echo "id =>".$id;
	}
	
	load();
	
	
function load(){

	global $type,$today;
	$session_empty = true;
	$empty = true;
	
	$count = 0;	
	
	$sql = "SELECT * FROM attendance_status WHERE id = '{$_SESSION['last']}' ";
	//echo $sql;
	$query = mysql_query($sql) or die(mysql_error());
	
	$result = mysql_fetch_array($query);
	$att_name = $result['name'];

	echo "<table width = '300px' border = '1'style = 'margin-top:10px'>"
		."<tr><td style='color:#ffffff; background-color:#008010' colspan = '2'>Employee selected as <strong>".ucfirst($att_name)."</strong> for today</td></tr>";
	
	if(isset($_SESSION['attendance'][$_SESSION['last']])){
		for($x = 0; $x < count($_SESSION['attendance'][$_SESSION['last']]); $x++)
		{
			$sql_emp = "SELECT * FROM employee WHERE id = '".$_SESSION['attendance'][$_SESSION['last']][$x]."'";
			//echo $sql_emp;
			$query = mysql_query($sql_emp) or die(mysql_error());
			$row = mysql_fetch_array($query);
			
			if(isset($_SESSION['emp_shift'][$_SESSION['attendance'][$_SESSION['last']][$x]]) AND $_SESSION['emp_shift'][$_SESSION['attendance'][$_SESSION['last']][$x]] == $_SESSION['shift']){
				$count++;
				echo "<tr><td width='30' class = 'tbl_select'>".$count
					."</td><td bgcolor = '#FFFFFF' class = 'tbl_select' ondblclick='removeEmp(\"{$row['id']}\")'>"
					.$row['firstname']." ".$row['middlename']." ".$row['surname']."</td></tr>";
				$empty = false;
			}
		}
		if(!empty($_SESSION['attendance'][$_SESSION['last']]))
			$session_empty = false;
	}
	
	$not_in = "";
	if(isset($_SESSION['attendance'][1]) && !empty($_SESSION['attendance'][1]))
	{
		$in_session = $_SESSION['attendance'][1];//employee in session_variable;
		$not_in = "AND employee.id  NOT IN ('".trim(implode("','",$in_session),",'")."')";
	}
		
	$sql = "SELECT * FROM attendance,employee WHERE employee.id = attendance.employeeid AND date = '{$today}' AND attendance.status = '{$_SESSION['last']}' AND shiftid = '{$_SESSION['shift']}' ".$not_in;
	$query = mysql_query($sql) or die(mysql_error());
	//echo $sql;
	$num_rows = mysql_num_rows($query);
	
	while($row = mysql_fetch_array($query)){
		$count++;
		echo "<tr><td width='30' class = 'tbl_select'>".$count
			."</td><td bgcolor = '#FFFFFF' class = 'tbl_select' ondblclick='removeEmp(\"{$row['id']}\")'>"
			.$row['firstname']." ".$row['middlename']." ".$row['surname']."</td></tr>";
	}
	
	if(mysql_num_rows($query) > 0){
		$empty = false;
	}
	
	if($empty){
		echo "<tr><td width='30' class = 'tbl_select'></td><td class = 'tbl_select' bgcolor = '#FFFFFF' style = 'color:orange'><strong>No employee selected yet</strong></td></tr></table>";
	}
	
	echo "</table>"; 
	
	
	$sql = "SELECT * FROM attendance,employee WHERE employee.id = attendance.employeeid AND date = '{$today}' AND shiftid = '{$_SESSION['shift']}'";
	$query = mysql_query($sql) or die(mysql_error());
	
	
	if(mysql_num_rows($query) > 0 && !$session_empty){
		if(in_array($_SESSION['shift'],$_SESSION['emp_shift']))
			echo "::"."<strong style = 'color:orange'><i>But there are changes made, and are not saved, to save those changes hit save button</i></strong>";
	}else if(!$session_empty){
		if(!empty($_SESSION['attendance'][$_SESSION['last']]) && in_array($_SESSION['shift'],$_SESSION['emp_shift']))
			echo "::"."<strong style = 'color:orange'>Attendance for this shift not saved</strong>";
	}
	

}	
?>
