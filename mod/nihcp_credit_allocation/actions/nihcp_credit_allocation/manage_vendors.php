<?php
use Nihcp\Entity\CommonsCreditVendor;
$action = get_input('action');
if($action === "Add") {
	$new_vendor_name = get_input("new_vendor_name");
	$new_vendor_id = get_input("new_vendor_id");
	if(CommonsCreditVendor::getByName($new_vendor_name)) {
		register_error('Vendor already exists with that name');
	} elseif(CommonsCreditVendor::getByVendorId($new_vendor_id)) {
		register_error('Vendor already exists with that ID');
	} elseif($new_vendor_name && $new_vendor_id) {
		$ia = elgg_set_ignore_access();
		$vendor = new CommonsCreditVendor();
		$vendor->title = $new_vendor_name;
		$vendor->vendor_id = $new_vendor_id;
		if($vendor->save()) {
			system_message("Saved vendor");
		} else {
			register_error("Unable to save");
		}
		elgg_set_ignore_access($ia);
	}
} else {
	$ia = elgg_set_ignore_access();
	$vendors = CommonsCreditVendor::getAllVendors();
	foreach($vendors as $vendor) {
		$is_active = get_input($vendor->vendor_id, false) == "on" ? true : false;
		$vendor->active = $is_active;
	}
	elgg_set_ignore_access($ia);
}