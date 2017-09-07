<?php

elgg_push_context('catalog-save');
$guid = get_input('guid');

// See if we're modifying an existing catalog
if (is_numeric($guid) && elgg_entity_exists($guid)) {
	$entity = get_entity($guid);
	$subtype = $entity::SUBTYPE;
} else {
	$subtype = get_input('subtype');
	$catalog_subtypes = [\Nihcp\Entity\Equivalency::SUBTYPE, \Nihcp\Entity\Glossary::SUBTYPE, \Nihcp\Entity\Services::SUBTYPE];
	if(!in_array($subtype, $catalog_subtypes)) {
		register_error(elgg_echo('nihcp_catalog:save:fail'));
		forward(REFERRER);
	}
	$class = get_subtype_class('object', $subtype);
	$entity = new $class();
}

$subtype_name = elgg_echo("item:object:$subtype");

// Register Top Right Menu Buttons
$delete_button = [
	'name' => 'delete-catalog',
	'text' => elgg_echo("nihcp_catalog:button:delete", [$subtype_name]),
	'href' => "action/catalog/delete?guid={$entity->guid}",
	'confirm' => elgg_echo("nihcp_catalog:delete:confirm", [$subtype_name]),
];

$view_button = [
	'name' => 'view-catalog',
	'text' => elgg_echo("nihcp_catalog:close", [$subtype_name]),
	'href' => "/catalog/$subtype/view/{$entity->guid}",
];

elgg_register_menu_item('top-alt', $delete_button);
elgg_register_menu_item('top-alt', $view_button);

foreach ($entity::getFields() as $field => $field_vars) {
    $field_vars['name'] = $field;

    // If we've got an existing entity, pre-populate the existing field values
    if (isset($entity)) {
        $field_vars['value'] = $entity->$field;
    }

    // If the field is required add a * to the label
    if ($field_vars['required']) {
        $field_vars['label'] .= '*';
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
            $attrs = $field_vars;
            unset($attrs['type']);
            //echo "<div>{$field_vars['label']}<br>" . elgg_view($field_vars['type'], $field_vars) . '</div>'; //orig
            echo "<div>{$field_vars['label']}<br>" . elgg_view($field_vars['type'], $attrs) . '</div>';
    }
}
//echo elgg_view('input/file',['name'=>'test','type'=>'input/file']);
echo elgg_view('input/hidden', [
    'name' => 'guid',
    'value' => $guid,
]);

echo elgg_view('input/hidden', [
    'name' => 'subtype',
    'value' => $subtype,
]);

echo '<div>' . elgg_view('input/submit', ['value' => 'Save']) . '</div>';

elgg_pop_context();