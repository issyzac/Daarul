<script type="text/javascript" language="javascript">
	function confirmDelete(){
		if(confirm("Are you sure you want to Delete these Information?")) return true;
		else return false;
	}
</script>
<?php
	if(isset($_GET['action'])){
		$action=$_GET['action'];
		if($action=="edit"){
		
		}
		else if($action="delete"){
			$sql="DELETE from blendformula WHERE productid='{$_GET['pid']}' AND materialid IN(SELECT id FROM material WHERE type='Packing Material')";
			$resultDelete=mysql_query($sql);
			if($resultDelete){
				echo "Packing formula deleted<br />";
				$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Deleted a Packing Material Formula for the Product','".date('Y-m-d')."','".date('H:i:s')."','{$_GET['pid']}','product')";
				mysql_query($auditSql);
			}
		}
	}
	
	if(isset($_POST['save'])){
		$counter=$_POST['counter']-1;
		for($sk=1;$sk<=$counter;$sk++){
			$matindex="matid".$sk;
			$pctindex="qty".$sk;
			$pct=$_POST[$pctindex];
			if($pct!=0){
				$query="INSERT INTO blendformula(productid,materialid,percentage) VALUES('{$_GET['pid']}','{$_POST[$matindex]}','{$pct}')";
				$result=mysql_query($query);
				if(!$result) echo mysql_error();
			}
		}
		$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Set a Packing Material Formula for the Product','".date('Y-m-d')."','".date('H:i:s')."','{$_GET['pid']}','product')";
		mysql_query($auditSql);
	}
	
	$sqlPackingFormula="SELECT percentage,name FROM blendformula,material WHERE blendformula.materialid=material.id AND productid='{$_GET['pid']}' AND type='Packing Material'";
	$resultPF=mysql_query($sqlPackingFormula);
	if(mysql_num_rows($resultPF)>0){
		echo "The Packing Material usage for this Product";
		echo "<table>";
		echo "<tr style='color:#ffffff; background-color:#008010' ><th>Packing Material</th><th>Acceptable Wastage</th></tr>";
		$sjk=1;
		while($row=mysql_fetch_array($resultPF)){
			if($sjk % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
			echo "<td>{$row['name']}</td><td>{$row['percentage']}</td></tr>";
			$sjk++;
		}
		echo "<tr><td><a href='?opt=view_blend_formula&pid={$_GET['pid']}&action=edit' ><img src='images/edit.png' alt='edit' />Edit</a></td><td><a href='?opt=view_blend_formula&pid={$_GET['pid']}&action=delete' onclick='return confirmDelete()' ><img src='images/delete.png' alt='delete' />Delete</a></td></tr>";
		echo "</table>";
	}
	else{
		$sqlPacking="SELECT id,name FROM material WHERE status=1 AND type='Packing Material'";
		$resultMaterial=mysql_query($sqlPacking);
		if($resultMaterial){
			if(mysql_num_rows($resultMaterial)>0){
				echo "Please Enter the following Information";
				echo "<form id='frmpackingformula' name='frmpackingformula' method='post' action='#' >";
				echo "<table>";
				echo "<tr style='color:#ffffff; background-color:#008010' ><th>Packing Material</th><th>Acceptable Wastage</th></tr>";
				$k=1;
				while($row=mysql_fetch_array($resultMaterial)){
					if($k % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
					echo "<td>{$row['name']}</td><td><input type='text' id='qty{$k}' name='qty{$k}' /></td></tr>";
					echo "<input type='hidden' name='matid".$k."' id='matid".$k."' value='".$row['id']."' />";
					$k++;
				}
				echo "<input type='hidden' name='counter' id='counter' value='{$k}' />";
				if($k % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
				echo "<td colspan='2' align='right' ><input type='reset' id='clear' name='clear' value='Clear' /><input type='submit' id='save' name='save' value='Save' /></td></tr>";
				echo "</table></form>";
			}else echo "No packing material registered";
		}else echo "Failed to retrieve packing materials";
	}
?>