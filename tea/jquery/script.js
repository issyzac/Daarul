// JavaScript Document
	var speed='normal';
	function HideAllMenu(){
		
		if(document.getElementById('EmployeesMenu') != undefined){
			$('#EmployeesMenu').slideUp(speed);//innerHTML = '';
		}
		
		if(document.getElementById('ProductionMenu') != undefined){
			$('#ProductionMenu').slideUp(speed);
		}
		
		if(document.getElementById('AllocationMenu') != undefined){
			$('#AllocationMenu').slideUp(speed);
		}
		
		if(document.getElementById('StockMenu') != undefined){
			$('#StockMenu').slideUp(speed);
		}
		
		if(document.getElementById('BranchesMenu') != undefined){
			$('#BranchesMenu').slideUp(speed);
		}
		
		if(document.getElementById('ReportsMenu') != undefined){
			$('#ReportsMenu').slideUp(speed);
		}
		
		if(document.getElementById('ConfigurationsMenu') != undefined){
			$('#ConfigurationsMenu').slideUp(speed);
		}
		
		if(document.getElementById('AccountMenu') != undefined){
			$('#AccountMenu').slideUp(speed);
		}
		
	}

	function CompareText(textOne, textTwo){
		if(textOne == textTwo){
			return true;
		} else {
			alert("Passwords do not match!");
			return false;
		}
	}
	
	function ShowEmployeesMenu(){
		HideAllMenu();
		$('#EmployeesMenu').html('<img src="images/leaf.png" /><a href="?opt=register_employee"> Register Employee </a> <br /><img src="images/leaf.png" /><a href="?opt=manage_employees"> Manage Employees </a><br /><img src="images/leaf.png" /><a href="?opt=deleted_employees"> Deleted Employees </a><br />').slideDown(speed);
	}
	
	function ShowProductionMenu(){
		HideAllMenu();
		$('#ProductionMenu').html('<img src="images/leaf.png" /><a href="?opt=input"> Daily Input </a> <br /><img src="images/leaf.png" /><a href="?opt=output"> Daily Output </a> <br /><img src="images/leaf.png" /><a href="?opt=excel_upload"> Excel Upload </a> <br />').slideDown(speed);
	}
	
	function ShowAllocationMenu(){
		HideAllMenu();
		$('#AllocationMenu').html('<img src="images/leaf.png" /><a href="?opt=attendance"> Attendance </a>  <br /><img src="images/leaf.png" /><a href="?opt=man_power"> Man Power </a> <br />').slideDown(speed);
	}
	
	function ShowStockMenu(){
		HideAllMenu();
		$('#StockMenu').html('<img src="images/leaf.png" /><a href="?opt=open_stock"> Opening Stock </a> <br /><img src="images/leaf.png" /><a href="?opt=sales"> Sales </a> <br /><img src="images/leaf.png" /><a href="?opt=order_material"> Order Material </a><br /><img src="images/leaf.png" /><a href="?opt=purchase_stock"> Purchase Material </a> <br /><img src="images/leaf.png" /><a href="?opt=return_stock"> Material Returned</a> <br /><img src="images/leaf.png" /><a href="?opt=returned_product"> Returned Products</a> <br /><img src="images/leaf.png" /><a href="?opt=stock_transfer"> Product to Stores </a> <br /><img src="images/leaf.png" /><a href="?opt=current_stock"> Current Stock </a> <br /><img src="images/leaf.png" /><a href="?opt=offstock"> Offstock Products </a> <br />').slideDown(speed);//<img src="images/leaf.png" /><a href="?opt=close"> Close Stock </a> <br />';
	}
	
	function ShowConfigurationsMenu(){
		HideAllMenu();
		$('#ConfigurationsMenu').html('<img src="images/leaf.png" /><a href="?opt=blend"> Blends &amp; Products </a> <br /><img src="images/leaf.png" /><a href="?opt=shift"> Shifts </a>  <br /><img src="images/leaf.png" /><a href="?opt=job_allocation"> Job Descriptions </a><br />' + 
		'<img src="images/leaf.png" /><a href="?opt=material&action=packing"> Packing Materials </a> <br /><img src="images/leaf.png" /><a href="?opt=material&action=blending"> Blending Materials </a> <br /><img src="images/leaf.png" /><a href="?opt=stores"> Branches &amp; Stores </a> <br /><img src="images/leaf.png" /><a href="?opt=store_types"> Store Types </a> <br /><img src="images/leaf.png" /><a href="?opt=attendance_status"> Attendance Status</a> <br /><img src="images/leaf.png" /><a href="?opt=supplier"> Supplier </a> <br /><img src="images/leaf.png" /><a href="?opt=backup_restore"> Backup & Restore </a> <br />').slideDown(speed);
	}
	
	function ShowReportsMenu(){
		HideAllMenu();
		$('#ReportsMenu').html('<img src="images/leaf.png" /><a href="?opt=production_report"> Production Report </a> <br /><img src="images/leaf.png" /><a href="?opt=attendance_report">Attendance Report</a><br /><img src="images/leaf.png" /><a href="?opt=transit_report">Transit/Brach stocks</a><br/><img src="images/leaf.png" /><a href="?opt=critical_levels_report">Critical Levels Report</a><br /><img src="images/leaf.png" /><a href="?opt=system_log">System Log</a><br/><img src="images/leaf.png" /> <a href="?opt=material_returned">Material_Returned</a><br/><img src="images/leaf.png" /><a href="?opt=stock_report">Stock Report</a>').slideDown(speed);
		
	}
	
	function ShowAccountMenu(){
		HideAllMenu();
		$('#AccountMenu').html('<img src="images/leaf.png" /><a href="?opt=change_password"> Change Password </a> <br />'+logout()).slideDown(speed);
	}
	
	function showSystemFields(){
		if(document.getElementById('checkSystem').checked == true){
			$('#SystemUser').hide().html('<table><tr><td>Username: </td><td> <input type="text" id="txtUname" name="txtUname" size="30" style="float:right" /> </td></tr>' + 
			'<tr><td> Password: </td><td><input type="password" id="txtPword" name="txtPword" size="30" style="float:right"/></td></tr>' +
			'<tr><td style="vertical-align:top">Title: </td><td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <select name="txtPrivilege" style="width:210px; color:#4c4c4c; font-size:13px"><option>General Manager</option><option>Production Manager</option><option>Administrator</option></select> <br /><br /> </td></tr>' + 
			'<tr><td style="vertical-align:top"> Privileges: </td><td> <table>' + 
			'<tr><td> <input type="checkbox" name="bra" value="1"/> Branches </td><td> <input type="checkbox" name="emp" /> Employees  </td></tr>' +
			'<tr><td> <input type="checkbox" name="pro" /> Production </td><td> <input type="checkbox" name="all" /> Allocation  </td></tr>' +
			'<tr><td> <input type="checkbox" name="sto" /> Stock </td><td> <input type="checkbox" name="rep" /> Reports  </td></tr>' +
			'<tr><td> <input type="checkbox" name="con" /> Configurations </td><td> <input type="checkbox" name="acc" checked="checked" disabled="true" /> Account  </td></tr>' +
			'</table></td></tr> </table>').slideDown('slow');
		} else {
			$('#SystemUser').slideUp('slow',function(){$(this).html('');});
		}
		
	}
	
	function generateReport(reportType){
	document.getElementById("report_status").innerHTML="<center><img src='images/loading_bar.gif' alt='Loading...' width='500px'/> <br /> Generating Report.. Please Wait</center>";	
	var xmlhttp;
	if (window.XMLHttpRequest){	
		xmlhttp=new XMLHttpRequest();  // Initialize HTTP request
	}
	else {
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("report_status").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","report/report_generator/make_report.php?report_type="+reportType,true);
	xmlhttp.send();	
}
