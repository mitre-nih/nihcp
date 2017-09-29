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

$entity = new CommonsCreditAllocationFile();

echo "<div>Upload a CSV file for Remaining Credit Allocations summary. File should have the following columns:</div>";
echo "<div>Account Holder Name,CCREQ ID,Vendor ID,Cloud Account ID,Remaining Credits,Initial Credits</div>";


foreach ($entity::getFields() as $field => $field_vars) {
    $field_vars['name'] = $field;

    // If we've got an existing entity, pre-populate the existing field values
    if (isset($entity)) {
        $field_vars['value'] = $entity->$field;
    }

    //Echo out each field
    switch ($field_vars['type']) {
        case 'input/checkbox':
            //Prevents checkbox from being labeled twice
            $field_vars['value'] = false;
            $field_vars['checked'] = ($entity->$field) ? true : false;
            echo '<div>' . elgg_view($field_vars['type'], $field_vars);

            // If help text exists, echo the help text inside of the main field div
            if ($field_vars['help_text']) {
                echo "<div class='help-text'>{$field_vars['help_text']}</div>";
            }

            // Close the main field div
            echo '</div>';
            break;
        case 'input/hidden':
            //Spit out the hidden input field but not any visible elements
            echo elgg_view($field_vars['type'], $field_vars);
            break;
        default:
            //doing a convoluted copy/unset because having a duplicate 'type' in the field_vars makes input/file blow up
            //not sure if that's a bugg (elgg bug) or not yet.  tmp fix. oh yeah should mark this #TODO
            //this is the same fix as the catalog/save
            $attrs = $field_vars;
            unset($attrs['type']);
            //echo "<div>{$field_vars['label']}<br>" . elgg_view($field_vars['type'], $field_vars) . '</div>';
            echo "<div>{$field_vars['label']}<br>" . elgg_view($field_vars['type'], $attrs) . '</div>';
    }
}

echo '<div>' . elgg_view('input/submit', ['value' => 'Submit']) . '</div>';
