<?php
	@session_start();
	
	require_once "../db_connection.php";
	

	$name = $_POST['name'];
	
	$all = "";	
	$today = date('Y-m-d');
	
	if(isset($_SESSION['attendance'])){
		$keys = array_keys($_SESSION['attendance']);
		//var_dump($keys);
		for($x = 0; $x < count($keys); $x++)
		{
			for($z = 0; $z < count($_SESSION['attendance'][$keys[$x]]); $z++)
			{	  
				$all .= "'".$_SESSION['attendance'][$keys[$x]][$z]."'".",";
			}
		}
	}
	
	$sql_search = "SELECT * FROM employee WHERE (firstname LIKE '%".$name."%' || middlename LIKE '%".$name."%' || surname LIKE '%".$name."%') ";
	
	if(!empty($all))
	{
		$all = trim($all,",");
		$sql_search .=" AND id NOT IN (".$all.") "; 
	}

	$sql_search .= " AND status = 1 AND id NOT IN (SELECT employeeid FROM attendance WHERE date = '{$today}') ORDER BY firstname ASC ";
	
	$query_emp = mysql_query($sql_search) or die(mysql_error());
	if(mysql_num_rows($query_emp) > 0)
	{
		echo "<select size = '10' style = 'width:260px'>";
		while($row = mysql_fetch_assoc($query_emp))
		{
			echo "<option value = '".$row['id']."' ";
			
			if(isset($_SESSION['last'])){
				echo "ondblclick='loadPeople(\"".$_SESSION['last']."\",this.value)";
			}
			
			echo "'>".$row['firstname']." ".$row['middlename']." ".$row['surname']
				."</option>";
		}
		echo "</select>";
	}
?>
