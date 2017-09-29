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
