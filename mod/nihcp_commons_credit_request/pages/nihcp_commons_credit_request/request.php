<?php

use \Nihcp\Manager\RoleManager;
use \Nihcp\Entity\CommonsCreditRequest;
use \Nihcp\Entity\CommonsCreditRequestDelegation;

nihcp_role_gatekeeper(array(RoleManager::INVESTIGATOR, RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR, RoleManager::DOMAIN_EXPERT));

$guid = get_input('request_guid');

$ia = elgg_get_ignore_access();

$delegate = CommonsCreditRequestDelegation::getDelegateForCCREQ($guid);

// check to see if reviewer or delegate
if (nihcp_role_gatekeeper(array(RoleManager::TRIAGE_COORDINATOR, RoleManager::NIH_APPROVER, RoleManager::DOMAIN_EXPERT), false)
	|| (!empty($delegate) && ($delegate->guid === elgg_get_logged_in_user_guid()))) {
    $ia = elgg_set_ignore_access(true);
}

if($guid) {
	$request = get_entity($guid); // this should only return a value if ignore access succeeded above, or user is the owner
	if (!($request instanceof CommonsCreditRequest)) {
		register_error(elgg_echo('error:404:content'));
		forward('/nihcp_commons_credit_request/overview');
	}
}

$form_vars = array(
	'enctype' => 'multipart/form-data',
	'id' => 'ccreq-form'
);

$body_vars = array(
	'current_request' => $request
);

// allow form editing only if new request or in draft state.
// allow only delegate to edit if delegation is in "delegated" state
// allow only PI to edit if delegation is in
if ((empty($request)
		|| (($request->status === "Draft" || !$request->status) && $request->isDraftEditable()))
		&& nihcp_investigator_gatekeeper(false)) {
    $content = elgg_view_form('request', $form_vars, $body_vars);
} else {
    $content = elgg_view('commons_credit_request/submitted_request', $body_vars);
}

elgg_set_ignore_access($ia);
$params = array(
    'title' => elgg_echo("nihcp_commons_credit_request"),
    'content' => $content,
    'filter' => '',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page(elgg_echo("nihcp_commons_credit_request"), $body);


