<?php
/**
 * Content stats widget
 */

$num_display = sanitize_int($vars['entity']->num_display, false);
// set default value for display number
if (!$num_display) {
    $num_display = 10;
}

$db_prefix = _elgg_services()->config->get('dbprefix');


$options = array(
    'type' => 'object',
    'limit' => $num_display,
    'metadata_name_value_pairs' => [
        ['name' => 'pageCounter', 'value' => '0', 'operand' => '>'],
    ],
    'order_by_metadata' => array(
        'name' => 'pageCounter','direction' => 'DESC', 'as' => 'integer',
    ),
);

//$results = elgg_get_entities_from_metadata($options);
$results = elgg_get_entities_from_metadata($options);


echo elgg_view_form('nihcp_portal_usage/export_page_hits');
echo '<table class="elgg-table-alt">';
echo '<tr><th>' . elgg_echo('nihcp_portal_usage:page_counter:widget:title:label') . '</th>';
echo '<th>' . elgg_echo('nihcp_portal_usage:page_counter:widget:counter:label') . '</th></tr>';
foreach ($results as $result) {
    $s = $result->getSubtype();
    $h = $result->getURL();

    if(in_array($s,array('services','catalog','equivalence','glossary'))){
        $t = $result->elggURI;
        $u = $result->elggURI;
    }else{
        $t = $result->title;
        $u = $result->getURL();
    }

    $url = elgg_view('output/url', [
        'text' => $t,
        'href' => $u,
        'is_trusted' => true,
    ]);
    //$u = $result->getURL();
    //$u = elgg_echo("item:object:$s");
    //$u = $result->description;
    echo "<tr><td>$url</td><td>$result->pageCounter</td></tr>";
}
echo '</table>';

echo '</div>';
