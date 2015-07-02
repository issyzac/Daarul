<script type="text/javascript" language="javascript">
	function confirmDelete(){
		if(confirm("Are you sure you want to Remove this job?")) return true;
		else return false;
	}
</script>
<p>
	Manage attendance status
	<hr width = '100%'></hr>
	<table width = '100%' >
	<tr >
	<td height = '130px' >
	<form action = "" method = 'POST'>
		<table>
			<?php
				if(isset($_GET['e']) && !empty($_GET['e']))
				{
					$att_id = $_GET['e'];

					$sql = "SELECT name FROM attendance_status WHERE id = '{$att_id}'";
					
					$query = mysql_query($sql) or die(mysql_error());

					$value = mysql_fetch_array($query);
		
					$value = $value[0];
	
					echo "<tr><td align = 'right'><i><b>Edit Status:</b></i></td><td><input type = 'text' name = 'att_name'"
						." value = '{$value}' ></td>"
						."<input type = 'hidden' name = 'edit' value = '{$att_id}'>"
						."<td colspan = '2' align = 'left'><input type = 'submit' name = 'submit' value = 'Save'></td>"
						."<td>&nbsp;</td>"	
						."<td>&nbsp;</td></tr>";
					mysql_free_result($query);
				}
				else
				{
					echo 	 "<tr><td align = 'right'><i><b>New Attendance Status:</b></i></td><td><input type = 'text' name = 'att_name'></td>"
					    	."<td colspan = '2' align = 'left'><input type = 'submit' name = 'submit' value = 'Save'></td>"
					    	."</tr>";
				}
			?>
		</table>	
	</form>
	</td>
	<td align = 'center' width = '50%' valign = 'top'>
	<?php
		if(isset($_SESSION['attsucc'])){
			echo '<img src="images/ok.png" alt="Ok..." height = "100px"/>';
			if($_SESSION['attsucc'] == "insert"){ 
				echo '<br/>Attendance sucessfully added.';
			}else if ($_SESSION['jobsucc'] == "edit"){
				echo '<br/>Attendace sucessfully edited.';
			}else if ($_SESSION['attsucc'] == "remove"){
				echo '<br/>Attendance sucessfully removed.';			
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
		if(!empty($_POST['att_name']))
		{
			$name = $_POST['att_name'];
			
			if(isset($_POST['edit']))
			{
				$sql = "UPDATE attendance_status SET name = '{$name}' WHERE id = '{$_POST['edit']}'";	
				mysql_query($sql) or die(mysql_error());
				$message = "edit";
			}
			else
			{
				
				$sql  =   "INSERT INTO attendance_status (name) "
						 ."VALUES ('{$name}')";
				mysql_query($sql) or die(mysql_error());
				$message = "insert";
			}

			$_SESSION['attsucc'] = $message;
	
			header("Location:?opt=attendance_status");
			exit;
		}		
	}

	if(isset($_GET['r']) && !empty($_GET['r']) && !isset($_GET['e']))
	{
		$id = $_GET['r'];
		$date = date('Y-m-h');
		$time = date('H:i:s');
	
		$sql_event = "INSERT INTO event (employee_id,event,date,time,table_name,item_id) "
					."VALUES ('{$_SESSION['userid']}','Delete Attendance Status','{$date}','{$time}','attendance_status','{$id}')";
		mysql_query($sql_event) or die(mysql_error());		
		$sql_delete = "DELETE FROM attendance_status WHERE id ='".$id."'";
		mysql_query($sql_delete) or die(mysql_error());
		
		$_SESSION['jobsucc'] = "remove";
		header("Location:?opt=attendance_status");
		exit;
	}

	$sql_att = "SELECT * FROM attendance_status ";

	$q_att = mysql_query($sql_att) or die(mysql_error());
	
	if(mysql_num_rows($q_att) > 0)
	{
?>		<span><strong><i>List of attendance status:</i></strong></span>
		<table width = '100%' border = '1' style = 'margin-top:10px'>
		<tr style="color:#ffffff; background-color:#008010" >
			<td><strong>No.</strong></td>
			<td><strong>Attendance Name</strong></td>
			<td align = 'center'><strong>Action</strong></td>
		</tr>

		<?php
			$count = 1;	
			$att = mysql_fetch_assoc($q_att);
			
			if(mysql_num_rows($q_att) > 0)
			{
				do
				{
					if($count % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
						echo "<td>{$count}.</td>"
						."<td>{$att['name']}</td>"
						."<td align = 'center'>"
						."<a href = '?opt=attendance_status&e={$att['id']}' style = 'margin-left:20px'><img src='images/edit.png' alt='delete' />Edit</a>"
						."<a href = '?opt=attendance_status&r={$att['id']}' onclick='return confirmDelete()' style = 'margin-left:30px'><img src='images/delete.png' alt='delete' />Remove</a>"
						."</td>"
						."</tr>";
					$count++;				
				}while($att = mysql_fetch_assoc($q_att));
			}
		}
		?>
		</table>
<?php
	if(isset($_SESSION['attsucc'])){
		unset($_SESSION['attsucc']);
	}
?>	