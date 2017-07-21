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