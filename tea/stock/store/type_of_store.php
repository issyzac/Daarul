<script type="text/javascript" language="javascript">
	function confirmDelete(n){
		if(n<5){
			alert("Sorry, this item cannot be deleted...");
			return false;
		}
		if(confirm("Are you sure you want to delete this Item?")) return true;
		else return false;
	}
	function confirmChanges(){
		if(confirm("Are you sure you want save the Changes you made?")) return true;
		else return false;
	}
</script>
<?php
	if(isset($_GET['act'])&&$_GET['act']=="edit"){
		$query="SELECT * FROM store_type WHERE typeid=".$_GET['typeid'];
		$result=mysql_query($query);
		$row=mysql_fetch_array($result);
?>
		<form action="" method="post" id="frmstoretype" name="frmstoretype" onsubmit="return confirmChanges()" >
			Type Name: <input type="text" required="required" name="type" id="type" placeholder="Type Name" size="30" value="<?php echo $row['typename'] ?>" /><br />
			Short description:<br />
			<textarea cols="40" rows="4" name="desc" id="desc" placeholder="Enter short descriptions here..." ><?php echo $row['description']?></textarea><br />
			<input type="submit" name="registerchanges" id="registerchanges" value="Save Changes" />
		</form>
<?php
		if(isset($_POST['registerchanges'])){
			$type=strtoupper($_POST['type']);
			$query="UPDATE store_type SET typename='{$type}',description='{$_POST['desc']}' WHERE typeid=".$_GET['typeid'];
			$result=mysql_query($query);
			if($result){
				header("Location: ?opt=store_types&status=Operation Successful");
				exit();
			}
		}
	}
	else{
?>
		<form action="" method="post" id="frmstoretype" name="frmstoretype" >
			Type Name: <input type="text" required="required" name="type" id="type" placeholder="Type Name" size="30" /><br />
			Short description:<br />
			<textarea cols="40" rows="4" name="desc" id="desc" placeholder="Enter short descriptions here..." ></textarea><br />
			<input type="submit" name="register" id="register" value="Register new Type" />
		</form>
<?php
		if(isset($_POST['register'])){
			$type=strtoupper($_POST['type']);
			$query="INSERT INTO store_type(typename,description) VALUES('{$type}','{$_POST['desc']}')";
			$result=mysql_query($query);
			if($result){
				header("Location: ?opt=store_types&status=Operation Successful");
				exit();
			}
		}
	}
	if(isset($_GET['act'])&&$_GET['act']=="delete"){
		if($_GET['typeid']<5){
			header("Location: ?opt=store_types&status=Operation Failed, this item cannot be deleted");
			exit();
		}
		else{
			$query="UPDATE store_type SET status=0 WHERE typeid=".$_GET['typeid'];
			$result=mysql_query($query);
			if($result){
				header("Location: ?opt=store_types&status=Operation Successful");
				exit();
			}
		}
	}
	### DISPLAY MESSAGE
	echo "<br />";
	if(isset($_GET['status'])) echo $_GET['status'];
	
	$query="SELECT * FROM store_type WHERE status=1";
	$result=mysql_query($query);
	if(mysql_num_rows($result)>0){
		echo "<br />Registered Types:";
		echo "<table>";
		echo "<tr style='color:#ffffff; background-color:#008010' ><th>#</th><th>Type Name</th><th>Description</th><th>Action</th></tr>";
		$i=1;
		while($row=mysql_fetch_array($result)){
			if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
			echo "<td>{$i}</td><td>{$row['typename']}</td><td>{$row['description']}</td><td><a href='?opt=store_types&act=edit&typeid={$row['typeid']}' ><img src='images/edit.png' alt='edit' />Edit</a> <a href='?opt=store_types&act=delete&typeid={$row['typeid']}' onclick='return confirmDelete({$row['typeid']})' ><img src='images/delete.png' alt='delete' />Delete</a></td></tr>";
			$i++;
		}
		echo "</table>";
	}
	else
		echo "No store type registered";
?>