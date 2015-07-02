
		<script language="javascript" type="text/javascript">
		 function validateData(){
		 	var shiftName=document.getElementById("name").value.toString();
			var startTime=document.getElementById("starttime").value.toString();
			var endTime=document.getElementById("endtime").value.toString();
			if(shiftName.length==0||startTime.length==0||endTime.length==0){
				alert("Please Enter complete Details");
				return false;
			}
			else
			return true;
		 }
		 function del()
		 {
		 if(confirm("Do you want to delete this item"))
		 {
		 return true;
		 }
		 else
		 return false;
		 }
		</script>
		<?php
		if(isset($_GET['reply'])) echo $_GET['reply'];
		?>
		<form action="#" method="post" name="frmShift" onSubmit="return validateData()">
	<fieldset>
		<legend align="center">SHIFT REGISTRATION"</legend>
		<table>
		<tr><td align = 'right'><i>Shift Name:</i></td><td><input type="text" id="name" name="name" placeholder="Name"/></td></tr>
		<tr><td align = 'right'><i>Start Time:</i></td><td><input type="text" id="starttime" name="starttime" style="cursor:pointer" placeholder="HH:MM:SS"/></td></tr>
		<tr><td align = 'right'><i>End Time:</i></td><td><input type="text" id="endtime" name="endtime" style="cursor:pointer" placeholder="HH:MM:SS"/></td></tr>
		<tr><td align = 'right'><i>Number of employee:</i></td><td><input type="text" id="noemployee" name="noemployee" placeholder="Number"/></td></tr>
		<tr><td></td><td>

		<input type="submit" id="btnSubmit" value="Submit" name="btnSubmit"/></td></tr>
		</table>
	</fieldset>
</form>
<script type="text/javascript" language="javascript" src="jquery/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="jquery/jquery.timepicker.css" />
<script type="text/javascript" language="javascript" src="jquery/jquerytimepicker.js"></script>
<script type="text/javascript" language="javascript">
	$('#starttime').timepicker();
	$('#endtime').timepicker();
</script>

<?php
	$reply="";
	 
	if(isset($_POST['btnSubmit'])){
	
	$sql="INSERT INTO shift (  name, starttime,endtime,noemployee) VALUES ('$_POST[name]','$_POST[starttime]','$_POST[endtime]','{$_POST['noemployee']}')"; 
	if (!mysql_query($sql)){
		die('Error: ' . mysql_error());
	}else{
		$result=mysql_query("select max(id) from shift");
		$shiftid=mysql_fetch_array($result);
		$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Registered a new shift','".date('Y-m-d')."','".date('H:i:s')."','{$shiftid['max(id)']}','shift')";
		mysql_query($auditSql);
		$reply= '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />Shift added successfully  </p><br /></center>';
	}
   
	header("Location:?opt=shift&su& reply=$reply ");
	if(isset($_GET['su'])){
		echo "success";
	}
	}
		
	if(isset($_GET['delid']))
	{
		$query = mysql_query("Update shift set status='0' WHERE id='{$_GET['delid']}'");
		if($query)
		{
		
			$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Deleted item from table','".date('Y-m-d')."','".date('H:i:s')."','{$_GET['delid']}','shift')";
			mysql_query($auditSql);
			$reply= '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />Shift successfully deleted</p><br /></center>';
		}
		header("Location:?opt=shift&su& reply=$reply ");
		
	}
	if(isset($_GET['editid']))
	{
		$query = mysql_query("UPDATE shift SET name='{$_POST['name']}',starttime='{$_POST['starttime']}',endtime='{$_POST['endtime']}',noemployee = '{$_POST['noemployee']}' WHERE id='{$_GET['editid']}'");
		if($query)
		{
			$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Edited item from table','".date('Y-m-d')."','".date('H:i:s')."','{$_GET['editid']}','shift')";
			mysql_query($auditSql);
			$reply= '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />Shift successfully edited </p><br /></center>';
		
		}
		header("Location:?opt=shift&su& reply=$reply ");
	}
	$query = mysql_query("SELECT * FROM shift where status='1'");
	if(mysql_num_rows($query)>0){
	echo "<table border='1'><tr style='color:#ffffff; background-color:#008010'><td>#</td><td style='width:200px'>Name</td><td style='width:100px'>Starttime</td> <td style='width:100px'>Endtime</td><td style='width:100px'>NO:- Employee</td><td colspan='2' align = 'center' style='width:200px'>Action</td></tr>";
	$i = 1;
	while($row= mysql_fetch_array($query))
	{
		if($i% 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
		echo "<td>$i</td>";
		echo "<td>{$row['name']}</td>";
		echo "<td>{$row['starttime']}</td>";
		echo "<td>{$row['endtime']}</td>";
		echo "<td align = 'center'>{$row['noemployee']}</td>";
		echo "<td><a href='?opt=edit_shift&editid={$row['id']}'><img src='images/edit.png'alt='edit' />Edit</a></td>";
		echo "<td><a href='?opt=shift&delid={$row['id']}' onclick='return del()' ><img src='images/delete.png' alt=delete/> Delete</a></td>";
		echo "</tr>";
		$i++;
	}
	echo "</table>";
	}
	else echo "No registered shift";
?>

	


