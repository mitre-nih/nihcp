<?php

use Nihcp\Manager\RoleManager;
nihcp_role_gatekeeper(array(RoleManager::INVESTIGATOR, RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR, RoleManager::DOMAIN_EXPERT));

$guid = get_input('request_guid');

$ia = elgg_set_ignore_access();
$current_request = get_entity($guid);
elgg_set_ignore_access($ia);

if(!($current_request instanceof \Nihcp\Entity\CommonsCreditRequest) || !$current_request->isDraftEditable()) {
	register_error(elgg_echo('error:404:content'));
	forward('/nihcp_commons_credit_request/overview');
}

$content = elgg_view_form('confirm', null, ['current_request' => $current_request]);

$params = array(
    'title' => elgg_echo("nihcp_commons_credit_request"),
    'content' => $content,
    'filter' => '',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page(elgg_echo("nihcp_commons_credit_request"), $body);