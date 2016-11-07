<?php

namespace Nihcp\Entity;

use Nihcp\Manager\RoleManager;

nihcp_role_gatekeeper(array(RoleManager::DOMAIN_EXPERT, RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR));

$ia = elgg_set_ignore_access(true);
// guid of ccreq under review
$request_guid = get_input('request_guid');
$request_entity = get_entity($request_guid);
$alignment_commons_credits_objectives_guid = AlignmentCommonsCreditsObjectives::getFromRequestGuid($request_guid);
$alignment_commons_credits_objectives_entity = get_entity($alignment_commons_credits_objectives_guid);

// editable view only for Triage Coordinators and available only while a review is not fully completed.
if (nihcp_triage_coordinator_gatekeeper(false) && $request_entity->isEditable()) {

    $content = elgg_view_form('align-cc-obj', null, array(
        'request_guid' => $request_guid,
        'alignment_commons_credits_objectives_guid' => $alignment_commons_credits_objectives_guid));

// if there is an entity, show the non-editable object view
// Domain Experts should see reviews for requests they have been assigned
// Triage Coordinators and NIH Approvers should see reviews for completed reviews
} else if ( (nihcp_domain_expert_gatekeeper(false) && RiskBenefitScore::isDomainExpertAssignedToRequest(elgg_get_logged_in_user_entity(), $request_guid))
                    || (nihcp_role_gatekeeper(array(RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR), false) && $request_entity->isComplete()) ) {

    if (empty($alignment_commons_credits_objectives_entity)) {
        $content = "No review was made.";
    } else {
        $content = elgg_view_entity($alignment_commons_credits_objectives_entity);
    }
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
    'class' => 'ccr-align-cc-obj'
);

$body = elgg_view_layout('one_column', $params);

echo elgg_view_page("nihcp_credit_request_review", $body);

