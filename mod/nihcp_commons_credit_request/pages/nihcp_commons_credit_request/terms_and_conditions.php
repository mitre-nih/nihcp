<?php

$params = array(
    'title' => "Terms and Conditions",
    'content' => elgg_view('commons_credit_request/terms_and_conditions'),
    'filter' => '',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page("nihcp_commons_credit_request", $body);
