<?php 
	require("authentication/functions.php");
	if(isset($_GET['opt']) && isset($_SESSION['username']) && auth_include()){	
		echo '<fieldset style="width:724px"> <legend style="text-transform:uppercase; font-weight:bold">'.$_GET['opt'].'</legend>';
		switch($_GET['opt'])
		{
			case "manage_employees":
				include('employees/manage_employees.php');
				break;
			case "deleted_employees":
				include('employees/deleted_employees.php');
				break;
			case "register_employee":
				include('employees/register_employee.php');
				break;
			case "view_employee_details":
				include('employees/employee_details.php');
				break;
			case "insert_employee":
				include('employees/insert_employee.php');
				break;
			case "edit_employee":
				include('employees/edit_employee.php');
				break;
			case "blend":
				include('configuration/blend.php');
				break;
			case "edit_blend":
				include('configuration/edit_blend.php');
				break;
			case "shift":
				include('configuration/shift.php');
				break;
			case "edit_shift":
				include('configuration/edit_shift.php');
				break;
			case "product":
				include('configuration/product.php');
				break;
			case "edit_product":
				include('configuration/edit_product.php');
				break;
				case "supplier":
				include('configuration/supplier.php');
				break;
					case "edit_supplier":
				include('configuration/edit_supplier.php');
				break;
			case "edit_product":
				include('configuration/edit_product.php');
				break;
			case "input":
				include('production/input.php');
				break;
			case "blend_input":
				include('production/blend_input.php');
				break;	
			case "output":
				include('production/output.php');
				break;
			case "edit_output":
				include('production/edit_output.php');
				break;
			case "edit_input":
				include('production/edit_input.php');
				break;
			case "attendance":
				include('allocation/attendance.php');
				break;
			case "manage_allocations":
				include('allocation/job_allocation.php');
				break;
			case "job_allocation":
				include('allocation/job_allocation.php');
				break;
			case "material":
				include('material/material.php');
				break;
			case "edit_material":
				include('material/edit_material.php');
				break;
			case "open_stock":
				include('stock/opening_stock.php');
				break;
			case "purchase_stock":
				include('stock/purchase.php');
				break;
			case "change_password":
				include('authentication/change_password.php');
				break;
			case "sales":
				include('stock/sales.php');
				break;
			case "current_stock":
				include('stock/current_stock.php');
				break;
			case "confirm_delivery":
				include('stock/confirm_delivery.php');
				break;
			case "production_report":
				include('report/production_report.php');
				break;
			case "attendance_report":
				include('report/attendance_report.php');
				break;
			case "stock_report":
				include('report/stock_report.php');
				break;
				case "material_returned":
				include('report/material_returned.php');
				break;
			case "transit_report":
				header('Location: report/report_generator/branch_stock.php');
				exit;
				break;
			case "system_log":
				include('report/audit_trail/audit_trail.php');
				break;
			case "excel_upload":
				include('production/uploading_production_data/upload_production_data.php');
				break;
			case "man_power":
				include('allocation/manpower.php');
				break;
			case "blend_formula":
				include('configuration/theoretical_usage.php');
				break;
			case "view_blend_formula":
				include('configuration/product_formula.php');
				break;
			case "products_and_material_substraction":
				include('stock/stock_out.php');
				break;
			case "return_stock":
				include('stock/return_stock.php');
				break;
			case "edit_return_stock":
				include('stock/edit_return_stock.php');
				break;
			case "stock_transfer":
				include('stock/store/stock_transfer.php');
				break;
			case "order_material":
				include('stock/place_order.php');
				break;
			case "view_orders":
				include('stock/view_orders.php');
				break;
			case "stores":
				include('stock/store/register_and_view_store.php');
				break;
			case "edit_store":
				include('stock/store/edit_store.php');
				break;
			case "invoice":
				include('stock/store/invoices_received.php');
				break;
			case "backup_restore":
				include('configuration/backup.php');
				break;
			case "branches":
				include('branches/branches.php');
				break;
			case "critical_levels_report":
				include('report/critical_levels.php');
				break;
			case "returned_product":
				include('stock/returned_product.php');
				break;
			case "edit_return_product":
				include('stock/edit_return_product.php');
				break;
			case "store_types":
				include('stock/store/type_of_store.php');
				break;
			case "offstock":
				include('stock/store/offstock.php');
				break;
			case "attendance_status":
				include('configuration/attendance_status.php');
				break;
			default:
				include('default.php');
		}
		echo '</fieldset>';
	}else{
	if(isset($_SESSION['username']))
			echo' Login was successful, Select required option from the left menu:';
		else 
			echo' Please login by providing your Username and Password at the top right form:';
	echo '<h2><b>Kyimbila Tea Packing Limited</b><br />
	P.O.Box 1344 Dar Es Salaam<br />
	Tel: +255 22 2861391 <br />
	Email: tausisales@tatepa.com<br /></h2>
	Authorised Distributors: <br />
	<ul>
	<li> <b>Arusha (Head Office) 0683 555 888</b> </li>
	<li> Moshi Branch 0715 501 506 </li>
	<li> Tanga Branch 0762 974 321 </li>
	<li> Mwanza Branch 0717 929 233 </li>
	<li> Dar Es Salaam Branch 0788 313 131 </li>
	<li> Mbeya Branch 0752 381 808 </li>
	</ul>';
	}
?>