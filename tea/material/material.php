<script>
function del(val)
{
	if(confirm("Are you sure you want to delete the Material?"))
	{
		return true;
	}
	return false;
}
function del()
{
	if(confirm("Are you sure you want to delete this material?"))
	{
		return true;
	}
	else
		return false;
}
function edit()
{/*
	if(confirm("Save changes to this material?"))
	{
		return true;
	}
	else
		return false;*/
	return true;
}

</script>
<?php
	$action = "";
	if(isset($_GET['action']))
	{
		if($_GET['action'] == "packing")
		{
			$action = "Packing Material";
		}else if($_GET['action'] == "blending")
		{
			$action = "Blending Material";
		}else
		{
			exit();
		}
	}else
	{
		exit();
	}
?>
<form action="" method="post" onsubmit="return isValid(this)">
<fieldset>
	<legend align="center"><?php echo "Add {$action}"; ?></legend>
<center>Name:<input name="name" />Critical Level:<input type="text" name="critical" id="critical" />
<input type="submit" name="add" value="Submit"/>
</center>
</fieldset>
</form>
<center>
<?php
	echo "<h3>{$action}s </h3>";
	echo "The following are the {$action}s:<br />";
	$reply = "";
	if(isset($_POST['add']))
	{
		$sql = "INSERT INTO material(name,type,criticallevel,status) VALUES('{$_POST['name']}','{$action}','{$_POST['critical']}','1')";
		$result = mysql_query($sql) or die(mysql_error());
		if($result)
		{
			mysql_query("INSERT INTO event VALUES('{$_SESSION['userid']}','Inserted item from table','".date("Y-m-d")."','".date("H:i:s")."','material','{$_POST['materialid']}')");
			$reply = '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />The material was added succeffuly.</p><br /></center>';
		}
		header("Location: index.php?opt={$_GET['opt']}&action={$_GET['action']}&reply=$reply");
	}else if(isset($_POST['edit']))
	{
		$sql = "UPDATE material SET name='{$_POST['name']}',criticallevel='{$_POST['critical']}' WHERE id='{$_POST['materialid']}'";
		$result = mysql_query($sql) or die(mysql_error());
		if($result)
		{
			mysql_query("INSERT INTO event VALUES('{$_SESSION['userid']}','Changed a material table','".date("Y-m-d")."','".date("H:i:s")."','material','{$_POST['materialid']}')");
			$reply = '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />The product has been Edited Sucessfully.</p><br /></center>';
		}
		header("Location: ?opt={$_GET['opt']}&action={$_GET['action']}&reply=$reply");
	}else if(isset($_POST['delete']))
	{
		$sql = "UPDATE material SET status='0' WHERE id={$_POST['materialid']}";
		$result = mysql_query($sql);
		if($result)
		{
			mysql_query("INSERT INTO event VALUES('{$_SESSION['userid']}','Deleted item from table','".date("Y-m-d")."','".date("H:i:s")."','material','{$_POST['materialid']}')");
			$reply = '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />The material was deleted succeffuly.</p><br /></center>';
		}
		header("Location: ?opt={$_GET['opt']}&action={$_GET['action']}&reply=$reply");
	}
	if(isset($_GET['reply']))
		echo $_GET['reply'];
	$sql = "Select * FROM material WHERE type='$action' AND status='1'";
	$result = mysql_query($sql);
	if(mysql_num_rows($result) > 0)
	{
		echo "<table><tr style='color:#ffffff; background-color:#008010'><td width='20px'>#</td><td width='200px'>Material</td><td>Critical Level</td><td colspan='2'>Action</td></tr>";
		$i = 1;
		while($row = mysql_fetch_array($result))
		{
		if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
			echo "<td >$i</td>";
			echo "<td>{$row['name']}</td><td>{$row['criticallevel']}</td>";
			echo "<td><form name='efrm{$row['id']}' action='?opt=edit_material&action={$_GET['action']}' method='post'>
						<input type='hidden' name='materialid' value='{$row['id']}' />
						<input type='hidden' name='edit' value='Edit'/>
						<img src='images/edit.png'alt='edit' onclick='document.efrm{$row['id']}.submit()'/>
							<a href='javascript:document.efrm{$row['id']}.submit()' onclick='return edit()'>Edit</a>
						
						</form></td>";
			echo "<td><form name='dfrm{$row['id']}' onsubmit='return del(this)' method='post'>
						<input type='hidden' name='materialid' value='{$row['id']}' />
						<input type='hidden' name='delete' value='Delete'/>
						<img src='images/delete.png'alt='delete' onclick='document.dfrm{$row['id']}.submit()'/>
						<a href='javascript:document.dfrm{$row['id']}.submit()' onclick='return del()'>delete</a>
						
						
						</form></td>";
			echo "</tr>";
			$i++;
		}
		echo "</table>";
	}else
	{
		echo "There are no {$action}s added yet.";
	}
?>
</center>
<script>
function isValid(val)
{
	if(val.elements['name'].value == ""||val.elements['critical'].value == "")
	{
		alert("Please fill all the required information for the material.");
		return false;
	}
	return true;
}
</script>