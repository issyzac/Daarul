<?php
	$today = date('Y-m-d');
	$no_record = false;
	
	if(isset($_POST['change'])){
		require_once "../db_connection.php";
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			
			$sql_tempx = "SELECT sum(manpower) AS sum FROM manpower WHERE date = '{$today}' AND shift_id = '{$id}'";
			$query_tempx = mysql_query($sql_tempx) or die(mysql_error());
			$totalx = 0;
			$totalx = mysql_fetch_assoc($query_tempx);
			$totalx = $totalx['sum'];
			
			if($totalx == NULL){
				$totalx = 0;
			}
		
			echo getTable($id)."::".$totalx;
			if($no_record){
				echo "<br/><br/><b style = 'color:red'>Error: No Job Description, Configure first</b>";
			}	
		}else{
				echo "<strong style = 'color:red'>Error:No shift found,configure shift first</strong>";
		}
		exit;
	}
	
	if(isset($_POST['total'])){
		require_once "../db_connection.php";
		if(isset($_POST['id'])){
			$shiftid = $_POST['id'];
			
			$sql_abs = "SELECT count(employeeId) AS total FROM attendance WHERE date = '{$today}' AND shiftid = '{$shiftid}'";
			$query_abs = mysql_query($sql_abs) or die(mysql_error());
			$total_arr = mysql_fetch_assoc($query_abs);
			$total_abs = $total_arr['total'];
			
			$sql_total = "SELECT noemployee FROM shift WHERE id = '{$shiftid}'";
			$query_total = mysql_query($sql_total) or die(mysql_error());
			$total_arr = mysql_fetch_assoc($query_total);
			$total = $total_arr['noemployee'];
			
			$present = $total - $total_abs;
			echo $present;
		}
		exit;
	}
?>
<script src = 'allocation/jquery.js'></script>
<script>
    
    $(document).ready(function() {
        id = ($('input[name=shift]:checked').val());
		changeTable(id);	
	});
	
	function checkSum(id){
		total = 0;
		form = document.getElementById('xxx');
		for(x = 0; x < form.length; x++){
			if(form[x].type == 'text'){
				if(form[x].value != 0){
					num = (parseInt(""+form[x].value));
					total = total + num;
				}
			}
		}
		
		present = $('span[name=present]').html();
		
		if(total > present){
			alert("You have exceeded total value of employee available for this shift");
		}else{
			$('span[name=total]').html(total);
		}
	}
	
	function changeTotal(id){
	
		$.post("allocation/manpower.php",{total:"total",id:id},
				function(data){
					$('span[name=present]').html(data)
				},"html");
	}

	function changeTable(id){
		$.post("allocation/manpower.php",{change:"change",id:id},
				function(data){
					data_arr = data.split("::");
					$("#table").html(data_arr[0]);
					$("span[name=total]").html(data_arr[1]);
				},"html");
		changeTotal(id);		
	}
	
	function validate(){
		
		total = 0;
		form = document.getElementById('xxx');
		for(x = 0; x < form.length; x++){
			if(form[x].type == 'text'){
				if(form[x].value != 0){
					num = (parseInt(""+form[x].value));
					total = total + num;
				}
			}
		}
		present = $('span[name=present]').html();
		
		if(total > parseInt(present)){
			alert("Total manpower exceed total number of present employee");
			return false;
		}else{
			return true;
		}
	}
</script>
<div style = 'width:100%' align = 'center'>
<?php

	require_once "./db_connection.php";
	$no_record = false;
	
	if(isset($_POST['submit'])){
		
		$keys = array_keys($_POST['ids']);
		$shiftid = $_POST['shift'];
		var_dump($_POST);
		//echo $shiftid;
		//exit;
		for($x = 0; $x < count($keys); $x++){
			if(!empty($_POST['ids'][$keys[$x]]) || ($_POST['ids'][$keys[$x]]) == 0){
				if(($_POST['ids'][$keys[$x]] - 0) > 0 || ($_POST['ids'][$keys[$x]] == "0")){
					$sqlx = "SELECT * FROM manpower WHERE job_id = '{$keys[$x]}' AND date = '{$today}' AND shift_id = '{$shiftid}'";
					$queryx = mysql_query($sqlx) or die(mysql_error());
				
					if(mysql_num_rows($queryx) == 0){
						$sql =	"INSERT INTO manpower (job_id,manpower,date,shift_id) VALUES ('".$keys[$x]."','".$_POST['ids'][$keys[$x]]."','".$today."','".$shiftid."')";
					}else{
						$sql = "UPDATE manpower SET manpower = '{$_POST['ids'][$keys[$x]]}' WHERE date = '{$today}' AND shift_id = '{$shiftid}' AND job_id = '{$keys[$x]}'";
					}
					mysql_query($sql) or die(mysql_error());
				}
			}
			$_SESSION['lastx'] = $shiftid;
		}
		$_SESSION['jobsucc'] = "manpowersucc";
		header("Location:?opt=man_power");
		exit;
	}
	
	$sql_shift = "SELECT * FROM shift WHERE status = 1";
		
	$query_shift = mysql_query($sql_shift) or die(mysql_error());
	
	 echo "<form action = '?opt=man_power' method = 'POST' onSubmit = 'return validate()' name = 'manpower_form' id = 'xxx'>"
		 ."<table width = '100%'>"
		 ."<tr style='color:#ffffff; background-color:#008010'><td colspan = '".(mysql_num_rows($query_shift)+1)."'><b>Select shift</b></td></tr>"
		 ."<tr>"
		 .""
		 ."<td align = 'left'>";
	$c = 1;
	while($row = mysql_fetch_assoc($query_shift)){
			if($c == 1 && !isset($_SESSION['lastx'])){
				$shiftid = $row['id'];
				echo "<input style = 'margin-left:20px;' type = 'radio' name = 'shift' value = '{$row['id']}' checked='checked' onchange = 'changeTable(\"{$row['id']}\")'/> {$row['name']}";
				$c = 0;
			}else if(isset($_SESSION['lastx']) && $_SESSION['lastx'] == $row['id']){
				$shiftid = $row['id'];
				echo "<input style = 'margin-left:20px;' type = 'radio' id = 'shift' name = 'shift' value = '{$row['id']}' checked='checked' onchange = 'changeTable(\"{$row['id']}\")'/> {$row['name']}";
			}else{
				echo "<input style = 'margin-left:20px;' type = 'radio' id = 'shift' name = 'shift' value = '{$row['id']}' onchange = 'changeTable(\"{$row['id']}\")'/> {$row['name']}";
			}
	}
	
	//echo $sql_tempx;
	//echo mysql_num_rows($query_tempx);
	echo "</td>"
		."<td>"
		."<span><strong style = 'color:black; font-size:14px' ><i>Total number of present employee for this shift:</i></strong> "
		."<strong><span name = 'present'></span><br/>"
		."<span><strong style = 'color:black; font-size:14px' ><i>Total number of employee assigned to tasks:</i></strong> "
		."<strong><span name = 'total'></span>"
		."</strong>"
		."</td>"
		."</tr>"
		."</table>";
	
	if(isset($shiftid)){	
		$table = getTable($shiftid);
	}else{
		$table = "";
	}

	echo "<div>"
		."<table width= '100%'>"
		."<tr>"
		."<td width = '60%' align = 'left'><div id = 'table'>{$table}</div></td>"
		."<td align = 'center' id = 'succ'>";
		
		if(isset($_SESSION['jobsucc'])){
			
				if($_SESSION['jobsucc'] == "manpowersucc"){ 
					echo '<img src="images/ok.png" alt="Ok..." height = "100px"/>';
					echo '<br/>Sucessfully saved.'
						.' ';
				}
		}
		
	echo "</td>"
		."</tr>"
		."</table>"
		."</div>"
		."</form>";

		
	function getTable($shiftid){
		global $today;
		$leo = $today;
		$string = "";
		
		$sql_t = "SELECT * FROM manpower WHERE date = '{$leo}'  AND shift_id = '{$shiftid}' ";
		
		$query_t = mysql_query($sql_t) or die(mysql_error());
	
		$num_rows = mysql_num_rows($query_t);
		
		$sql =  "SELECT * FROM allocation ";
		$query = mysql_query($sql."WHERE enabled = 1") or die(mysql_error());
		
		$manpower = array();
		
		if(mysql_num_rows($query) == 0){
			global $no_record;
			$no_record = true;
		}
		
		if( $num_rows > 0){
			$sql .= ",manpower WHERE allocation.id = manpower.job_id AND date = '{$leo}' AND shift_id = '{$shiftid}' AND enabled = 1";
			$query_man = mysql_query($sql) or die(mysql_error());
		
		
		
			while($row = mysql_fetch_assoc($query_man)){
				$manpower[$row['job_id']] = $row['manpower'];	
			}
			global $no_record;
			if(!$no_record){
				$leo_done = "<strong style = 'color:blue;font-size:14px;'>Manpower have already been assigned, with following values.</strong>";
			}else{
				$leo_done = "";
			}
			
		}else{
			$leo_done = "";
		}
		

			
		$string .=  "<table style = 'width:100%'>"
					."<tr>"
					."<td colspan = '2'>{$leo_done}</td>"
					."</tr>";
		while($rows = mysql_fetch_assoc($query)){
			$string .= "<tr>"
					."<td align = 'right'><i>{$rows['name']}:</i></td>"
					."<td>"
					."<input type = 'text' id = '{$rows['id']}' ";
					
			if(array_key_exists($rows['id'],$manpower)){
				$string .= "value = '{$manpower[$rows['id']]}' ";
			}
					
			$string .= " name = 'ids[".$rows['id']."]' onChange = 'checkSum(this.id)'/>"
					."</td>"
					."</tr>";
		}
		
		$string .="<tr>"
				. "<td colspan = '2' align = 'center'>";
		global $no_record;		
		if(!$no_record){		
			$string	.="<input type = 'submit' ";
				
			if($num_rows > 0){
				$string .= " value = 'Update' ";
			}else{
				$string .= " value = 'Submit' ";
			}
					
			$string .= " name = 'submit'/>";
		}
		
		$string .="</td>"
				."</tr>"
				."</table>";
		return $string;
	}

	if(isset($_SESSION['jobsucc'])){
		unset($_SESSION['jobsucc']);
	}
?>
</div>
<script>
	$('input[name=shift]').change(
		function () {
			$('#succ').html("");
		}
	);
	
</script>
