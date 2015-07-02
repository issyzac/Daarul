<script>
	var qtyAvailable;
	var request;
	var bra=<?php echo $_GET['branchid']; ?>;
	function del()
	 {
		 if(confirm("Do you want to delete this item"))
		 {
			return true;
		 }
		 else
			return false;
	 }
	 function getRequest(){
		if(window.XMLHttpRequest) request=new XMLHttpRequest();
		else request=new ActiveXObject("Microsoft.XMLHTTP");
	}
	 function validateQuantity(){
		var qtySale=parseFloat(document.forms['frmbr']['quantity'].value);
		var pro=document.forms['frmbr']['productid'].value;
		var date=document.forms['frmbr']['date'].value.toString();
		var type=document.forms['frmbr']['type'].value;
		var ref=document.forms['frmbr']['receipt'].value.toString().trim();
		if(date.length==0||ref.length==0||type==0||qtySale.toString().trim().length==0||pro==0){
			alert("You must enter all the required fields");
			return false;
		}
		var url="branches/getquantity.php?pro="+pro+"&bra="+bra;
		getRequest();
		request.onreadystatechange=processrequest;
		request.open("GET",url,false);
		request.send();
		if(qtyAvailable<qtySale){
			alert("You don't have enough products fullfill this quantity in this branch");
			return false;
		}
		else
		return true;
	 }
	 function processrequest(){
		if(request.readyState==4){
			if(request.status==200){
				qtyAvailable=parseFloat(request.responseText);
			}else alert(request.statusRequest);
		}
	 }
</script>
<?php
	$branch_id = $_GET['branchid'];
	
	if(isset($_REQUEST['productid'])){	
		$receipt = $_REQUEST['receipt'];
		$productid = $_REQUEST['productid'];
		$branchid = $_GET['branchid'];
		$quantity = $_REQUEST['quantity'];
		$date = $_REQUEST['date'];
		$type = $_REQUEST['type'];
		
		$insert_query = "INSERT INTO `pmngdb`.`branch_sales` (`receipt`, `productid`, `branchid`, `quantity`, `date`, `type`) 
		VALUES('$receipt', $productid, $branchid, $quantity, '$date', '$type')";
		
		echo '<center>';
		if($insert_sales = mysql_query($insert_query)){
			echo'<img src="images/ok.png" alt="Ok" style="width:100px" /><br />
			Sales Data Saved Successfully.
			';
		} else {
			echo 'There was an Error Saving Sales Data: '.mysql_error();
		}
		echo '</center>';
	}
	
	if(isset($_REQUEST['editid'])){	
		$edit_id = $_REQUEST['editid'];
		$receipt = $_REQUEST['receipt'];
		$branchid = $_GET['branchid'];
		$quantity = $_REQUEST['quantity'];
		$date = $_REQUEST['date'];
		$type = $_REQUEST['type'];
		
		$insert_query = "UPDATE branch_sales SET receipt = '$receipt', branchid = $branchid, quantity = $quantity, date = '$date', type = '$type' WHERE salesid = $edit_id ";
		
		echo '<center>';
		if($insert_sales = mysql_query($insert_query)){
			echo'<img src="images/ok.png" alt="Ok" style="width:100px" /><br />
			Sales Data Updated Successfully.
			';
		} else {
			echo 'There was an Error Updating Sales Data: '.mysql_error();
		}
		echo '</center>';
	}
	
	if(isset($_REQUEST['delete'])){
		$delete_id = $_REQUEST['delete'];
		echo '<center>';
		if(mysql_query("DELETE FROM branch_sales WHERE salesid = $delete_id")){
			echo'<img src="images/ok.png" alt="Ok" style="width:100px" /><br />
			Sales Data Deleted.
			';
		}	else {
		echo 'There was an Error Deleting Sales Data: '.mysql_error();
		}
		echo '</center>';
	}
	
	if(isset($_REQUEST['edit'])){
		$edit_id = $_REQUEST['edit'];
		$branch_sales = mysql_query("SELECT * FROM product, branch_sales WHERE salesid = $edit_id AND branch_sales.branchid = $branch_id AND branch_sales.productid = product.id") or die(mysql_error());
		while ($data = mysql_fetch_array($branch_sales)){
		
			echo'
	
	<br />Edit Sales for '.$data["name"].' in this branch: <br />
	<form method="post" action="index.php?opt=branches&amp;branchid='.$branch_id.'">
	
	<input name="date"  style="width:130px" id="datepick" placeholder="Select Date" readonly="true" style="cursor:pointer" value="'.$data["date"].'" />
	
	<select name="editid" style="width:120px">
	
	<option value="'.$edit_id.'"> '.$data["name"].' </option>
	';
		
	$products = mysql_query("SELECT * FROM product WHERE status = 1") or die(mysql_error());
	while ($dataproducts = mysql_fetch_array($products)){
		echo '<option value="'.$dataproducts["id"].'"> '.$dataproducts["name"].' </option>';
	}
	echo'
	</select>
	
	<input name="quantity" style="width:75px" type="text" size="10" placeholder="Quantity" value="'.$data["quantity"].'" />
	
	<select name="type" style="width:120px">
		<option value="'.$data["type"].'"> '.$data["type"].' </option>
		<option value="sales"> Normal Sales </option>
		<option value="van"> Sales Van </option>
		<option value="free"> Free Issue </option>
	</select>
	
	<input name="receipt" type="text"  style="width:118px" placeholder="Ref Number" value="'.$data["receipt"].'" />
	
	<input type="submit" value="Save Changes" style="width:120px">
	</form>
	';

		
		}
	
	} else {
	echo'
	
	<br />Select products to sell in this branch: <br />
	<form method="post" action="index.php?opt=branches&amp;branchid='.$branch_id.'" onsubmit="return validateQuantity()" name="frmbr" id="frmbr" >
	
	<input name="date"  style="width:130px" id="date" placeholder="Select Date" readonly="true" style="cursor:pointer" />
	
	<select name="productid" style="width:120px" id="productid">
	
	<option value="0"> Select Product </option>
	';
	
	#################################################################################
	$products = mysql_query("SELECT * FROM product WHERE status = '1' AND id IN( SELECT productid FROM branch_stock WHERE branchid='{$_GET['branchid']}')") or die(mysql_error());
	while ($data = mysql_fetch_array($products)){
		echo '<option value="'.$data["id"].'"> '.$data["name"].' </option>';
	}
	echo'
	</select>
	
	<input name="quantity" style="width:75px" type="text" size="10" placeholder="Quantity" id="quantity" />
	
	<select name="type" style="width:120px" id="type" >
		<option value="0"> Type of Sales </option>
		<option value="sales"> Normal Sales </option>
		<option value="van"> Sales Van </option>
		<option value="free"> Free Issue </option>
	</select>
	
	<input name="receipt" type="text" id="receipt"  style="width:118px" placeholder="Ref Number" />
	
	<input type="submit" value="Submit" style="width:120px">
	</form>
	';
	}
	
	
	$branch_sales = mysql_query("SELECT * FROM product, branch_sales WHERE branch_sales.branchid = $branch_id AND branch_sales.productid = product.id") or die(mysql_error());
	
	if(mysql_num_rows($branch_sales)){	
		echo '<br />Product sold in this branch:<br />';
		echo'<table>
		<tr style="color:#ffffff; background-color:#008010"><th>#</th>
		<th style="width:120px">Date of Sales</th>
		<th style="width:120px">Product Name</th>
		<th style="width:80px">Quantity</th>
		<th style="width:120px">Type of Sales</th>
		<th style="width:120px">Reference No.</th>
		<th style="width:120px">Action</th></tr>
		';
		$i = 1;
		while ($data = mysql_fetch_array($branch_sales)){
			if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
			echo'
			<td>'.$i.'</td>
			<td>'.$data["date"].'</td>
			<td>'.$data["name"].'</td>
			<td>'.$data["quantity"].'</td>
			<td>'.$data["type"].'</td>
			<td>'.$data["receipt"].'</td>
			<td><a href="index.php?opt=branches&amp;branchid='.$branch_id.'&amp;edit='.$data["salesid"].'"> <img src="images/edit.png" alt="edit" />Edit</a> &nbsp;
			<a onclick="return del()" href="index.php?opt=branches&amp;branchid='.$branch_id.'&amp;delete='.$data["salesid"].'"> <img src="images/delete.png" alt="delete" />Delete</a></td>
			';
			echo '</tr>';
			$i++;
		}
		echo'</table>';
	} else {
		echo 'There is no product sold in this branch. <br /><br />';
	}
	
	
?>

<script type="text/javascript" src="branches/datepickr.js"></script>
<script type="text/javascript">
	new datepickr('date', {
		'dateFormat': 'Y-m-d'
	});
</script>