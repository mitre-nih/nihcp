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
 


require_once(dirname(__FILE__) . "/lib/functions.php");

use Nihcp\Entity\CommonsCreditRequestDelegation;
use Nihcp\Entity\CommonsCreditRequest;
use Nihcp\Entity\CommonsCreditAllocation;
use Nihcp\Entity\CommonsCreditAllocationFile;
use Nihcp\Entity\CommonsCreditVendor;
use Nihcp\Manager\RoleManager;
use Nihcp\Entity\WeeklyDigest;
use Nihcp\Entity\CreditRequestStats;
use Nihcp\Entity\HelpdeskStats;

elgg_register_event_handler('init', 'system', 'nihcp_notifications_init');

//daily ccreq and helpdesk stats
$digest = new WeeklyDigest();
$ccreqStats = new CreditRequestStats();
$helpdeskStats = new HelpdeskStats();

function nihcp_notifications_init() {
	elgg_register_event_handler('submit', 'object:'.CommonsCreditRequest::SUBTYPE, 'handle_submit_request_notifications');
	elgg_register_event_handler('submit', 'object:'.CommonsCreditAllocation::SUBTYPE, 'handle_submit_allocation_notifications');
	elgg_register_event_handler('decide', 'object:'.CommonsCreditRequest::SUBTYPE, 'handle_nih_decision_notifications');

	elgg_register_event_handler('risk_benefit_score_complete', 'object:'.CommonsCreditRequest::SUBTYPE, 'handle_risk_benefit_score_complete_notifications');

	elgg_register_event_handler('withdraw_decision', 'object:'.CommonsCreditRequest::SUBTYPE, 'handle_withdraw_decision_notifications');

	elgg_register_event_handler('ingest:after', 'object:'.CommonsCreditAllocationFile::SUBTYPE, 'allocations_updated_trigger_events');

	elgg_register_plugin_hook_handler('get', 'subscriptions', 'modify_ccreq_subscriptions_for_notification');

	elgg_register_event_handler('allocations_updated', 'object', 'handle_allocations_updated');
	elgg_register_plugin_hook_handler('prepare', 'notification:allocations_updated:object:'. CommonsCreditRequest::SUBTYPE, 'allocations_updated_prepare_notifications');


	elgg_register_event_handler('nihcp_email_change_attempt', 'object', 'handle_nihcp_email_change_attempt');
}

function handle_submit_request_notifications($event, $object_type, $object) {

	$ia = elgg_set_ignore_access();

	$subject = elgg_echo('nihcp_notifications:notify:submit_request:subject');
	$body = "\tTitle: {$object->project_title}\n\tCCREQ ID: {$object->getRequestId()}\n\n";
	$body .= elgg_echo('nihcp_notifications:notify:submit_request:body');



	$recipients = array();
	$recipients[] = $object->getOwnerEntity()->email;

	$delegate = CommonsCreditRequestDelegation::getDelegateForCCREQ($object->guid);

	if ($delegate) {
		$recipients[] = $delegate->email;
	}

	elgg_set_ignore_access($ia);

	foreach ($recipients as $to) {
		nihcp_notifications_send_email($to, $subject, $body);
	}

}
// the event should come with an array of CommonsCreditRequestAllocation entities associated with a particular ccreq
function handle_submit_allocation_notifications($event, $object_type, $object) {

	if (empty($object)) {
		register_error("Error sending notification.");
		return false;
	}

	$ia = elgg_set_ignore_access();

	$ccreq_guid = $object[0]->getRequestGUID();
	$ccreq_entity = get_entity($ccreq_guid);

	$subject = elgg_echo('nihcp_notifications:notify:submit_allocations:subject');
	$body = elgg_echo('nihcp_notifications:notify:submit_allocations:body');
	$body .= " \r\n\r\n";

	$body .= "\tTitle: {$ccreq_entity->project_title}\n\tCCREQ ID: {$ccreq_entity->getRequestId()}\n";

	foreach ($object as $allocation) {
		$vendor_name = CommonsCreditVendor::getByVendorId($allocation->vendor)->getDisplayName();

		$body .= "\t\t$vendor_name:\t$allocation->credit_allocated\n";
	}

	$recipients = array();
	$recipients[] = $ccreq_entity->getOwnerEntity()->email;

	$delegate = CommonsCreditRequestDelegation::getDelegateForCCREQ($ccreq_guid);

	if ($delegate) {
		$recipients[] = $delegate->email;
	}

	elgg_set_ignore_access($ia);

	foreach ($recipients as $to) {
		nihcp_notifications_send_email($to, $subject, $body);
	}

}

function handle_nih_decision_notifications($event, $object_type, $object) {

	$ia = elgg_set_ignore_access();

	$owner = $object->getOwnerEntity();
	$subject = elgg_echo('nihcp_notifications:notify:decide:subject', [$object->status]);
	$body = elgg_echo('nihcp_notifications:notify:decide:body', [$object->getRequestId(), $object->project_title, strtolower($object->status)]);

	$recipients = array();
	$recipients[] = $object->getOwnerEntity()->email;

	$delegate = CommonsCreditRequestDelegation::getDelegateForCCREQ($object->guid);

	if ($delegate) {
		$recipients[] = $delegate->email;
	}

	elgg_set_ignore_access($ia);

	foreach ($recipients as $to) {
		nihcp_notifications_send_email($to, $subject, $body);
	}
}

function handle_risk_benefit_score_complete_notifications($event, $object_type, $object) {
	$ia = elgg_set_ignore_access();

	$users = RoleManager::getRoleByName(RoleManager::TRIAGE_COORDINATOR)->getMembers(['limit' => 0]);

	//$rbs_entities = \Nihcp\Entity\RiskBenefitScore::getRiskBenefitScoreEntitiesForRequest($object->getGUID());

	// Title for the notification
	$subject = elgg_echo('nihcp_notifications:notify:risk_benefit_score_complete:subject');

	// Message body for the notification
	$message = elgg_echo('nihcp_notifications:notify:risk_benefit_score_complete:body', [$object->getRequestId()]);

	$summary = elgg_echo('nihcp_notifications:notify:risk_benefit_score_complete:summary', array($object->getRequestId()));

	foreach($users as $user) {
		notify_user($user->getGUID(), elgg_get_site_entity()->getGUID(), $subject, $message,
			$params = ['object' => $object, 'action' => 'risk_benefit_score_complete', 'summary' => $summary],
			$methods_override = 'email');
	}

	elgg_set_ignore_access($ia);
}

function handle_withdraw_decision_notifications($event, $object_type, $object) {
	$ia = elgg_set_ignore_access();

	$users = RoleManager::getRoleByName(RoleManager::TRIAGE_COORDINATOR)->getMembers(['limit' => 0]);
	$feedback = \Nihcp\Entity\Feedback::getFeedback($object->getGUID());
	$users[] = $feedback->getOwnerEntity();

	$status_change = $feedback->getStatusChange();

	// Title for the notification
	$subject = elgg_echo('nihcp_notifications:notify:withdraw_decision:subject');

	// Message body for the notification
	$message = elgg_echo('nihcp_notifications:notify:withdraw_decision:body', array(
		$object->project_title,
		$object->getRequestId(),
		$feedback->decision,
		$object->status,
		$status_change->reason,
	));

	$summary = elgg_echo('nihcp_notifications:notify:withdraw_decision:summary', array($object->getRequestId()));

	foreach($users as $user) {
		elgg_log('Notifying '.$user->getDisplayName().' of withdrawn decision');
		notify_user($user->getGUID(), elgg_get_site_entity()->getGUID(), $subject, $message,
			$params = ['object' => $object, 'action' => 'withdraw_decision', 'summary' => $summary],
			$methods_override = 'email');
	}

	elgg_set_ignore_access($ia);
}

function allocations_updated_trigger_events($event, $object_type, $object) {
	$request_guids = $object['requests'];
	$ia = elgg_set_ignore_access();
	foreach($request_guids as $request_guid) {
		$request = get_entity($request_guid);
		elgg_trigger_event('allocations_updated', 'object', $request);
	}
	elgg_set_ignore_access($ia);
}

function modify_ccreq_subscriptions_for_notification($hook, $type, $subscriptions, $params) {
	if(in_array($params['event']->getAction(), ["allocations_updated"])) {
		$entity = $params['event']->getObject();
		$subscriptions[$entity->getOwnerGUID()] = ['email'];
	}
	return $subscriptions;
}

function handle_allocations_updated($event, $object_type, $object) {
	$owner = $object->getOwnerEntity();

	$ia = elgg_set_ignore_access();

	$subject = elgg_echo('nihcp_notifications:notify:allocations_updated:subject');
	$body = elgg_echo('nihcp_notifications:notify:allocations_updated:body') . "\n";

	$new_allocations = CommonsCreditAllocation::getAllocations($object->getGUID());
	if (!empty($new_allocations)) {
		$body .= "\tTitle: {$object->project_title}\n\tCCREQ ID: {$object->getRequestId()}\n";
	}

	foreach ($new_allocations as $allocation) {
		$vendor_name = CommonsCreditVendor::getByVendorId($allocation->vendor)->getDisplayName();

		$body .= "\t\t$vendor_name:\t$allocation->credit_remaining\n";
	}

	$recipients = array();
	$recipients[] = $owner->email;

	$delegate = CommonsCreditRequestDelegation::getDelegateForCCREQ($object->guid);

	if ($delegate) {
		$recipients[] = $delegate->email;
	}

	elgg_set_ignore_access($ia);

	foreach ($recipients as $to) {
		nihcp_notifications_send_email($to, $subject, $body);

	}

}

// object passed in should be the user for which email change is attempted
function handle_nihcp_email_change_attempt($event, $object_type, $object) {
	if (empty($object)) {
		return false;
	}

	nihcp_notifications_send_email($object->email, elgg_echo('nihcp_notifications:email_change:subject'), elgg_echo('nihcp_notifications:email_change:body'));

}
