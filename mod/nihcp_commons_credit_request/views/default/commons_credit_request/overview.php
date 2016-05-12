<?php
elgg_require_js('request');
elgg_require_js('autoNumeric');

$full_view = elgg_extract('full_view', $vars, true);
$requests = $vars['requests'];

$content = "
<div class=\"pbm\">
	<a class=\"elgg-button-submit elgg-button\" href=\"".elgg_get_site_url()."nihcp_commons_credit_request/request\">".elgg_echo("nihcp_commons_credit_request:ccreq:new")."</a>
</div>";

if ($requests) {

	$content .= "
	<table class=\"elgg-table ccreq-overview-table\">
		<tr>
                      <th><b>Project Name</b></th>";
	if ($full_view) {
		$content .= "
			<th><b>Submission Date</b></th>";
	}
	$content .= "
			<th><b>Status</b></th>
			<th><b>Credit ($)</b></th>";
	if ($full_view) {
		$content .= "
			<th><b>Action</b></th>";
	}
	$content .= "
		</tr>
	";

	foreach ($requests as $request) {

		$delete_or_withdraw_button = null;
		// default delete button for drafts
		if ($request->status === "Draft") {
			$delete_or_withdraw_button = elgg_view('input/button', array(
				'value' => 'Delete',
				'request_guid' => $request->getGUID(),
				'class' => 'elgg-button-cancel ccreq-delete-button'
			));
		}
		// change button to withdraw button if already submitted
		if ($request->status === "Submitted") {
			$delete_or_withdraw_button = elgg_view('input/button', array(
				'value' => 'Withdraw',
				'request_guid' => $request->getGUID(),
				'class' => 'elgg-button-cancel ccreq-withdraw-button'
			));
		}

		$credit_amount = $request->getExpectedCostTotal();

		$row = "	<tr id='$request->guid'>
					<td><a href=\"" .
			elgg_get_site_url() .
			"nihcp_commons_credit_request/request/$request->guid\">$request->project_title</a></td>";
		if ($full_view) {
			$row .= "
					<td>$request->submission_date</td>";
		}
		$row .= "
					<td class='ccreq-status'>$request->status</td>
					<td>$credit_amount</td>";
		if ($full_view) {
			$row .= "
					<td>
						$delete_or_withdraw_button
					</td>";
		}
		$row .= "
				</tr>
			";
		$content .= $row;
	}

	$content .= "</table>";
} else {
	$content .= "<p>".elgg_echo('nihcp_commons_credit_request:ccreq:none')."</p>";
}

echo $content;
