<script type="text/javascript" language="javascript">
	function confirmDelete(){
		if(confirm("Are you sure you want to Remove this job?")) return true;
		else return false;
	}
</script>
<p>
	Add New Job Allocation
	<hr width = '100%'></hr>
	<table width = '100%' >
	<tr >
	<td height = '130px' >
	<form action = "?opt=manage_allocations" method = 'POST'>
		<table>
			<?php
				if(isset($_GET['e']) && !empty($_GET['e']))
				{
					$job_id = $_GET['e'];

					$sql = "SELECT name FROM allocation WHERE id = '{$job_id}'";
					
					$query = mysql_query($sql) or die(mysql_error());

					$value = mysql_fetch_array($query);
		
					$value = $value[0];
	
					echo "<tr><td align = 'right'><i><b>Edit Job:</b></i></td><td><input type = 'text' name = 'job_name'"
						." value = '{$value}' ></td>"
						."<input type = 'hidden' name = 'edit' value = '{$job_id}'>"
						."<td colspan = '2' align = 'left'><input type = 'submit' name = 'submit' value = 'Save'></td>"
						."<td>&nbsp;</td>"	
						."<td>&nbsp;</td></tr>";
					mysql_free_result($query);
				}
				else
				{
					echo 	 "<tr><td align = 'right'><i><b>New Job:</b></i></td><td><input type = 'text' name = 'job_name'></td>"
					    	."<td colspan = '2' align = 'left'><input type = 'submit' name = 'submit' value = 'Save'></td>"
					    	."</tr>";
				}
			?>
		</table>	
	</form>
	</td>
	<td align = 'center' width = '50%' valign = 'top'>
	<?php
		if(isset($_SESSION['jobsucc'])){
			echo '<img src="images/ok.png" alt="Ok..." height = "100px"/>';
			if($_SESSION['jobsucc'] == "insert"){ 
				echo '<br/>Job sucessfully added.';
			}else if ($_SESSION['jobsucc'] == "edit"){
				echo '<br/>Job sucessfully edited.';
			}else if ($_SESSION['jobsucc'] == "remove"){
				echo '<br/>Job sucessfully removed.';			
			}
		}
	?>
	</td>
	</tr>
	</table>
</p>

<?php
	@session_start();
	
	if(isset($_POST['submit']))
	{
		if(!empty($_POST['job_name']))
		{
			$name = $_POST['job_name'];
			
			if(isset($_POST['edit']))
			{
				$sql = "UPDATE allocation SET name = '{$name}' WHERE id = '{$_POST['edit']}'";	
				mysql_query($sql) or die(mysql_error());
				$message = "edit";
			}
			else
			{
				
				$sql  =   "INSERT INTO allocation (name,enabled) "
						 ."VALUES ('{$name}',1)";
				mysql_query($sql) or die(mysql_error());
				$message = "insert";
			}

			$_SESSION['jobsucc'] = $message;
	
			header("Location:?opt=manage_allocations");
			exit;
		}		
	}

	if(isset($_GET['r']) && !empty($_GET['r']) && !isset($_GET['e']))
	{
		$id = $_GET['r'];
		$date = date('Y-m-h');
		$time = date('H:i:s');
		
		$sql_rm = "UPDATE allocation SET enabled = 0 WHERE id = '{$id}'";
		mysql_query($sql_rm) or die(mysql_error());
		$sql_event = "INSERT INTO event (employee_id,event,date,time,table_name,item_id) "
					."VALUES ('{$_SESSION['userid']}','Delete Job/Task in allocation table','{$date}','{$time}','allocation','{$id}')";
		mysql_query($sql_event) or die(mysql_error());		
		
		$_SESSION['jobsucc'] = "remove";
		header("Location:?opt=manage_allocations");
		exit;
	}

	$sql_alloc = "SELECT * FROM allocation WHERE enabled = 1";

	$q_alloc = mysql_query($sql_alloc) or die(mysql_error());
	
	if(mysql_num_rows($q_alloc) > 0)
	{
?>		<span><strong><i>List of all job:</i></strong></span>
		<table width = '100%' border = '1' style = 'margin-top:10px'>
		<tr style="color:#ffffff; background-color:#008010" >
			<td><strong>No.</strong></td>
			<td><strong>Job Name</strong></td>
			<td align = 'center'><strong>Action</strong></td>
		</tr>

		<?php
			$count = 1;	
			$job = mysql_fetch_assoc($q_alloc);
			
			if(mysql_num_rows($q_alloc) > 0)
			{
				do
				{
					if($count % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
						echo "<td>{$count}.</td>"
						."<td>{$job['name']}</td>"
						."<td align = 'center'>"
						."<a href = '?opt=manage_allocations&e={$job['id']}' style = 'margin-left:20px'><img src='images/edit.png' alt='delete' />Edit</a>"
						."<a href = '?opt=manage_allocations&r={$job['id']}' onclick='return confirmDelete()' style = 'margin-left:30px'><img src='images/delete.png' alt='delete' />Remove</a>"
						."</td>"
						."</tr>";
					$count++;				
				}while($job = mysql_fetch_assoc($q_alloc));
			}
		}
		?>
		</table>
<?php
	if(isset($_SESSION['jobsucc'])){
		unset($_SESSION['jobsucc']);
	}
?>	