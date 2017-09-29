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
