<script type="text/javascript">
	function editStock(proId, stockValue, fieldId){
		document.getElementById(fieldId).innerHTML = '<input name="new_value" type="text" size="10" value="'+stockValue+'" />';
		document.getElementById(fieldId+'_save').innerHTML = '<input name="proID" type="hidden" value="'+proId+'" /><input type="submit" value="Save" />';
	}

</script>
<?php
	if(isset($_POST['proID'])){
		$editid = $_POST['proID'];
		$sql = "UPDATE product_stock SET openingstock = '{$_POST['new_value']}' WHERE productid='{$_POST['proID']}' AND date='".date("Y-m-d")."'";
		$result = mysql_query($sql) or die(mysql_error());
		if($result)
		{
			mysql_query("INSERT INTO event VALUES('{$_SESSION['userid']}','Changed values for products opening stock','".date("Y-m-d")."','".date("H:i:s")."','dailyinput','')");
		}
		
		$sql = "UPDATE material_stock SET openingstock = '{$_POST['new_value']}' WHERE materialid='{$_POST['proID']}' AND date='".date("Y-m-d")."'";
		$result = mysql_query($sql) or die(mysql_error());
		if($result)
		{
			mysql_query("INSERT INTO event VALUES('{$_SESSION['userid']}','Changed values for material opening stock','".date("Y-m-d")."','".date("H:i:s")."','dailyinput','')");
		}
	}

	echo "<h3>Products Opening Stock</h3>";
	if(isset($_POST['submit'])){
		$counter=$_POST['count'];
		for($i=1;$i<$counter;$i++){
			$qty=$_POST['qty'.$i];
			mysql_query("insert into product_stock(productid,openingstock,closingstock,date) values('".$_POST[$i]."','".$qty."','".$qty."','".date('Y-m-d')."')") or die('Error: '.mysql_error());
			$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Initialized Opening Stock for the Product','".date('Y-m-d')."','".date('H:i:s')."','{$_POST[$i]}','product_stock')";
			mysql_query($auditSql);
		}
	}		
	
	$i=1;
	$sqlBlend="SELECT id,name FROM blend WHERE status=1";
	$resultBlend=mysql_query($sqlBlend);
	if(mysql_num_rows($resultBlend)>0){
		while($blendRow=mysql_fetch_array($resultBlend)){
			$bid=$blendRow['id'];
			$bname=$blendRow['name'];
			echo "<span style='font-size:20px'>{$bname}</span><br />";
			$result=mysql_query("select product.id as id, product.name as name,openingstock,date from product_stock,product where date=(select max(date) from product_stock) AND product.id=product_stock.productid and status=1 AND blendid='{$bid}'") or die(mysql_error());
			if(mysql_num_rows($result)>0){
			echo '<form action="?opt=open_stock" method="post"> ';
			echo '<table border="1">';
			echo '<tr style="color:#ffffff; background-color:#008010" >
			<th>#</th><th>PRODUCT NAME</th><th>OPENING STOCK</th><th>ACTION</th></tr>';
	
			while($row=mysql_fetch_array($result)){
				if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
				echo "<td>".$i."</td><td>{$row['name']}</td><td id='".$i."'>{$row['openingstock']}</td>";
				echo "<td id='".$i."_save'>";
				
				if($_SESSION['privilege'] == 'Administrator') echo "<img src='images/edit.png'alt='edit' /><a href='javascript: editStock({$row['id']}, {$row['openingstock']}, ".$i.");' >Edit </a>";
				
				echo"</td>";
				echo "</tr>";
				$i++;
			}
			echo '</table><br />';
			echo '</form>';
			}
		}
	}
	$strQuery='select * from product where id not in(select productid from product_stock) and status=1';
	$result=mysql_query($strQuery);
	if(!$result){
		die('Error: '.mysql_error());
	}
	if(mysql_num_rows($result)>0){
		echo "<h3>Please Initialize the following Product(s)</h3>";
		echo '<form action="#" method="post"><table border="1">';
		echo '<tr style="color:#ffffff; background-color:#008010" ><th>#</th><th>PRODUCT NAME</th><th>OPENING STOCK</th></tr>';
		$i=1;
		while($row=mysql_fetch_array($result)){
			if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
			echo "<td>".$i."</td><td>{$row['name']}</td><td><input type='text' name='qty".$i."' id='qty".$i."' /></td></tr>";	
			echo "<input type='hidden' value='{$row['id']}' id='{$i}' name='{$i}'>";	
			$i++;
		}
		echo "<input type='hidden' value='{$i}' name='count' id='count'>";
		echo '<tr><td colspan="3" align="right"><input type="submit" value="Submit" name="submit" id="submit"  /></td></tr>';
		echo '</table></form>';
	}
?>