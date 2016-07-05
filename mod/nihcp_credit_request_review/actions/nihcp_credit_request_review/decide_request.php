<?php
namespace Nihcp\Entity;
nihcp_nih_approver_gatekeeper();

$action = get_input('action', '', false);

switch ($action) {
	case 'Save':

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
			$feedback = $request->getFeedback();
			if (!$feedback) {
				$feedback = new Feedback();
				$feedback_guid = $feedback->save();
				add_entity_relationship($guid, Feedback::RELATIONSHIP_CCREQ_TO_FEEDBACK, $feedback_guid);
			}
			$feedback->decision = $decision;
			$feedback->comments = $comments;
			elgg_set_ignore_access($ia);
			return true;
		}
		break;
	default:
		// Save was not clicked, so don't save the form
		break;
}
forward(elgg_get_site_url() . "nihcp_credit_request_review/overview");