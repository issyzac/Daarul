<html>
	<body>
	<script language="javascript" type="text/javascript">
		function confirmEdit(){
			var confirmed=confirm("Are you sure you want to perform this Operation?");
			if(confirmed) return true;//alert("Confirmed");
			else return false;//alert("Aborted");
		}
	</script>
<?php 
	if(isset($_POST['rdoEmp'])){
	//removing employee from a database
		if($_POST['cboAction']=='remove'){
			$con=mysql_connect("localhost","root","");
			if(!$con){
				die('Error: '.mysql_error());
			}
			mysql_select_db("pmngdb", $con);
			$strquery="delete from employee where id=".$_POST['rdoEmp'];
			$result=mysql_query($strquery);
			if(!$result){
				die("<center>Could not perform your Query ".mysql_error()."</center>");
			}
			echo 'center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />Employee successfully removed</center>';
			mysql_close($con);
		}
		
		//editing employee details
		else if($_POST['cboAction']=='edit'){
		
			//staff privileges
			$privileges=array(strtoupper("Managerial"),strtoupper("Staff Grade"),strtoupper("Casual Grade"));
		
			$con=mysql_connect("localhost","root","");
			if(!$con){
				die('Error: '.mysql_error());
			}
			mysql_select_db("pmngdb", $con);
			$strquery="select * from employee where id=".$_POST['rdoEmp'];
			$result=mysql_query($strquery);
			if(!$result){
				die("Could not perform your Query ".mysql_error());
			}
			while($result_row=mysql_fetch_row($result)){
				echo "Edit details for Employee whose ID is: ".$result_row[0]."<br />";
				echo '<form action="frmManageEmployee.php" method="post" onsubmit="return confirmEdit()"><table border="1">
	<tr><td><label for="txtFname">First Name</label></td><td><input type="text" id="txtFname" name="txtFname" value="'.$result_row[1].'" /></td></tr>
	<tr><td><label for="txtMname">Middle Name</label></td><td><input type="text" id="txtMname" name="txtMname" value="'.$result_row[2].'" /></td></tr>
	<tr><td><label for="txtSurname">Surname</label></td><td><input type="text" id="txtSurname" name="txtSurname" value="'.$result_row[3].'" /></td></tr>
	<tr><td><label for="txtGender">Gender</label></td><td><input type="text" id="txtGender" name="txtGender" value="'.$result_row[4].'" /></td></tr>
	<tr><td><label for="txtDOB">Date of Birth</label></td><td><input type="text" id="txtDOB" name="txtDOB" value="'.$result_row[5].'" /></td></tr>
	<tr><td><label for="txtDOE">Date of Employment</label></td><td><input type="text" id="txtDOE" name="txtDOE" value="'.$result_row[6].'" /></td></tr>
	<tr><td><label>Employee Type</label></td><td><select id="cboToe" name="cboToe">';
	
	echo '<option id="'.$result_row[0].'" value="'.$result_row[7].'" selected="selected">'.strtoupper($result_row[7]).'</option>';
	for($i=0;$i<3;$i++){
		if($privileges[$i]!=strtoupper($result_row[7]))
		echo '<option id="'.$result_row[0].'" value="'.$privileges[$i].'">'.$privileges[$i].'</option>';
	}
	echo '</select></td></tr>
	<tr><td></td><td><input type="submit" value="Save Changes"/></td></tr>
</table><input type="hidden" name="empId" id="empId" value="'.$result_row[0].'" /></form>';
			}
			mysql_close($con);
			echo "Done";
		}
	}
	else echo "Please select Employee";
?>
</body>
</html>