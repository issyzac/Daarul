<script type="text/javascript" language="javascript" >
	function confirmDelete(){
		if(confirm("This Entry will be deleted, are you sure you want to continue?")) return true;
		else return false;
	}
</script>
<?php
	if(isset($_GET['act'])){
		if($_GET['act']=="delete"){
			$sqlDelete="DELETE FROM on_transit WHERE branchid='{$_GET['bid']}' AND transitid='{$_GET['tid']}' AND productid='{$_GET['pid']}' AND dispatch='{$_GET['dispatch']}' AND date='{$_GET['date']}'";
			$resultDelete=mysql_query($sqlDelete);
			if($resultDelete) $status="1 Entry deleted";
			else $status="Failed to delete Entry";
			header("Location: ?opt=stock_transfer&action=view&status=".$status);
		}
	}
	$sqlView="SELECT p.name AS pname,s.storename AS sname,o.quantity AS qty,o.dispatch AS dn,o.branchid AS bid,o.productid AS pid,o.transitid AS tid,o.date AS tarehe FROM store s,product p,on_transit o WHERE o.productid=p.id AND o.transitid=s.storeid AND o.delivery='0'";
	$resultView=mysql_query($sqlView);
	if($resultView){
		if(mysql_num_rows($resultView)>0){
			echo "Products on transit";
			echo "<table>";
			echo "<tr style='color:#ffffff; background-color:#008010' ><th>#</th><th>Transit</th><th>To</th><th>Product Name</th><th>DN</th><th>Quantity</th><th>Transfered on:</th><th>Action</th></tr>";
			$i=1;
			while($row=mysql_fetch_array($resultView)){
				if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
				$sqlBranch="SELECT storename FROM store WHERE storeid='{$row['bid']}'";
				$resBranch=mysql_query($sqlBranch);
				$rowBranch=mysql_fetch_array($resBranch);
				echo "<td>{$i}</td><td>{$row['sname']}</td><td>{$rowBranch['storename']}</td><td>{$row['pname']}</td><td>{$row['dn']}</td><td>{$row['qty']}</td><td>{$row['tarehe']}</td><td><a href='?opt=stock_transfer&action=view&act=delete&pid={$row['pid']}&bid={$row['bid']}&tid={$row['tid']}&dispatch={$row['dn']}&date={$row['tarehe']}' onclick='return confirmDelete()' ><img src='images/delete.png' alt='delete' />Delete</a></td></tr>";///<a href='' ><img src='images/edit.png' alt='edit' />Edit</a>&nbsp;
				$i++;
			}
			echo "</table>";
		}else echo "No product on transit";
	}else echo "Failed to retrieve information";
?>