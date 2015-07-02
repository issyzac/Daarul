<span id="report_status">
<h3>Select Date:</h3>
		<input id="datepick" size="8" readonly="" style="cursor:pointer"/>
		
		<hr />
		
		<script type="text/javascript" src="report/datepickr.js"></script>
		<script type="text/javascript">
			new datepickr('datepick', {
				'dateFormat': 'Y-m-d'
			});
			function gen()
			{
				if(document.getElementById('datepick').value != "")
				{
					generateReport('Attendance&date='+document.getElementById('datepick').value);
				}else{
					alert("Please click the boxe to select the date.");
				}
			}
		</script>
<a href="javascript:gen()"> Click Here to Generate Attendance Report </a> <br />
</span>
