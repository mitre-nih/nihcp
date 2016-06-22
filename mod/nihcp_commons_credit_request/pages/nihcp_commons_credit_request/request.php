<?php

use Nihcp\Manager\RoleManager;

$guid = get_input('request_guid');

$ia = elgg_get_ignore_access();

if (nihcp_role_gatekeeper(array(RoleManager::TRIAGE_COORDINATOR, RoleManager::NIH_APPROVER, RoleManager::DOMAIN_EXPERT), false)) {
    $ia = elgg_set_ignore_access(true);
}

if($guid) {
	$request = get_entity($guid);
	if (!$request instanceof \Nihcp\Entity\CommonsCreditRequest) {
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
if ((empty($request) || $request->status === "Draft" || !$request->status) && nihcp_investigator_gatekeeper(false)) {
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

echo elgg_view_page("nihcp_commons_credit_request", $body);


