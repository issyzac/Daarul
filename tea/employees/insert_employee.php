	<?php
	
		$gender=$_POST['rdoGender'];
		$doeMonth=$_POST['doeMonth'];
		$dobMonth=$_POST['dobMonth'];
		//echo $gender." ".$doeMonth." ".$dobMonth;
		$mweziKuzaliwa=getNumberedMonth($dobMonth);
		$mweziAjira=getNumberedMonth($doeMonth);
		
		function getNumberedMonth($strMonth){ //function to change string months to equivalent numbered months.
			if($strMonth=="January")return 1;
			if($strMonth=="February") return 2;
			if($strMonth=="March") return 3;
			if($strMonth=="April") return 4;
			if($strMonth=="May") return 5;
			if($strMonth=="June") return 6;
			if($strMonth=="July") return 7;
			if($strMonth=="August") return 8;
			if($strMonth=="September") return 9;
			if($strMonth=="October") return 10;
			if($strMonth=="November") return 11;
			if($strMonth=="December") return 12;
		}
		$tareheKuzaliwa=$_POST['dobYear']."-".$mweziKuzaliwa."-".$_POST['dobDay'];
		$tareheAjira=$_POST['doeYear']."-".$mweziAjira."-".$_POST['doeDay'];
		
		$strquery="insert into employee(id,firstname,middlename,surname,gender,dob,doe,toe) values('$_POST[txtID]','$_POST[txtFname]','$_POST[txtMname]','$_POST[txtSurname]','$gender','$tareheKuzaliwa','$tareheAjira','$_POST[cboToe]')";
		
		if(mysql_query($strquery)){
			echo '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />New Employee Added <br /> </center>';
		} else {
			echo '<center>< <br />Adding Employee Failed  <br /> </center>';
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
			$bra = 0;
			if(isset($_POST['bra'])) $bra = 1;
			$rep = 0;
			if(isset($_POST['rep'])) $rep = 1;
			$con = 0;
			if(isset($_POST['con'])) $con = 1;
			
			$insertLogin =  mysql_query("INSERT INTO `pmngdb`.`user` VALUES ('$_POST[txtID]', '$_POST[txtUname]', MD5('$_POST[txtPword]'), '$_POST[txtPrivilege]', $mat, $emp, $pro, $all, $sto, $bra, $rep, $con);") or die(mysql_error());
			
			if($insertLogin){
				echo '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />New System User Added <br /> </center>';
			} else {
				echo '<center><br />Adding System User Failed  <br /></center>';
			}
		}
	?>
