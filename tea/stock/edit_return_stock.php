<?php
	echo "Edit {$_POST['material']} return stock";
	echo "<form action='?opt=return_stock' method='post'>
		<input type='hidden' name='materialid' value='{$_POST['materialid']}' />
		Quantity:<input type='text' name='quantity' value='{$_POST['quantity']}' />
		<input type='submit' name='edit' value='Edit'/>
	  </form>";
?>