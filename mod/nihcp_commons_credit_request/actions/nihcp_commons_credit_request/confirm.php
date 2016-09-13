<?php

use \Nihcp\Entity\CommonsCreditRequest;
use \Nihcp\Entity\CommonsCreditRequestId;

$guid = get_input('request_guid');
$current_request = get_entity($guid);
if($current_request instanceof CommonsCreditRequest) {
	$action = get_input('action');

	switch ($action) {
		case 'Submit':
			if ($current_request->assignToCycle()) {
				$current_request->status = 'Submitted';
				$current_request->submission_date = date('n/j/Y');
			} else {
				register_error(elgg_echo("nihcp_commons_credit_request:cycle:noactive"));
			}
			if(!CommonsCreditRequestId::assignToRequest($guid)) {
				register_error(elgg_echo("nihcp_commons_credit_request:id:assign_fail"));
			}
			break;
		default:
			break;
	}
} else {
	register_error(elgg_echo('error:404:content'));
}
forward('nihcp_commons_credit_request/overview');