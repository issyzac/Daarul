<script type="text/javascript" language="javascript">
	function validateData(){
		var date=document.getElementById('date').value;
		var plo=document.getElementById('plo').value;
		var supplier=document.getElementById('supplier').value;
		var material=document.getElementById('material').value;
		var quantity=document.getElementById('quantity').value;
		if(date==""||plo==""||supplier==0||material==0||quantity==""||date==""){
			alert("Please fill all the required fields");
			return false;
		}
		else{
			if(confirm("Place Order?")) return true;
			else return false;
		}
	}
	function validateOrder(){
		var file=document.forms['frmorder']['orderfile'].value.toString().trim();
		var supplier=document.forms['frmorder']['supplieru'].value;
		if(file.length==0||supplier==0){
			alert("You must select a file to upload and a supplier");
			return false;
		}
		else{
			if(confirm("Upload File?"))return true;
			else return false;
		}
	}
</script>
<?php
	if(isset($_GET['reply'])) echo $_GET['reply'];
	$reply="";
?>
<center>
<a href="?opt=view_orders">View placed orders</a>
<fieldset><legend align="center" >Upload Order details using Excel File</legend>
<a href="stock/uploading_data/material_list.php?operation=place_order" ><p align="center" >Download Material's List</p></a>
<form action="#" method="post" enctype="multipart/form-data" id="frmorder" name="frmorder" onsubmit="return validateOrder()" >
	Supplier:<select id="supplieru" name="supplieru" >
		<option value="0">-Select Supplier-</option>
		<?php
			$sql="SELECT * FROM supplier WHERE status=1";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
				echo "<option value='{$row['supplierid']}'>{$row['name']}</option>";
			}
		?>
	</select>
	Choose Excel File:<input type="file" name="orderfile" id="orderfile" /><input type="submit" id="upload" name="upload" value="Upload" />
</form></fieldset>
<?php
	#UPLOADING EXCEL FILE
	if(isset($_POST['upload'])){
		$exp=explode(".",$_FILES['orderfile']['name']);
		if(strtolower(end($exp))!="xls"&&strtolower(end($exp)!="xlsx")){
			echo "<span style='font-size:40px' >Invalid File Uploaded</span><br />";
		}
		else{
			$file="stock/uploading_data/uploads/".$_SESSION['userid'].$_FILES['orderfile']['name'];
			move_uploaded_file($_FILES['orderfile']['tmp_name'],$file);
			include("uploading_data/insert_order_data.php");
			insert($file);
			unlink($file);
		}
	}
?>
<form action="?opt=order_material" name="order" id="order" method="post" onsubmit="return validateData()">
	<table>
		<tr><td>LPO:</td><td><input type="text" id="plo" name="plo" /></td></tr>
		<tr><td>Material:</td><td><select type="text" id="material" name="material">
			<option value="0">-Select Material-</option>
			<?php
				$sql="SELECT * FROM material WHERE status=1";
				$result=mysql_query($sql);
				while($row=mysql_fetch_array($result)){
					echo "<option value='{$row['id']}'>{$row['name']}</option>";
				}
			?>
		</select></td></tr>
		<tr><td>Supplier:</td><td><select id="supplier" name="supplier" >
			<option value="0">-Select Supplier-</option>
			<?php
				$sql="SELECT * FROM supplier WHERE status=1";
				$result=mysql_query($sql);
				while($row=mysql_fetch_array($result)){
					echo "<option value='{$row['supplierid']}'>{$row['name']}</option>";
				}
			?>
		</select></td></tr>
		<tr><td>Quantity:</td><td><input type="text" id="quantity" name="quantity" /></td></tr>
		<tr><td>Date:</td><td><input type="text" id="date" name="date"  style="cursor:pointer" readonly="readonly" /></td></tr>
		<tr><td colspan="2" align="right" ><input type="reset" id="clear" name="clear" value="Clear" /><input type="submit" id="submit" name="submit" value="Submit" /></td></tr>
	</table>
	<link rel="stylesheet" href="jquery/jquery.datepick.css"/>
	<script type="text/javascript" language="javascript" src="jquery/jquery.js" ></script>
	<script type="text/javascript" language="javascript" src="jquery/jquery.datepick.js" ></script>
	<script type="text/javascript" language="javascript" >
	$('#date').datepick({dateFormat: 'yyyy-mm-dd', maxDate: new Date()});
</script>
</form>
<?php
	if(isset($_POST['submit'])){
		$plo=mysql_real_escape_string(htmlentities($_POST['plo']));
		$material=mysql_real_escape_string(htmlentities($_POST['material']));
		$supplier=mysql_real_escape_string(htmlentities($_POST['supplier']));
		$quantity=mysql_real_escape_string(htmlentities($_POST['quantity']));
		$date=mysql_real_escape_string(htmlentities($_POST['date']));
		$sql="INSERT INTO ordered_material(orderid,date,quantity,supplierid,materialid,outstanding) VALUES('{$plo}','{$date}','{$quantity}','{$supplier}','{$material}','{$quantity}')";
		$result=mysql_query($sql);
		if($result) $reply= '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />Order successfully recorded </p><br /></center>';
		else $reply= '<center>Failed to record order <br /></center>';
		header("Location: ?opt=order_material&reply={$reply}");
	}
?>
</center>