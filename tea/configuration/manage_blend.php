<h1>Blend Management</h1>
<?php
	$conn=mysql_connect('localhost','root','');
	if(!$conn){
		die(mysql_error());
	}
	mysql_select_db('pmngdb',$conn);
	$result = mysql_query("select * from blend");
		if(!$result)
		{
			die ("message");
		}
		echo '<table border="5">';
		echo '<tr><th>Blend Name</th><th>Blend Location</th><th>Edit</th><th>Delete</th></tr>';
		while($result_row=mysql_fetch_array($result)){
			echo "<tr>";
			echo "<td>{$result_row['name']}</td>";
			echo "<td>{$result_row['location']}</td>";
			echo "<td><a href=''><b>Edit</b></a></td>";
			echo "<td><a href=''><b>Delete</b></a></td>";
			echo "</tr>";
		}
		mysql_close($conn);
		echo '</table>';
		/*<form action="" method="post" name="manage_blend" id="manage_blend">
		<input type="text" id="txtSearch" name="txtSearch" size="50"  />
	</form>*/
?>