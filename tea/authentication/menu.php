<?php
if(isset($_SESSION['username'])){	
	
	if($_SESSION['employees']){
		echo '<a href="javascript: ShowEmployeesMenu();" class="Menu"> EMPLOYEES </a>
		<div id ="EmployeesMenu" style="width:200px; float=left"></div><br />';
	}
	
	if($_SESSION['production']){
		echo '<a href="javascript: ShowProductionMenu();" class="Menu"> PRODUCTION </a>				
		<div id ="ProductionMenu" style="width:200px; float=left"></div><br />';
	}
	
	if($_SESSION['allocation']){
		echo '<a href="javascript: ShowAllocationMenu();" class="Menu"> ALLOCATION </a>				
		<div id ="AllocationMenu" style="width:200px; float=left"></div><br />';
	}
	
	if($_SESSION['branches']){
		echo '<a href="javascript: ShowBranchesMenu();" class="Menu"> BRANCHES </a>				
		<div id ="BranchesMenu" style="width:200px; float=left"></div><br />';
	}
	
	if($_SESSION['stock']){
		echo '<a href="javascript: ShowStockMenu();" class="Menu"> STOCK MANAGEMENT </a>				
		<div id ="StockMenu" style="width:200px; float=left"></div><br />';
	}
	
	if($_SESSION['reports']){
		echo '<a href="javascript: ShowReportsMenu();" class="Menu"> REPORTS </a>				
		<div id ="ReportsMenu" style="width:200px; float=left"></div><br />';
	}
	
	if($_SESSION['configurations']){
		echo '<a href="javascript: ShowConfigurationsMenu();" class="Menu"> CONFIGURATIONS </a>				
		<div id ="ConfigurationsMenu" style="width:200px; float=left"></div><br />';
	}
	
	echo'<a href="javascript: ShowAccountMenu();" class="Menu"> MY ACCOUNT </a>				
		<div id ="AccountMenu" style="width:200px; float=left">				
		</div>';
} else{
	echo '<img src="images/tea_left.jpg" alt="Chai Plant" style="width:180px" />';
}
?>
