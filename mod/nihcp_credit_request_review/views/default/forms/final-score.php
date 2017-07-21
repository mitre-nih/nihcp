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
 
$ia = elgg_set_ignore_access(true);
$request_guid = $vars['request_guid'];
$project_title = get_entity($request_guid)->project_title;


$review_class = get_input('review_class');
$review_class_text = '';
$fs_guid = null;
switch($review_class) {
    case 'datasets':
        $review_class_text = 'Datasets';
        $fs_guid = \Nihcp\Entity\FinalScore::getFinalScoreDatasetsFromRequestGuid($request_guid);
        break;
    case 'applications_tools':
        $review_class_text = 'Applications/Tools';
        $fs_guid = \Nihcp\Entity\FinalScore::getFinalScoreAppsToolsFromRequestGuid($request_guid);
        break;
    case 'workflows':
        $review_class_text = 'Workflows';
        $fs_guid = \Nihcp\Entity\FinalScore::getFinalScoreWorkflowsFromRequestGuid($request_guid);
        break;
    default:
        break;
}

echo "<h3>" . elgg_echo('nihcp_credit_request_review:crr:final_score:sv') . " for " . $review_class_text . "</h3>";
echo "<br>";


$fs_entity = get_entity($fs_guid);
if (isset($fs_entity)) {
    $mean_class_sbr = $fs_entity->sbr;
    $scientific_value = $fs_entity->sv;
    $cf = $fs_entity->cf;
}
elgg_set_ignore_access($ia);

if (!empty($request_guid)) {
    echo "<div>";
    echo "<a class=\"elgg-button-submit elgg-button\" href=\" " . elgg_get_site_url() . "nihcp_credit_request_review/overview\">Back to Overview</a>";
    echo "</div>";
    echo "<div>";
    $project_url = elgg_get_site_url() . "nihcp_credit_request_review/review/$request_guid";
    echo "Project : <a href=\"$project_url\">$project_title</a>";
    echo "</div>";
}

echo "<div>";
echo "<label for ='mean_class_sbr'>" . elgg_echo("nihcp_credit_request_review:crr:final_score:sbr") . "</label>";
echo elgg_view('input/text', array(
    'id' => 'mean_class_sbr',
    'name' => 'mean_class_sbr',
    'value' => $mean_class_sbr,
));
echo "</div>";

echo "<div>";
echo "<label for ='scientific_value'>" . elgg_echo("nihcp_credit_request_review:crr:final_score:sv") . "</label>";
echo elgg_view('input/text', array(
    'id' => 'scientific_value',
    'name' => 'scientific_value',
    'value' => $scientific_value,
));
echo "</div>";

echo "<div>";
echo "<label for ='cf'>" . elgg_echo("nihcp_credit_request_review:crr:final_score:cf") . "</label>";
echo elgg_view('input/text', array(
    'id' => 'cf',
    'name' => 'cf',
    'value' => $cf,
));
echo "</div>";

echo '<br />';
echo '<br />';
?>


<?php
echo "<div>";
echo elgg_view('input/hidden', array('name' => 'request_guid', 'id'=>'request_guid', 'value'=>$request_guid));
echo elgg_view('input/hidden', array('name' => 'review_class', 'id'=>'review_class', 'value'=>$review_class));
echo elgg_view('input/hidden', array('name' => 'fs_guid', 'id'=>'fs_guid', 'value'=>$fs_guid));
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Save'));
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Discard Changes'));
echo "</div>";