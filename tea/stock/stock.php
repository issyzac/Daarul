<?php
	echo "<h3>Products Opening Stock</h3>";
	if(isset($_POST['submit'])){
		$counter=$_POST['count'];
		for($i=1;$i<$counter;$i++){
			$qty=$_POST['qty'.$i];
			mysql_query("insert into product_stock(productid,openingstock,closingstock,date) values('".$_POST[$i]."','".$qty."','".$qty."','".date('Y-m-d')."')") or die('Error: '.mysql_error());
		}
	}		
	$result=mysql_query("select product.id as id, product.name as name,openingstock,date from product_stock,product where date=(select max(date) from product_stock) AND product.id=product_stock.productid") or die(mysql_error());
	if(mysql_num_rows($result)>0){
	echo '<table border="1">';
	echo '<tr><th>#</th><th>PRODUCT NAME</th><th>OPENING STOCK</th><th>DATE</th></tr>';
	$i=1;
	while($row=mysql_fetch_array($result)){
		echo "<tr><td>".$i."</td><td>{$row['name']}</td><td>{$row['openingstock']}</td><td>{$row['date']}</td></tr>";
		$i++;
	}
	echo '</table>';
	}	
	$strQuery='select * from product where id not in(select productid from product_stock)';
	$result=mysql_query($strQuery);
	if(!$result){
		die('Error: '.mysql_error());
	}
	if(mysql_num_rows($result)>0){
		echo "<h3>Please Initialize the following Product(s)</h3>";
		echo '<form action="#" method="post"><table border="1">';
		echo '<tr><th>#</th><th>PRODUCT NAME</th><th>OPENING STOCK</th><th>DATE</th></tr>';
		$i=1;
		while($row=mysql_fetch_array($result)){
			echo "<tr><td>".$i."</td><td>{$row['name']}</td><td><input type='text' name='qty".$i."' id='qty".$i."' /></td><td>".date('Y-m-d')."</td><tr>";	
			echo "<input type='hidden' value='{$row['id']}' id='{$i}' name='{$i}'>";	
			$i++;
		}
		echo "<input type='hidden' value='{$i}' name='count' id='count'>";
		echo '<tr><td colspan="4" align="right"><input type="submit" value="Submit" name="submit" id="submit"  /></td></tr>';
		echo '</table></form>';
	}
?>