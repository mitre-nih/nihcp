<?php
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
