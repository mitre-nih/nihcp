<?php

$content = elgg_view_form('submission', null, []);

$params = array(
    'title' => elgg_echo("nihcp_pir"),
    'content' => $content,
    'filter' => '',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page("nihcp_pir", $body);