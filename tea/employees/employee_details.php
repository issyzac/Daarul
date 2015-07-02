<script type="text/javascript" language="javascript" >
	function confirmDelete(){
		if(confirm("Are you sure you want to Remove this Employee?")) return true;
		else return false;
	}
</script>
<?php
	if(isset($_GET['action'])){
		$sql="UPDATE employee SET status=0 WHERE id='{$_GET['empid']}'";
		$result=mysql_query($sql);
		if($result) echo 'center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />Employee Successfully Removed</center>';
		else echo "Failed to remove Employee";
	}
	
	$sqlDetails="SELECT * FROM employee WHERE id='{$_GET['empid']}'";
	$result=mysql_query($sqlDetails);
	if(mysql_num_rows($result)>0){
		$row=mysql_fetch_array($result);
		echo "<table>";
			echo "<tr class='odd' ><td><b>First Name:</b></td><td>{$row['firstname']}</td></tr>";
			echo "<tr class='even' ><td><b>Middle Name:</b></td><td>{$row['middlename']}</td></tr>";
			echo "<tr class='odd' ><td><b>Surname:</b></td><td>{$row['surname']}</td></tr>";
			echo "<tr class='even' ><td><b>Gender:</b></td><td>{$row['gender']}</td></tr>";
			echo "<tr class='odd' ><td><b>Date of Birth:</b></td><td>{$row['dob']}</td></tr>";
			echo "<tr class='even' ><td><b>Date of Employment:</b></td><td>{$row['doe']}</td></tr>";
			echo "<tr class='odd' ><td><b>Type of Employee:</b></td><td>{$row['toe']}</td></tr>";
			if($row['status']==1)
				echo '<tr class="even" ><td><a href="?opt=edit_employee&editid='.$_GET['empid'].'&search=" ><img src="images/edit.png" alt="edit" /> Edit Details</a></td><td><a href="?opt=view_employee_details&empid='.$_GET['empid'].'&action=delete" onclick="return confirmDelete()" ><img src="images/delete.png" alt="delete" /> Delete this Employee</a></td></tr>';
			elseif($row['status']==0)
				echo '<tr class="even" ><td colspan="2" align="center" ><a href="?opt=deleted_employees&restoreid='.$row["id"].'"> <img src="images/restore.png" alt="restore" /> Restore </a></td></tr>';
		echo "</table>";
	}
?>