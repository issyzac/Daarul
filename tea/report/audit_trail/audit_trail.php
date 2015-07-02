<script type="text/javascript" language="javascript">
	function validate(){
		var idate=document.getElementById('datepick').value.toString();
		var fdate=document.getElementById('datepick2').value.toString();//document.forms['frmaudit']['datepick2'].value.toString();
		if(idate.length==0||fdate.length==0){
			alert("You must enter the initial and final Dates");
			return false;
		}
		else return true;
	}
	function getDate(){
		document.getElementById('initialdate2').value=document.getElementById('datepick').value;
		document.getElementById('finaldate2').value=document.getElementById('datepick2').value;
		return validate();
	}
</script>
<form action="#" method="post" name="audit" id="audit" onsubmit="return validate()" id="frmaudit" name="frmaudit">
	<table>
		<tr><td colspan="2" align="center" >Select a Time range:</td></tr>
		<tr><td>From: <input type="text" id="datepick" name="datepick" size="8" readonly="readonly" style="cursor:pointer"/></td><td>To: <input type="text" id="datepick2" name="datepick2" size="8" readonly="readonly" style="cursor:pointer"/><input type="submit" value="View Logs" id="view" name="view" /></td></tr>
	</table>
</form>
<!--DATE HANDLING-->
<script type="text/javascript" src="report/datepickr.js"></script>
<script type="text/javascript">
	new datepickr('datepick', {
		'dateFormat': 'Y-m-d'
	});
	new datepickr('datepick2', {
		'dateFormat': 'Y-m-d'
	});
</script>
<form action="report/audit_trail/audit_report.php" id="audit_report" name="audit_report" onsubmit="return getDate()" method="post">
	<input type="hidden" name="initialdate2" id="initialdate2" value="" /><input type="hidden" name="finaldate2" id="finaldate2" value="" />
	<input type="submit" id="download" value="Download Report" name="download" onclick="redirect()" />
</form>
<?php
	if(isset($_POST['view'])){
		$sql="select firstname,middlename,surname,event,date,time,item_id,table_name from event,employee where employee.id=event.employee_id AND date BETWEEN'{$_POST['datepick']}' AND '{$_POST['datepick2']}' ORDER BY date asc,time ASC";
		$result=mysql_query($sql);
		$name="";
		function getName($table,$item_id){
			$resultName="select name from {$table} where id='{$item_id}'";
			$itemResult=mysql_query($resultName);
			if($itemResult){
				$itemRow=mysql_fetch_array($itemResult);
				$name=$itemRow['name'];
			}else{
				$name="";
			}
			return $name;
		}
		if($result&&mysql_num_rows($result)>0){
			echo "<table border='1'>";
			echo "<tr style='color:#ffffff; background-color:#008010' ><th>#</th><th>NAME</th><th>DATE</th><th>TIME</th><th>EVENT</th><th>AFFECTED</th></tr>";
			$i=1;
			while($row=mysql_fetch_array($result)){
				$table=$row['table_name'];
				$name="";
				$item_id=$row['item_id'];
				if(strtolower($table)=="user"||strtolower($table)=="employee"){
					$resultName="select firstname,middlename,surname from employee where id='{$row['item_id']}'";
					$itemResult=mysql_query($resultName);
					$itemRow=mysql_fetch_array($itemResult);
					$name="{$itemRow['surname']},{$itemRow['firstname']}{$itemRow['middlename']}";
				}
				else if(strtolower($table)!="system"){
					$name=getName($table,$item_id);
				}
				else if(strtolower($table)=="product_stock"){
					$table="product";
					$name=getName($table,$item_id);
				}
				else if(strtolower($table)=="material_stock"){
					$table="material";
					$name=getName($table,$item_id);
				}
				if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
				echo "<td>{$i}</td><td>{$row['surname']}, {$row['firstname']} {$row['middlename']}</td><td>{$row['date']}</td><td>{$row['time']}</td><td>{$row['event']}</td><td>{$name}</td></tr>";
				$i++;
			}
			echo "</table>";
		}
	}
?>