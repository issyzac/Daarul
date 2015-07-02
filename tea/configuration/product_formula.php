<?php
	$sqlProduct="SELECT product.name AS pname,blend.name AS bname FROM product,blend WHERE product.id={$_GET['pid']} AND product.blendid=blend.id";
	$result=mysql_query($sqlProduct);
	$row=mysql_fetch_array($result);
	$bname=$row['bname'];
	$pname=$row['pname'];
?>
<table>
	<tr><td colspan="2" align="center" valign="top" ><?php echo "<span style='font-size: 25px'>Blend: {$bname}, Product: {$pname}</span>"; ?></td></tr>
	<tr><td valign="top" width='400px' ><?php include("view_blend_formula.php"); ?></td><td valign="top" width='400px' ><?php include("packing_formula.php"); ?></td></tr>
</table>