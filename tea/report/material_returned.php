<span id="report_status">
	Start Date:
		<input id="start_datepick" size="8" readonly="" style="cursor:pointer"/>
	End Date: <input id="end_datepick" size="8" readonly="" style="cursor:pointer"/>
	
	
	
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
					generateReport('material&start_datepick='+document.getElementById('start_datepick').value+'&end_datepick='+document.getElementById('end_datepick').value);
				}else{
					alert("Please click the box to select the date.");
				}
			}
		</script>
<a href="javascript:gen()"> Click Here to Generate Material Returned Report </a> <br />
</span>

