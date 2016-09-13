<?php

namespace Nihcp\Entity;

// restrict access to only credit admins
nihcp_credit_admin_gatekeeper();

$ia = elgg_set_ignore_access();
$entities = elgg_get_entities_from_metadata([
	'type' => 'object',
	'subtype' => CommonsCreditAllocationFile::SUBTYPE,
	'order_by' => 'time_created desc',
]);


if($entities !== false) {
	$content = elgg_view_entity_list($entities, ['full_view' => true]);
} else {
	$content = elgg_echo("nihcp_credit_allocation:file:none");
}

elgg_set_ignore_access($ia);

$body = elgg_view_layout('one_sidebar', ['content' => $content, 'title' => elgg_echo("nihcp_credit_allocation")]);

echo elgg_view_page(elgg_echo("nihcp_credit_allocation"), $body);