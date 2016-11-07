<?php
use Nihcp\Entity\CommonsCreditRequest;

$guid = get_input('request_guid');
$status = get_input('status');
$reason = get_input('reason');

if($guid && $status && in_array($status, [CommonsCreditRequest::SUBMITTED_STATUS, CommonsCreditRequest::COMPLETED_STATUS])) {
	$request = get_entity($guid);
	if(!$request instanceof CommonsCreditRequest) {
		return false;
	}
	$decided_statuses = [CommonsCreditRequest::APPROVED_STATUS, CommonsCreditRequest::DENIED_STATUS];
	$prev_status = $request->status;
	$status_change = $request->changeStatus($status, $reason);
	if(in_array($prev_status, $decided_statuses) && $prev_status !== $request->status) {
		error_log('Withdrawing decision for request '.$request->getRequestId());
		$feedback = \Nihcp\Entity\Feedback::getFeedback($guid);
		add_entity_relationship($feedback->getGUID(), \Nihcp\Entity\CommonsCreditStatusChange::RELATIONSHIP_FEEDBACK_TO_STATUS_CHANGE, $status_change);
		elgg_trigger_event('withdraw_decision', 'object:'.CommonsCreditRequest::SUBTYPE, $request);
	}
	return true;
}
return false;