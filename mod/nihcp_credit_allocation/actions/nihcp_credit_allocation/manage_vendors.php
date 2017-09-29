<?php
/*
Copyright 2017 The MITRE Corporation
 
This software was written for the NIH Commons Credit Portal. General questions 
can be forwarded to:

opensource@mitre.org

Technology Transfer Office
The MITRE Corporation
7515 Colshire Drive
McLean, VA 22102-7539

Permission is hereby granted, free of charge, to any person obtaining a copy 
of this software and associated documentation files (the "Software"), to deal 
in the Software without restriction, including without limitation the rights 
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
 

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
