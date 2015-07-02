<script type="text/javascript" language="javascript">
	function validateData(){
		var storeid=document.forms['frminvoice']['storeid'].value;
		var qty=document.forms['frminvoice']['txtinvoiceqty'].value.toString();
		var invoice=document.forms['frminvoice']['txtinvoiceid'].value.toString();
		if(invoice.length==0||qty.length==0||storeid==0){
			alert("You must enter all the required information");
			return false;
		}
		else return true;
	}
	function confirmUpdate(){
		if(confirm("You are about to save Changes made, Continue?")) return true;
		else return false;
	}
</script>
<?php
	if(isset($_GET['action'])){		
		if($_GET['action']=="insert"){
		
			if(isset($_POST['txtinvoiceid'])){
				$date=date('Y-m-d');
				$sqlInvoice="INSERT INTO invoice(invoiceid,storeid,productid,date,quantity) VALUES('{$_POST['txtinvoiceid']}','{$_POST['storeid']}','{$_GET['pid']}','{$date}','{$_POST['txtinvoiceqty']}')";
				$resultInvoice=mysql_query($sqlInvoice);
				if($resultInvoice){
					$resp="Invoice successfully entered";
					header("Location: ?opt=invoice&resp={$resp}");
				}else{
					$resp="This information already exist in database";
					header("Location: ?opt=invoice&resp={$resp}");
				}
			}
		
			echo "<form action='#' name='frminvoice' id='frminvoice' method='post' onsubmit='return validateData()' >";
				echo "<table>";
					echo "<tr style='color:#ffffff; background-color:#008010' ><th>Store Name</th><th>Invoice Number</th><th>Product Name</th><th>Quantity</th></tr>";
					echo "<tr class='odd' ><td><select name='storeid' id='storeid' ><option value='0' >Select Store</option>";
						$sqlStore="SELECT s.storeid as sid,s.storename as sname FROM store s,store_product sp WHERE  s.storeid=sp.storeid AND sp.productid='{$_GET['pid']}'";
						$resultStore=mysql_query($sqlStore);
						while($rowStore=mysql_fetch_array($resultStore)){
							echo "<option value='{$rowStore['sid']}'>{$rowStore['sname']}</option>";
						}
					echo "</select></td><td><input type='text' id='txtinvoiceid' name='txtinvoiceid' /></td><td>{$_GET['pname']}</td><td><input type='text' id='txtinvoiceqty' name='txtinvoiceqty' /></td></tr>";
					echo "<tr class='even' ><td colspan='4' align='right' ><input type='submit' id='btnsubmitinvoice' name=btnsubmitinvoice' value='Submit' /></td></tr>";
				echo "</table>";
			echo "</form>";
		}
		
		if($_GET['action']=="edit"){
		
			$date=date('Y-m-d');
			if(isset($_POST['submit'])){
				$sqlUpdate="UPDATE invoice SET quantity='{$_POST['txtqty']}',invoiceid='{$_POST['txtinvid']}' WHERE productid='{$_GET['pid']}' AND date='{$date}' AND invoiceid='{$_GET['invid']}' AND storeid='{$_GET['sid']}'";
				if(mysql_query($sqlUpdate)){
					$resp="Data Updated Successfully";
					header("Location: ?opt=invoice&resp={$resp}");
				}else echo mysql_error();
			}
			
			$sqlInvoice="SELECT s.storename AS sname,p.name AS pname,i.quantity AS qty FROM invoice i,store s,product p WHERE i.productid=p.id AND i.storeid=s.storeid AND date='{$date}' AND i.storeid='{$_GET['sid']}' AND i.productid='{$_GET['pid']}' AND i.invoiceid='{$_GET['invid']}'";
			$resultInvoice=mysql_query($sqlInvoice);
			$row=mysql_fetch_array($resultInvoice);
			echo "<form id='frmupdate' name='frmupdate' action='#' method='post' onsubmit='return confirmUpdate()' >";
				echo "<table>";
					echo "<tr style='color:#ffffff; background-color:#008010' ><th>Store Name</th><th>Invoice Number</th><th>Product</th><th>Quantity</th></tr>";
					echo "<tr class='odd' ><td>{$row['sname']}</td><td><input type='text' id='txtinvid' name='txtinvid' value='{$_GET['invid']}' /></td><td>{$row['pname']}</td><td><input type='text' name='txtqty' id='txtqty' value='{$row['qty']}' /></td></tr>";
					echo "<tr class='even' ><td colspan='4' align='right' ><input type='submit' id='submit' value='Save Changes' name='submit' /></td></tr>";
				echo "</table>";
			echo "</form>";
		}
	}
	
	if(isset($_GET['resp'])) echo $_GET['resp']."<br />";
	
	##TODAYS INVOICE LIST
	$date=date('Y-m-d');
	$sqlInvoiceList="SELECT i.invoiceid AS invid,s.storename AS sname,p.name AS pname,i.quantity AS qty,i.storeid AS sid,p.id AS pid FROM invoice i,store s,product p WHERE i.productid=p.id AND i.storeid=s.storeid AND date='{$date}'";
	$resultInvoiceList=mysql_query($sqlInvoiceList);
	if(mysql_num_rows($resultInvoiceList)){
		echo "Invoices received from Stores today";
		echo "<table>";
		echo "<tr style='color:#ffffff; background-color:#008010' ><th>#</th><th>Store Name</th><th>Invoice Number</th><th>Product</th><th>Quantity</th><th>Action</th></tr>";
		$i=1;
		while($listRow=mysql_fetch_array($resultInvoiceList)){
			if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
			echo "<td>{$i}</td><td>{$listRow['sname']}</td><td>{$listRow['invid']}</td><td>{$listRow['pname']}</td><td>{$listRow['qty']}</td><td><a href='?opt=invoice&action=edit&pid={$listRow['pid']}&sid={$listRow['sid']}&invid={$listRow['invid']}' ><img src='images/edit.png' alt='edit' />Edit</a></td></tr>";
			$i++;
		}
		echo "</table>";
	} else echo "No invoice entered for today";
	
	$sqlBlend="SELECT * FROM blend WHERE status='1'";
	$result=mysql_query($sqlBlend);
	echo "<table>";
	echo "<tr>";
	while($row=mysql_fetch_array($result)){
	echo "<td width='150px' valign='top' >";
		echo "<span style='font-size:30px;'>{$row['name']}</span><br />";
		$sql="SELECT p.id AS pid,p.name AS pname FROM product p,blend b WHERE p.blendid=b.id AND b.id='{$row['id']}' AND p.id IN(SELECT productid FROM store_product)";
		$result1=mysql_query($sql) or die(mysql_error());
		$i=1;
		if(mysql_num_rows($result1)>0){
			while($row1=mysql_fetch_array($result1)){
				echo "<a href='?opt=invoice&pid={$row1['pid']}&action=insert&pname={$row1['pname']}' >{$row1['pname']}</a><br />";
				$i++;
			}
		}else echo "No product transfered to store in this Blend";
	echo "</td>";
	}
	echo "</tr></table>";
?>