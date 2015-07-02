<?php
	if(isset($_GET['storeid'])){
		include("outside_stock.php");
	}
	else{
?>
<span style="font-size:23px;font-weight:bold">Stock in different Branches and Stores:</span><br />
Please click any link to view its stock<br />
<?php
	$sql="SELECT * FROM store_type";
	$result=mysql_query($sql);
	while($row=mysql_fetch_array($result)){
		echo $row['typename']."<br />";
		//$sql="SELECT * FROM store WHERE type='{$row['typeid']}' AND belongto=0 AND status=1";
		$sql="SELECT * FROM store WHERE type='{$row['typeid']}' AND status=1";
		$result2=mysql_query($sql)or die (mysql_error());
		if(mysql_num_rows($result2)==0) echo "<span style='color:red' >No {$row['typename']} Registered</span>";
		else
		while($row2=mysql_fetch_array($result2)){
			if($row2['storeid']==0)
				echo "<a href='?opt=current_stock' >".$row2['storename']."</a><br />";
			else
				echo "<a href='?opt=offstock&storeid={$row2['storeid']}' >".$row2['storename']."</a><br />";
		}
		echo "<br />";
	}
	}
?>