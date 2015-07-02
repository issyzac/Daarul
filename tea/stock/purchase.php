<center><script language="javascript" type="text/javascript">
	function confirmEdit(){
		if(confirm("Save changes?")) return true;
		else return false;
	}
	function confirmDelete(){
		if(confirm("Delete this Entry?")) return true;
		else return false;
	}
	var httpRequest;
	var outstanding;
	function getRequest(){
		if(XMLHttpRequest) httpRequest=new XMLHttpRequest();
		else httpRequest=new ActiveXObject("Microsoft.XMLHTTP");
	}
	function getOutstanding(){
		getRequest();
		var url="stock/get_outstanding_order.php?orderid="+document.forms['purchase']['lpo'].value;
		httpRequest.onreadystatechange=function(){
			if(httpRequest.status==200&&httpRequest.readyState==4)
				outstanding=parseFloat(httpRequest.responseText);
		}
		httpRequest.open("GET",url,false);
		httpRequest.send();
	}
	function validateData(){
		var refno=document.forms['purchase']['refno'].value.toString().trim();
		var qty=document.forms['purchase']['pqty'].value.toString().trim();
		var lpo=document.forms['purchase']['lpo'].value;
		var purchaseQuantity=parseFloat(qty);
		if(refno.length==0||qty.length==0 || lpo==0){
			alert("You must enter complete information");
			return false;
		}
		else{
			getOutstanding();
			if(purchaseQuantity>outstanding){
				alert("Quantity larger than outstanding order of "+outstanding+" for this LPO");
				return false;
			}
			else return true;
		}
	}
	function validatePurchase(){
		var file=document.forms['frmpurchase']['purchasefile'].value.toString().trim();
		if(file.length==0){
			alert("You must select a excel file to upload");
			return false;
		}
		else return true;
	}
</script>
<?php
	if(isset($_GET['todo'])){
		echo '<center><br /><a href="?opt=purchase_stock" >Back to Purchases</a><br /><br />';
		$sql2="SELECT name,type,purchaseqty,materialid,refno,lpo,ordered_material.orderid AS agizo FROM material_purchase,material,ordered_material WHERE material.id=ordered_material.materialid AND ordered_material.orderid=material_purchase.orderid AND material_purchase.date='".date('Y-m-d')."'";
		$result=mysql_query($sql2) or die(mysql_error());
		if($result&&mysql_num_rows($result)>0){
			echo "<table>";
				echo "<tr style='color:#ffffff; background-color:#008010' ><th>#</th><th>Material Name</th><th>Material Type</th><th>LPO</th><th>GRN</th><th>Purchased Quantity</th><th>Action</th></tr>";
				$i=1;
				while($row=mysql_fetch_array($result)){
					if($i% 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
					echo "<td>{$i}</td><td>{$row['name']}</td><td>{$row['type']}</td><td>{$row['lpo']}</td><td>{$row['refno']}</td><td>{$row['purchaseqty']}</td><td>";/*<img src='images/edit.png'alt='edit' /><a href='?opt=purchase_stock&action=edit&refno={$row['refno']}&orderid={$row['agizo']}'>Edit</a>&nbsp;*/
					echo "<img src='images/delete.png' alt=delete/><a href='?opt=purchase_stock&action=delete&refno={$row['refno']}&orderid={$row['agizo']}&delqty={$row['purchaseqty']}' onclick='return confirmDelete()'>Delete</a></td></tr>";
					$i++;
				}
			echo "</table></center>";
		}else echo '<span style="font-size:40px" >No Material Purchased today</span>';
	}else{
?>
<fieldset><legend align="center" >Upload Material's Purchase Data using Excel File</legend>
<a href="stock/uploading_data/material_list.php?operation=purchase" ><p align="center" >Download Material's List</p></a>
<form action="#" method="post" enctype="multipart/form-data" id="frmpurchase" name="frmpurchase" onsubmit="return validatePurchase()" >
	Choose Excel File to Upload:<input type="file" name="purchasefile" id="purchasefile" /><input type="submit" id="upload" name="upload" value="Upload" />
</form>
<?php
	#UPLOADING EXCEL FILE
	if(isset($_POST['upload'])){
		$exp=explode(".",$_FILES['purchasefile']['name']);
		if(strtolower(end($exp))!="xls"&&strtolower(end($exp)!="xlsx")){
			echo "<span style='font-size:40px' >Invalid File Uploaded</span><br />";
		}
		else{
			$file="stock/uploading_data/uploads/".$_SESSION['userid'].$_FILES['purchasefile']['name'];
			move_uploaded_file($_FILES['purchasefile']['tmp_name'],$file);
			include("uploading_data/insert_purchase_data.php");
			insert($file);
			unlink($file);
		}
	}
?>
</fieldset>
<?php
	if(isset($_POST['submit7'])){
		$sql1="INSERT INTO material_purchase(orderid,refno,purchaseqty,date) VALUES('{$_POST['lpo']}','{$_POST['refno']}','{$_POST['pqty']}','".date('Y-m-d')."')";
		$result=mysql_query($sql1);
		if($result){
			$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Entered a new material purchase entry in the database','".date('Y-m-d')."','".date('H:i:s')."','{$_GET['materialid']}','material_purchase')";
			$sqlUpdate="UPDATE ordered_material SET outstanding=outstanding-{$_POST['pqty']} WHERE orderid='{$_POST['lpo']}'";
			mysql_query($sqlUpdate);
			mysql_query($auditSql);
			echo "Data successfully entered";
		}
		else{
			$sql1="UPDATE material_purchase SET purchaseqty=purchaseqty+{$_POST['pqty']} WHERE orderid='{$_POST['lpo']}' AND date='".date('Y-m-d')."'";
			$result=mysql_query($sql1);
			if($result){
				$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Entered a new material purchase entry in the database','".date('Y-m-d')."','".date('H:i:s')."','{$_GET['materialid']}','material_purchase')";
				$sqlUpdate="UPDATE ordered_material SET outstanding=outstanding-{$_POST['pqty']} WHERE orderid='{$_POST['lpo']}'";
				mysql_query($sqlUpdate);
				mysql_query($auditSql);
				echo "Data successfully entered";
			}
		}
	}
	#EDITING PURCHASE DATA
	if(isset($_POST['submitEdit'])){
		$sqlEdit="UPDATE material_purchase SET purchaseqty='{$_POST['pqty']}' WHERE materialid='{$_GET['materialid']}' AND date='".date('Y-m-d')."'";
		$result=mysql_query($sqlEdit);
		if($result){
			echo "Successfully Updated";
			$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Changed this day's purchased quantity for the Material','".date('Y-m-d')."','".date('H:i:s')."','{$_GET['materialid']}','material_purchase')";
			mysql_query($auditSql);
			unset($_GET['action']);
		}
		else echo "Not Updated";
	}
	if(isset($_GET['action'])){
		$action=$_GET['action'];
		if($action=="insert"){
			$name=urldecode($_GET['matname']);
			$type=urldecode($_GET['type']);
			echo "<form action='#' method='post' id='purchase' name='purchase' onsubmit='return validateData()' >";
			echo "<table>";
				echo "<tr style='color:#ffffff; background-color:#008010' ><th>Material Name</th><th>Type</th><th>LPO</th><th>GRN</th><th>Purchased Quantity</th></tr>";
				echo "<tr class='odd' ><td>{$name}</td><td>{$type}</td><td>";
				$sql="SELECT * FROM ordered_material WHERE materialid={$_GET['materialid']} AND outstanding>0";
				$result=mysql_query($sql);
				if(mysql_num_rows($result)<=0) $unordered=true;
				else $unordered=false;
				?>
				<select name="lpo" id="lpo">
					<option value="0">Select</option>
					<?php
						while($row=mysql_fetch_array($result)){
							echo "<option value='{$row['orderid']}'>{$row['lpo']}</option>";
						}
					?>
				</select>
				<?php
				echo "</td><td><input type='text' id='refno' name='refno' /></td><td><input type='text' id='pqty' name='pqty' /></td></tr>";
				echo "<tr class='even' ><td align='right' colspan='5' ><input type='submit' value='Submit' id='submit7' name='submit7' /></td></tr>";
			echo "</form></table>";
			if($unordered) echo '<span style="color:#ff0000;font-size:20px;">No outstanding order for this Material</span>';
		}
		else if($action=="edit"){
			echo "Editing Purchase Quantity for the product:";
			$sql="SELECT purchaseqty,name,type FROM material,material_purchase,ordered_material WHERE material_purchase.orderid=ordered_material.orderid AND ordered_material.materialid=material.id AND ordered_material.orderid='{$_GET['orderid']}' AND material_purchase.refno='{$_GET['refno']}'";
			$result=mysql_query($sql);
			if($result){
				$row=mysql_fetch_array($result);
				echo "<form action='#' method='post' id='editpurchase' name='editpurchase' onsubmit='return confirmEdit()' >";
				echo "<table>";
				echo "<tr style='color:#ffffff; background-color:#008010' ><th>Material Name</th><th>Type</th><th>Purchased Quantity</th></tr>";
				echo "<tr class='odd' ><td>{$row['name']}</td><td>{$row['type']}</td><td><input type='text' id='pqty' name='pqty' value='{$row['purchaseqty']}'/></td></tr>";
				echo "<tr class='even' ><td align='right' colspan='3' ><input type='submit' value='Submit' id='submitEdit' name='submitEdit' /></td></tr>";
			echo "</form></table>";
			}
		}
		else if($action=="delete"){
			$sql="DELETE FROM material_purchase WHERE orderid={$_GET['orderid']} AND refno='{$_GET['refno']}'";
			$result=mysql_query($sql);
			if($result){
				$sql="UPDATE ordered_material SET outstanding=outstanding+{$_GET['delqty']} WHERE orderid='{$_GET['orderid']}'";
				mysql_query($sql);
				/*$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Deleted material purchase entry in the database','".date('Y-m-d')."','".date('H:i:s')."','{$_GET['materialid']}','material_purchase')";
				mysql_query($auditSql);*/
			}else echo mysql_error();
		}
	}
	echo '<center><br /><a href="?opt=purchase_stock&todo=view" >View Materials purchased today</a><br /></center>';
	echo "<table>";
	echo "<tr><td colspan='2' align='center' ><span style='font-size:40px;'>Materials</span></td></tr>";
	echo "<tr>";
	$sqlType="SELECT type FROM material GROUP BY type";
	$resultType=mysql_query($sqlType);
	if(mysql_num_rows($resultType)>0){
		while($typeRow=mysql_fetch_array($resultType)){
			$type=$typeRow['type'];
			echo "<td valign='top' width='300px' >";
			echo "<span style='font-size:30px'>{$type}s</span><br />";
			$sql="SELECT * FROM material WHERE status='1' AND type='{$type}'";
			$result=mysql_query($sql);
			if(mysql_num_rows($result)>0){
				while($row=mysql_fetch_array($result)){
					echo "<a href='?opt=purchase_stock&action=insert&materialid={$row['id']}&matname=".urlencode($row['name'])."&type=".urlencode($row['type'])."'>{$row['name']}</a><br />";
				}
			}
			echo "</td>";
		}
	}
	echo "</tr></table>";
	}
?></center>