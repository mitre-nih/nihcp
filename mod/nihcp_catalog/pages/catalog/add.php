<?php

// restrict access to only vendor admins
nihcp_role_gatekeeper(\Nihcp\Manager\RoleManager::VENDOR_ADMIN, true);

// set up form here
$subtype = get_input("subtype");
$view_vars = [];
$form_vars = ['enctype' => 'multipart/form-data'];
$content = elgg_view_form('catalog/save', $form_vars, $view_vars);
$title = elgg_echo("item:object:$subtype");
$body = elgg_view_layout('one_sidebar', ['content' => $content, 'title' => $title, 'class' => 'catalog-add']);
echo elgg_view_page($title, $body);