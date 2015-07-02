<span id="report_status">
<h3>Select Start Date:</h3>
<input id="datepick" size=size="8" readonly="" style="cursor:pointer" />
<hr />

<h3>Select End Date:</h3>
<input id="datepick2" size=size="8" readonly="" style="cursor:pointer" />
<hr />
<script type="text/javascript" src="report/datepickr.js"></script>
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
<a href="javascript:gen()"> Click Here to Generate Production Report </a> <br />
</span>
