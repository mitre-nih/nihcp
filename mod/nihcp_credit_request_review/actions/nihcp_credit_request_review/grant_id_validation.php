<?php

if (nihcp_triage_coordinator_gatekeeper()) {
    $action = get_input('action', '', false);

    $rb_guid = htmlspecialchars(get_input('rb_guid', '', false), ENT_QUOTES, 'UTF-8');
    $request_guid = htmlspecialchars(get_input('request_guid', '', false), ENT_QUOTES, 'UTF-8');
    $is_active = get_input('is_active','',false);
    $is_active_comment = htmlspecialchars(get_input('is_active_comment', '', false), ENT_QUOTES, 'UTF-8');

    // Because we create the RiskBenefitScore entity when a domain expert is assigned,
    // this entity should already exist by this point.
    $ia = elgg_set_ignore_access();
    $req = get_entity($request_guid);
    elgg_set_ignore_access($ia);

    switch ($action) {
        case 'Save':

            $ia2 = elgg_set_ignore_access();
            $req->is_active = $is_active;
            $req->is_active_comment = $is_active_comment;
            elgg_set_ignore_access($ia2);
            break;
        default:
            // do nothing
            break;
    }

    forward(elgg_get_site_url() . "nihcp_credit_request_review/overview");

}