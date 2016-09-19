<?php

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

	elgg_register_ajax_view('nihcp_credit_allocation/allocations/allocations_in_cycle');

	// Add a new widget
	elgg_register_widget_type('nihcp_credit_allocation', elgg_echo("nihcp_credit_allocation"), elgg_echo("nihcp_credit_allocation:widget:description"));

	elgg_register_page_handler('nihcp_credit_allocation', 'nihcp_credit_allocation_page_handler');

	elgg_register_event_handler('ingested', 'object:'.\Nihcp\Entity\CommonsCreditAllocationFile::SUBTYPE, 'nihcp_credit_allocation_handle_notifications');
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
		default:
			return false;
	}
	return true;
}

function nihcp_credit_allocation_handle_notifications($event, $object_type, $object) {

	$ia = elgg_set_ignore_access();
	$users = $object['users'];
	$subject = elgg_echo('nihcp_credit_allocation:notify:subject');
	foreach($users as $user_guid) {
		$user = get_entity($user_guid);
		$body = elgg_echo('nihcp_credit_allocation:notify:body', [$user->getDisplayName()])."\n";
		//$body .= "<table>";
		$active_requests = \Nihcp\Entity\CommonsCreditRequest::getByUserAndCycle('!'.\Nihcp\Entity\CommonsCreditRequest::DRAFT_STATUS, $user_guid);
		/*if(!empty($active_requests)) {
			//$body .= "<tr><th>CCREQ ID</th><th>Vendor</th><th>Credit Remaining</th></tr>";
			//$body .= "\nCCREQ ID\tVendor\tCredit Remaining\n";
		}*/
		foreach($active_requests as $request) {

			$new_allocations = \Nihcp\Entity\CommonsCreditAllocation::getAllocations($request->getGUID());
			if(!empty($new_allocations)) {
				//$body .= "<tr><td>{$request->getRequestId()}:</td><td></td><td></td></tr>";
				$body .= "\t{$request->getRequestId()}:\n";
			}

			foreach($new_allocations as $allocation) {
				$vendor_name = \Nihcp\Entity\CommonsCreditVendor::getByVendorId($allocation->vendor)->getDisplayName();
				/*$body .= '<tr><td></td>';
				$body .= "<td>$vendor_name</td>";
				$body .= "<td>$allocation->credit_remaining</td>";
				$body .= "</tr>";*/
				$body .= "\t\t$vendor_name:\t$allocation->credit_remaining\n";
			}
		}
		//$body .= "</table>";
		elgg_send_email(elgg_get_config('siteemail'), $user->email, $subject, $body, []);

		elgg_set_ignore_access($ia);
	}
}