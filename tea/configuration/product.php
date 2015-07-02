
	<script language="javascript" type="text/javascript">
		function validateData(){
		 	var name=document.getElementById('name').value.toString();
			var weightperprimary =document.getElementById('wtp').value.toString();
			var weightpercarton =document.getElementById('wtc').value.toString();
			var primaryunit=document.getElementById('pc').value.toString();
			var pkts  =document.getElementById('pk').value.toString();
			if(name.length==0||weightperprimary.length==0||weightpercarton.length==0||primaryunit.length==0|| pkts.length==0){
				alert("Please Enter complete Details");
				return false;
			}
			else
			return true;
			}
	</script>


<?php 

$unique_product = true;

if(isset($_POST['add'])){
	$check_query = mysql_query("SELECT * FROM product WHERE name = '{$_POST['name']}' AND blendid='{$_GET['viewid']}' AND status='1'");
	if(mysql_num_rows($check_query)) {
		$unique_product = false;
		echo '<span style="color:red">Duplicate product <b>'.$_POST['name'].'</b>. Product could not be added!<br /><br /></span>';
	}
}

if(isset($_GET['reply']) && $unique_product) echo $_GET['reply'];

?>


	<form action="#" method="post" onsubmit="return validateData()" name="product" id="product"/>
<fieldset>
	<legend align="center">PRODUCT REGISTRATION</legend>
	<table>
<tr><td>Packet Size(g): </td><td><input type="text"  id="name" name="name" /></td></tr>
<tr><td>Wt PerPrimaryUnit(gms)</td><td><input type="text" id="wtp" name="wtp" /></td></tr>
<tr><td>Wt PerCarton(kgs)</td><td><input type="text" id="wtc" name="wtc" /></td></tr>
<tr><td>Primary unitPerCarton</td><td><input type="text" id="pc" name="pc" /></td></tr>
<tr><td>Pkts/ct</td><td><input type="text" id="pk" name="pk" /></td></tr>
<tr><td>Critical Level:</td><td><input type="text" id="critical" name="critical" /></td></tr>

<tr><td></td><td>
<input type="submit" id="btnsuubmit" value="Submit" name="add"/></td></tr>

</table>
</fieldset>

</form>
<?php
$reply="";
	if(isset($_GET['viewid'])){
		if(isset($_POST['add']) && $unique_product){
		   $weightperprimary=$_POST['wtp'];
			$weightpercarton=$_POST['wtc'];
			$primaryunit=$_POST['pc'];
			$pkts=$_POST['pk'];
			$sql="INSERT INTO product (name,blendid,wtperprimaryunit,wtpercarton,primaryunitpercarton,pkts,criticallevel) VALUES ('{$_POST['name']}','{$_GET['viewid']}','{$weightperprimary}','{$weightpercarton}','{$primaryunit}','{$pkts}','{$_POST['critical']}')"; 
			if (!mysql_query($sql)){
				die('Error: ' . mysql_error());
			}else
			{
				$reply= '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />Product added successifuly  </p><br /></center>';
			}
			header("Location: ?opt={$_GET['opt']}&viewid={$_GET['viewid']}&reply={$reply}");
		}
	if(isset($_GET['delid']))
	{
		$query = mysql_query("Update product set status='0' WHERE id='{$_GET['delid']}'");
		if($query)
		{
		
			$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Deleted item from table','".date('Y-m-d')."','".date('H:i:s')."','{$_GET['delid']}','product')";
			mysql_query($auditSql);
			$reply= '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />Product was deleted successifuly  </p><br /></center>';
		}
			header("Location: ?opt={$_GET['opt']}&viewid={$_GET['viewid']}&reply={$reply}");
		
	}
	if(isset($_POST['edit']))
	{
		$query = mysql_query("UPDATE product SET name='{$_POST['name']}',criticallevel='{$_POST['critical']}' WHERE id='{$_POST['editid']}'");
		if($query)
		$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Edited item from table','".date('Y-m-d')."','".date('H:i:s')."','{$_GET['editid']}','product')";
			mysql_query($auditSql);
		{
		$reply= '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />Product was  successifuly Edited </p><br /></center>';
			
		}
			header("Location: ?opt={$_GET['opt']}&viewid={$_GET['viewid']}&reply={$reply}");
	}
	$query = mysql_query("SELECT * FROM blend where status=1 AND id='{$_GET['viewid']}'");
	
	$row= mysql_fetch_array($query);
	echo "Blend: {$row['name']}<br />";
	echo"Key:<br />WTP=weight per Primary Unit(gms)<br />";
	echo"WTC=weight per Carton(kgs)<br />";
	echo"PUC=Primary Unit Per Carton<br />";
	echo"PS=Packet Size<br />";
	$query2 = mysql_query("SELECT * FROM product WHERE blendid='{$row['id']}' AND status='1'");
	echo "<table border='1'><tr style='color:#ffffff; background-color:#008010'><td>#</td><td style='width:100px'>PS(g)</td><td>WTP(gms)</td><td>WTC(kgs)</td><td style='width:50px'>PUC</td style='width:150px'><td>Pkts/ct</td><td>Critical Level</td><td colspan='3' width='50px'>Action</td></tr>";
	
	$i = 1;

	while($row2= mysql_fetch_array($query2))
	{
		if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
		echo "<td>$i</td>";
		
		echo "<td>{$row2['name']}</td>";
		echo "<td>{$row2['wtperprimaryunit']}</td>";
		echo "<td>{$row2['wtpercarton']}</td>";
		echo "<td>{$row2['primaryunitpercarton']}</td>";
		echo "<td>{$row2['pkts']}</td>";
		echo "<td>{$row2['criticallevel']}</td>";
		
		echo "<td><a href='?opt=view_blend_formula&pid={$row2['id']}'><img src='images/browse.png'alt='edit' />Formula</a></td>";
		echo "<td style='width:50px'><a href='?opt=edit_product&editid={$row2['id']}&viewid={$_GET['viewid']}' ><img src='images/edit.png'alt='edit' />Edit</a></td>";
		echo "<td style='width:100px'><a href='?opt=product&delid={$row2['id']}&viewid={$_GET['viewid']}' onclick='return del()'><img src='images/delete.png' alt=delete/> Delete</a></td>";
		echo "</tr>";
		$i++;
	}
	echo "</table>";
	}
?>
