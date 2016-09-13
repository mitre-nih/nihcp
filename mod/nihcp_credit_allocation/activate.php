<?php
if (get_subtype_id('object', 'commonscreditallocation')) {
	update_subtype('object', 'commonscreditallocation', 'Nihcp\Entity\CommonsCreditAllocation');
} else {
	add_subtype('object', 'commonscreditallocation', 'Nihcp\Entity\CommonsCreditAllocation');
}
if (get_subtype_id('object', 'commonscreditallocationfile')) {
	update_subtype('object', 'commonscreditallocationfile', 'Nihcp\Entity\CommonsCreditAllocationFile');
} else {
	add_subtype('object', 'commonscreditallocationfile', 'Nihcp\Entity\CommonsCreditAllocationFile');
}
if (get_subtype_id('object', 'commonscreditvendor')) {
	update_subtype('object', 'commonscreditvendor', 'Nihcp\Entity\CommonsCreditVendor');
} else {
	add_subtype('object', 'commonscreditvendor', 'Nihcp\Entity\CommonsCreditVendor');
}

use \Nihcp\Entity\CommonsCreditVendor;
$vendors = ['VXY00891' => "Broad Institute", 'VXY00892' => "IBM", 'VXY00893' => "DLT", 'VXY00894' => "Google"];
foreach($vendors as $vendor_id => $vendor_name) {
	if(!CommonsCreditVendor::getByVendorId($vendor_id)) {
		$vendor = new CommonsCreditVendor();
		$vendor->setVendorId($vendor_id);
		$vendor->setDisplayName($vendor_name);
		$vendor->save();
	} else {
		error_log("$vendor_name exists. Skipping.");
	}
}