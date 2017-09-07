<?php

use \Nihcp\Entity\CommonsCreditCycle;


elgg_require_js('tablesorter');
elgg_require_js('pir');

$content = "<div>";

$cycles = $vars['cycles'];
if(!empty($cycles)) {
    $cycle_options = [];
    foreach($cycles as $cycle) {
        $cycle_options[$cycle->guid] = elgg_view_entity($cycle);
    }
}

$selected_cycle_guid = $vars['selected_cycle_guid'];
if(!$selected_cycle_guid){
    $selected_cycle_guid = CommonsCreditCycle::getActiveCycleGUID();
}

$content .= "<div class=\"pbm\">";
$content .= "<label for=\"nihcp-ccreq-cycle-select\" class=\"prm\">".elgg_echo('item:object:commonscreditcycle').":</label>".elgg_view('input/select', array('value' => $selected_cycle_guid, 'options_values' => $cycle_options, 'id' => 'nihcp-ccreq-cycle-select'));
$content .= "</div>";

if (nihcp_triage_coordinator_gatekeeper(false) || nihcp_nih_approver_gatekeeper(false)) {
    $content .= "<div class=\"pbm\">";
    $content .= "<label class='prm' for='nihcp-pir-search-input'>PIR Search:</label>";
    $content .= elgg_view('input/text', array('value' => get_input("search_term"), "placeholder" => elgg_echo("search"), 'id' => 'nihcp-pir-search-input', 'alt' => 'NIHCP PIR Search Box'));
    $content .= elgg_view('input/submit', array('value' => 'Submit', 'id' => 'nihcp-pir-search-submit', 'alt' => 'NIHCP PIR Search Submit Button'));
    $content .= "</div>";
}

$content .= "<div id=\"nihcp-pir-overview-reports\"></div>";

$content .= "</div>";



echo $content;