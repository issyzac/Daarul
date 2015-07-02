<script type="text/javascript" language="javascript">
	function frmSubmit(){
		if(document.getElementById('name').value==""||document.getElementById('critical').value==""){
			alert("Please fill all the required information");
			return false;
		}
		else return confirm("Save Changes?");
	}
</script>
<?php
$sql = "SELECT * FROM material WHERE id={$_POST['materialid']}";
$result = mysql_query($sql);
$row=mysql_fetch_array($result);
//echo "<h3>Edit {$row['mt']} {$row['mn']} of Product {$row['pn']} in {$row['bn']}</h3>";
echo "<form action='?opt=material&action={$_GET['action']}' method='post' onsubmit='return frmSubmit()' name='editfrm' id='editfrm'>
		<input type='hidden' name='materialid' value='{$row['id']}' />
		Name:<input type='text' name='name' id='name' value='{$row['name']}' />
		Critical Level:<input type='text' name='critical' id='critical' value='{$row['criticallevel']}' />
		<input type='submit' name='edit' value='Save'/>
	  </form>";
?>