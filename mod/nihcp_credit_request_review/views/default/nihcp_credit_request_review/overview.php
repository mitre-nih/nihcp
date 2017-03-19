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

$content .= "<div class=\"pbm\"><label class=\"prm\">".elgg_echo('item:object:commonscreditcycle').":</label>".elgg_view('input/select', array('value' => $selected_cycle_guid, 'options_values' => $cycle_options, 'id' => 'nihcp-ccreq-cycle-select'))."</div>";

$content .=  "<div>";
$content .= elgg_view('graphics/ajax_loader', array(
    'class' => 'embed-throbber mtl crrLoader',
    'id' => 'crrLoader',
));
$content .= "</div>";

$content .= "<div id=\"nihcp-crr-overview-requests\"></div>";

$content .= "</div>";

echo $content;
