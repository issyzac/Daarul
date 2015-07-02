<script language="javascript" type="text/javascript">
	function validateData(){
		 	var name=document.getElementById("name").value.toString();
			if(name.length==0){
			alert("Please BlendName can not be empty");
			return false;
			}
			else
			return true;
		}
	function confirmDelete(){
		if(confirm("Are you sure you want to remove this Blend?")) return true;
		else return false;
	}
</script>
<?php
	if(isset($_GET['reply'])) echo $_GET['reply'];
?>
<form action="#" method="post" onsubmit="return validateData()" name="blend">
<fieldset style="text-align:center">
<legend align="center">BLEND REGISTRATION</legend>

Blend Name:<input type="text" id="name" name="name" />
<input type="Submit" id="btnSubmit" name="btnSubmit" value="Submit"/>

</fieldset>
</form>
<?php 
$reply="";
 if(isset($_POST['name'])){
		$sql="INSERT INTO blend (name) VALUES ('{$_POST['name']}')"; 
		if (!mysql_query($sql)){
			die('Error: ' . mysql_error());
		}else{
			$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Registered a new Blend','".date('Y-m-d')."','".date('H:i:s')."','','blend')";
			mysql_query($auditSql);
			$reply= '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />Blend Registration successful </p><br /></center>';
		}
		
			header("Location:?opt=blend&reply=$reply");
	
		}

	if(isset($_GET['delid']))
	{
		$query = mysql_query("Update blend set status='0' WHERE id='{$_GET['delid']}'");
		if($query)
		{
			$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Deleted item from table','".date('Y-m-d')."','".date('H:i:s')."','{$_GET['delid']}','blend')";
			mysql_query($auditSql);
				$reply= '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />Blend successfully  deleted</p><br /></center>';
		}
		header("Location:?opt=blend&reply=$reply");
	
		
	}
	if(isset($_GET['editid']))
	{
		$query = mysql_query("UPDATE blend SET name='{$_POST['name2']}' WHERE id='{$_GET['editid']}'");
		if($query)
		{
		$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Edited item from table','".date('Y-m-d')."','".date('H:i:s')."','{$_GET['editid']}','blend')";
		mysql_query($auditSql);
			
		$reply= '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />Blend successfully  edited</p><br /></center>';
		}
		header("Location:?opt=blend&reply=$reply");
	}
	$query = mysql_query("SELECT * FROM blend  where status='1'");
	echo "<table border='1'><tr style='color:#ffffff; background-color:#008010'><th>#</th><th style='width:100px'> Name</th>
	<th style='width:200px'>Products</th> <th style='width:200px' >Action</th></tr>"; 
	$i = 1;
	while($row= mysql_fetch_array($query))
	{
		if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
		echo "<td>$i</td>";
		echo "<td style='width:200px'>{$row['name']}</td>";
		echo "<td style='width:100px'><a href='?opt=product&viewid={$row['id']}' ><img src='images/browse.png'alt='edit' /> View Products</a></td>";
		echo "<td><a href='?opt=edit_blend&editid={$row['id']}' ><img src='images/edit.png'alt='edit' /> Edit</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
		<a href='?opt=blend&delid={$row['id']}' onclick='return confirmDelete()'> <img src='images/delete.png' alt=delete/> Delete</a></td>";
		echo "</tr>";
		$i++;
	}
	echo "</table>";
?>
