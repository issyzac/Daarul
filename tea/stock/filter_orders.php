<?php
	require("../db_connection.php");
	$startdate=$_GET['startdate'];
	$enddate=$_GET['enddate'];
	echo "<h3>Orders placed between ".date('d-M-Y',strtotime($startdate))." and ".date('d-M-Y',strtotime($enddate))."</h3>";
	$sql="SELECT material.name AS matname,ordered_material.orderid AS orid,ordered_material.lpo AS plo,ordered_material.date AS date,ordered_material.quantity AS qty,ordered_material.outstanding AS deni,supplier.name AS sname FROM ordered_material,material,supplier WHERE ordered_material.supplierid=supplier.supplierid AND ordered_material.materialid=material.id AND date BETWEEN '{$startdate}' AND '{$enddate}'";
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
		echo "No order placed";
	}
?>