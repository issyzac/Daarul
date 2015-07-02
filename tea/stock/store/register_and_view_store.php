<script type="text/javascript" language="javascript">
	function validateData(){
		var name=document.forms['frmstore']['storename'].value.toString();
		var cont=document.forms['frmstore']['storecontact'].value.toString();
		var type=document.forms['frmstore']['type'].value;
		if(name.length==0||cont.length==0||type==0){
			alert("Please fill all the required spaces");
			return false;
		}
		else{
			if(confirm("You are about to Register new Store, Continue?")) return true;
			else return false;
		}
	}
	function confirmDelete(bid){
		if(bid==0){
			alert("Sorry, Main Branch can not be deleted");
			return false;
		}
		if(confirm("Are you sure you want to delete this Store?")) return true;
		else return false;
	}
	var request;
	function getRequest(){
		if(window.XMLHttpRequest){
			request=new XMLHttpRequest();
		}
		else{
			request=new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	function getBranch(){
		getRequest();
		var url="stock/store/get_branch.php";
		request.onreadystatechange=processRequest;
		request.open("GET",url,true);
		request.send(null);
	}
	function processRequest(){
		if(request.readyState==4){
			if(request.status==200){
				document.getElementById("bt").innerHTML='<option value="0">Main Branch</option>'+request.responseText;
			}else alert(request.statusRequest);
		}
	}
	function processChanges(){
		var id=document.getElementById("type").value;
		document.getElementById("bt").innerHTML='<option value="0">Main Branch</option>';
		if(parseInt(id)>2) getBranch();
	}
</script>
<form action="#" method="post" onsubmit="return validateData()" name="frmstore" id="frmstore" >
<table>
	<tr><td>Store Name:</td><td><input type="text" id="storename" name="storename"  /></td></tr>
	<tr><td>Contacts:</td><td><input type="text" id="storecontact" name="storecontact" /></td></tr>
	<tr><td>Type:</td><td>
		<select id="type" name="type" onchange="processChanges()" >
			<option selected="selected" value="0" >Select Type of Store</option>
			<?php
				$storeQuery="SELECT typeid,typename FROM store_type WHERE status='1'";
				$storeResult=mysql_query($storeQuery);
				while($row=mysql_fetch_array($storeResult)){
					echo "<option value='{$row['typeid']}' >{$row['typename']}</option>";
				}
			?>
		</select>
	</td></tr>
	<tr><td>Belong to</td><td>
		<select id="bt" name="bt" >
			<option value="0">Main Branch</option>
		</select>
	</td></tr>
	<tr><td colspan="2" align="right" ><input type="reset" id="clear" name="clear" value="Clear" /><input type="submit" id="save" name="save" value="Save" /></td></tr>
</table>
</form>
<?php
	if(isset($_POST['save'])){
		$sqlStore="INSERT INTO store(storename,contacts,type,status,belongto) VALUES('{$_POST['storename']}','{$_POST['storecontact']}','{$_POST['type']}','1','{$_POST['bt']}')";
		$resultInsert=mysql_query($sqlStore);
		if($resultInsert){
			$response="Store successfully registered";
			header("Location: ?opt=stores&resp={$response}");
		}
		else echo mysql_error();
	}
	if(isset($_POST['saveedit'])){
		$sqlEdit="UPDATE store SET storename='{$_POST['storename']}',contacts='{$_POST['storecontact']}',type='{$_POST['type']}',belongto='{$_POST['bt']}' WHERE storeid='{$_POST['storeid']}'";
		$result=mysql_query($sqlEdit);
		if($result){
			$response="Store details successfully updated";
			header("Location: ?opt=stores&resp={$response}");
		}
		else echo mysql_error();
	}
	if(isset($_GET['delsid'])){
		if($_GET['delsid']==0){
			$response="Sorry, Main Branch can not be deleted";
			header("Location: ?opt=stores&resp={$response}");
			exit();
		}
		else{
			$sqlDelete="UPDATE store SET status=0 WHERE storeid='{$_GET['delsid']}'";
			$result=mysql_query($sqlDelete);
			if($result){
				$response="Store successfully deleted";
				header("Location: ?opt=stores&resp={$response}");
				exit();
			}
		}
	}
	
	if(isset($_GET['resp'])) echo $_GET['resp'];
	
	$sqlFetchStore="SELECT * FROM store s,store_type st WHERE s.status=1 AND st.status=1 AND s.type=st.typeid AND s.storeid<>0";
	$resultFetchStore=mysql_query($sqlFetchStore);
	if($resultFetchStore){
		if(mysql_num_rows($resultFetchStore)>0){
			echo "<table>";
			echo "<tr style='color:#ffffff; background-color:#008010' ><th>#</th><th>Store Name</th><th>Contacts</th><th>Type of Store</th><th>Action</th></tr>";
			$i=1;
				while($row=mysql_fetch_array($resultFetchStore)){
					if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
					echo "<td>{$i}</td><td>{$row['storename']}</td><td>{$row['contacts']}</td><td>{$row['typename']}</td><td><a href='?opt=edit_store&sid={$row['storeid']}' ><img src='images/edit.png' alt='edit' />Edit</a>&nbsp;<a href='?opt=stores&delsid={$row['storeid']}' onclick='return confirmDelete({$row['storeid']})' ><img src='images/delete.png' alt='delete' />Delete</a></td></tr>";
					$i++;
				}
			echo "</table>";
		}else echo "No store registered";
	}
	else echo "Failed to Fetch Store data ".mysql_error();
?>