<?php

$guid = get_input('request_guid');
$current_request = get_entity($guid);

if(!$current_request instanceof \Nihcp\Entity\CommonsCreditRequest) {
	register_error(elgg_echo('error:404:content'));
	forward('/nihcp_commons_credit_request/overview');
}

$content = elgg_view_form('confirm', null, ['current_request' => $current_request]);

$params = array(
    'title' => elgg_echo("nihcp_commons_credit_request"),
    'content' => $content,
    'filter' => '',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page("nihcp_commons_credit_request", $body);