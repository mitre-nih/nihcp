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

