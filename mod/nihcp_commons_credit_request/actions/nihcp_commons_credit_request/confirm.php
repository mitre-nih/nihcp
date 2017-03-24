<?php

use \Nihcp\Entity\CommonsCreditRequest;
use \Nihcp\Entity\CommonsCreditRequestId;
use \Nihcp\Entity\CommonsCreditRequestDelegation;

elgg_require_js('delegate');

$guid = get_input('request_guid');

$ia = elgg_set_ignore_access();
$current_request = get_entity($guid);
elgg_set_ignore_access($ia);
if(($current_request instanceof CommonsCreditRequest) && $current_request->isDraftEditable()) {
	$action = get_input('action');

	switch ($action) {
		case 'Submit':
			if ($current_request->assignToCycle()) {
				$current_request->status = 'Submitted';
				$current_request->submission_date = date('n/j/Y');
				$ia = elgg_set_ignore_access();
				$delegation = CommonsCreditRequestDelegation::getDelegationForCCREQ($guid);
				if (!empty($delegation)) {
					$delegation->setStatus(CommonsCreditRequestDelegation::DELEGATION_SUBMITTED_STATUS);
				}
				elgg_set_ignore_access($ia);

				if(!CommonsCreditRequestId::assignToRequest($guid)) {
					register_error(elgg_echo("nihcp_commons_credit_request:id:assign_fail"));
				}

				elgg_trigger_event('submit', 'object:'.CommonsCreditRequest::SUBTYPE, $current_request);
			} else {
				register_error(elgg_echo("nihcp_commons_credit_request:cycle:noactive"));
			}

			break;
		case 'Submit for PI Review': // should only get here if there was delegation
			$ia = elgg_set_ignore_access();
			$delegation = CommonsCreditRequestDelegation::getDelegationForCCREQ($guid);
			$delegation->setStatus(CommonsCreditRequestDelegation::DELEGATION_REVIEW_STATUS);
			elgg_set_ignore_access($ia);
			break;
		case 'Give control back to delegate':
			$ia = elgg_set_ignore_access();
			$delegation = CommonsCreditRequestDelegation::getDelegationForCCREQ($guid);
			$delegation->setStatus(CommonsCreditRequestDelegation::DELEGATION_DELEGATED_STATUS);
			elgg_set_ignore_access($ia);
			break;
		default:
			break;
	}
} else {
	register_error(elgg_echo('error:404:content'));
}
forward('nihcp_commons_credit_request/overview');