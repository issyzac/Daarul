<script language="javascript" type="text/javascript">
	function confirmDrop(){
		if(confirm("Are you sure you want to drop all privileges to this User?")) return true;
		else return false;
	}
	function confirmUpdate(){
		if(confirm("Save changes made?")) return true;
		else return false;
	}
	function validateData(){
		var fname=document.forms['updatedetails']['txtFname'].value.toString();
		var mname=document.forms['updatedetails']['txtMname'].value.toString();
		var surname=document.forms['updatedetails']['txtSurname'].value.toString();
		var dob=document.forms['updatedetails']['dob'].value.toString();
		var doe=document.forms['updatedetails']['doe'].value.toString();
		
		if(fname.length==0||mname.length==0||surname.length==0||dob.length==0||doe.length==0){
			alert("Please fill all the required fields");
			return false;
		}
		else{
			if(confirmUpdate()) return true;
			else return false;
		}
	}
	
	var request;
	function getRequest(){
		if (window.XMLHttpRequest) {
			request = new XMLHttpRequest();
		}
		else{
			request=new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	function resetPassword(empid){
		if(confirm("Are you sure you want to reset a Password for this user?")){
			getRequest();
			var url="employees/reset_password.php?empid="+empid;
			request.onreadystatechange=process;
			request.open("GET",url,true);
			request.send();
		}
	}
	function process(){
		if(request.readyState==4){
			if(request.status==200){
				document.getElementById('passwordresetstatus').innerHTML = request.responseText;
			}
			else{
				alert("There was a problem while loading the page:\n" + request.statusText);
			}
		}
		else document.getElementById('passwordresetstatus').innerHTML = "Resetting Password...";
	}
	function validateGrant(){
		var uname=document.forms['grant']['txtUname'].value.toString();
		var passwd=document.forms['grant']['txtPword'].value.toString();
		if(uname.length==0||passwd.length==0){
			alert("You must enter a username and password for this user");
			return false;
		}
		else return true;
	}
</script>
<?php
	$query = mysql_query("SELECT * FROM employee WHERE id='{$_GET['editid']}'");
	$row= mysql_fetch_array($query);
	$name = $row['firstname'].' '.$row['surname'];
	
	$casual='';
	if($row['toe']=="Casual Grade") $casual="selected='selected'";
	$staff='';
	if($row['toe']=="Staff Grade") $staff="selected='selected'";
	$mng='';
	if($row['toe']=="Managerial") $mng="selected='selected'";
	
	echo "<form method='post' action='?opt=manage_employees&editid={$_GET['editid']}' onsubmit='return validateData()' id='updatedetails' name='updatedetails'>";
	echo "<table border'1'>";
		echo "<tr><td>First Name:</td><td><input name='txtFname' value=\"{$row['firstname']}\" /></td></tr>";
		echo "<tr><td>Middle Name:</td><td><input name='txtMname' value=\"{$row['middlename']}\" /></td></tr>";
		echo "<tr><td>Surname:</td><td><input name='txtSurname' value=\"{$row['surname']}\" /></td></tr>";
		$male='';
		if($row['gender']=="M") $male="checked='checked'";
		$female='';
		if($row['gender']=="F") $female="checked='checked'";
		echo "<tr><td>Gender:</td><td><input type='radio' name='txtGender' value='F'".$female." />Female<input type='radio' name='txtGender' value='M'".$male." />Male</td></tr>";
		
		echo "<tr><td>Date of Birth:</td><td><input type='text' id='dob' name='dob' value='{$row['dob']}' style='cursor:pointer' readonly='readonly' /></td></tr>";
		echo "<tr><td>Date of employment:</td><td><input type='text' id='doe' name='doe' value='{$row['doe']}' style='cursor:pointer' readonly='readonly' /></tr>";
		
		echo "<tr><td>Type of Employee:</td><td>
			<select id='txtToe' name='txtToe' >
				<option id='casual' value='Casual Grade' ".$casual." >Casual Grade</option>
				<option id='staff' value='Staff Grade' ".$staff." >Staff Grade</option>
				<option id='managerial' value='Managerial'".$mng.">Managerial</option>
			</select>
		</td></tr>";
		echo "<tr><td colspan='2' align='right' ><input type='submit' name='edit' value='Update' /></tr>";
	echo "</table>";
	echo "</form>";
?>
	<link rel="stylesheet" href="jquery/jquery.datepick.css"/>
	<script type="text/javascript" language="javascript" src="jquery/jquery.js" ></script>
	<script type="text/javascript" language="javascript" src="jquery/jquery.datepick.js" ></script>
	<script type="text/javascript" language="javascript" >
		var minYear=<?php echo date('Y')-70; ?>;
		var minimumDate=minYear+'-1-1';
		$('#dob').datepick({dateFormat: 'yyyy-mm-dd', maxDate: new Date(),minDate: minimumDate});
		$('#doe').datepick({dateFormat: 'yyyy-mm-dd', maxDate: new Date()});
	</script>

	<br />	
<?php	
	$query = mysql_query("SELECT * FROM user WHERE id='{$_GET['editid']}'");
	$row= mysql_fetch_array($query);
	if(!mysql_num_rows($query)){
		echo'
			Assign System Privileges to '.$name.':<br />
			<form method="post" action="?opt=manage_employees&assign=true&name='.$name.'&editid='.$_GET["editid"].'" onsubmit="return validateGrant()" name="grant" id="grant" >
			<table border"1">
			<tr><td style="width:140px">Username:</td><td><input name="txtUname" /></td></tr>
			<tr><td>Password:</td><td><input type="password" name="txtPword" /></td></tr>
			<tr><td>Title:</td><td>';
			
			echo "<select id='txtPrivilege' name='txtPrivilege' >
				<option value='General Manager' >General Manager</option>
				<option value='Production Manager' >Production Manager</option>
				<option value='Administrator' >Administrator</option>
			</select>";
			
			
			echo '</td></tr><tr><td style="vertical-align:top">Privileges: </td><td> 
			<input type="checkbox" name="mat" value="1"/> Materials <br /> <input type="checkbox" name="emp" /> Employees  <br />
			<input type="checkbox" name="pro" /> Production <br /> <input type="checkbox" name="all" /> Allocation  <br />
			<input type="checkbox" name="sto" /> Stock <br /> <input type="checkbox" name="rep" /> Reports  <br />
			<input type="checkbox" name="con" /> Configurations <br /> <input type="checkbox" name="acc" checked="checked" disabled="true" /> Account  
			</td></tr>
			<tr><td colspan="2" align="right" ><input type="submit" name="edit" value="Assign" /></tr>
			</table>
			</form>
		';
	} else {
		$mat = '';
		if($row['materials']) $mat = 'checked="checked"';
		$emp = '';
		if($row['employees']) $emp = 'checked="checked"';
		$pro = '';
		if($row['production']) $pro = 'checked="checked"';
		$all = '';
		if($row['allocation']) $all = 'checked="checked"';
		$bra = '';
		if($row['branches']) $bra = 'checked="checked"';
		$sto = '';
		if($row['stock']) $sto = 'checked="checked"';
		$rep = '';
		if($row['reports']) $rep = 'checked="checked"';
		$conf = '';
		if($row['configurations']) $conf = 'checked="checked"';
		
		echo
			$name .' ('.$row["privilege"].') can access the follwing menus, [<a href="?opt=manage_employees&drop=true&name='.$name.'&editid='.$_GET["editid"].'" onclick="return confirmDrop()">Drop All</a>]
			<form method="post" action="?opt=manage_employees&reassign=true&name='.$name.'&editid='.$_GET["editid"].'" onsubmit="return confirmUpdate()">
			<table>
			<tr><td><input type="button" value="Reset Password" onclick="resetPassword('.$_GET["editid"].')" /></td><td><div id="passwordresetstatus" name="passwordresetstatus" ></div></td></tr>
			<tr><td style="vertical-align:top; width:140px">Privileges: </td><td> 
			<input type="checkbox" name="mat" value="1" '.$mat.' /> Materials <br /> <input type="checkbox" name="emp" '.$emp.' /> Employees  <br />
			<input type="checkbox" name="pro" '.$pro.' /> Production <br /> <input type="checkbox" name="all" '.$all.' /> Allocation  <br />
			<input type="checkbox" name="sto" '.$sto.' /> Stock <br /> <input type="checkbox" name="bra" '.$bra.' /> Branches <br /> 
			<input type="checkbox" name="rep" '.$rep.' /> Reports  <br />
			<input type="checkbox" name="con" '.$conf.' /> Configurations <br /> <input type="checkbox" name="acc" checked="checked" disabled="true" /> Account  
			</td></tr><tr><td colspan="2" align="right" ><input type="submit" name="edit" value="Assign" /> </td></tr>
			</table>
			</form>
		';
	}
?>