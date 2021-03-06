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
 


elgg_register_event_handler('init', 'system', 'credit_allocation_init');

function credit_allocation_init() {
	elgg_define_js('autoNumeric', [
		"src" => elgg_get_site_url() . "mod/nihcp_commons_credit_request/vendor/bower-asset/autoNumeric/autoNumeric.js"
	]);

	// Register a script to handle (usually) a POST request (an action)
	$action_path = __DIR__ . '/actions';
	elgg_register_action('nihcp_credit_allocation/allocate', "$action_path/nihcp_credit_allocation/allocate.php");
	elgg_register_action('nihcp_credit_allocation/save', "$action_path/nihcp_credit_allocation/save.php");
	elgg_register_action('nihcp_credit_allocation/submit_allocations', "$action_path/nihcp_credit_allocation/submit_allocations.php");
	elgg_register_action('nihcp_credit_allocation/delete', "$action_path/nihcp_credit_allocation/delete.php");
	elgg_register_action('nihcp_credit_allocation/manage_vendors', "$action_path/nihcp_credit_allocation/manage_vendors.php");

	elgg_register_ajax_view('nihcp_credit_allocation/allocations/allocations_in_cycle');

	// Add a new widget
	elgg_register_widget_type('nihcp_credit_allocation', elgg_echo("nihcp_credit_allocation"), elgg_echo("nihcp_credit_allocation:widget:description"));
	elgg_register_widget_type('manage_vendors', elgg_echo("nihcp_credit_allocation:widgets:manage_vendors:title"), elgg_echo("nihcp_credit_allocation:widgets:manage_vendors:description"));

	elgg_register_page_handler('nihcp_credit_allocation', 'nihcp_credit_allocation_page_handler');
}

function nihcp_credit_allocation_page_handler($page) {
	$cca_dir = elgg_get_plugins_path() . 'nihcp_credit_allocation/pages/nihcp_credit_allocation';

	if (!isset($page[0])) {
		$page[0] = 'allocations';
	}

	$page_type = $page[0];
	if (isset($page[1])) {
		set_input('request_guid', $page[1]);
	}
	if (isset($page[2])) {
		set_input('vendor_guid', $page[2]);
	}
	switch ($page_type) {
		case 'allocations':
			include "$cca_dir/allocations.php";
			break;
		case 'allocate':
			include "$cca_dir/allocate.php";
			break;
		case 'update':
			include "$cca_dir/save.php";
			break;
		case 'attachment':
			include "$cca_dir/attachment.php";
			break;
		case 'upload_history':
			include "$cca_dir/upload_history.php";
			break;
		case 'balance_history':
			include "$cca_dir/balance_history.php";
			break;
		case 'vendors':
			include "$cca_dir/vendors.php";
			break;
		default:
			return false;
	}
	return true;
}
