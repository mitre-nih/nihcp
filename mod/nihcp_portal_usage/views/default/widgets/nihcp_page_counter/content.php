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
    'calculation' => "sum",
    'limit' => $num_display,
    'annotation_names' => array('pageHit'),
);
$results = elgg_get_entities_from_annotation_calculation($options);

echo elgg_view_form('nihcp_portal_usage/export_page_hits');
echo '<div>';
echo '<table class="elgg-table-alt">';
echo '<tr><th>' . elgg_echo('nihcp_portal_usage:page_counter:widget:title:label') . '</th>';
echo '<th>' . elgg_echo('nihcp_portal_usage:page_counter:widget:counter:label') . '</th></tr>';
foreach ($results as $result) {
    $s = $result->getSubtype();
    $h = $result->getURL();

    if(in_array($s,array('services','catalog','equivalency','glossary'))){
        $t = $result->elggURI;
        $u = $result->elggURI;
    }else{
        $t = $result->title;
        $u = $result->getURL();
    }
    if($result->title == "investigator-portal-user-manual"){
        $t = $result->title;
        $u = elgg_get_site_url() . "nihcp_commons_credit_request/investigator-portal-user-manual";
    }else if($result->title == "FAQ Listing Page"){
        $t = $result->title;
        $u = $result->elggURI;
    }else if($result->title == "Help Center") {
        $t = $result->title;
        $u = $result->elggURI;
    }

    $url = elgg_view('output/url', [
        'text' => $t,
        'href' => $u,
        'is_trusted' => true,
    ]);
    //$u = $result->getURL();
    //$u = elgg_echo("item:object:$s");
    //$u = $result->description;
    $sum = $result->getAnnotationsSum("pageHit");
    echo "<tr><td>$url</td><td>$sum</td></tr>";
}
echo '</table>';

echo '</div>';
