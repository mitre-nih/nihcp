<?php

namespace Nihcp\Entity;

use Nihcp\Manager\RoleManager;


nihcp_role_gatekeeper(array(RoleManager::DOMAIN_EXPERT, RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR));

$ia = elgg_set_ignore_access(true);

$request_guid = get_input('request_guid');
$rb_guid = get_input('rb_guid');

$request_entity = get_entity($request_guid);
$rb_entity = get_entity($rb_guid);

// DEs should only be able to see/edit reviews they have been assigned.
if ( ( nihcp_domain_expert_gatekeeper(false) && RiskBenefitScore::isDomainExpertAssignedToRequest(elgg_get_logged_in_user_entity(), $request_guid) )
        || (nihcp_role_gatekeeper(array(RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR), false)) ) {

// editable only be DEs and only when request review is not in completed state
    if (nihcp_domain_expert_gatekeeper(false) && $request_entity->isEditable()) {
        $content = elgg_view_form('risk-benefit-score', null, array(
            'request_guid' => $request_guid,
            'rb_guid' => $rb_guid));


// show summary view for TCs if entity is not empty
// or for NAs and DEs after TC completely finishes review.
    } else if ( !empty($rb_entity) && (nihcp_triage_coordinator_gatekeeper(false) || $request_entity->isComplete()) ) {
        $content = elgg_view_entity($rb_entity);
    } else if ($request_entity->isComplete()) {
        $content = elgg_echo("nihcp_credit_request_review:no_review");
    } else {

        $content = elgg_echo("nihcp_credit_request_review:no_access");
    }

    elgg_set_ignore_access($ia);

    $params = array(
        'title' => elgg_echo("nihcp_credit_request_review"),
        'content' => $content,
        'filter' => '',
    );

    $body = elgg_view_layout('one_column', $params);

    echo elgg_view_page(elgg_echo("nihcp_credit_request_review"), $body);
} else {
    register_error(elgg_echo("nihcp_groups:role:gatekeeper"));
    forward(REFERER);
}
