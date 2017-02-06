<?php

use \Nihcp\Entity\CommonsCreditRequest;
use \Nihcp\Entity\CommonsCreditRequestDelegation;

$delegation_guid = get_input('delegation_guid');

// check that logged in user is actually the intended delegate
$ia = elgg_set_ignore_access();
$delegation = get_entity($delegation_guid);
$delegate = get_user_by_email($delegation->getDelegateEmail())[0];
$is_delegate = !empty($delegate)
    && (elgg_get_logged_in_user_guid() === $delegate->getGUID());
$is_pending = !empty($delegation && ($delegation->getStatus() === CommonsCreditRequestDelegation::DELEGATION_PENDING_STATUS));
elgg_set_ignore_access($ia);

if (!$is_delegate || !$is_pending) {
    return false;
}

$action = get_input('action');
$ia = elgg_set_ignore_access();
switch ($action) {
    case  elgg_echo("nihcp_commons_credit_request:delegate:request:accept"):
        $ia = elgg_set_ignore_access();
        $ccreq = CommonsCreditRequestDelegation::getCCREQForDelegation($delegation->getGUID());
        if ($ccreq->status === CommonsCreditRequest::DRAFT_STATUS) {
            $delegation->setStatus(CommonsCreditRequestDelegation::DELEGATION_DELEGATED_STATUS);
        } else {
            $delegation->setStatus(CommonsCreditRequestDelegation::DELEGATION_SUBMITTED_STATUS);
        }
        add_entity_relationship($delegation->getGUID(), CommonsCreditRequestDelegation::RELATIONSHIP_DELEGATION_DELEGATED_TO, $delegate->getGUID());
        add_entity_relationship($ccreq->getGUID(), CommonsCreditRequestDelegation::RELATIONSHIP_CCREQ_TO_DELEGATE, $delegate->getGUID());
        elgg_log("Delegate with email " . $delegate->email . " has accepted delegation.");
        elgg_add_subscription(elgg_get_logged_in_user_guid(), 'email', $ccreq->getGUID());
        system_message("You have accepted delegation.");
        elgg_set_ignore_access($ia);
        break;
    case  elgg_echo("nihcp_commons_credit_request:delegate:request:decline"):
        $delegation->setStatus(CommonsCreditRequestDelegation::DELEGATION_DECLINED_STATUS);
        elgg_log("Delegate with email " . $delegate->email . " has declined delegation.");
        system_message("You have declined delegation.");
        break;
    default:
        break;
}
elgg_set_ignore_access($ia);
forward(elgg_get_site_url());
