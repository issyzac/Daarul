<script type="text/javascript" language="javascript" >
	var request;
	function getRequest(){
		if(window.XMLHttpRequest){
			request=new XMLHttpRequest();
		}
		else{
			request=new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	function getProduct(){
		getRequest();
		var url="stock/store/get_product.php?blendid="+document.getElementById('blend').value;
		request.onreadystatechange=processRequest;
		request.open("GET",url,true);
		request.send(null);
	}
	function processRequest(){
		if(request.readyState==4){
			if(request.status==200){
				document.getElementById("product").innerHTML=request.responseText;
			}else alert(request.statusRequest);
		}
		else document.getElementById("product").innerHTML="Fetching Products, please wait...";
	}
	function validateData(){
		var blend=document.forms['frmtransit']['blend'].value;
		var transit=document.forms['frmtransit']['transit1'].value;
		var branch=document.forms['frmtransit']['branch1'].value;
		var dn=document.forms['frmtransit']['dn1'].value.toString().trim();
		if(blend==0||transit==0||branch==0||dn.length==0){
			alert("You must enter all the required information");
			return false;
		}
		else return true;
	}
</script>
<?php
	if(isset($_POST['submit'],$_POST['branch1'])){
		$blend=$_POST['blend'];
		$transit=$_POST['transit1'];
		$branch=$_POST['branch1'];
		$dn=$_POST['dn1'];
		$counter=$_POST['counter'];
		$date=date('Y-m-d');
		$commit=true;
		
		for($j=1;$j<$counter;$j++){
			$idIndex="id".$j;
			$qtyIndex="qty".$j;
			$quantity=$_POST[$qtyIndex];
			if($quantity>0){
				$sqlTransfer="INSERT INTO on_transit(branchid,transitid,productid,dispatch,quantity,date) VALUES('{$branch}','{$transit}','{$_POST[$idIndex]}','{$dn}','{$quantity}','{$date}')";
				$resultTransfer=mysql_query($sqlTransfer);
				if(!$resultTransfer) $commit=false;
				else{
					#########################
					$sql="SELECT * FROM branch_stock_tracking WHERE storeid='{$transit}' AND productid='{$_POST[$idIndex]}'";
					$result=mysql_query($sql);
					if(mysql_num_rows($result)==0){
						$sqlStock="INSERT INTO branch_stock_tracking(storeid,productid,openingstock,closingstock,date) VALUES('{$transit}','{$_POST[$idIndex]}','0.0','{$quantity}','{$date}')";
						$resultStock=mysql_query($sqlStock);
					}
				}
			}
		}
		if($commit) $status="Successfully Transfered";
		else $status="Transfer Failure";
		header("Location: ?opt=stock_transfer&status=".$status);
	}
	###
	elseif(isset($_POST['submit'],$_POST['branch2'])){
		$blend=$_POST['blend'];
		$transit=$_POST['transit2'];
		$branch=$_POST['branch2'];
		$dn=$_POST['dn2'];
		$counter=$_POST['counter'];
		$date=date('Y-m-d');
		$commit=true;
		
		for($j=1;$j<$counter;$j++){
			$idIndex="id".$j;
			$qtyIndex="qty".$j;
			$quantity=$_POST[$qtyIndex];
			if($quantity>0){
				$sqlTransfer="INSERT INTO offstock_product(storeid,productid,dispatch,quantity,date) VALUES('{$branch}','{$_POST[$idIndex]}','{$dn}','{$quantity}','{$date}')";
				$resultTransfer=mysql_query($sqlTransfer);
				if($resultTransfer){
					#########################
					$sql="SELECT * FROM branch_stock_tracking WHERE storeid='{$branch}' AND productid='{$_POST[$idIndex]}'";
					$result=mysql_query($sql);
					if(mysql_num_rows($result)==0){
						$sqlStock="INSERT INTO branch_stock_tracking(storeid,productid,openingstock,closingstock,date) VALUES('{$branch}','{$_POST[$idIndex]}','0.0','{$quantity}','{$date}')";
						$resultStock=mysql_query($sqlStock);
					}
				}
				else $commit=false;
			}
		}
		if($commit) $status="Successfully Transfered";
		else $status="Transfer Failure";
		header("Location: ?opt=stock_transfer&status=".$status);
	}
?>

<center>
<?php
	if(!isset($_GET['inbranch'])){
?>
<a href="?opt=stock_transfer&inbranch" >Transfer to Sales Van or Sales Personnel</a><br /><br />
<form id="frmtransit" name="frmtransit" action="#" onsubmit="return validateData()" method="post" >
Product Blend:<select id="blend" name="blend" onchange="getProduct()" >
	<option value="0">Select Blend</option>
	<?php
		$sqlBlend="SELECT id,name FROM blend WHERE status='1'";
		$resultBlend=mysql_query($sqlBlend);
		while($row=mysql_fetch_array($resultBlend)){
			echo "<option value='{$row['id']}'>{$row['name']}</option>";
		}
	?>
</select>
Transit: <select id="transit1" name="transit1" onchange="" >
	<option value="0">Select Transit</option>
	<?php
		$sqlTransit="SELECT storeid,storename FROM store WHERE status='1' AND type=2";
		$resultTransit=mysql_query($sqlTransit);
		while($row=mysql_fetch_array($resultTransit)){
			echo "<option value='{$row['storeid']}' >{$row['storename']}</option>";
		}
	?>
</select>
To: <select id="branch1" name="branch1" onchange="" >
	<option value="0">Select Branch</option>
	<?php
		$sqlBranch="SELECT storeid,storename FROM store WHERE status='1'  AND type='1' AND storeid <> 0";
		$resultBranch=mysql_query($sqlBranch);
		while($row=mysql_fetch_array($resultBranch)){
			echo "<option value='{$row['storeid']}' >{$row['storename']}</option>";
		}
	?>
</select>
DN: <input type="text" id="dn1" name="dn1" />
<br />
<div id="product" name="product" >
	<?php
		if(isset($_GET['status'])) echo $_GET['status']."<br />";
		if(isset($_GET['action'])) include("stock/store/view_transfer.php");
		else echo '<a href="?opt=stock_transfer&action=view" >View products on transit now!</a><br />';
	?>
</div>
</form>

<?php ##ELSE PART
	}
	else{
?>
	<a href="?opt=stock_transfer" >Transfer to Branch</a><br /><br />
	<form id="frmtransit" name="frmtransit" action="#" onsubmit="return validateData()" method="post" >
Product Blend:<select id="blend" name="blend" onchange="getProduct()" >
	<option value="0">Select Blend</option>
	<?php
		$sqlBlend="SELECT id,name FROM blend WHERE status='1'";
		$resultBlend=mysql_query($sqlBlend);
		while($row=mysql_fetch_array($resultBlend)){
			echo "<option value='{$row['id']}'>{$row['name']}</option>";
		}
	?>
</select>
To: <select id="branch2" name="branch2" onchange="" >
	<option value="0">Van/Sales Person</option>
	<?php
		$sqlBranch="SELECT storeid,storename FROM store WHERE status='1'  AND (type='3' OR type='4')";
		$resultBranch=mysql_query($sqlBranch);
		while($row=mysql_fetch_array($resultBranch)){
			echo "<option value='{$row['storeid']}' >{$row['storename']}</option>";
		}
	?>
</select>
DN: <input type="text" id="dn2" name="dn2" />
<br />
<div id="product" name="product" >
	<?php
		if(isset($_GET['status'])) echo $_GET['status']."<br />";
		if(isset($_GET['action'])) include("stock/store/view_transfer.php");
		else echo '<a href="?opt=stock_transfer&action=view" >View products on transit now!</a><br />';
	?>
</div>
</form>
<?php
	}
?>
</center>