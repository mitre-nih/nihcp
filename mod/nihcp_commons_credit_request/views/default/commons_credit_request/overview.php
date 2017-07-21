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
				<label class=\"prm\">".elgg_echo('item:object:commonscreditcycle').":</label>".
					elgg_view('input/select', array('value' => $selected_cycle_guid, 'options_values' => $cycle_options, 'id' => 'nihcp-ccreq-cycle-select', 'class' => 'mts')).
			"</div>";

$content .= "<div id=\"nihcp-ccr-overview-requests-in-cycle\"></div>";

echo $content;
