<?php
	if(isset($_GET['restoreid'])){
		$sql="UPDATE employee SET status='1' WHERE id='{$_GET['restoreid']}'";
		$result=mysql_query($sql);
	}
?>
<script language="javascript" type="text/javascript" >
	function confirmRestore(){
		if(confirm("Restore this Employee?")) return true;
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
		xmlhttp.open("GET","employees/employee_list.php?search="+keyword+"&status=0",true);
		xmlhttp.send();	
	}
</script>
<center><center><label for "search" >Search Employee:</label><input type="text" name="search" id="search" onkeyup="findnames()" /><div id="employees" name="employees" >
<?php
	$strquery="select * from employee where status=0";
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
		<td><a href="?opt=deleted_employees&restoreid='.$row["id"].'" onclick="return confirmRestore()"> <img src="images/restore.png" alt="restore" /> Restore </a></td>';
		$row_count++;
	}
}
else{
	echo "No deleted employee";
}
?>
</table></div></center>