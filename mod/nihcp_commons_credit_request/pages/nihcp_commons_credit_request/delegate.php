<?php

use \Nihcp\Entity\CommonsCreditRequest;
use \Nihcp\Entity\CommonsCreditRequestDelegation;
use Nihcp\Manager\RoleManager;

nihcp_role_gatekeeper(array(RoleManager::INVESTIGATOR, RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR, RoleManager::DOMAIN_EXPERT));
$guid = get_input('request_guid');
$current_request = get_entity($guid);

// $current_request should be empty if current user is not the owner or admin
if (empty($current_request) || !($current_request instanceof CommonsCreditRequest)) {
    register_error(elgg_echo("nihcp_groups:role:gatekeeper"));
    forward(REFERER);
} else {

    // if no delegation exists yet, show the form for adding one
    // else show the existing delegation
    $delegation = CommonsCreditRequestDelegation::getDelegationForCCREQ($guid);

    $body_vars = ['current_request' => $current_request, 'delegation' => $delegation];
    if (empty($delegation)) {
        $content = elgg_view_form('delegate', null, $body_vars);
    } else {
        $content = elgg_view('commons_credit_request/delegate', $body_vars);
    }

    $params = array(
        'title' => elgg_echo("nihcp_commons_credit_request:delegate"),
        'content' => $content,
        'filter' => '',
    );

    $body = elgg_view_layout('content', $params);

    echo elgg_view_page("nihcp_commons_credit_request", $body);

}


