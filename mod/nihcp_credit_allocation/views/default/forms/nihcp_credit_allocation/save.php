<?php

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
            echo "<div>{$field_vars['label']}<br>" . elgg_view($field_vars['type'], $field_vars) . '</div>';
    }
}

echo '<div>' . elgg_view('input/submit', ['value' => 'Save']) . '</div>';
