<?php

namespace Nihcp\Entity;
use Nihcp\Manager\RoleManager;


$request_guid = get_input("request_guid");
$vendor_guid = get_input("vendor_guid");


if (!$request_guid || !$vendor_guid) {
    register_error(elgg_echo('error:404:content'));
    forward(REFERER);
}


// restrict access to only submitting investigator, delegate, credit admins, NIH approvers, triage coordinators
if (CommonsCreditRequest::hasAccess($request_guid) || nihcp_role_gatekeeper(array(RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR, RoleManager::CREDIT_ADMIN), false)) {


    $content = elgg_view('nihcp_credit_allocation/balance_history', array("request_guid" => $request_guid, "vendor_guid" => $vendor_guid));

    $body = elgg_view_layout('one_sidebar', ['content' => $content, 'title' => elgg_echo("nihcp_credit_allocation")]);

    echo elgg_view_page(elgg_echo("nihcp_credit_allocation"), $body);
} else {
    register_error(elgg_echo("nihcp_groups:role:gatekeeper"));
    forward(REFERER);
}
