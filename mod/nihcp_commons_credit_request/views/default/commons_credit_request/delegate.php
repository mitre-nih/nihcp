<?php

elgg_require_js('delegate');

$delegation = $vars['delegation'];
$ccreq = $vars['current_request'];

echo "<div class='pvs'>";
echo "<a class=\"elgg-button-submit elgg-button\" href=\" " . elgg_get_site_url() . "nihcp_commons_credit_request/overview\">Back to Overview</a>";
echo "</div>";

echo "<div>";
echo "<h4>Project Title: $ccreq->project_title</h4>";
echo "</div>";

echo "<div id='delegate-instructions'>";
echo elgg_echo('nihcp_commons_credit_request:delegate:instructions');
echo "</div>";

echo "<div id='delegate-table'>";

echo "<table class='elgg-table'>";
echo "<tr><th>Project Name</th><th>Delegate Email</th><th>Last Updated</th><th>Status</th></th><th>Action</th></tr>";

$status = elgg_echo('nihcp_commons_credit_request:delegate:' . $delegation->getStatus());
$delegation_guid = $delegation->getGUID();

echo "<tr id='$delegation_guid'><td>$ccreq->project_title</td><td>"
    . $delegation->getDelegateEmail()
    . "</td><td>"
    . date('M j, Y h:i:s A',$delegation->getTimeUpdated())
    . "</td><td>"
    . $status
    . "</td><td><input type='button' id ='delegate-delete-button' class='elgg-button elgg-button-cancel' value='Remove Delegate'/></td></tr>";
echo "</table>";

echo "</div>";

