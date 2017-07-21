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