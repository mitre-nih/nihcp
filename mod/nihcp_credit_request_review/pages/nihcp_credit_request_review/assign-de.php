<?php

nihcp_triage_coordinator_gatekeeper();

$ia = elgg_set_ignore_access(true);
// guid of ccreq under review
$request_guid = get_input('request_guid');
$request = get_entity($request_guid);
elgg_set_ignore_access($ia);


if ($request->isEditable()) {
    $params = array(
        'title' => elgg_echo("nihcp_credit_request_review"),
        'content' => elgg_view_form('assign-de', null, array(
            'request_guid' => $request_guid,
        )),
        'filter' => '',
    );

    $body = elgg_view_layout('one_column', $params);

    echo elgg_view_page("nihcp_credit_request_review", $body);
} else {
    register_error(elgg_echo("nihcp_groups:role:gatekeeper"));
    forward(REFERER);
}
