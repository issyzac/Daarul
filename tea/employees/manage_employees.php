<script language="javascript" type="text/javascript" >
	function confirmDelete(){
		if(confirm("Are you sure you want to Remove this Employee?")) return true;
		else return false;
	}
	function findnames(){
		var keyword=document.getElementById("search").value;
		var xmlhttp;
		if (window.XMLHttpRequest){	
			xmlhttp=new XMLHttpRequest();  // Initialize HTTP request
		}
		else {
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				document.getElementById("employees").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","employees/employee_list.php?search="+keyword+"&status=1",true);
		xmlhttp.send();	
	}
</script>
	<center><label for "search" >Search Employee:</label><input type="text" name="search" id="search" onkeyup="findnames()" />
<?php 

if(isset($_GET['reassign']))
{
	$Id = $_GET["editid"];
	$Name = $_GET['name'];
	
	$mat = 0;
	if(isset($_POST['mat'])) $mat = 1;
	$emp = 0;
	if(isset($_POST['emp'])) $emp = 1;
	$pro = 0;
	if(isset($_POST['pro'])) $pro = 1;
	$all = 0;
	if(isset($_POST['all'])) $all = 1;
	$sto = 0;
	if(isset($_POST['sto'])) $sto = 1;
	$rep = 0;
	if(isset($_POST['rep'])) $rep = 1;
	$conf = 0;
	if(isset($_POST['con'])) $conf = 1;
	
	$insertLogin =  mysql_query("UPDATE `pmngdb`.`user` SET materials = $mat, employees = $emp, production = $pro, allocation = $all, stock = $sto, reports = $rep, configurations = $conf  WHERE id ='$Id'") or die(mysql_error());
		
	if($insertLogin){
		echo '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />Privileges granted to '.$Name.' <br /> </center>';
	} else {
		echo '<center>Granting privileges to '.$Name.' Failed  <br /></center>';
	}
}

if(isset($_GET['assign']))
{
	$Name = $_GET['name'];
	$Id = $_GET["editid"];
	$Uname = mysql_real_escape_string(htmlentities($_POST['txtUname']));
	$Pword = mysql_real_escape_string(htmlentities($_POST['txtPword']));
	$Privilege = mysql_real_escape_string(htmlentities($_POST['txtPrivilege']));
	
	$mat = 0;
	if(isset($_POST['mat'])) $mat = 1;
	$emp = 0;
	if(isset($_POST['emp'])) $emp = 1;
	$pro = 0;
	if(isset($_POST['pro'])) $pro = 1;
	$all = 0;
	if(isset($_POST['all'])) $all = 1;
	$sto = 0;
	if(isset($_POST['sto'])) $sto = 1;
	$rep = 0;
	if(isset($_POST['rep'])) $rep = 1;
	$conf = 0;
	if(isset($_POST['con'])) $conf = 1;
	
	$insertLogin =  mysql_query("INSERT INTO `pmngdb`.`user` VALUES ('$Id', '$Uname', MD5('$Pword'), '$Privilege', $mat, $emp, $pro, $all, $sto, $rep, $conf);");// or die(mysql_error());
		
	if($insertLogin){
		$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Granted privileges to a user','".date('Y-m-d')."','".date('H:i:s')."','{$Id}','user')";
		mysql_query($auditSql);
		echo '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />Privileges granted to '.$Name.' <br /> <center>';
	} else {
		echo '<center>Granting privileges to '.$Name.' Failed  <br /></center>';
	}
}

if(isset($_GET['drop']) && !isset($_GET['assign']) && !isset($_GET['reassign']))
{
	$Name = $_GET['name'];
	$query = mysql_query("DELETE FROM user WHERE id='{$_GET['editid']}'");
	if($query)
	{
		$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Dropped all privileges for a user','".date('Y-m-d')."','".date('H:i:s')."','{$_GET['editid']}','user')";
		mysql_query($auditSql);
		echo '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />Privilege droped for '.$Name.'</center>';
	}
}


if(isset($_GET['delid']) && !isset($_GET['assign']) && !isset($_GET['reassign']) && !isset($_GET['drop']))
{
	$query = mysql_query("UPDATE employee SET status=0 WHERE id='{$_GET['delid']}'");
	if($query)
	{
		$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Deleted Employee from a database','".date('Y-m-d')."','".date('H:i:s')."','{$_GET['delid']}','employee')";
		mysql_query($auditSql);
		echo '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />The Employee was deleted successfully.</center>';
	}
}

if(isset($_GET['editid']) && !isset($_GET['assign']) && !isset($_GET['reassign']) && !isset($_GET['drop'])){
	$dob=mysql_real_escape_string(htmlentities($_POST['dob']));
	$doe=mysql_real_escape_string(htmlentities($_POST['doe']));
	$strquery="update employee set firstname='".$_POST['txtFname']."',surname='".$_POST['txtSurname']."',middlename='".$_POST['txtMname']."',gender='".$_POST['txtGender']."',dob='".$dob."',doe='".$doe."',toe='".$_POST['txtToe']."' where id='".$_GET['editid']."'";
	$result=mysql_query($strquery);
	if(!$result){
		die('<center>Could not Update Employee Information '.mysql_error().'</center>');
	}	
	else {
		$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Changed employee details in a database','".date('Y-m-d')."','".date('H:i:s')."','{$_GET['editid']}','employee')";
		mysql_query($auditSql);
		echo '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />Employee information updated successfully</center>';
	}
	
}
echo '<div id="employees" name="employees" >';
	$strquery="select * from employee where status=1";
	$result=mysql_query($strquery);
	if(!$result){
		die('<center>Could not Query the Database for Employee Information '.mysql_error().'</center>');
	}
if(mysql_num_rows($result)>0){
	echo'<table>
	<tr style="color:#ffffff; background-color:#008010">
	<th>#</th>
	<th>Firstname</th>
	<th>middlename</th>
	<th>Lastname</th>
	<th colspan="3" >Actions</th>
	</tr>';
	$row_count = 1;
	while($row= mysql_fetch_array($result))
	{
		if($row_count % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
		echo '<td>'.$row_count.'</td>
		<td>'.$row["firstname"].'</td>
		<td>'.$row["middlename"].'</td><td> '.$row['surname'].'</td>
		<td><a href="?opt=view_employee_details&empid='.$row['id'].'"><img src="images/browse.png" alt="details" />View More Details</a></td>
		<td><a href="?opt=edit_employee&editid='.$row["id"].'"> <img src="images/edit.png" alt="edit" /> Edit </a></td>
		<td><a href="?opt=manage_employees&delid='.$row["id"].'" onclick="return confirmDelete()"> <img src="images/delete.png" alt="delete" /> Delete </a></td>';
		$row_count++;
	}
	echo "</table></div>";
}
else{
	echo "No registered employee...";
}
?>
</center>