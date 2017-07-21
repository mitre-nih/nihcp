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
$alignment_commons_credits_objectives_guid = $vars['alignment_commons_credits_objectives_guid'];
$alignment_commons_credits_objectives = get_entity($alignment_commons_credits_objectives_guid);



echo "<h3>" . elgg_echo('nihcp_credit_request_review:crr:align_cc_obj') . "</h3>";
echo "<br>";

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

if (isset($alignment_commons_credits_objectives) && $alignment_commons_credits_objectives->question1) {
    echo elgg_view('input/checkbox', array(
        'name' => 'question1',
        'id' => 'question1',
        'label' => elgg_echo("nihcp_credit_request_review:crr:align_cc_obj:question1"),
        'checked' => true));
} else {
    echo elgg_view('input/checkbox', array(
        'name' => 'question1',
        'id' => 'question1',
        'label' => elgg_echo("nihcp_credit_request_review:crr:align_cc_obj:question1"),));
}
echo "<div class=\"elgg-icon-info-hover elgg-icon tooltip mlm\"><span class=\"tooltiptext\">".elgg_echo("nihcp_credit_request_review:crr:align_cc_obj:tooltip1")."</span></div>";
echo "</div>";

echo "<div>";

if (isset($alignment_commons_credits_objectives) && $alignment_commons_credits_objectives->question2) {
    echo elgg_view('input/checkbox', array(
        'name' => 'question2',
        'id' => 'question2',
        'label' => elgg_echo("nihcp_credit_request_review:crr:align_cc_obj:question2"),
        'checked' => true));
} else {
    echo elgg_view('input/checkbox', array(
        'name' => 'question2',
        'id' => 'question2',
        'label' => elgg_echo("nihcp_credit_request_review:crr:align_cc_obj:question2")));
}
echo "<div class=\"elgg-icon-info-hover elgg-icon tooltip mlm\"><span class=\"tooltiptext\">".elgg_echo("nihcp_credit_request_review:crr:align_cc_obj:tooltip2")."</span></div>";
echo "</div>";

echo "<div>";
if (isset($alignment_commons_credits_objectives) && $alignment_commons_credits_objectives->question3) {
    echo elgg_view('input/checkbox', array(
        'name' => 'question3',
        'id' => 'question3',
        'label' => elgg_echo("nihcp_credit_request_review:crr:align_cc_obj:question3"),
        'checked' => true));
} else {
    echo elgg_view('input/checkbox', array(
        'name' => 'question3',
        'id' => 'question3',
        'label' => elgg_echo("nihcp_credit_request_review:crr:align_cc_obj:question3")));
}
echo "<div class=\"elgg-icon-info-hover elgg-icon tooltip mlm\"><span class=\"tooltiptext\">".elgg_echo("nihcp_credit_request_review:crr:align_cc_obj:tooltip3")."</span></div>";
echo "</div>";

echo "<div>";
if (isset($alignment_commons_credits_objectives) && $alignment_commons_credits_objectives->question4) {
    echo elgg_view('input/checkbox', array(
        'name' => 'question4',
        'id' => 'question4',
        'label' => elgg_echo("nihcp_credit_request_review:crr:align_cc_obj:question4"),
        'checked' => true));
} else {
    echo elgg_view('input/checkbox', array(
        'name' => 'question4',
        'id' => 'question4',
        'label' => elgg_echo("nihcp_credit_request_review:crr:align_cc_obj:question4")));
}
echo "<div class=\"elgg-icon-info-hover elgg-icon tooltip mlm\"><span class=\"tooltiptext\">".elgg_echo("nihcp_credit_request_review:crr:align_cc_obj:tooltip4")."</span></div>";
echo "</div>";

elgg_set_ignore_access($ia);

echo "<div>";
echo elgg_view('input/hidden', array('name' => 'request_guid', 'id'=>'request_guid', 'value'=>$request_guid));
echo elgg_view('input/hidden', array('name' => 'alignment_commons_credits_objectives_guid', 'id'=>'alignment_commons_credits_objectives_guid', 'value'=>$alignment_commons_credits_objectives_guid));
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Save'));
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Discard Changes'));
echo "</div>";