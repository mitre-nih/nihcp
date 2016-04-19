<?php

$guid = get_input('request_guid');
$form_vars = array(
    'enctype' => 'multipart/form-data',
    'id' => 'ccreq-form'
);

$body_vars = array(
    'request_guid' => $guid
);

$request = get_entity($guid);

// allow form editing only if new request or in draft state.
if ($request->status === "Draft" || !$request->status) {

    $content = elgg_view_form('request', $form_vars, $body_vars);
} else {
    $content = elgg_view('commons_credit_request/submitted_request', $body_vars);
}


$params = array(
    'title' => elgg_echo("nihcp_commons_credit_request"),
    'content' => $content,
    'filter' => '',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page("nihcp_commons_credit_request", $body);


