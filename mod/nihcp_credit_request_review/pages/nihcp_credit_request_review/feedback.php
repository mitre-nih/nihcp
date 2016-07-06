<?php

namespace Nihcp\Entity;

use Nihcp\Manager\RoleManager;


// guid of ccreq under review
$request_guid = get_input('request_guid');

$request_entity = get_entity($request_guid);

// NIH Approvers, Triage Coordinators, and the Investigator that submitted the request
// should get access to the NIH Feedback for a specific ccreq
if ( nihcp_role_gatekeeper(array(RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR), false)
    || (!empty($request_entity) && $request_entity->getOwnerGUID() == elgg_get_logged_in_user_guid()) ) {


    $ia = elgg_set_ignore_access(true);

    $feedback_guid = Feedback::getFeedback($request_guid);
    $content = elgg_view_entity(get_entity($feedback_guid));

    elgg_set_ignore_access($ia);

    $params = array(
        'title' => elgg_echo("nihcp_credit_request_review"),
        'content' => $content,
        'filter' => ''
    );


    $body = elgg_view_layout('one_column', $params);

    echo elgg_view_page("nihcp_credit_request_review", $body);

} else {
    register_error(elgg_echo("nihcp_groups:role:gatekeeper"));
    forward(REFERER);
}