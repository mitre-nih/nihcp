<?php

namespace Nihcp\Entity;


$cca_file = new CommonsCreditAllocationFile();

// Save the current date and the creating user
$cca_file->created_on = date('n/j/Y');
$cca_file->created_by = elgg_get_logged_in_user_guid();


// assign values to object
foreach ($cca_file::getFields() as $field => $options) {
    // Skip the created_on & created_by & upload fields as they're handled elsewhere
    if ('created_by' === $field || 'created_on' === $field || 'upload' === $field) {
        continue;
    }

    $value = get_input($field, '');
    $cca_file->$field = $value;
}

// Attempt to save the values in the DB, let the user know if there was an issue saving
$save_success = $cca_file->save();
if ($save_success && $cca_file->saveUploadedFile() > 0) { // greater than 0 means success, -1 for errors
    system_message(elgg_echo("nihcp_catalog:save:success", [$_FILES['upload']['name']]));

} else {
    if ($save_success) {
        $cca_file->delete();
    }
    register_error(elgg_echo("nihcp_catalog:save:fail", [$_FILES['upload']['name']]));
}


// Send the user back to view
forward(elgg_get_site_url()."nihcp_credit_allocation/allocations");