<?php

namespace Nihcp\Entity;
use Nihcp\Manager\RoleManager;

nihcp_role_gatekeeper(array(RoleManager::TRIAGE_COORDINATOR, RoleManager::NIH_APPROVER, RoleManager::DOMAIN_EXPERT));

$ia = elgg_set_ignore_access(true);

$request_guid = get_input('request_guid');

if($request_guid) {
    $request = get_entity($request_guid);
    if (!$request instanceof \Nihcp\Entity\CommonsCreditRequest) {
        register_error(elgg_echo('error:404:content'));
        forward('/nihcp_credit_request_review/overview');
    }
}


$content = elgg_view('nihcp_credit_request_review/review', array('request_guid' => $request_guid));
elgg_set_ignore_access($ia);

$params = array(
    'title' => elgg_echo("nihcp_credit_request_review"),
    'content' => $content,
    'filter' => '',
);

$body = elgg_view_layout('one_column', $params);

echo elgg_view_page("nihcp_credit_request_review", $body);