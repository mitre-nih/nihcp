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
 


use Nihcp\Entity\CommonsCreditCycle;

nihcp_triage_coordinator_gatekeeper();

$ia = elgg_set_ignore_access();
$cycles = CommonsCreditCycle::getCycles($omit_future = true);

if(!empty($cycles)) {
    $cycle_options = [];
    foreach($cycles as $cycle) {
        $cycle_options[$cycle->guid] = elgg_view_entity($cycle);
    }
}

$cycle_options[0] = 'All';

$report_options = array();

$report_options['Triage Report'] = 'Triage Report';
$report_options['Pledged Digital Objects'] = 'Pledged Digital Objects';
$report_options['Tracking Sheet'] = 'Tracking Sheet';
$report_options['Domain Expert List'] = 'Domain Expert List';
$report_options['How Did You Learn About The Portal'] = 'How Did You Learn About The Portal';
$report_options['CCREQ Summaries'] = 'CCREQ Summaries';
$report_options['PIR Summaries (text)'] = 'PIR Summaries (text)';
$report_options['PIR Summaries (csv)'] = 'PIR Summaries (csv)';

elgg_set_ignore_access($ia);

$content = "<div class=\"nihcp-ccreq-top-bar ptl pbm\">
				<label class=\"prm\">".elgg_echo('item:object:commonscreditcycle').":</label>".
                elgg_view('input/select', array('options_values' => $cycle_options, 'name' => 'nihcp-ccreq-cycle-select', 'id' => 'nihcp-ccreq-cycle-select', 'class' => 'mts', 'alt' => 'NIHCP CCREQ Cycle Select')).
            "</div>";

$content .= "<div class=\"nihcp-ccreq-report nihcp-ccreq-top-bar ptl pbm\">
				<label class=\"prm\">".elgg_echo('nihcp_report_export:type').":</label>".
    elgg_view('input/select', array('options_values' => $report_options, 'name' => 'nihcp-ccreq-report-select', 'id' => 'nihcp-ccreq-report-select', 'class' => 'mts', 'alt' => 'NIHCP CCREQ Report Type Select')).
    "</div>";


$content .= elgg_view('input/submit', ['value' => "Export"]);
echo $content;
