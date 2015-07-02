<?php
function auth_include()
{
	$o = $_GET['opt'];
	if(($o == "register_employee" || $o == "manage_employees" || $o =="insert_employee" || $o =="edit_employee" || $o =="view_employee_details" || $o == "deleted_employees" ) && isset($_SESSION['employees']))
	{
		return true;
	}else if(($o == "input" || $o == "blend_input" || $o == "output" || $o == "edit_input" || $o == "edit_output" || $o == "excel_upload") && isset($_SESSION['production']))
	{
		return true;
	}else if(($o == "attendance" || $o == "manage_allocations" || $o == "man_power") && isset($_SESSION['allocation']))
	{
		return true;
	}else if(($o == "open_stock" || $o == "sales" || $o == "purchase_stock" || $o == "current_stock" || $o == "stock_transfer" || $o == "products_and_material_substraction" || $o == "return_stock" || $o == "edit_return_stock" || $o == "invoice" || $o == "returned_product" || $o == "edit_return_product" || $o == "offstock" || $o == "order_material" || $o == "view_orders") && isset($_SESSION['stock']))
	{
		return true;
	}else if(($o == "blend" || $o == "edit_blend" || $o == "shift" || $o == "edit_shift" || $o == "product" || $o == "edit_product" || $o == "material" || $o == "edit_material" || $o == "blend_formula" || $o == "view_blend_formula" || $o == "job_allocation" || $o == "stores" || $o == "edit_store" || $o == "backup_restore" || $o == "store_types" || $o == "supplier" || $o == "edit_supplier") && isset($_SESSION['configurations']))
	{
		return true;
	}else if(($o == "transit_report" || $o == "production_report" || $o == "attendance_report" || $o == "stock_report" || $o == "system_log" || $o == "attendance_status" || $o == "material_returned") && isset($_SESSION['reports']))
	{
		return true;
	}
	else if(($o == "branches" || $o == "confirm_delivery" ) && isset($_SESSION['branches']))
	{
		return true;
		
	}else if($o == "change_password")
	{
		return true;
	}else if($o == "critical_levels_report")
	{
		return true;
	}else
	{
		echo "No directory for this link.";
		return false;
	}
}
?>