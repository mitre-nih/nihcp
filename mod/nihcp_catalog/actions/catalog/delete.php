<?php
$guid = get_input('guid');
$entity = get_entity($guid);
$subtype = $entity::SUBTYPE;
$subtype_name = elgg_echo("item:object:$subtype");

if (($entity) && ($entity->canEdit())) {
	if ($entity->delete()) {
		system_message(elgg_echo('nihcp_catalog:delete:success', [$guid]));
	} else {
		register_error(elgg_echo('nihcp_catalog:delete:fail', [$guid]));
	}
} else {
	register_error(elgg_echo('nihcp_catalog:delete:fail', [$guid]));
}

// Send the user back to view
forward(elgg_get_site_url()."catalog/$subtype");