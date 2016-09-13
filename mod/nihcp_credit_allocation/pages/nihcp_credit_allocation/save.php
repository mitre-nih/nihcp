<?php

// restrict access to only credit admins
nihcp_credit_admin_gatekeeper();

// set up form here
$view_vars = [];
$form_vars = ['enctype' => 'multipart/form-data'];
$content = elgg_view_form('nihcp_credit_allocation/save', $form_vars, $view_vars);
$title = elgg_echo("nihcp_credit_allocation");
$body = elgg_view_layout('one_sidebar', ['content' => $content, 'title' => $title]);
echo elgg_view_page($title, $body);