<?php

use \Nihcp\Entity\CommonsCreditRequest;
use \Nihcp\Entity\CommonsCreditRequestDelegation;

nihcp_investigator_gatekeeper();

$ccreq_guid = get_input('request_guid');
$ccreq = get_entity($ccreq_guid);

$delegation_guid = get_input('delegation_guid');

// check that logged in user is actually the intended delegate
// and check for delegation request is pending
$ia = elgg_set_ignore_access();
$delegation = get_entity($delegation_guid);
if (!empty($delegation)) {
    $delegate = get_user_by_email($delegation->getDelegateEmail())[0];
}
$is_delegate = !empty($delegate)
                && (elgg_get_logged_in_user_guid() === $delegate->getGUID());
$is_pending = !empty($delegation && ($delegation->getStatus() === CommonsCreditRequestDelegation::DELEGATION_PENDING_STATUS));
elgg_set_ignore_access($ia);

if(!$is_delegate || !$is_pending) {
    register_error(elgg_echo('error:404:content'));
    forward('/nihcp_commons_credit_request/overview');
} else {

    $content = elgg_view_form('delegate_request', null, ['delegation' => $delegation]);

    $params = array(
        'title' => elgg_echo("nihcp_commons_credit_request:delegate"),
        'content' => $content,
        'filter' => '',
    );

    $body = elgg_view_layout('content', $params);

    echo elgg_view_page("nihcp_commons_credit_request", $body);
}