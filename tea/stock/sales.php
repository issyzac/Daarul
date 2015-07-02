<script language="javascript" type="text/javascript">
	var request;
	var dbquantity;
	var quantity;
	function getRequest(){
		if(window.XMLHttpRequest) request=new XMLHttpRequest();
		else request=new ActiveXObject("Microsoft.XMLHTTP");
	}
	function checkProduct(productid){
		getRequest();
		var url="stock/check_availability.php?productid="+productid;
		request.onreadystatechange=processPequest;
		request.open("GET",url,false);
		request.send(null);
	}
	function processPequest(){
		if(request.readyState==4){
			if(request.status==200){
				dbquantity=parseFloat(request.responseText);
			}else alert(request.statusRequest);
		}
	}
	function confirmUpdate(){
		if(confirm("Are you sure you want to Save Changes?")) return true;
		else return false;
	}
	function confirmDelete(){
		if(confirm("Are you sure you want to delete this entry?")) return true;
		else return false;
	}
	function validateData(control){
		var strqty=document.getElementById(control).value.toString();
		if(strqty.length==0){
			alert("No Quantity Entered");
			return false;
		}
		else{
			return true;
		}
	}
	function saveSales(){
		var refno=document.forms['sales']['refnumber'].value.toString();
		var qty=document.forms['sales']['quantity'].value.toString();
		
		try{
			quantity=parseFloat(qty);
			var productid=document.forms['sales']['productid'].value;
			checkProduct(productid);
			if(quantity>dbquantity){
				alert("You don't have enough products to fulfill this quantity");
				return false;
			}
		}
		catch(e){
			alert("Invalid input detected in Quantity field");
			return false;
		}
		
		if(refno.length==0||qty.length==0){
			alert("You have to enter complete details");
			return false;
		}
		else return true;
	}
	function confirmUpload(){
		var file=document.forms['uploadsales']['salesData'].value.toString();
		if(file.length!=0){
			if(confirm("You are about to upload a file, Continue?")) return true;
			else return false;
		}
		else{
			alert("You must select a file to upload");
			return false;
		}
	}
</script>
<?php
	require_once "availability_function.php";
	if(isset($_GET['todo'])){
		echo '<center><a href="?opt=sales" >Back to Sales</a><br /><br />';
		$sql="select product_id,quantity,name,action,receiptnumber from sales,product where product_id=id AND date='".date('Y-m-d')."'";
		$result=mysql_query($sql);
		if($result){
			if(mysql_num_rows($result)>0){
				echo "Sold Products and their Quantities for this day";
				echo "<table border='1'>";
				echo '<tr style="color:#ffffff; background-color:#008010"><th>#</th><th>Type</th><th>Reference Number</th><th>Product Name</th><th>Quantity</th><th>Action</th></tr>';
				$i=1;
				while($row=mysql_fetch_array($result)){
					if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
					echo "<td>{$i}</td><td>{$row['action']}</td><td>{$row['receiptnumber']}</td><td>{$row['name']}</td><td>{$row['quantity']}</td><td><a href='?opt=sales&action=edit&productid={$row['product_id']}&type={$row['action']}' ><img src='images/edit.png' alt='edit' />Edit</a><a href='?opt=sales&action=delete&productid={$row['product_id']}&type={$row['action']}' onclick='return confirmDelete()' ><img src='images/delete.png' alt='delete' />Delete</a>&nbsp;</td></tr>";
					$i++;
				}
				echo "</table></center>";
			}else echo "<span style='font-size:25px' >No entry made today</span>";
		}
		else echo mysql_error();
	}else{
?>
<fieldset><legend>Enter Sales Data using Excel File</legend>
	<a href="stock/uploading_data/product_list.php" ><p align="center" >Download Product List</p></a>
	<form name="uploadsales" id="uploadsales" action="#" method="post" enctype="multipart/form-data" onsubmit="return confirmUpload()" align="center" >
		Type: <select id="type" name="type" ><option value="Sales" >Sold Products</option><option value="Free Issue" >Freely Issued</option></select>
		Select Excel file to upload :<input type="file" id="salesData" name="salesData" /><input type="submit" id="uploadFile" name="uploadFile" value="Upload" />
	</form>
</fieldset>
<?php
	#UPLOADING EXCEL FILE
	if(isset($_POST['uploadFile'])){
		$type=$_POST['type'];
		$exp=explode(".",$_FILES['salesData']['name']);
		if(strtolower(end($exp))!="xls"&&strtolower(end($exp)!="xlsx")){
			echo "<span style='font-size:40px' >Invalid File Uploaded</span>";
		}
		else{
			$file="stock/uploading_data/uploads/".$_FILES['salesData']['name'];
			move_uploaded_file($_FILES['salesData']['tmp_name'],$file);
			include("uploading_data/insert_sales_data.php");
			insert($file,$type);
			unlink($file);
		}
	}
	
	if(isset($_POST['submitUpdate'])){
		$sql="UPDATE SALES SET quantity='{$_POST['quantityupdate']}' WHERE product_id='{$_GET['productid']}' AND date='".date('Y-m-d')."' AND action='{$_GET['type']}'";
		if(!mysql_query($sql)) echo "Failed to Update!";
		else{
			$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Changed this day's Sales quantity for the product','".date('Y-m-d')."','".date('H:i:s')."','{$_GET['productid']}','sales')";
			mysql_query($auditSql);
		}
		unset($_GET['action']);
	}
	if(isset($_GET['action'])){
		if($_GET['action']=="edit"){##EDITING
			$sql="select quantity,name from sales,product where product_id=id AND date='".date('Y-m-d')."' AND product_id='{$_GET['productid']}' AND action='{$_GET['type']}'";
			$resultEdit=mysql_query($sql);
			$row=mysql_fetch_array($resultEdit);
			echo "<p>Edit Quantity for this Product</p>";
			echo '<form action="#" method="post" onsubmit="return confirmUpdate()"><table>';
				echo '<tr style="color:#ffffff; background-color:#008010" ><th>Product Name</th><th>Quantity</th></tr>';
				echo "<tr class='odd' ><td >{$row['name']}</td><td><input type='text' id='quantityupdate' value='{$row['quantity']}' name='quantityupdate' /></td></tr>";
				echo "<tr class='even' ><td colspan='2' align='right' ><input type='submit' value='Update' id='submitUpdate' name='submitUpdate' /></td></tr>";
			echo '</table></form>';
		}
		else if($_GET['action']=="delete"){##DELETING
			$sql="DELETE FROM SALES WHERE product_id='{$_GET['productid']}'AND date='".date('Y-m-d')."' AND action='{$_GET['type']}'";
			$fld=mysql_query($sql);
			if($fld){
				$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Deleted Sales information for the product from the database','".date('Y-m-d')."','".date('H:i:s')."','{$_GET['productid']}','sales')";
				mysql_query($auditSql);
			}
		}
		else if($_GET['action']=="insert"){##INSERTING
			$productId=$_GET['productid'];
			$sql="select name,criticallevel from product where id={$productId}";
			$available = check_availability($productId,date('Y-m-d'));
			
			$result=mysql_query($sql);
			$row=mysql_fetch_array($result);
			echo "<strong>Available product ".$available.".</strong><br/><br/>";
			if($available <= $row['criticallevel']){
				echo  "<strong style = 'color:red'>Critical level reached, add more product</strong><br/><br/>";
			}
			echo "Enter a sold quantity for the product";
			
			echo '<form action="#" method="post" name="sales" id="sales" onsubmit="return saveSales()" ><table border="1" onsubmit="return validateData(\'quantity\')">';
				echo '<tr style="color:#ffffff; background-color:#008010"><th>Type</th><th>Ref. Number</th><th>Product Name</th><th>Quantity</th></tr>';
				echo "<tr class='odd'><td>";
?>
				<select id="type" name="type" >
					<option value="Sales" >Sold Product</option>
					<option value="Free Issue" >Freely Issued</option>
				</select>
<?php
				echo "</td><td><input type='text' id='refnumber' name='refnumber' /></td><td>{$row['name']}</td><td><input type='text' id='quantity' name='quantity' /></td></tr>";
				echo "<tr class='even' ><td colspan='4' align='right'><input type='submit' id='submit' name='submit' value='Submit' /></td></tr>";
				echo "<input type='hidden' id='productid' name='productid' value='{$_GET['productid']}' />";
			echo '</table></form>';
			if(isset($_POST['submit'])){
				$qty=$_POST['quantity'];
				$date=date('Y-m-d');
				$sql="insert into sales(Product_Id,action,receiptnumber,quantity,date) values('{$productId}','{$_POST['type']}','{$_POST['refnumber']}','{$qty}','{$date}')";
				$succ=mysql_query($sql);
				if(!$succ){
					$qry="update sales set quantity=quantity+{$qty} WHERE product_id={$_GET['productid']} AND date='".date('Y-m-d')."'";
					$upd=mysql_query($qry);
					if(!$upd) $status="Failed to add sales data";
					else{
						$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Entered sold quantity for the product','".date('Y-m-d')."','".date('H:i:s')."','{$productId}','sales')";
						mysql_query($auditSql);
						$status="Successfully Recorded";
					}
					header("Location: ?opt=sales&status=".$status);
				}else{
					$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Entered sold quantity for the product','".date('Y-m-d')."','".date('H:i:s')."','{$productId}','sales')";
					mysql_query($auditSql);
					$status="Successfully Recorded";
					header("Location: ?opt=sales&status=".$status);
				}
			}
		}
	}
		echo "<br />";
		if(isset($_GET['status']))echo "<center><span style='font-size:25px'>".$_GET['status']."</span></center><br />";
		echo '<center><a href="?opt=sales&todo=view" >View Sold products for this day</a><br /><br /></center>';
		
	$blendResult=mysql_query("select * from blend where status=1")or die(mysql_query());
	echo "<table>";
	echo "<tr>";
	while($blendRow=mysql_fetch_array($blendResult)){
		$blendId=$blendRow['id'];
		
		echo "<td align='center' valign='top' width='150px' >";
		echo "<span style='font-size:25px'>".$blendRow['name']."</span><br />";
		$sqlProduct="select product.name as pname,product.id as pid from product,blend where product.blendid={$blendId} AND product.blendid=blend.id AND product.status=1";
		$productResult=mysql_query($sqlProduct) or die(mysql_error());
		while($productRow=mysql_fetch_array($productResult)){
			$pid=$productRow['pid'];
			echo "<a href='?opt=sales&productid={$pid}&action=insert'>{$productRow['pname']}</a><br />";
		}
		echo "</td>";
	}
	echo "</tr></table>";
	}

	
	
	if(isset($_SESSION['rejected'])){
		header("Location:stock/uploading_data/product_list.php");
		exit;
		#unset($_SESSION['rejected']);
	}
?>