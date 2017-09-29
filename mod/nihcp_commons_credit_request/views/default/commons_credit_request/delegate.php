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

