<?php
use \Nihcp\Entity\CommonsCreditRequest;

$requests = elgg_extract('requests', $vars);
$full_view = $vars['full_view'];

$content = '';
if(!empty($requests)) {
	foreach ($requests as $request) {
		$delete_or_withdraw_button = null;
		// default delete button for drafts
		if ($request->status === "Draft") {
			$delete_or_withdraw_button = elgg_view('input/button', array(
				'value' => 'Delete',
				'class' => 'elgg-button-cancel ccreq-delete-button'
			));
		}
		// change button to withdraw button if already submitted
		if ($request->status === "Submitted") {
			$delete_or_withdraw_button = elgg_view('input/button', array(
				'value' => 'Withdraw',
				'class' => 'elgg-button-cancel ccreq-withdraw-button'
			));
		}

		$credit_amount = $request->getExpectedCostTotal();

		$row = "<tr id='$request->guid'>
				<td><a href=\"" .
			elgg_get_site_url() .
			"nihcp_commons_credit_request/request/$request->guid\">$request->project_title</a></td>";
		if ($full_view) {
			$row .= "<td>$request->submission_date</td>";
		}
		if($request->status === CommonsCreditRequest::APPROVED_STATUS || $request->status === CommonsCreditRequest::DENIED_STATUS) {
			$ia = elgg_set_ignore_access();
			$feedback = get_entity($request->getFeedback());
			$feedback_url = elgg_get_site_url() . "nihcp_credit_request_review/feedback/" . $request->getGUID();
			$row .= "<td class='ccreq-status'><span class='tooltip tooltipborder'><a href='$feedback_url'>$request->status</a><span class='tooltiptext feedback'><h4>"
				.elgg_echo('nihcp_commons_credit_request:ccreq:feedback').":</h4>$feedback->comments</span></span></td>";
			elgg_set_ignore_access($ia);
		} else {
			$row .= "<td class='ccreq-status'>$request->status</td>";
		}
		$row .= "<td>$credit_amount</td>";
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
}
echo $content;