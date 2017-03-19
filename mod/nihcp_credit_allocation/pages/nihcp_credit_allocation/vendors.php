<?php

nihcp_vendor_admin_gatekeeper();

$content = elgg_view_form("nihcp_credit_allocation/manage_vendors");
$title = elgg_echo('nihcp_credit_allocation:widgets:manage_vendors:title');

$body = elgg_view_layout('one_sidebar', ['content' => $content, 'title' => $title]);
echo elgg_view_page($title, $body);