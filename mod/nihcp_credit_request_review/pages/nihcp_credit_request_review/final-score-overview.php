<?php

namespace Nihcp\Entity;

use Nihcp\Manager\RoleManager;

nihcp_role_gatekeeper(array(RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR));

$request_guid = get_input('request_guid');


$ia = elgg_set_ignore_access(true);

$request_entity = get_entity($request_guid);

// NAs should only see this page if the review is complete
if ( nihcp_triage_coordinator_gatekeeper(false) || (nihcp_nih_approver_gatekeeper(false) && $request_entity->isComplete() && FinalScore::hasFinalScores($request_guid)) ) {
    $content = elgg_view('nihcp_credit_request_review/final-score-overview', array('request_guid' => $request_guid));
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
