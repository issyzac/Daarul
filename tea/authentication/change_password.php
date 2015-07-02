<script type="text/javascript" language="javascript">
	function validatePassword(){
		var newPasswd=document.forms.changepassword.new_password.value.toString();
		var confPaswwd=document.forms.changepassword.new_password2.value.toString();
			if(newPasswd.length==0||confPaswwd.length==0){
				alert("Password Cannot be empty");
				return false;
			}
			else{
				if(CompareText(newPasswd,confPaswwd))return true;
				else return false;
			}
	}
</script>
<?php

if(isset($_POST['current_password'])){
	include('db_connection.php');
	$username = $_SESSION['username'];
	$password = $_POST['current_password'];
	$new_password = $_POST['new_password'];

	$sql = "SELECT * FROM user WHERE username = '$username' AND password = MD5('$password') ";

	$query = mysql_query($sql);
	if(mysql_num_rows($query)){	
		$sql2 = mysql_query("UPDATE user SET password = MD5('$new_password') WHERE username = '$username' ");
		if($sql2){
			echo 'Your Password was Changed Successfully.';
		} else {
			echo 'There was an Error, Password Could Not be Changed!';
		}
	} 	else {
		echo '<span style="color:#ff0000">Current Password is Incorrect</span><br />
		<form name="changepassword" id="" action="?opt=change_password" method="post" style="color:#4c4c4c; margin-top:20px; margin-right:5px"
		onSubmit="return validatePassword()">
		
		<table>
		<tr><td valign="top">Enter Your Current Password:</td>
		<td><input type="password" name="current_password" style="width:200px; color:#4c4c4c;" /> <br /><br /> </td></tr>
		
		<tr><td>Enter Your New Password:</td>
		<td><input type="password" name="new_password" style="width:200px; color:#4c4c4c;" /></td></tr>
		<tr><td>Repeat New Password:</td>
		<td><input type="password" name="new_password2" style="width:200px; color:#4c4c4c;" /> <br /><br /> </td></tr>
		<tr><td>   </td>
		<td><input type="submit" value="Save New Password" style="width:207px; color:#4c4c4c;" /></td></tr>
		</table>
		';
	}
} else {
	echo '<form name="changepassword" action="?opt=change_password" method="post" style="color:#4c4c4c; margin-top:20px; margin-right:5px"
		onSubmit="return validatePassword()">
		
	<table>
	<tr><td valign="top">Enter Your Current Password:</td>
	<td><input type="password" name="current_password" style="width:200px; color:#4c4c4c;" /> <br /><br /> </td></tr>
	
	<tr><td>Enter Your New Password:</td>
	<td><input type="password" name="new_password" style="width:200px; color:#4c4c4c;" /></td></tr>
	<tr><td>Repeat New Password:</td>
	<td><input type="password" name="new_password2" style="width:200px; color:#4c4c4c;" /> <br /><br /> </td></tr>
	<tr><td>   </td>
	<td><input type="submit" value="Save New Password" style="width:207px; color:#4c4c4c;" /></td></tr>
	</table>
	</form>';
}

?>

