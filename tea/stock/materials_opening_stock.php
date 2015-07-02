<?php
	echo "<h3>Materials Opening Stock</h3>";
	if(isset($_POST['save'])){
		$counter=$_POST['count'];
		for($j=1;$j<$counter;$j++){
			$qty=$_POST['qty'.$j];
			mysql_query("insert into material_stock(materialid,openingstock,closingstock,date) values('".$_POST[$j]."','".$qty."','".$qty."','".date('Y-m-d')."')") or die('Error: '.mysql_error());
			$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Initialized Opening Stock for the Material','".date('Y-m-d')."','".date('H:i:s')."','{$_POST[$j]}','material_stock')";
			mysql_query($auditSql);
		}
	}
	
	$sqlType="SELECT type FROM material GROUP BY type";
	$resultType=mysql_query($sqlType);
	$i=1;
	if(mysql_num_rows($resultType)>0){
		while($typeRow=mysql_fetch_array($resultType)){
			$type=$typeRow['type'];
			echo "<span style='font-size:20px'>{$type}s</span><br />";
			$result=mysql_query("select material.id as id,material.type as type,material.name as name,material_stock.date as date,material_stock.openingstock as openingstock from material,material_stock where date=(select max(date) from material_stock) AND material.id=material_stock.materialid AND status=1 AND material.type='{$type}'") or die(mysql_error());
			if(mysql_num_rows($result)>0){
			echo '<form action="?opt=open_stock" method="post"> ';
			echo '<table border="1">';
			echo '<tr style="color:#ffffff; background-color:#008010" ><th>#</th><th>MATERIAL\'S NAME</th><th>OPENING STOCK</th><th>ACTION</th></tr>';
		
			while($row=mysql_fetch_array($result)){
				if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
				echo "<td>".$i."</td><td>{$row['name']}</td><td id='".$i."'>{$row['openingstock']}</td>";
				echo "<td id='".$i."_save'>";
				
				if($_SESSION['privilege'] == 'Administrator') echo "<img src='images/edit.png'alt='edit' /><a href='javascript: editStock({$row['id']}, {$row['openingstock']}, ".$i.");' >Edit </a></td>";
				
				echo "</tr>";
				$i++;
			}
			echo '</table><br />';
			echo '</form>';
			}
			
		}
	}
	
	$result=mysql_query('select * from material where id not in(select materialid from material_stock) and status=1') or die("Error ".mysql_error());
	if(mysql_num_rows($result)>0){
		echo "<h3>Please Initialize the following Material(s)</h3>";
		echo "<form action='#' method='post'>";
		echo "<table border='1'>";
		echo "<tr style='color:#ffffff; background-color:#008010' ><th>#</th><th>Material Name</th><th>Type</th><th>Opening Stock</th></tr>";
		$i=1;
		while($row=mysql_fetch_array($result)){
			if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
			echo "<td>{$i}</td><td>".$row['name']."</td><td>".$row['type']."</td><td><input type='text' id='qty".$i."' name='qty".$i."' placeholder='QTY' /></td></tr>";
			echo "<input type='hidden' value='{$row['id']}' id='{$i}' name='{$i}'>";
			$i++;
		}
		echo "<input type='hidden' value='{$i}' name='count' id='count'>";
		echo "<tr><td colspan='4' align='right'><input type='reset' value='Clear' id='clear' name='clear' /><input type='submit' value='Submit' id='save' name='save' /></td></tr>";
		echo "</table></form>";
	}
?>