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
 

namespace Nihcp\Entity;
nihcp_nih_approver_gatekeeper();

$action = get_input('action', '', false);

switch ($action) {
	case elgg_echo("nihcp_credit_request_review:crr:decision:save"):

		$guid = get_input('request_guid');
		$decision = get_input('decision');
		$comments = get_input('feedback_comments');

		if($guid) {
			$ia = elgg_set_ignore_access();
			$request = get_entity($guid);
			if (!$request instanceof CommonsCreditRequest) {
				elgg_set_ignore_access($ia);
				return false;
			}
			if ($decision === 'Approve') {
				$request->status = CommonsCreditRequest::APPROVED_STATUS;
			} elseif ($decision === 'Deny') {
				$request->status = CommonsCreditRequest::DENIED_STATUS;
			} else {
				elgg_set_ignore_access($ia);
				return false;
			}
			$old_feedback = $request->getFeedback();
			$feedback = new Feedback();
			$feedback_guid = $feedback->save();
			add_entity_relationship($guid, Feedback::RELATIONSHIP_CCREQ_TO_FEEDBACK, $feedback_guid);
			if($old_feedback) {
				add_entity_relationship($old_feedback->getGUID(), Feedback::RELATIONSHIP_FEEDBACK_TO_FEEDBACK, $feedback_guid);
			}
			$feedback->owner_guid = elgg_get_logged_in_user_guid();
			$feedback->decision = $decision;
			$feedback->comments = $comments;
			elgg_set_ignore_access($ia);
			elgg_trigger_event('decide', 'object:'.CommonsCreditRequest::SUBTYPE, $request);
			return true;
		}
		break;
	default:
		// Save was not clicked, so don't save the form
		break;
}
forward(elgg_get_site_url() . "nihcp_credit_request_review/overview");