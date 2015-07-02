<?php
	$pid=$_GET['pid'];
	$sql="SELECT percentage,material.name as matname FROM blendformula,material WHERE blendformula.productid={$pid} AND material.id=blendformula.materialid AND type='Blending Material'";
	$result=mysql_query($sql);
	if($result){
		if(mysql_num_rows($result)>0){
			echo "The standard Blend formula for this Product";
			echo "<table>";
			echo "<tr style='color:#ffffff; background-color:#008010' ><th>Blending Material</th><th>Percentage Composition (%)</th></tr>";
			$j=1;
			while($row=mysql_fetch_array($result)){
				if($j % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
				echo "<td>{$row['matname']}</td><td>{$row['percentage']}</td></tr>";
				$j++;
			}
			echo "</table>";
		}
		else include("theoretical_usage.php");//echo "<span>The Standard Formula for this Product is not set<br />Please <a href='?opt=blend_formula&pid={$_GET['pid']}' >Click Here</a> to set it.</span>";
	}
	else echo mysql_error();
?>