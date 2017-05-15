<?php
elgg_require_js('tablesorter');
elgg_require_js('request');

$full_view = elgg_extract('full_view', $vars, true);

$content = "<div class=\"nihcp-ccreq-top-bar pbm\">
				<a class=\"elgg-button-submit elgg-button\" href=\"".elgg_get_site_url()."nihcp_commons_credit_request/request\">".elgg_echo("nihcp_commons_credit_request:ccreq:new")."</a>
			</div>";

$content .= elgg_view('commons_credit_request/overview/draft_requests', ['full_view' => $full_view]);

$cycles = $vars['cycles'];

if(!empty($cycles)) {
	$cycle_options = [];
	foreach($cycles as $cycle) {
		$cycle_options[$cycle->guid] = elgg_view_entity($cycle);
	}
}

$selected_cycle_guid = $vars['selected_cycle_guid'];

$content .= "<div class=\"nihcp-ccreq-top-bar ptl pbm\">
				<label for=\"nihcp-ccreq-cycle-select\" class=\"prm\">".elgg_echo('item:object:commonscreditcycle').":</label>".
					elgg_view('input/select', array('value' => $selected_cycle_guid, 'options_values' => $cycle_options, 'id' => 'nihcp-ccreq-cycle-select', 'class' => 'mts', 'alt' => 'NIHCP CCREQ Cycle Select'));
if($full_view) {
    $content .= elgg_view('input/text', array('value' => get_input("search_term"), "placeholder" => elgg_echo("search"), 'id' => 'nihcp-ccreq-search-input', 'alt' => 'NIHCP CCREQ Search Box'));
    $content .= elgg_view('input/submit', array('value' => 'Submit', 'id' => 'nihcp-ccreq-search-submit', 'alt' => 'NIHCP CCREQ Search Submit Button'));
}
$content .= "</div>";

$content .=  "<div>";
$content .= elgg_view('graphics/ajax_loader', array(
    'class' => 'embed-throbber mtl crrLoader',
    'id' => 'crrLoader',
));
$content .= "</div>";

$content .= "<div id=\"nihcp-ccr-overview-requests-in-cycle\"></div>";

echo $content;
