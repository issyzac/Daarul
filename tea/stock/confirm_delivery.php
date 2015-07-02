<?php
	@session_start();
	require_once "db_connection.php";	
	
	if(isset($_POST['confirm'])){
		
		$keys = array_keys($_POST['product']);
		
		
		$branchId = $_POST['branchId'];
		$transitId = $_POST['transitId'];
		$date = $_POST['date'];
		$sent_date = $_POST['sdate'];
		if(isset($_POST['edit'])){
		
			for($x = 0; $x < count($keys); $x++){
				
				$quantity = $_POST['product'][$keys[$x]];
				$productId = $keys[$x];
				mysql_query( "UPDATE branch_stock SET quantity = '{$quantity}',date = '{$date}' "
							."WHERE branchid = '{$branchId}' AND transitid = '{$transitId}' "
							."AND productid = '{$productId}'") or die(mysql_error());
			}
			$_SESSION['success_flag'] = true;
				
		}else{
			for($x = 0; $x < count($keys); $x++){
				
				$quantity = $_POST['product'][$keys[$x]];
				$productId = $keys[$x];
				
				mysql_query( "INSERT INTO branch_stock (branchid,transitid,productid,quantity,date) "
							."VALUES ('{$branchId}','{$transitId}','{$productId}','{$quantity}','{$date}')	 ") or die(mysql_error());
				mysql_query("UPDATE on_transit SET delivery = '1' WHERE transitid = '{$transitId}' AND productid = '{$productId}' AND date = '{$sent_date}' ") 
						or die(mysql_error());
						
						
						#########################
						$sql="SELECT * FROM branch_stock_tracking WHERE storeid='{$branchId}' AND productid='{$productId}'";
						$result=mysql_query($sql);
						if(mysql_num_rows($result)==0){
							$sqlStock="INSERT INTO branch_stock_tracking(storeid,productid,openingstock,closingstock,date) VALUES('{$branchId}','{$productId}','0.0','{$quantity}','{$sent_date}')";
							$resultStock=mysql_query($sqlStock);
						}
						
			}
			$_SESSION['success_flag'] = true;
		}	
		header("Location:?opt=confirm_delivery");
		exit;
	}

	
	if(isset($_SESSION['success_flag'])){
		echo '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br/>Succeffuly saved.</p><br /></center>';
		unset($_SESSION['success_flag']);
	}
	
	
	if(isset($_GET['id']) && !empty($_GET['id'])){
		$id  = $_GET['id'];
		$bId = $_GET['bId'];
		$query_brname = mysql_query("SELECT storename FROM store WHERE storeid = '{$bId}'") or die(mysql_error());
		$brname_row = mysql_fetch_assoc($query_brname);
		
		$branchName = $brname_row['storename'];
		
		if(isset($_GET['edit'])){
			$query_date = mysql_query("SELECT date FROM branch_stock WHERE branchid = '{$bId}' AND transitid = '{$id}'") or die(mysql_error());
			$date_row = mysql_fetch_assoc($query_date);
			
			$sql_transit = "SELECT *  FROM branch_stock,product "
						  ."WHERE branch_stock.productid = product.id ";
		}else{
			$sql_transit = "SELECT *  FROM on_transit,product "
						  ."WHERE on_transit.productid = product.id "
						  ."AND transitid = '{$id}' AND branchid = '{$bId}' AND on_transit.delivery = '0'";		
		}
		$query = mysql_query($sql_transit) or die(mysql_error());
		
		if(mysql_num_rows($query)){
			echo "<form action = '?opt=confirm_delivery' method = 'POST'>"
				."<table border = '0'>"
				."<tr><td align = 'right'><i>Recepient branch:</i></td><td>{$branchName}</td></tr>";
				
				if(isset($_GET['edit'])){
					echo "<tr><td align = 'right'><i>Date:</i></td><td><input type = 'text' id = 'datepick' name = 'date' value = '{$date_row['date']}' style = 'cursor:pointer; text-align:right;'/></td></tr>"
						."<input type = 'hidden' name = 'edit' value = 'edit'/>";
				}else{
					echo "<tr><td align = 'right'><i>Date:</i></td><td><input  name = 'date' id = 'datepick' readonly = 'readonly' style = 'cursor:pointer; text-align:right;'/></td></tr>";
				}
				
			while($row = mysql_fetch_assoc($query)){
				$date = $row['date'];
				echo "<tr><td align = 'right'><i>{$row['name']}:</i></td><td><input type = 'text' name = 'product[{$row['id']}]' style = 'text-align:right' value = '{$row['quantity']}'/></td></tr>";
			}
			echo "<tr><td colspan = '2' align = 'right'><input type = 'submit' name = 'confirm' value = 'Confirm'/></td></tr>"
				."</table>"
				."<input type = 'hidden' name = 'branchId'  value = '{$bId}'/>"
				."<input type = 'hidden' name = 'sdate'  value = '{$date}'/>"				
				."<input type = 'hidden' name = 'transitId' value = '{$id}'/>"
				."</form>";
		}else{
			echo "<strong style = 'color:red'>Nothing to display</strong>";
		}
	} else if(isset($_GET['edit'])){
		echo "<br/><!--<a href = '?opt=confirm_delivery&edit=all'>--><b>Show all delivery from</b> "
		    ."<form method='POST'>"
		    ."</br>"
		    ."<input id='datepick'    name = 'date1' size=size='6' value = '";
			if(isset($_SESSION['filter']['date1'])) echo $_SESSION['filter']['date1'];
		echo "' readonly='' style='cursor:pointer' /> to  "
		    ."<input id='datepick2'   name = 'date2' size=size='6' value = '";
		    	if(isset($_SESSION['filter']['date2'])) echo $_SESSION['filter']['date2'];
		echo "' readonly='' style='cursor:pointer' />&nbsp;&nbsp;"
	   	    ."<input type='submit' name='submit' value='Show'>"
		    ."</form>";

		$sql_edit = "SELECT * FROM branch_stock,store WHERE store.storeid = branch_stock.branchid ";
		
		if(isset($_POST['submit'])){
			$_SESSION['filter']['filter'] =" AND branch_stock.date >= '{$_POST['date1']}' AND branch_stock.date <= '{$_POST['date2']}'";
			$_SESSION['filter']['date1'] = $_POST['date1'];
			$_SESSION['filter']['date2'] = $_POST['date2'];
			header("Location:?opt=confirm_delivery&edit");
			exit;
		}
			

		if(isset($_SESSION['filter'])){
			$sql_edit .= $_SESSION['filter']['filter'];		
		}

		$sql_edit .=" GROUP BY transitid,branch_stock.branchid ORDER BY date DESC";

		$query = mysql_query($sql_edit) or die(mysql_error());
		
		if (mysql_num_rows($query) > 1){
		echo "<br/><table border = '1' width='100%'>"
			."<tr style='color:#ffffff; background-color:#008010'><td><strong>Transit Id</strong></td><td><strong>Branch name</strong></td><td align = 'center'><strong>Action</strong></td></tr>";
		$i = 0;
		while($row = mysql_fetch_assoc($query)){
			$query_trname = mysql_query("SELECT storename AS transitname FROM store WHERE storeid = '{$row['transitid']}'") or die(mysql_error());
			$trans_row = mysql_fetch_assoc($query_trname);

			if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
		
			echo "<td>{$trans_row['transitname']}</td><td>{$row['storename']}</td><td align = 'center'><a href = '?opt=confirm_delivery&id={$row['transitid']}&bId={$row['branchid']}&edit'><img src='images/edit.png'alt='edit' />&nbsp;&nbsp;&nbsp;Edit</a></td></tr>";
			$i++;
		}
		echo "</table>";
		}else{
			echo "<br/><b>No data found</b></br></br>";		
		}
		
	} else {
		
		$sql_transit = "SELECT * FROM store,on_transit,product WHERE store.storeid = on_transit.branchid AND on_transit.productid = product.id AND on_transit.delivery = '0' GROUP BY transitid, branchid ";	
		$query_transit = mysql_query($sql_transit) or die(mysql_error());
		
		echo "<br/><a href = '?opt=confirm_delivery&edit'>Click here to edit previous delivery infomartion</a><br/><br/>";	
		if(mysql_num_rows($query_transit)){
			echo "<table width = '50%'>"
				."<tr style='color:#ffffff; background-color:#008010'>"
				."<td><strong>Transit</strong></td>"
				."<td><strong>Destination Branch</strong></td>"		
				."<td><strong>Action</strong></td>"		
				."</tr>";
			$i = 0;
			while($row = mysql_fetch_assoc($query_transit)){
				$query_trname = mysql_query("SELECT storename AS transitname FROM store WHERE storeid = '{$row['transitid']}'") or die(mysql_error());
				$trans_row = mysql_fetch_assoc($query_trname);
				if($i % 2 == 0) echo '<tr class="even">'; else echo '<tr class="odd">';
				
				echo "<td>{$trans_row['transitname']}</td>"
					."<td>{$row['storename']}</td>"
					."<td><a href = '?opt=confirm_delivery&id={$row['transitid']}&bId={$row['branchid']}&tId={$row['transitid']}'>Confirm delivery</a></td>"						
					."</tr>";
				$i++;
			}
			echo "</table>";
		}else{
			echo "<strong style = 'color:red'>Nothing to display</strong>";
		}
	}

unset($_SESSION['filter']);
?>
<script type="text/javascript" src="./report/datepickr.js"></script>
<script type="text/javascript">
			new datepickr('datepick', {
				'dateFormat': 'Y-m-d'
			});
			new datepickr('datepick2', {
				'dateFormat': 'Y-m-d'
			});
			function gen()
			{
				if(document.getElementById('datepick').value != "" && document.getElementById('datepick2').value != "")
				{
				generateReport('Production&startdate='+document.getElementById('datepick').value+'&enddate='+document.getElementById('datepick2').value);
				}else
				{
					alert("Please click the boxes to select the start date and end date");
				}
			}
</script>
