<script type="text/javascript" language="javascript">
	function confirmRegister(){
		if(confirm("Register Employee?")) return true;
		else return false;
	}
	function validateData(){
		var fname=document.forms['frmEmployee']['txtFname'].value.toString();
		var mname=document.forms['frmEmployee']['txtMname'].value.toString();
		var sname=document.forms['frmEmployee']['txtSurname'].value.toString();
		var empid=document.forms['frmEmployee']['txtID'].value;

		var doe=document.forms['frmEmployee']['doe'].value.toString();
		var dob=document.forms['frmEmployee']['dob'].value.toString();
		if(fname.length==0||mname.length==0||sname.length==0||empid.length==0||doe.length==0||dob.length==0){
			alert("Enter complete information");
			return false;
		}
		else{
			if(confirmRegister()) return true;
			else return false;
		}
	}
</script>
<?php
	if(isset($_GET['reply'])) echo $_GET['reply'];
	$reply="";
	if(isset($_POST['btnSave'])){
		$gender=$_POST['rdoGender'];
		$tareheKuzaliwa=$_POST['dob'];
		$tareheAjira=$_POST['doe'];
		
		$strquery="insert into employee(id,firstname,middlename,surname,gender,dob,doe,toe) values('{$_POST['txtID']}','{$_POST['txtFname']}','{$_POST['txtMname']}','{$_POST['txtSurname']}','{$gender}','{$tareheKuzaliwa}','{$tareheAjira}','{$_POST['cboToe']}')";
		
	
		if(mysql_query($strquery)){
			$reply= '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />New Employee registered </p><br /></center>';
		} else {
			$reply= '<center>Adding Employee Failed  <br /></center>';
		}
		
		if(isset($_POST['txtUname'], $_POST['txtPword'], $_POST['txtPrivilege'])){
			
			$username = mysql_real_escape_string(htmlentities($_POST['txtUname']));
			$password = mysql_real_escape_string(htmlentities($_POST['txtPword']));
			$privilege = mysql_real_escape_string(htmlentities($_POST['txtPrivilege']));
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
			$con = 0;
			if(isset($_POST['con'])) $con = 1;
			$bra = 0;
			if(isset($_POST['bra'])) $bra = 1;
			
				$insertLogin =  mysql_query("INSERT INTO  user VALUES ('$_POST[txtID]', '$_POST[txtUname]', MD5('$_POST[txtPword]'), '$_POST[txtPrivilege]', $mat, $emp, $pro, $all, $sto,$bra, $rep, $con)") or die(mysql_error());
			
			if($insertLogin){
				echo '<center>New System User Added <br /></center>';
			} else {
				echo '<center>Adding System User Failed  <br /></center>';
			}
		}
				header("Location:?opt=register_employee&reply=$reply");

	}
?>
<form name="frmEmployee" id="frmEmployee" action="#" method="post" onsubmit="return validateData()">
	<table cellpadding="4">
		<tr><td><label for="txtID">Employee ID</label></td><td><input type="text" id="txtID" name="txtID" size="47" /></td></tr>
		<tr><td><label for="txtFname">First Name</label></td><td><input type="text" id="txtFname" name="txtFname" size="47" /></td></tr>
		<tr><td><label for="txtMname">Middle Name(s)</label></td><td><input type="text" id="txtMname" name="txtMname" size="47" /></td></tr>
		<tr><td><label for="txtSurname">Surname</label></td><td><input type="text" id="txtSurname" name="txtSurname" size="47" /></td></tr>
		<tr><td valign="top">System User?</td><td><input type="checkbox" value="System" id="checkSystem" name="rdoSystem" onchange="showSystemFields();"/> Yes, Provide Username and Password <br /><br />
		<div id="SystemUser">   </div>
		</td></tr>
		
		<tr><td><label for="rdoGender">Gender</label></td><td align="justify"><input type="radio" value="F" checked="checked" id="radioFemale" name="rdoGender" /><label for="rdoFemale">Female</label><input type="radio" value="M" id="radioMale" name="rdoGender" /><label for="rdoMale">Male</label></td></tr>
		<tr><td><label for="dob">Date of Birth</label></td><td><input type="text" id="dob" name="dob" style="cursor:pointer" readonly="readonly" size="47" /></td></tr>
		<tr><td><label for="doe">Date of Employment</label></td><td><input type="text" id="doe" name="doe" style="cursor:pointer" readonly="readonly" size="47" /></td></tr>
		<tr><td><label for="cboToe">Employee Type</label><td>
		<select id="cboToe" name="cboToe" >
			<option id="Managerial " value="MG" >Managerial</option>
			<option id="Staff Grade" value="SG" >Staff Grade</option>
			<option id="Casual Grade" value="Causal" >Casual Grade</option>
		</select>
		</td></td></td></tr>
		<tr><td></td><td align="right"> 
			<input type="submit" value="Save" id="btnSave" name="btnSave" /></td></tr>	
	</table>
	<link rel="stylesheet" href="jquery/jquery.datepick.css"/>
<script type="text/javascript" language="javascript" src="jquery/jquery.js" ></script>
<script type="text/javascript" language="javascript" src="jquery/jquery.datepick.js" ></script>
<script type="text/javascript" language="javascript" >
	var minYear=<?php echo date('Y')-70; ?>;
	var minimumDate=minYear+'-1-1';
	$('#dob').datepick({dateFormat: 'yyyy-mm-dd', maxDate: new Date(),minDate: minimumDate});
	$('#doe').datepick({dateFormat: 'yyyy-mm-dd', maxDate: new Date()});
</script>
</form>