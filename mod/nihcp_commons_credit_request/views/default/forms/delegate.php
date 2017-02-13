<?php

elgg_require_js('delegate');

$current_request = $vars['current_request'];

echo "<div class='pvs'>";
echo "<a class=\"elgg-button-submit elgg-button\" href=\" " . elgg_get_site_url() . "nihcp_commons_credit_request/overview\">Back to Overview</a>";
echo "</div>";

echo "<div>";
echo "<h4>Project Title: $current_request->project_title</h4>";
echo "</div>";

echo "<div id='delegate-instructions'>";
echo elgg_echo('nihcp_commons_credit_request:delegate:instructions');
echo "</div>";

echo "<div id='delegate-email' class='pvm'>";

echo "<div>";
echo "<label>" . elgg_echo("nihcp_commons_credit_request:delegate:email") ."</label>";
echo "</div>";

echo "<div>";
echo elgg_view('input/text', array(
    'name' => 'delegate_email',
    'required' => 'true'
));
echo "</div>";
echo "</div>";

echo "<div id='delegate-comment' class='pvm'>";

echo "<div>";
echo "<label>" . elgg_echo("nihcp_commons_credit_request:delegate:message") ."</label>";
echo " (Limit 300 characters)";
echo "</div>";

echo "<div>";
echo "<textarea maxlength='300' name='delegate_message'></textarea>";
echo "</div>";
echo "</div>";

echo "<div>";

echo "<div class='pvl'>";
echo elgg_view('input/hidden', array('name' => 'request_guid', 'id'=>'request_guid', 'value'=>$current_request->guid));
echo elgg_view('input/submit', array('id' => 'ccreq-add-delegate-button', 'class'=>'elgg-button-submit confirmation-required', 'name' => 'action', 'value' => 'Add'));
echo elgg_view('input/submit', array('name' => 'action', 'value' => 'Cancel', 'formnovalidate'=>'true'));
echo "</div>";

echo "</div>";

