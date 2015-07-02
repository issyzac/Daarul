<script src = 'allocation/jquery.js'></script>
<script>
$(document).ready(
	function(){
		shift = ($('input[name=shift]:checked').val());
		selectShift(shift);
		att_type = ($('input[name=xxx]').val());
		loadPeople(att_type);
		
	}
);

function changeTotal(id){
	total = $('input[name=stotal]').val();
	$.post("allocation/alloc.php",{cTotal:"cTotal",id:id,total:total});
}

function loadPeople(type,id)
{
	name = document.getElementById("name").value;
		
	if(typeof(id) != 'undefined')
	{
		$.post("allocation/alloc.php",{type:type,id:id},
		function (data){
				arr = data.split("::");
				$("#employee").html(arr[0]);
				$("#message").html(arr[1]);
				searchEmp(name);
			},"html");
	}
	else
	{
		$.post("allocation/alloc.php",{type:type},
			function (data){
					arr = data.split("::");
					$("#employee").html(arr[0]);
					$("#message").html(arr[1]);
					searchEmp(name);
				},"html");
	}				
}
function removeEmp(id){
	name = document.getElementById("name").value;
	
	$.post("allocation/alloc.php",{remove:"remove",id:id},
			function (data){
					loadPeople(data);
					searchEmp(name);
			},"html");
}
function searchEmp(name){

		$.post("allocation/searchEmp.php",{name:name},
		function (data){ 
				$("#result").html(data);
			},"html");	

}

function selectShift(id){
	$.post("allocation/alloc.php",{shift:"shift",id:id}, 
		  function (data){
			arr = data.split("::");
			
			if(arr.length == 3){
				$("#employee").html(arr[0]);
				$("#active_shift").html("<strong style = 'color:black; font-size:13px'><i>Active shift : "+arr[1]+"</i></strong>");
				$("#succ").html(arr[2]);
				$("#message").html("&nbsp;");
			}
			else{
				$("#employee").html(arr[0]);
				$("#message").html(arr[1]);
				$("#succ").html(arr[3]);
				$("#active_shift").html("<strong style = 'color:black; font-size:13px'><i>Active shift : "+arr[2]+"</i></strong>");
			}
		  },"html");	
}
</script>
<style>
.tbl_select{
		border-top:groove;
		border-bottom:groove;
		border-left:groove;
		border-width:1px;
		border-color:#999999;
		font-size:14px;
		cursor:pointer;
	}
</style>

<div style = 'float:left'>
<?php	
	@session_start();

	$date = date("Y-m-d");	
	$all = "";
	if(isset($_POST['submit']))
	{
		$changes = false;
		
		if(isset($_SESSION['shift'])){
			$shift = $_SESSION['shift'];
		}else{
			header("Location:?opt=attendance&shift");	
			exit;
		}
		
		
		if(!isset($_SESSION['attendance']))
			header("Location:?opt=attendance");

		$keys = array_keys($_SESSION['attendance']);
		$emp_keys = array_keys($_SESSION['emp_shift']);
		
	
			$totalx = array();
			for($z = 0; $z < count($emp_keys); $z++){
				if(isset($_SESSION['emp_shift'][$emp_keys[$z]])){
					if(isset($totalx[$_SESSION['emp_shift'][$emp_keys[$z]]])){
						$totalx[$_SESSION['emp_shift'][$emp_keys[$z]]] += 1;
					}else{
						$totalx[$_SESSION['emp_shift'][$emp_keys[$z]]] = 1;
					}			
				}
			}
			$shift_keys = array_keys($totalx);
			
			for($x = 0; $x < count($shift_keys); $x++){
				
				
				$id = $shift_keys[$x];
				$absentees = $totalx[$id];
				
				$query_att = mysql_query("SELECT count(employeeid) AS total FROM attendance WHERE date = '{$date}' AND shiftid = '{$id}'") or die(mysql_error());
				
				$att = mysql_fetch_assoc($query_att);
				$att = $att['total'];
				if($att == NULL){
					$att = 0;
				}	
				$absentees += $att; #hawapo job per shift
				
				$query_total = mysql_query("SELECT noemployee,name FROM shift WHERE id = '{$id}'") or die(mysql_error());
				$total = mysql_fetch_assoc($query_total);
				$name = $total['name'];
				$total = $total['noemployee']; #expected per shift
				#echo "t".$total." "."a".$absentees."<br/>";
				if($total >= $absentees){
					$present = $total - $absentees;
					$query_manpower = mysql_query("SELECT sum(manpower) AS manpower FROM manpower WHERE shift_id = '{$id}' AND date = '{$date}'") or die(mysql_error());
					$manpower = mysql_fetch_assoc($query_manpower);
					$manpower = $manpower['manpower']; #manpower per this shift
					
					if($manpower == "NULL"){
						$manpower = 0;
					}
					
					if($present < $manpower){
						unset($_SESSION['attendance']);
						unset($_SESSION['emp_shift']);
						header("Location:?opt=attendance&errm={$name}");
						exit;
					}
				}else{
						unset($_SESSION['attendance']);
						unset($_SESSION['emp_shift']);
						header("Location:?opt=attendance&errx={$name}");
						exit;
				}
			
			}
		
		for($x = 0; $x < count($keys); $x++)
		{
			for($z = 0; $z < count($_SESSION['attendance'][$keys[$x]]); $z++)
			{	
				$sql = "INSERT INTO attendance (employeeid,date,status,shiftid) "
					  ."VALUES ('".$_SESSION['attendance'][$keys[$x]][$z]."','".$date."','".$keys[$x]."','".$_SESSION['emp_shift'][$_SESSION['attendance'][$keys[$x]][$z]]."') ";		
				mysql_query($sql);  
				$changes = true;
			}
			
		}
			
		
		unset($_SESSION['attendance']);
		unset($_SESSION['shift']);
		unset($_SESSION['emp_shift']);
		if($changes){
			$_SESSION['succ']  = "success";
		}
		header("Location:?opt=attendance");
		exit;
	}
	
	$today = date('Y-m-d');
	
	if(isset($_SESSION['succ'])){ 
					echo '<p style = "margin-left:50%" id = "tiki"><img src="images/ok.png" alt="Ok..." height = "100px"/>';
					echo '<br/>Sucessfully saved.</p>';
	}else{
		$sql_adb = "SELECT shift.name AS name FROM attendance,shift WHERE shift.id = attendance.shiftid AND date = '{$today}' GROUP BY attendance.shiftid";
		$query_adb = mysql_query($sql_adb) or die(mysql_error());
		if(mysql_num_rows($query_adb) > 0){
			$done = "";
			while($result = mysql_fetch_array($query_adb)){
				$done .= $result['name'].","; 
			}
			$done = trim($done,",");
			
			echo "<strong style = 'color:blue'><br/>Attendance for <i>".$done."</i> shift(s) already taken</br></strong>";
		}
	}

if(isset($_GET['errm'])){
	echo "<strong style = 'color:red; font-size:14px'><br/>Error: Total number of manpower allocated for shift {$_GET['errm']} <br/>exceed number present employee for this shift,<br/>attendance not saved</strong>";
}else if (isset($_GET['errx'])){
	echo "<strong style = 'color:red; font-size:14px'><br/>Error: Attendance for shift {$_GET['errx']} <br/>exceed total number of employees for this shift,<br/>attendance not saved</strong>";
}	
?>
</div>

<div align = 'right' id = 'succ'></div>

<div style ='weight:100%;'>
<div>
<p>&nbsp;</p>
<table style = 'width:100%'>
<tr>
<p id = 'message' style = 'margin:0px'>&nbsp;</p>
<td colspan = '2'>
<p id = 'active_shift' style = 'margin:0px'>
<?php
if(isset($_SESSION['shift'])){
	$query_shift = mysql_query("SELECT name FROM shift WHERE id = '{$_SESSION['shift']}'") or die(mysql_error());
	$result = mysql_fetch_array($query_shift);
	
	echo "<strong style = 'color:black; font-size:13px'><i>Active shift : {$result['name']}</i></strong>";
}else{
	echo "&nbsp";
}
?>
</p>
</td>
</tr>
<tr>
<td align = 'center'>
<table width = '300px'>
<?php
	$sql_shift = 'SELECT * FROM shift WHERE status = 1';
	$query_shift = mysql_query($sql_shift) or die(mysql_error());
	if(mysql_num_rows($query_shift) == 0){
		echo "<b style = 'color:red'>Error: No shift found, Configure shift first</b>";
	}else{
		echo  "<tr style='color:#ffffff; background-color:#008010'><td colspan = '".mysql_num_rows($query_shift)."'><strong>Select Shift</strong></td></tr>" 
			 ."<tr>";
		$xxx = 0;
		while($row = mysql_fetch_assoc($query_shift)){
			$checked = "";
			if($xxx == 0){
				$xxx = 1;
				$checked = "checked = 'checked'";
			}
			echo "<td align = 'center'><input type = 'radio' name = 'shift' id = 'shift' value = '{$row['id']}' onChange = 'selectShift(\"{$row['id']}\")' {$checked}/>&nbsp;{$row['name']}</td>";
		}
		echo "</tr>";
	}
?>
</table>
</td>
<td align = 'center'>
<table width="300px">
<?php
    $sql = "SELECT * "
          ."FROM attendance_status";

    $q_alloc = mysql_query($sql) or die(mysql_error());	
	$num_rows = mysql_num_rows($q_alloc);
	
    if($num_rows > 0)
    {
		echo "<tr><td colspan = '".$num_rows."' style='color:#ffffff; background-color:#008010; margin-top:10px' ><span><strong>Select Type</strong></span></td></tr>"
			."<tr>";
			
    	$a_row = mysql_fetch_assoc($q_alloc);	
		$xxx = 0;
		do
		{	
			if($xxx == 0){
				echo "<input type  = 'hidden' name = 'xxx' value = '{$a_row['id']}'/>";
			}
			
			if(isset($_GET['l']) && ($_GET['l'] == $a_row['id']))
			{
				$selected = "selected=selected";
			}
			else
			{
				$selected = "";		
			}
			echo "<td class='tbl_select' id = 'att_type' bgcolor = '#FFFFFF' width = '70' align = 'center' onClick='loadPeople(\"{$a_row['id']}\")'>".$a_row['name']."</td>";
		}while($a_row = mysql_fetch_assoc($q_alloc));
   } else{
   			echo "<input type  = 'hidden' name = 'xxx' value = 'empty'/>";
   }  

   mysql_free_result($q_alloc);  
?>
</tr>
</table>
</td>
</tr>
</table>
</div>

<div width = '100%'>
<table width = '100%'>
<tr>
<td style = 'width:50%' align = 'center' valign = 'top'>
<div id = 'employee'  width = '1000px' align = 'center' >

</div>
</td>
<td align = 'center' valign = 'top'>
<div id = 'search'  align = 'center'>
	<div style='margin-bottom:20px;'>
		<table width = '300px' style = 'margin-top:10px'>
		<tr><td colspan = '2' style='color:#ffffff; background-color:#008010; '><strong>Search for employee</strong></td></tr>
		<tr><td><i>Search employee:</i><input type = 'text' onKeyUp='searchEmp(this.value)' id = 'name' name = 'name'></td></tr>
		</table>
	</div>

	<div id = 'result' align = 'center'></div>
</div>
</td>
</tr>
</table>
</div>

</div>
<div style = 'width:100%; float:right; margin-top:20px'>
<form action = '?opt=attendance' method = 'POST'>
<input type = 'submit' name = 'submit' value = 'Save' >
</form>
</div>
<?php
	function update($id,$date,$total){
		$sql_update = "UPDATE employee_shift SET total_emp = '{$total}' "
					 ."WHERE date = '{$date}' AND shift_id = {$id} ";
					 
		//echo $sql_update;
		mysql_query($sql_update) or die(mysql_error()); 
	}
	
	if(isset($_SESSION['succ'])){
		unset($_SESSION['succ']);
	}
?>
<script>
$('input[type=radio]').change(function () {($('#tiki').html(""))});
</script>
