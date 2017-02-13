<?php
$vendors = \Nihcp\Entity\CommonsCreditVendor::getAllVendors();

$add_vendor = "<div><label>" . "Vendor Name";
$add_vendor .= elgg_view("input/text", array("name" => "new_vendor_name")) . "</label></div>";
$add_vendor .= "<div class=\"mts\"><label>" . "Vendor ID";
$add_vendor .= elgg_view("input/text", array("name" => "new_vendor_id")) . "</label></div>";
$add_vendor .= elgg_view("input/submit", array("name" => "action", "type" => "submit", "value" => "Add", "class" => "elgg-button elgg-button-submit mtm"));

echo elgg_view_module("inline", "Add Vendor", $add_vendor);

$existing_vendors = "<div>* Active</div>";
$existing_vendors .= "<table class=\"elgg-table\">";
$existing_vendors .= "<tr><th class='nihcp-manage-vendor-header-name'>Name</th><th class='nihcp-manage-vendor-header-id'>ID</th><th>*</th></tr>";
$ia = elgg_set_ignore_access();
foreach($vendors as $vendor) {
	$chkbx_params = array("name" => $vendor->vendor_id);
	if($vendor->active) {
		$chkbx_params["checked"] = "checked";
	}
	$existing_vendors .= "<tr><td>$vendor->title</td><td>$vendor->vendor_id</td><td>".elgg_view("input/checkbox", $chkbx_params)."</td></tr>";
}
elgg_set_ignore_access($ia);
$existing_vendors .= "</table>";
$existing_vendors .= elgg_view("input/submit", array("name" => "action", "type" => "submit", "value" => "Save", "class" => "elgg-button elgg-button-submit mtm"));

echo elgg_view_module("inline", "Vendors", $existing_vendors);