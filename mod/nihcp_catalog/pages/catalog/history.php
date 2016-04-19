<?php

// restrict access to only vendor admins
nihcp_role_gatekeeper(\Nihcp\Manager\RoleManager::VENDOR_ADMIN, true);

$subtype = get_input("subtype");

$entities = elgg_get_entities_from_metadata([
	'type' => 'object',
	'subtype' => $subtype,
	'order_by' => 'time_created desc',
]);

$subtype_name = elgg_echo("item:object:$subtype");

if($entities !== false) {
	$content = elgg_view_entity_list($entities, ['full_view' => true]);
} else {
	$content = elgg_echo("nihcp_catalog:none", [$subtype_name]);
}

$body = elgg_view_layout('one_sidebar', ['content' => $content, 'title' => $subtype_name, 'class' => 'catalog-history']);

echo elgg_view_page($subtype_name, $body);