<?php

namespace Nihcp\Entity;

use Nihcp\Manager\RoleManager;

nihcp_role_gatekeeper(array(RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR));

$ia = elgg_set_ignore_access(true);

$request_guid = get_input('request_guid');
$request_entity = get_entity($request_guid);

$final_recommendation_entity = get_entity(FinalRecommendation::getFinalRecommendation($request_guid));

// editable view only for Triage Coordinators and available only while a review is not fully completed.
if ( nihcp_triage_coordinator_gatekeeper(false) && $request_entity->isEditable() ) {
    $content = elgg_view_form('final-recommendation', null, array('request_guid' => $request_guid));

// if an review entity exists, show the non-editable view for:
// TCs and NAs if the review is in completed state
} else if ( !empty($final_recommendation_entity) && $request_entity->isComplete() ) {
    $content = elgg_view_entity($final_recommendation_entity);
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

echo elgg_view_page("nihcp_credit_request_review", $body);
