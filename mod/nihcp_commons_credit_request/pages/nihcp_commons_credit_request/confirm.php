<?php

$guid = get_input('request_guid');

$content = elgg_view_form('confirm', ['request_guid' => $guid]);

$params = array(
    'title' => elgg_echo("nihcp_commons_credit_request"),
    'content' => $content,
    'filter' => '',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page("nihcp_commons_credit_request", $body);