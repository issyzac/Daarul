<html>
<head>
<script type="text/javascript">
function confirmDelete()
{
if(confirm("Are you sure you want to remove this supplier?"))
return true;
else
return false;
}
	function validateData(){
		var Name=document.forms['supplier']['name'].value.toString();
		var contact=document.forms['supplier']['contact'].value.toString();	
		
		
		if(Name.length==0 || contact.length==0  ){
		
	
			alert("Fill the required details before submition ");
			return false;
		}
		else
		return true;
	}



</script>
</head>



<body>
<form action="" method="post"  name="supplier"  onsubmit="return validateData()">
<fieldset>
<legend align="center">Supplier Registration</legend>
<table>
<tr><td>Supplier Name</td><td><input type="text" name="name" id="name" /></td></tr>

<tr><td>Contact</td><td><input type="text" name="contact" id="contact" /></td></tr>
<tr><td><input type="submit" name="submit" value="submit" /></td></tr>


</table>
</fieldset>
</form>
<?php

if(isset($_POST['submit']))
{
$name=$_POST['name'];
//$mname=$_POST['middle'];
//$sname=$_POST['surname'];

$contact=$_POST['contact'];
$sql="insert into supplier(name,contact)values('$name','$contact')";
$result=mysql_query($sql);

}
if(isset($_GET['delete']))
{
$remove=mysql_query("Update  supplier set status='0'  where supplierid='{$_GET['delete']}'");
if(!$remove)
{
die(mysql_error());

//$auditSql="INSERT INTO event (employee_id,event,date,time,item_id,table_name) VALUES('{$_SESSION['userid']}','Deleted item from table','".date('Y-m-d')."','".date('H:i:s')."','{$_GET['delid']}','blend')";
			//mysql_query($auditSql);
				//$reply= '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />Supplier successfully  deleted</p><br /></center>';
		}

}
if(isset($_POST['edit']))
{
$change=mysql_query("update supplier  set  name='{$_POST['name2']}', contact='{$_POST['contact2']}' where supplierid='{$_GET['edit']}'");
}
	$query = mysql_query("SELECT * FROM supplier  where status='1' ");
	echo "<table border='1'><tr style='color:#ffffff; background-color:#008010'><th>#</th><th style='width:100px'> Supplier Name</th>
	<th style='width:100px'> Contact</th><th style='width:200px' >Action</th></tr>"; 
	$i = 1;
	while($row= mysql_fetch_array($query))
	{
		if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
		echo "<td>$i</td>";
		echo "<td style='width:200px'>{$row['name']}</td>";
			//echo "<td style='width:200px'>{$row['mname']}</td>";
				//echo "<td style='width:200px'>{$row['sname']}</td>";
					echo "<td style='width:200px'>{$row['contact']}</td>";
	
		echo "<td><a href='?opt=edit_supplier&edit={$row['supplierid']}' ><img src='images/edit.png'alt='edit' /> Edit</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
	<a href='?opt=supplier&delete={$row['supplierid']}' onclick='return confirmDelete()'> <img src='images/delete.png' alt=delete/> Delete</a></td>";
		echo "</tr>";
		$i++;
	}
	echo "</table>";


?>
</body>
</html>