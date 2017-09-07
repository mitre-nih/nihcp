<?php

namespace Nihcp\Entity;

use Nihcp\Manager\RoleManager;

nihcp_role_gatekeeper(array(RoleManager::DOMAIN_EXPERT, RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR));



$ia = elgg_set_ignore_access(true);
// guid of ccreq under review
$request_guid = get_input('request_guid');
$request_entity = get_entity($request_guid);

$review_class = get_input('review_class');
$review_class_text = '';
$gs_guid = null;
switch($review_class) {
    case 'datasets':
        $gs_guid = \Nihcp\Entity\GeneralScore::getGeneralScoreDatasetsFromRequestGuid($request_guid);
        break;
    case 'applications_tools':
        $gs_guid = \Nihcp\Entity\GeneralScore::getGeneralScoreAppsToolsFromRequestGuid($request_guid);
        break;
    case 'workflows':
        $gs_guid = \Nihcp\Entity\GeneralScore::getGeneralScoreWorkflowsFromRequestGuid($request_guid);
        break;
    default:
        break;
}

$gs_entity = get_entity($gs_guid);


// editable view only for Triage Coordinators and available only while a review is not fully completed.
if (nihcp_triage_coordinator_gatekeeper(false) && $request_entity->isEditable()) {

    $content = elgg_view_form('general-score', null, array(
        'request_guid' => $request_guid,));

// if there is an entity, show the non-editable object view
// Domain Experts should see reviews for requests they have been assigned
// Triage Coordinators and NIH Approvers should see reviews for completed reviews
} else if (!empty($gs_entity)
    && ( (nihcp_domain_expert_gatekeeper(false) && RiskBenefitScore::isDomainExpertAssignedToRequest(elgg_get_logged_in_user_entity(), $request_guid))
        || (nihcp_role_gatekeeper(array(RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR), false) && $request_entity->isComplete()) )
) {

    $content = elgg_view_entity($gs_entity);
} else if ($request_entity->isComplete()) {
    $content = elgg_echo("nihcp_credit_request_review:no_review");
} else {

    $content = elgg_echo("nihcp_credit_request_review:no_access");
}

$params = array(
    'title' => elgg_echo("nihcp_credit_request_review"),
    'content' => $content,
    'filter' => '',
);

$body = elgg_view_layout('one_column', $params);

elgg_set_ignore_access($ia);
echo elgg_view_page(elgg_echo("nihcp_credit_request_review"), $body);
