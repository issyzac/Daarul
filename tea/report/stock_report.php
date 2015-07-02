<span id="report_status">
	Start Date:
		<input id="start_datepick" size="8" readonly="" style="cursor:pointer"/>
	End Date: <input id="end_datepick" size="8" readonly="" style="cursor:pointer"/>
	Select Branch:<select id="branch" name="branch" >
	<?php
		$sql="SELECT * FROM store WHERE type=1 AND status=1";
		$result=mysql_query($sql);
		while($row=mysql_fetch_array($result)){
			echo "<option value='{$row['storeid']}' >{$row['storename']}</option>";
		}
	?>
	</select>
	<hr />
	<script type="text/javascript" src="report/datepickr.js"></script>
		<script type="text/javascript">
			new datepickr('start_datepick', {
				'dateFormat': 'Y-m-d'
			});
			new datepickr('end_datepick', {
				'dateFormat': 'Y-m-d'
			});
			function gen()
			{
				if(document.getElementById('start_datepick').value != "" && document.getElementById('end_datepick').value != "")
				{
					generateReport('Stock&start_date='+document.getElementById('start_datepick').value+'&end_date='+document.getElementById('end_datepick').value+'&branchid='+document.getElementById('branch').value);
				}else{
					alert("Please click the box to select the date.");
				}
			}
		</script>
<a href="javascript:gen()"> Click Here to Generate Stock Report </a> <br />
</span>
