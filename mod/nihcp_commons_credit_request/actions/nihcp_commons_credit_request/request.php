<?php

use \Nihcp\Entity\CommonsCreditRequest;

elgg_make_sticky_form('request');

// which button was pressed
$action = get_input('action', '', false);
$guid = get_input('request_guid', '', false);

switch ($action) {
    case 'Next':
		if($guid) {
			$request = get_entity($guid);
			if(!$request instanceof CommonsCreditRequest) {
				//error, redirect
				register_error(elgg_echo('nihcp_commons_credit_request:save:failed'));
				forward('nihcp_commons_credit_request/overview');
			}
		} else {
			$request = new CommonsCreditRequest();
		}
		if($guid = CommonsCreditRequest::saveRequestFromForm($request)) {
			elgg_clear_sticky_form('request');
			forward("nihcp_commons_credit_request/confirm/$guid");
		}
        break;
    case 'Save':
		if($guid) {
			$request = get_entity($guid);
			if(!$request instanceof CommonsCreditRequest) {
				//error, redirect
				register_error(elgg_echo('nihcp_commons_credit_request:save:failed'));
				forward('nihcp_commons_credit_request/overview');
			}
		} else {
			$request = new CommonsCreditRequest();
		}
		if($guid = CommonsCreditRequest::saveRequestFromForm($request)) {
			elgg_clear_sticky_form('request');
		} else {
			register_error(elgg_echo('nihcp_commons_credit_request:save:failed'));
		}
		forward('/nihcp_commons_credit_request/overview');
        break;
    default:
        elgg_clear_sticky_form('request');
        forward('/nihcp_commons_credit_request/overview');
        break;
}

