<script type="text/javascript" language="javascript">
	function confirmEdit(){
		if(confirm("Save changes made to this Blend?")) return true;
		else return false;
	}
	function validateData(){
		var blendName=document.forms['editblend']['name2'].value.toString();
		if(blendName.length!=0){
			if(confirmEdit()) return true;
			else return false;
		}
		else{
			alert("Blend name cannot be empty");
			return false;
		}
	}
</script>
<?php
	$query = mysql_query("SELECT * FROM blend WHERE id='{$_GET['editid']}'");
	$row= mysql_fetch_array($query);
	echo "<form method='post' action='?opt=blend&editid={$_GET['editid']}' onsubmit='return validateData()' name='editblend' id='editblend'>";
	echo "Blend Name:<input name='name2' id='name2' value='{$row['name']}' /><br />";
	echo "<input type='submit' name='edit' value='Update' />";
	echo "</form>";
?>
