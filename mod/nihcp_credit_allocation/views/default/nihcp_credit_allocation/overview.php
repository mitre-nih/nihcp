<?php

$full_view = elgg_extract('full_view', $vars, true); // for viewing widget view or full page view

$content = "<div class='cca-overview-page'>";
$cycles = $vars['cycles'];

if(!empty($cycles)) {
	$cycle_options = [];
	foreach($cycles as $cycle) {
		$cycle_options[$cycle->guid] = elgg_view_entity($cycle);
	}
}

$selected_cycle_guid = $vars['selected_cycle_guid'];

$content .= "<div class=\"pbm\"><label class=\"prm\">".elgg_echo('item:object:commonscreditcycle').":</label>".elgg_view('input/select', array('value' => $selected_cycle_guid, 'options_values' => $cycle_options, 'id' => 'nihcp-ccreq-cycle-select'))."</div>";


if (nihcp_credit_admin_gatekeeper(false)) {
	$content .= "<div class='pvm'>";
	$content .= "<span class =\"phs\"><a class=\"elgg-button-submit elgg-button\" href=\" " . elgg_get_site_url() . "nihcp_credit_allocation/update\">Upload Summary File</a></span>";
	$content .= "<span class =\"phs\"><a class=\"elgg-button-submit elgg-button\" href=\" " . elgg_get_site_url() . "nihcp_credit_allocation/upload_history\">Upload History</a></span>";
	$content .= "</div>";
}

$content .= "<div id=\"nihcp-cca-overview-allocations\"></div>";

$content .= "</div>";

echo $content;