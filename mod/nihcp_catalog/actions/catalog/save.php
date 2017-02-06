<?php

$guid = get_input('guid');
$subtype = get_input('subtype');
$class = get_subtype_class('object', $subtype);
$subtype_name = elgg_echo("item:object:$subtype");

// See if we're modifying an existing catalog
if (is_numeric($guid) && elgg_entity_exists($guid)) {
	// Grab the existing Partner Notification
	$entity = get_entity($guid);
} else {
	// Create a new Partner Notification
	$entity = new $class();

	// Save the current date and the creating user
	$entity->created_on = date('n/j/Y');
	$entity->created_by = elgg_get_logged_in_user_guid();
	$entity->access_id = ACCESS_PUBLIC;
}

if ($entity->canEdit(elgg_get_logged_in_user_guid())) {
    // assign values to object
    foreach ($entity::getFields() as $field => $options) {
        // Skip the created_on & created_by & upload fields as they're handled elsewhere
        if ('created_by' === $field || 'created_on' === $field || 'upload' === $field) {
            continue;
        }

        $value = get_input($field, '');
        $entity->$field = $value;
    }

    // Attempt to save the values in the DB, let the user know if there was an issue saving
    if ($entity->save()) {
        system_message(elgg_echo("nihcp_catalog:save:success", [$subtype_name]));

        // file attachments need to be handled after the entity is saved
        $entity->saveUploadedFile();

    } else {
        register_error(elgg_echo("nihcp_catalog:save:fail", [$subtype_name]));
    }
} else {
    register_error(elgg_echo("nihcp_catalog:edit:noaccess", [$subtype_name]));
}

// Send the user back to view
forward(elgg_get_site_url()."catalog/$subtype");