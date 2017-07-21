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
$cycles = $vars['cycles'];

$content = "<h3 class=\"mbl\">".elgg_echo("nihcp_commons_credit_request:cycles")."</h3>
<div class=\"pbm\">
	<a class=\"elgg-button-submit elgg-button\" href=\"".elgg_get_site_url()."credit_request_cycle/edit\">".elgg_echo("nihcp_commons_credit_request:cycle:add")."</a>
</div>";

if ($cycles) {

	$content .= "
	<table class=\"elgg-table ccreq-overview-table\">
		<tr><th><b>#</b></th><th><b>".elgg_echo('nihcp_commons_credit_request:cycle:start')."</b></th>
		<th><b>".elgg_echo('nihcp_commons_credit_request:cycle:finish')."</b></th>
		<th><b>".elgg_echo('nihcp_commons_credit_request:cycle:threshold')."</b></th>
		<th><b>".elgg_echo('nihcp_commons_credit_request:cycle:active')."</b></th>
		<th><b>".elgg_echo('nihcp_commons_credit_request:cycle:action')."</b></th></tr>";

	foreach ($cycles as $idx => $cycle) {
		$delete_button = '';
		if ($cycle->canDelete()) {
			$delete_button = elgg_view('input/button', array(
				'value' => 'Delete',
				'cycle_guid' => $cycle->getGUID(),
				'class' => 'elgg-button-cancel ccr-cycle-delete-button'
			));
		}
		$active = $cycle->isActive() ? "Yes" : "No";
		$row = "<tr id='$cycle->guid'>
					<td><a href=\"".elgg_get_site_url()."credit_request_cycle/edit/$cycle->guid\">".($idx+1)."</a></td>
					<td>$cycle->start</td>
					<td>$cycle->finish</td>
					<td>$cycle->threshold</td>
					<td>$active</td>
					<td>$delete_button</td>
				</tr>";
		$content .= $row;
	}

	$content .= "</table>";
} else {
	$content .= "<p>".elgg_echo('nihcp_commons_credit_request:cycles:none')."</p>";
}

echo $content;
