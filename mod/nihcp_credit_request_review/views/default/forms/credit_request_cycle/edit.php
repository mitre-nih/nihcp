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
 
elgg_require_js('cycle');

if (isset($vars['cycle_guid'])) {
	$cycle_guid = $vars['cycle_guid'];
	$cycle = get_entity($cycle_guid);
	$application_start = $cycle->start;
	$application_finish = $cycle->finish;
	$stratification_threshold = $cycle->threshold;
}

echo "<h3 class=\"mbl\">".elgg_echo('item:object:commonscreditcycle')."</h3>";

echo "<div class=\"required-field mbm\"><div class=\"mbs\"><label for ='application_start'>" . elgg_echo('nihcp_commons_credit_request:cycle:start') . "</label></div>";
echo elgg_view('input/date', array(
	'name' => 'application_start',
	'value' => $application_start,
));
echo "</div>";

echo "<div class=\"required-field mbm\"><div class=\"mbs\"><label for ='application_finish'>" . elgg_echo('nihcp_commons_credit_request:cycle:finish') . "</label></div>";
echo elgg_view('input/date', array(
	'name' => 'application_finish',
	'value' => $application_finish,
));
echo "</div>";

echo "<div class=\"required-field mbm\"><div class=\"mbs\"><label for ='stratification_threshold'>" . elgg_echo('nihcp_commons_credit_request:cycle:threshold') . "</label></div>";
echo elgg_view('input/text', array(
	'id' => 'ccr-cycle-stratification-threshold-input',
	'name' => 'stratification_threshold',
	'value' => $stratification_threshold,
));
echo "</div>";

echo "<div>";
echo elgg_view('input/hidden', array('name'=>'cycle_guid', 'value' => $cycle_guid));
echo elgg_view('input/submit', array('id' => 'ccr-cycle-save-button', 'name' => 'action', 'value' => 'Save'));
echo elgg_view('input/submit', array('id' => 'ccr-cycle-discard-button', 'name' => 'action', 'value' => 'Discard Changes'));
echo "</div>";