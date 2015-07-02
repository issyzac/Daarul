<?php
	echo "<h3>Materials Opening Stock</h3>";
	if(isset($_POST['save'])){
		$counter=$_POST['count'];
		for($i=1;$i<$counter;$i++){
			$qty=$_POST['qty'.$i];
			mysql_query("insert into material_stock(materialid,openingstock,closingstock,date) values('".$_POST[$i]."','".$qty."','".$qty."','".date('Y-m-d')."')") or die('Error: '.mysql_error());
		}
	}
	
	$result=mysql_query("select material.id as id,material.type as type,material.name as name,material_stock.date as date,material_stock.openingstock as openingstock from material,material_stock where date=(select max(date) from material_stock) AND material.id=material_stock.materialid") or die(mysql_error());
	if(mysql_num_rows($result)>0){
	echo '<table border="1">';
	echo '<tr><th>#</th><th>MATERIAL\'S NAME</th><th>MATERIAL\'S TYPE</th><th>OPENING STOCK</th><th>DATE</th></tr>';
	$i=1;
	while($row=mysql_fetch_array($result)){
		echo "<tr><td>".$i."</td><td>{$row['name']}</td><td>{$row['type']}</td><td>{$row['openingstock']}</td><td>{$row['date']}</td></tr>";
		$i++;
	}
	echo '</table>';
	}
	
	$result=mysql_query('select * from material where id not in(select materialid from material_stock)') or die("Error ".mysql_error());
	if(mysql_num_rows($result)>0){
		echo "<h3>Please Initialize the following Material(s)</h3>";
		echo "<form action='#' method='post'>";
		echo "<table border='1'>";
		echo "<tr><th>#</th><th>Material Name</th><th>Type</th><th>Opening Stock</th><th>Date</th></tr>";
		$i=1;
		while($row=mysql_fetch_array($result)){
			echo "<tr><td>{$i}</td><td>".$row['name']."</td><td>".$row['type']."</td><td><input type='text' id='qty".$i."' name='qty".$i."' placeholder='QTY' /></td><td>".date('Y-m-d')."</td></tr>";
			echo "<input type='hidden' value='{$row['id']}' id='{$i}' name='{$i}'>";
			$i++;
		}
		echo "<input type='hidden' value='{$i}' name='count' id='count'>";
		echo "<tr><td colspan='5' align='right'><input type='reset' value='Clear' id='clear' name='clear' /><input type='submit' value='Submit' id='save' name='save' /></td></tr>";
		echo "</table></form>";
	}
?>