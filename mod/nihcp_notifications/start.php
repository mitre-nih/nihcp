<?php

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
use Nihcp\Entity\PasswordExpiration;
use Nihcp\Entity\PostImplementationReport;

elgg_register_event_handler('init', 'system', 'nihcp_notifications_init');

//daily ccreq and helpdesk stats
$digest = new WeeklyDigest();
$ccreqStats = new CreditRequestStats();
$helpdeskStats = new HelpdeskStats();
$passwordExpiration = new PasswordExpiration();

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
	elgg_register_plugin_hook_handler('usersettings:save', 'user', 'nihcp_reset_password_age');
	elgg_register_event_handler('login:after', 'user', 'nihcp_password_expiration_reminder');
	elgg_register_plugin_hook_handler('register', 'user', 'nihcp_set_password_change_time');

	elgg_register_event_handler('submit_pir', 'object:'.PostImplementationReport::SUBTYPE, 'handle_submit_pir_notifications');
}


// set new user's password change time to today for purposes of password expiration
function nihcp_set_password_change_time($hook, $type, $return, $params) {

	$user = elgg_extract('user', $params);
	$user->password_change_time = time();
	$user->save();
}

// tell the user that their password is going to expire soon when they log in
function nihcp_password_expiration_reminder($event, $type, $user) {

	$passwordExpiration = new PasswordExpiration();
	$days_left = $passwordExpiration->get_days_left_before_password_expiration($user);

	if ($days_left <= PasswordExpiration::PASSWORD_EXPIRATION_DAYS_NOTICE_1) {
		system_message("You have $days_left before your password expires. Please change your password before then.");
	}
}

// reset user's password age when they update their password
function nihcp_reset_password_age() {
	if (!empty(get_input('password'))) {
		$user_guid = get_input('guid');

		if ($user_guid) {
			$user = get_user($user_guid);
		} else {
			$user = elgg_get_logged_in_user_entity();
		}

		$user->password_change_time = time();
	}
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


// email notification for triage coordinators when a PIR is submitted
function handle_submit_pir_notifications($event, $object_type, $object) {
	$ia = elgg_set_ignore_access();

	$pir = $object;
	$ccreq = get_entity(PostImplementationReport::getCcreqGuidFromPirGuid($pir->guid));

	$users = RoleManager::getRoleByName(RoleManager::TRIAGE_COORDINATOR)->getMembers(['limit' => 0]);

	// Title for the notification
	$subject = elgg_echo('nihcp_notifications:notify:pir:subject');

	// Message body for the notification
	$message = elgg_echo('nihcp_notifications:notify:pir:body', [$ccreq->getRequestId(), $ccreq->project_title]);

	$summary = elgg_echo('nihcp_notifications:notify:pir:body', [$ccreq->getRequestId(), $ccreq->project_title]);

	foreach($users as $user) {
		notify_user($user->getGUID(), elgg_get_site_entity()->getGUID(), $subject, $message,
			$params = ['object' => $object, 'action' => 'submit_pir', 'summary' => $summary],
			$methods_override = 'email');
	}

	elgg_set_ignore_access($ia);
}