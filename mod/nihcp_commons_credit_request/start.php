<?php

elgg_register_event_handler('init', 'system', 'commons_credit_request_init');

function commons_credit_request_init() {
	elgg_define_js('autoNumeric', [
			"src" => elgg_get_site_url() . "mod/nihcp_commons_credit_request/vendor/bower-asset/autoNumeric/autoNumeric.js"
	]);

	elgg_define_js('tablesorter',[
	        "src" => elgg_get_site_url() . "mod/nihcp_commons_credit_request/vendor/christianbach/tablesorter/jquery.tablesorter.js"
    ]);

	elgg_register_page_handler('nihcp_commons_credit_request', 'commons_credit_request_page_handler');
	
	// Register a script to handle (usually) a POST request (an action)
	$action_path = __DIR__ . '/actions';
	elgg_register_action('request', "$action_path/nihcp_commons_credit_request/request.php");
	elgg_register_action('confirm', "$action_path/nihcp_commons_credit_request/confirm.php");
	elgg_register_action('delegate', "$action_path/nihcp_commons_credit_request/delegate.php");
	elgg_register_action('delegate_request', "$action_path/nihcp_commons_credit_request/delegate_request.php");
	elgg_register_action('delete_delegate', "$action_path/nihcp_commons_credit_request/delete_delegate.php");
	elgg_register_action('file/delete', "$action_path/nihcp_commons_credit_request/file/delete.php");
	elgg_register_action('delete_request', "$action_path/nihcp_commons_credit_request/delete_request.php");
    elgg_register_action('withdraw_request', "$action_path/nihcp_commons_credit_request/withdraw_request.php");
    elgg_register_action('verify_grant_id',"$action_path/nihcp_commons_credit_request/verify_grant_id.php");

	elgg_register_ajax_view('commons_credit_request/overview/requests_in_cycle');

	// Extend the main CSS file
	elgg_extend_view('css/elements/layout', 'css/request');

	// Add a menu item to the main site menu
	$item = new ElggMenuItem('nihcp_commons_credit_request', elgg_echo('nihcp_commons_credit_request:menu'), 'nihcp_commons_credit_request');
	elgg_register_menu_item('site', $item);

	// List of NIH policies.
	// key: equal to last part of language file key, i.e. nihcp_commons_credit_request:nih_policies:data_sharing:website -> data_sharing:website
	// value: url link to the policy
	$policies = array(
		'data_sharing' => 'http://grants.nih.gov/grants/policy/data_sharing/data_sharing_guidance.htm',
		'data_sharing:website' => 'http://grants1.nih.gov/grants/policy/data_sharing/index.htm',
		'grants_policy:availability_information' => 'http://grants.nih.gov/grants/policy/nihgps_2010/nihgps_ch2.htm#info_availability',
		'grants_policy:availability_research_results' => 'http://grants.nih.gov/grants/policy/nihgps_2010/nihgps_ch8.htm#_Toc271264947',
		'amendment_a110' => 'http://grants.nih.gov/grants/policy/a110/a110_guidance_dec1999.htm',
		'grants_policy:access_research_data' => 'http://grants.nih.gov/grants/policy/nihgps_2010/nihgps_ch2.htm#info_confidentiality'
	);

	if(elgg_is_logged_in()) {
		foreach ($policies as $policy=>$policy_link) {
			elgg_register_menu_item('extras', array(
					'name' => 'nihcp-policy-'.$policy,
					'href' => $policy_link,
					'text' => elgg_echo('nihcp_commons_credit_request:nih_policies:' . $policy),
					'link_class' => 'nihcp-policy'
			));
		}

        // Add Service Request Worksheet link to sidebar
        elgg_register_menu_item('extras', array(
            'name' => 'Service Request Worksheet',
            'href' => elgg_get_site_url() . "nihcp_commons_credit_request/service-request-worksheet",
            'text' => 'Service Request Worksheet',
        ));

		// Add Commons Credit Request Overview link to sidebar
		elgg_register_menu_item('extras', array(
			'name' => 'Commons Credits Request Overview',
			'href' => elgg_get_site_url() . "nihcp_commons_credit_request/commons-credit-request-overview",
			'text' => 'Commons Credits Request Overview',
		));

		if (nihcp_investigator_gatekeeper(false)) {
			elgg_register_menu_item('extras', array(
				'name' => 'Investigator Portal User Manual',
				'href' => elgg_get_site_url() . "nihcp_commons_credit_request/investigator-portal-user-manual",
				'text' => 'Investigator Portal User Manual',
			));
		}
	}

	// Add the new widget
	elgg_register_widget_type('nihcp_commons_credit_request', elgg_echo("nihcp_commons_credit_request"), elgg_echo("nihcp_commons_credit_request:widget:description"));
}

function commons_credit_request_page_handler($page) {
	$ccreq_dir = elgg_get_plugins_path() . 'nihcp_commons_credit_request/pages/nihcp_commons_credit_request';

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	$page_type = $page[0];
	if (isset($page[1])) {
		set_input('request_guid', $page[1]);
	}
	switch ($page_type) {
		case 'overview':
			include "$ccreq_dir/overview.php";
			break;
		case 'request':
			include "$ccreq_dir/request.php";
			break;
        case 'confirm':
            include "$ccreq_dir/confirm.php";
            break;
		case 'terms-and-conditions':
			include "$ccreq_dir/terms_and_conditions.php";
			break;
		case 'attachment':
			include "$ccreq_dir/attachment.php";
			break;
		case 'delegate':
			if (isset($page[3])) { // this is a link for accepting delegation request
				set_input('delegation_guid', $page[3]);
				include "$ccreq_dir/delegate_request.php";
			} else { // adding/deleting delegates
				include "$ccreq_dir/delegate.php";
			}
			break;
		case 'all':
			include "$ccreq_dir/overview.php";
			break;

		// various file resources
		// the file with the specified filename must be in the elgg data directory under the /docs/ subdirectory.
		case 'service-request-worksheet':
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="service_request_worksheet.xlsx"');
			readfile(elgg_get_data_path() . '/docs/service_request_worksheet.xlsx');
			break;
		case 'commons-credit-request-overview':
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment; filename="NIH Commons Credits Request Overview.pdf"');
			readfile(elgg_get_data_path() . '/docs/NIH Commons Credits Request Overview.pdf');
			break;
		case 'investigator-portal-user-manual':
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment; filename="Investigator Portal User Manual.pdf"');
			readfile(elgg_get_data_path() . '/docs/Investigator Portal User Manual.pdf');
			break;



		default:
			return false;
	}
	return true;
}

function grant_id_is_valid($grant_id){
    $retVal = False;
    $grant_id = strtoupper(htmlspecialchars($grant_id));
    $user = elgg_get_logged_in_user_entity();
    $curDate = date("Y-m-d");
    try {
        $stmt = "SELECT count(*) as i from reporter_data where email='" . $user->email . "' and grant_id='" . $grant_id . "' and '" . $curDate . "' between start_date and end_date";
        $rslt = get_data($stmt);

        if (intval($rslt[0]->i) > 0) {
            $retVal = True;
        }
    }catch(Exception $e){
        error_log($e->getMessage());
        $retVal = "error";
    }

    return $retVal;
}
