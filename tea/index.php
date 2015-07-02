<?php 
	//error_reporting(0);
	session_start(); 
	include('db_connection.php');
	ob_start();
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
<meta charset="iso-8859-1">
<title>Kyimbila Tea Packing Limited </title>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<meta name="Keywords" content="Kyimbila">
<meta name="Description" content="Tea Packing Company">
<script type="text/javascript" src="jquery/jquery.js" ></script>
<script type="text/javascript" src="jquery/script.js" ></script>

<script type="text/javascript" >
	function ShowBranchesMenu(){
		HideAllMenu();
		$('#BranchesMenu').html('<?php	
		$branches = mysql_query("SELECT * FROM store s WHERE type=1 AND s.status = 1 AND s.storeid <> 0") or die(mysql_error());
		if(mysql_num_rows($branches)>0){
			while ($data = mysql_fetch_array($branches)){
				echo '<img src="images/leaf.png" /><a href="?opt=branches&amp;branchid='.$data["storeid"].'"> '.$data["storename"].' </a> <br />';
			}
			echo '<img src="images/leaf.png" /><a href="?opt=confirm_delivery"> Confirm delivery </a> <br />';
		}
	?>').slideDown(speed);
	}
	function logout(){
		return '<img src="images/leaf.png" /><a href="authentication/logout.php?userid=<?php echo $_SESSION['userid'];?>"> Logout </a> <br />';
	}
</script>
<link rel="stylesheet" href="style.css" >
</head>

<body style="margin:0; padding:0; background-color:#eef1ee ">
<div id="OuterContainer" style="position:relative; width:100%; margin:0; padding:0 ">
	<div id="CenterContainer" style="width:960px; margin-top:5px; margin-left:auto; margin-right:auto">
	<table style="border:0"><tr><td>
		<div id="TopBango" style="margin-top:15px; width:960px; height:100px; background-image:url('images/TopBango.png'); 
		background-repeat:no-repeat; position:relative">	
		
			<div id="LeftBlank" style="width:50px; height:100px; background:transparent; float:left">	
				
			</div>
			
			<div id="Logo" style="width:100px; height:100px; background:transparent; float:left">	
				<img src="images/logo.jpg" style="width:105px" alt="logo" />
			</div>
			
			<div id="Title" style="width:360px; background:transparent; margin-top:5px; padding-left:20px; float:left; font-size:35px; color:#104510">
				<b>Kyimbila <br /> Tea Packing Limited</b>
			</div>
			
			<div id="Login" style="width:400px; height:100px; background:transparent; float:right">
				<?php 
				if(isset($_SESSION['username'])){
					echo '<span style="font-size:16px; float:right; color:#ffffff"><br />
					Hello '.$_SESSION['firstname'].' '.$_SESSION['surname']. ', <a href="authentication/logout.php?userid='.$_SESSION['userid'].'" style="color:#ffff00">Logout </a>
					&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </span>';
				} else {
				echo'<form action="authentication/authentication.php" method="post" style="float:right; color:#4c4c4c; font-size:12px; margin-top:20px; margin-right:5px">
					<input type="text" name="username" value="" id="username" style="width:120px; color:#4c4c4c; font-size:13px;" placeholder="Username" />
				
					<input type="password" name="password" style="width:120px; color:#4c4c4c; font-size:13px;" placeholder="Password" />
						
					<input type="submit" value="Login" style="width:80px;">						
					</form> <br /><br /><br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;';
					if(isset($_REQUEST['login_status']) && $_REQUEST['login_status'] == 'failed') {
						echo '<span style="color:#ffff00; font-size:16px">Your username or password is incorrect!</span>';
					} else {
						echo '<span style="color:#ffff00; font-size:16px">Login by providing your username and password</span>';
					}
				}
				?>
				
			</div>
		</div>
		<div id="TopBango" style="margin-top:5px; width:960px; position:relative">	
		<div id="LeftBlank" style="width:200px; background:transparent; float:left; margin-top:12px">	
				<?php include('authentication/menu.php'); ?>
				<br />
			</div>
			
			<div id="Title" style="width:700px; background:transparent; margin-top:5px; padding-left:5px; float:left; color:#104510; font-size:16px">
				
				<?php 
				if(!isset($_SESSION['firstname'])){
					include("authentication/login.php");
				} 
				include('option.php'); 
				?>
				
			</div>			
		</div>
		</td></tr></table>
		<div style="text-align:center; color:#001500; font-size:14px; background-image:url('images/BottomBango.png'); ">
		<br />
		<b>Copyright &copy; Kyimbila Tea Packing Limited&#8482; 2011 - <?php echo date('Y'); ?> </b><br />
		P.O.Box 1344 Dar Es Salaam.
		Tel: +255 22 2861391 <br />
		Email: tausisales@tatepa.com<br />
		<br />
		</div>
	</div>
</div>
</body>
</html>	
<?php
ob_flush();
?>
	