<?php

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