<?php

use \Nihcp\Entity\CommonsCreditRequest;

$requests = elgg_get_entities([
    'type' => 'object',
    'subtype' => CommonsCreditRequest::SUBTYPE,
    'owner_guid' => elgg_get_logged_in_user_entity()->getGUID()
]);


$params = array(
    'title' => elgg_echo("nihcp_commons_credit_request"),
    'content' => elgg_view('commons_credit_request/overview', array('requests' => $requests)),
    'filter' => '',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page("nihcp_commons_credit_request", $body);