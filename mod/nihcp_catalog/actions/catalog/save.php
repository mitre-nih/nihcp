<?php
/*
Copyright 2017 The MITRE Corporation
 
This software was written for the NIH Commons Credit Portal. General questions 
can be forwarded to:

opensource@mitre.org

Technology Transfer Office
The MITRE Corporation
7515 Colshire Drive
McLean, VA 22102-7539

Permission is hereby granted, free of charge, to any person obtaining a copy 
of this software and associated documentation files (the "Software"), to deal 
in the Software without restriction, including without limitation the rights 
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
 


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