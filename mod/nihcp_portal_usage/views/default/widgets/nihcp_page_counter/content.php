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
    if($result->title == "User Manual"){
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
