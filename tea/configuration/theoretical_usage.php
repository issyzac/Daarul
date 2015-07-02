<script type="text/javascript" language="javascript">
	function validatePercent(){
		var counter=parseInt(document.forms['frmtheoretical']['counter'].value);
		var total=0.0;
		var pct=0.0;
		for(var j=0;j<counter;j++){
			k="pct"+j;
			var strPct=document.forms['frmtheoretical'][k].value;
			try{
				if(strPct!="")
				pct=parseFloat(strPct);
			}
			catch(e){
				alert("Invalid Input detected in one or more of the input fields, please correct it");
				return false;
			}
			total+=pct;
			pct=0.0;
		}
		if(total>100){
			alert("Total Percentage must be 100 You have entered more");
			return false;
		}
		else if(total<100){
			alert("Total Percentage must be 100 You have entered less");
			return false;
		}
		else{
			if(document.forms['frmtheoretical']['blend'].value==0){
				alert("You must select a Blend and Product before submitting");
				return false;
			}
			else return true;
		}
	}
</script>
<?php
	if(isset($_POST['submit'])){
	if(isset($_GET['pid'])) $productid=$_GET['pid'];
	else $productid=$_POST['products'];
		$counter=$_POST['counter'];
		for($k=0;$k<$counter;$k++){
			$pctIndex="pct".$k;
			$matpercent=$_POST[$pctIndex];
			$matid=$_POST[$k];
			if($matpercent>0){
				$thSql="INSERT INTO blendformula(productid,materialid,percentage) VALUES('{$productid}','{$matid}','{$matpercent}')";
				$result=mysql_query($thSql);
			}
			$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Set a blend formula for a product','".date('Y-m-d')."','".date('H:i:s')."','{$productid}','product')";
			mysql_query($auditSql);
			header("Location: ?opt=view_blend_formula&pid={$productid}");
		}
	}
	
	echo "<form id='frmtheoretical' name='frmtheoretical' action='#' method='post' onsubmit='return validatePercent()'>";
	echo "<table border='1' >";
	echo "<tr><td>";
	
	$sqlBlendMaterial="SELECT id,name FROM material WHERE status=1 AND type='Blending Material'";
	$resultBlendMaterial=mysql_query($sqlBlendMaterial);
	if($resultBlendMaterial){
		echo "<table>";
		echo "<tr style='color:#ffffff; background-color:#008010' ><th>Material Name</th><th>Usage in percentage</th></tr>";
		$j=0;
		while($matetialRow=mysql_fetch_array($resultBlendMaterial)){
			if($j % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
			echo "<td>{$matetialRow['name']}</td><td><input type='text' id='pct".$j."' name='pct".$j."' /></td></tr>";
			echo "<input type='hidden' name='{$j}' id='{$j}' value='{$matetialRow['id']}' />";
			$j++;
		}
		echo "<input type='hidden' value='0' name='product_id'id='product_id' />";
		echo "<input type='hidden' value='{$j}' name='counter' id='counter' />";
		echo "</table>";
	}
	echo "</td></tr>";
	if($j % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
	echo "<td colspan='2' align='right' ><input type='reset' id='clear' name='clear' value='Clear' /><input type='submit' id='submit' name='submit' value='Submit' /></td></tr>";
	echo "</table></form>";
?>