<?php

elgg_require_js('jquery');
elgg_require_js('tablesorter');
elgg_require_js('crr');

$full_view = elgg_extract('full_view', $vars, true); // for viewing widget view or full page view

$content = "<div class='crr-overview-page'>";

$cycles = $vars['cycles'];
if(!empty($cycles)) {
	$cycle_options = [];
	foreach($cycles as $cycle) {
		$cycle_options[$cycle->guid] = elgg_view_entity($cycle);
	}
}

$selected_cycle_guid = $vars['selected_cycle_guid'];
if(!$selected_cycle_guid){
    $selected_cycle_guid = \Nihcp\Entity\CommonsCreditCycle::getActiveCycleGUID();
}

$content .= "<div class=\"pbm\">";
$content .= "<label class=\"prm\">".elgg_echo('item:object:commonscreditcycle').":</label>".elgg_view('input/select', array('value' => $selected_cycle_guid, 'options_values' => $cycle_options, 'id' => 'nihcp-ccreq-cycle-select'));
if($full_view) {
    $content .= elgg_view('input/text', array('value' => get_input("search_term"), "placeholder" => elgg_echo("search"), 'id' => 'nihcp-crr-search-input', 'alt' => 'NIHCP CCREQ Search Box'));
    $content .= elgg_view('input/submit', array('value' => 'Submit', 'id' => 'nihcp-crr-search-submit', 'alt' => 'NIHCP CCREQ Search Submit Button'));
}
$content .= "</div>";

$content .=  "<div>";
$content .= elgg_view('graphics/ajax_loader', array(
    'class' => 'embed-throbber mtl crrLoader',
    'id' => 'crrLoader',
));
$content .= "</div>";

$content .= "<div id=\"nihcp-crr-overview-requests\"></div>";

$content .= "</div>";

echo $content;
