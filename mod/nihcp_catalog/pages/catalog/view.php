<?php
use Nihcp\Entity\Equivalency;

$subtype = get_input("subtype");

$entity = elgg_get_entities_from_metadata([
	'type' => 'object',
	'subtype' => $subtype,
	'order_by' => 'time_created desc',
	'limit' => 1,
])[0];

$subtype_name = elgg_echo("item:object:$subtype");

if($entity === null) {
	$content = elgg_echo("nihcp_catalog:none", [$subtype_name]);
} else {
	$content = elgg_view_entity($entity, ['attachment_view' => true]);
}

$body = elgg_view_layout('one_sidebar', ['content' => $content, 'title' => $subtype_name, 'class' => 'catalog-page']);

echo elgg_view_page($subtype_name, $body);
