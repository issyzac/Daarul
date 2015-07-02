<script>
function del(val)
{
	if(confirm("Are you sure you want to delete the Material"))
	{
		document.forms[val].submit();
		return true;
	}
	//return false;
}
function isFormValid(val)
{
	if(val.elements['material'].value == 0)
	{
		alert("Please select a material to estimate.");
		return false;
	}
	if(!isFinite(val.elements['quantity'].value) || val.elements['quantity'].value == "")
	{
		alert("Please enter a valid quantity.");
		return false;
	}
	return true;
}
</script>
<h3>Daily Actual Input</h3>
<?php
	if(isset($_POST['add']))
	{
		$sql = "INSERT INTO dailyinput VALUES('{$_POST['product']}','{$_POST['material']}','".date("Y-m-d")."','{$_POST['quantity']}')";
		$result = mysql_query($sql);
		if($result)
		{
			echo "The product has already been added. You can edit it.<br />";
		}
	}else if(isset($_POST['delete']))
	{
		$sql = "DELETE FROM dailyinput WHERE productid='{$_POST['dproduct']}' AND materialid='{$_POST['dmaterial']}' AND date='".date("Y-m-d")."'";
		$result = mysql_query($sql);
		if($result)
		{
			mysql_query("INSERT INTO event VALUES('{$_SESSION['userid']}','Deleted a material','".date("Y-m-d")."','".date("H:i:s")."','dailyinput','')");
			echo "The product has been Deleted Sucessfully.<br />";
		}
	}else if(isset($_POST['edit']))
	{
		$sql = "UPDATE dailyinput SET quantity='{$_POST['quantity']}' WHERE productid='{$_POST['product']}' AND materialid='{$_POST['material']}' AND date='".date("Y-m-d")."'";
		$result = mysql_query($sql) or die(mysql_error());
		if($result)
		{
			mysql_query("INSERT INTO event VALUES('{$_SESSION['userid']}','Changed Quantity of Daily Input','".date("Y-m-d")."','".date("H:i:s")."','dailyinput','')");
			echo "The product has been Edited Sucessfully.<br />";
		}
		
	}elseif(isset($_POST['repack_add']))
	{
		$sql = "UPDATE material_stock SET repack='{$_POST['repack_quantity']}' WHERE materialid='{$_POST['repack_material']}' AND date='".date("Y-m-d")."'";
		echo $sql;
		$result = mysql_query($sql) or die(mysql_error());
		if($result)
		{
			mysql_query("INSERT INTO event VALUES('{$_SESSION['userid']}','Set repacked materials for unsold products','".date("Y-m-d")."','".date("H:i:s")."','dailyinput','')");
		}
	}elseif(isset($_REQUEST['del_id']))
	{
		$sql = "UPDATE material_stock SET repack = 0 WHERE materialid='{$_REQUEST['del_id']}' AND date='".date("Y-m-d")."'";
		$result = mysql_query($sql) or die(mysql_error());
		if($result)
		{
			mysql_query("INSERT INTO event VALUES('{$_SESSION['userid']}','Deleted repacked materials for unsold products','".date("Y-m-d")."','".date("H:i:s")."','dailyinput','')");
		}
	}
	$result = mysql_query("SELECT * FROM blend WHERE id='{$_GET['blendid']}' AND status='1'") or die(mysql_error());
	if(mysql_num_rows($result) > 0)
	{
		while($row=mysql_fetch_array($result))
		{
			echo "{$row['name']}<br />";
			echo "<table>";

			echo "<tr>";
			$result3 = mysql_query("SELECT * FROM material GROUP BY type");
			while($row3=mysql_fetch_array($result3))
			{
				echo "<td valign='top'>
				<table border='1'><tr class='header'><td>{$row3['type']}</td><td>Quantity</td><td colspan='2'>Action</td></tr>";
				$sql = "SELECT * FROM dailyinput,material WHERE material.id=materialid AND productid='{$_GET['productid']}' AND date='".date("Y-m-d")."' AND type='{$row3['type']}'";
				$result4 = mysql_query($sql);
				$matid = array();
				$i = 1;
				while($row4=mysql_fetch_array($result4))
				{
					array_push($matid,$row4['materialid']);
					if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
					$i++;
					echo "<td>{$row4['name']}</td>";
					echo "<td>{$row4['quantity']}</td>";
					echo "<td><form name='efrm{$row4['materialid']}' action='?opt=edit_input&blendid={$_GET['blendid']}&productid={$_GET['productid']}' method='post'>
					<input type='hidden' name='blend' value='{$row['id']}' />
					<input type='hidden' name='product' value='{$_GET['productid']}' />
					<input type='hidden' name='material' value='{$row4['id']}' />
					<input type='hidden' name='quantity' value='{$row4['quantity']}' />
					<a href=\"javascript:document.forms['efrm{$row4['materialid']}'].submit()\" >
					<img src='images/edit.png'alt='edit' /> Edit</a>
					</form></td>";
					echo "<td>
					<form name='dfrm{$row4['materialid']}' onsubmit='return del(this)' method='post'>
					<input type='hidden' name='dblend' value='{$row['id']}' />
					<input type='hidden' name='dproduct' value='{$_GET['productid']}' />
					<input type='hidden' name='dmaterial' value='{$row4['id']}' />
					<input type='hidden' name='delete' value='delete' />
					<a href='javascript:del(\"dfrm{$row4['materialid']}\")' ><img src='images/delete.png'alt='edit' /> Delete</a>
					</form></td>";
					echo "</tr>";
				}
				echo "<tr><td colspan='4'>";
				{
					echo "<form method='post' onsubmit=\"return isFormValid(this)\">
					Add {$row3['type']}<br />
					<input type='hidden' name='product' value='{$_GET['productid']}' />
					<input type='hidden' name='blend' value='{$row['id']}' />
					<select name='material'>
					<option value='0'>Select Material</option>";
					$result4 = mysql_query("SELECT * FROM material WHERE type='{$row3['type']}'");
					while($row4=mysql_fetch_array($result4))
					{
						if(!in_array($row4['id'],$matid,true))
						{
							echo "<option value='{$row4['id']}'>{$row4['name']}</option>";
						}
					}
					echo "</select><input name='quantity' /><input type='submit' name='add' value='Submit'/>";
					echo "</form>";
				}
				echo "</td></tr></table></td>";
			}
			echo "</tr>";
			echo "</table>";
		}
	}else
	{
		echo "There is no blend registered. Please go to the configuration and configure blends";
	}
?>

<script type="text/javascript">

function editMaterial(matId, matName, matQuantity){
	document.getElementById('editable').innerHTML = 'Edit '+matName;
	document.getElementById('sel_material').innerHTML = '<option value="'+matId+'">'+matName+'</option>';
	document.getElementById('rep_quantity').value = matQuantity;
}

function sure(thing){
	var r = confirm(thing+" will be deleted, Are you sure?");
	
	if(r == true) {
		return true;
	} else {
		return false;
	}
}
</script>

<h3>Materials Used for Repacking of Unsold Products</h3>

<?php

echo"
<table > 
<tr class='header'>
<td style='width:142px'>Repacked Material</td> <td style='width:70px'>Quantity</td> <td style='width:130px'>Action</td> 
</tr>
";

$myblend = $_GET['blendid'];
$myproduct = $_GET['productid'];

$selected = array();
$i = 1;
$repack = mysql_query("SELECT * FROM material, material_stock WHERE id = materialid AND date='".date('Y-m-d')."' AND repack > 0") or die(mysql_error());

while($repacked=mysql_fetch_array($repack)){
array_push($selected,$repacked['materialid']);
$active_id = $repacked['materialid'];

if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
$i++;

echo "<td>{$repacked['name']}</td>";
echo "<td>{$repacked['repack']}</td>";
echo "<td><a href='#' onClick=\"editMaterial('{$active_id}', '{$repacked['name']}', {$repacked['repack']}); \"><img src='images/edit.png'alt='edit' /> Edit</a> &nbsp; &nbsp; &nbsp; ";
echo "<a onClick='return sure(\"{$repacked['name']}\");' href='?opt=blend_input&blendid=".$myblend."&productid=".$myproduct."&del_id=".$active_id."' \"><img src='images/delete.png'alt='edit' /> Delete</a></td>";
echo "</tr>";
}
echo "</table>";

echo "
<br /><span id='editable'>Add</span> Repacked Materials:<br />
<form name='repack_form' method='post' action= '?opt=blend_input&blendid=".$myblend."&productid=".$myproduct."'  onsubmit=\"return isFormValid(this)\">

<input type='hidden' name='repack_id' value='{$_GET['productid']}' />

<select id='sel_material' name='repack_material'>
<option value='0'>Select Material</option>";
$get_material = mysql_query("SELECT * FROM material WHERE type='Packing Material'");

while($got_material = mysql_fetch_array($get_material)){
	if(!in_array($got_material['id'],$selected,true)){
		echo "<option value='{$got_material['id']}'>{$got_material['name']}</option>";
	}
}
echo "</select>";

echo "<input id = 'rep_quantity' name='repack_quantity' /><input type='submit' name='repack_add' value='Submit'/>";
echo "</form>";
?>