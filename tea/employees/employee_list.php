<?php
	require("../db_connection.php");
	$search=mysql_real_escape_string(htmlentities($_REQUEST["search"]));
	$strquery="select * from employee where (firstname like '%{$search}%' or surname like '%{$search}%')AND status='{$_GET['status']}'";
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
	<td><a href="?opt=view_employee_details&empid='.$row['id'].'"><img src="images/browse.png" alt="details" />View More Details</a></td>';
	if($_GET['status']==1){
		echo '<td><a href="?opt=edit_employee&editid='.$row["id"].'&search='.$search.'"> <img src="images/edit.png" alt="edit" /> Edit </a></td>
		<td><a href="?opt=manage_employees&delid='.$row["id"].'&search='.$search.'" onclick="return confirmDelete()"> <img src="images/delete.png" alt="delete" /> Delete </a></td>';
	}
	elseif($_GET['status']==0){
		echo '<td><a href="?opt=deleted_employees&restoreid='.$row["id"].'"> <img src="images/restore.png" alt="restore" /> Restore </a></td>';
	}
	$row_count++;
}
echo "</table>";
}
else{
	if(trim($search)=="")
		echo"No employee available";
	else
		echo "Search keyword not found...";
}
?>