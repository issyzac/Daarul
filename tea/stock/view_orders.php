<link rel="stylesheet" href="jquery/jquery.datepick.css"/>
<script type="text/javascript" language="javascript" src="jquery/jquery.datepick.js" ></script>
<script type="" language="">
	function confirmDelete(){
		if(confirm("Delete this Order?")) return true;
		else return false;
	}
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
			if(confirm("Save Changes?")) return true;
			else return false;
		}
	}
	function filter(){
		var startdate=document.getElementById('startdate').value;
		var enddate=document.getElementById('enddate').value;
		if(startdate==""||enddate==""){
			alert("You must select both start and end date");
			return false;
		}
		$.ajax({
			url: 'stock/filter_orders.php?startdate='+startdate+'&enddate='+enddate,
			success:function(data){
				$('#placed_orders').html(data);
			}
		});
	}
</script>
<center><a href="?opt=order_material">Place more Orders</a><br />
<?php if(!isset($_GET['editid'])){ ?>
Filter From:<input type="text" name="startdate" id="startdate" style="cursor:pointer" readonly="readonly" />&nbsp;
To:<input type="text" name="enddate" id="enddate" style="cursor:pointer" readonly="readonly" />
<input type="button" name="btnfilter" id="btnfilter" value="Filter" onclick="filter()" />
<script type="text/javascript" language="javascript" >
	$('#startdate').datepick({dateFormat: 'yyyy-mm-dd', maxDate: new Date()});
	$('#enddate').datepick({dateFormat: 'yyyy-mm-dd', maxDate: new Date()});
</script>
<?php
}
	if(isset($_GET['reply'])) echo $_GET['reply'];
	$reply="";
	if(isset($_GET['editid'])){
		$sql="SELECT * FROM ordered_material WHERE orderid='{$_GET['editid']}'";
		$result=mysql_query($sql);
		$detailsRow=mysql_fetch_array($result);
		?><!--EDITING ORDER DETAILS-->
		<form action="" name="order" id="order" method="post" onsubmit="return validateData()">
		<table>
		<tr><td>LPO:</td><td><input type="text" id="plo" name="plo" value="<?php echo $detailsRow['lpo'];?>" /></td></tr>
		<tr><td>Material:</td><td><select type="text" id="material" name="material">
			<option value="0">-Select Material-</option>
			<?php
				$sql="SELECT * FROM material WHERE status=1";
				$result=mysql_query($sql);
				while($row=mysql_fetch_array($result)){
					if($detailsRow['materialid']==$row['id']) $select="selected='selected'";
					else $select="";
					echo "<option value='{$row['id']}' {$select} >{$row['name']}</option>";
				}
			?>
		</select></td></tr>
		<tr><td>Supplier:</td><td><select id="supplier" name="supplier" >
			<option value="0">-Select Supplier-</option>
			<?php
				$sql="SELECT * FROM supplier WHERE status=1";
				$result=mysql_query($sql);
				while($row=mysql_fetch_array($result)){
					if($detailsRow['supplierid']==$row['supplierid']) $select="selected='selected'";
					else $select="";
					echo "<option value='{$row['supplierid']}' {$select} >{$row['name']}</option>";
				}
			?>
		</select></td></tr>
		<tr><td>Quantity:</td><td><input type="text" id="quantity" name="quantity" value="<?php echo $detailsRow['quantity'];?>" /></td></tr>
		<tr><td>Date:</td><td><input type="text" id="date" name="date"  style="cursor:pointer" readonly="readonly" value="<?php echo $detailsRow['date'];?>" /></td></tr>
		<tr><td colspan="2" align="right" ><input type="submit" id="submit" name="submit" value="Save Changes" /></td></tr>
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
		$sql="UPDATE ordered_material SET supplierid='{$supplier}',materialid='{$material}',quantity='{$quantity}',date='{$date}' WHERE orderid='{$_GET['editid']}'";
		$result=mysql_query($sql);
		if($result) $reply= '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />Order successfully updated </p><br /></center>';
		else $reply= '<center>Failed to update order details <br /></center>';
		header("Location: ?opt=view_orders&reply={$reply}");
	}
	}
	elseif(isset($_GET['delid'])){ ///DELETING ORDER
		$sql="DELETE FROM ordered_material WHERE orderid='{$_GET['delid']}'";
		$result=mysql_query($sql);
		if($result) $reply= '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />Order successfully deleted </p><br /></center>';
		else $reply= '<center>Failed to delete order <br /></center>';
		header("Location: ?opt=view_orders&reply={$reply}");
	}
	else{//DISPLAYING ORDERS
		echo '<div id="placed_orders" name="placed_orders" >';
		$sql="SELECT material.name AS matname,ordered_material.orderid AS orid,ordered_material.lpo AS plo,ordered_material.date AS date,ordered_material.quantity AS qty,ordered_material.outstanding AS deni,supplier.name AS sname FROM ordered_material,material,supplier WHERE ordered_material.supplierid=supplier.supplierid AND ordered_material.materialid=material.id";
		$result=mysql_query($sql) or die(mysql_error());
		if(mysql_num_rows($result)>0){
		echo "<table>";
		echo "<tr style='color:#ffffff; background-color:#008010' ><th>LPO</th><th>Material</th><th>Supplier</th><th>Order Date</th><th>Ordered Quantity</th><th>Outstanding Order</th><th colspan='2'>Action</th></tr>";
		$row_count=1;
		while($row=mysql_fetch_array($result)){
			if($row_count % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
			echo "<td>{$row['plo']}</td><td>{$row['matname']}</td><td>{$row['sname']}</td><td>{$row['date']}</td><td>{$row['qty']}</td><td>{$row['deni']}</td><td><a href='?opt=view_orders&editid={$row['orid']}'> <img src='images/edit.png' alt='edit' /> Edit </a></td><td><a href='?opt=view_orders&delid={$row['orid']}' onclick='return confirmDelete()'> <img src='images/delete.png' alt='delete' /> Delete </a></td></tr>";
			$row_count++;
		}
		echo "</table>";
		}
		else{
			echo "No placed order";
		}
		echo '</div>';
	}
?>
</center>