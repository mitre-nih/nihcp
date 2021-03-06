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
 

use \Nihcp\Entity\CommonsCreditRequest;
use \Nihcp\Entity\CommonsCreditRequestDelegation;
use \Nihcp\Entity\AlignmentCommonsCreditsObjectives;

$requests = elgg_extract('requests', $vars);
$full_view = $vars['full_view'];

$content = '';
if(!empty($requests)) {
    $content .= "<tbody>";
	foreach ($requests as $request) {
		$ia = elgg_set_ignore_access();
		$action_button = null;
		$delegate = CommonsCreditRequestDelegation::getDelegationForCCREQ($request->getGUID());
		// set text of delegate button
		$delegate_button_text = empty($delegate) ? "Add" : elgg_echo("nihcp_commons_credit_request:delegate:".$delegate->getStatus());
		$delegate_link = elgg_get_site_url() . "nihcp_commons_credit_request/delegate/" . $request->getGUID();
		$delegate_button = elgg_view('input/button', array(
			'value' => $delegate_button_text,
			'class' => 'elgg-button-cancel ccreq-delegate-button',
			'onclick' => "location.href=\"$delegate_link\""
		));

		// default delete button for drafts
		if ($request->status === CommonsCreditRequest::DRAFT_STATUS) {
			$action_button = elgg_view('input/button', array(
				'value' => 'Delete',
				'class' => 'elgg-button-cancel ccreq-delete-button'
			));
		}
		// change button to withdraw button if already submitted
		// disallow withdrawal if review has passed the Alignment with Commons Credits Objectives
		$acco = get_entity(AlignmentCommonsCreditsObjectives::getFromRequestGuid($request->getGUID()));
		if ($request->status === CommonsCreditRequest::SUBMITTED_STATUS
				&& empty($acco)) {
			$action_button = elgg_view('input/button', array(
				'value' => 'Withdraw',
				'class' => 'elgg-button-cancel ccreq-withdraw-button'
			));
		}

		if(elgg_is_active_plugin('nihcp_credit_allocation')) {
			$allocation_action = \Nihcp\Entity\CommonsCreditAllocation::isAllocated($request->getGUID()) ? 'view_allocation' : 'allocate';

			if ($request->status === CommonsCreditRequest::APPROVED_STATUS) {
				$action_button = elgg_view('input/button', array(
					'value' => elgg_echo("nihcp_credit_allocation:action:$allocation_action"),
					'class' => 'elgg-button-submit ccreq-allocate-button',
					'onclick' => "location.href='" .
						elgg_get_site_url() .
						"nihcp_credit_allocation/allocations/$request->guid'"
				));
			}
		}

		$credit_amount = $request->getExpectedCostTotal();

		$row = "<tr id='$request->guid'>
				<td><a href=\"" .
			elgg_get_site_url() .
			"nihcp_commons_credit_request/request/$request->guid\">$request->project_title</a></td>";
		if ($full_view) {
			$ccreq_id = $request->submission_date ? $request->getRequestId() : 'N/A';
			$row .= "<td>$ccreq_id</td>";
			$row .= "<td>$request->submission_date</td>";
		}
		if($request->status === CommonsCreditRequest::APPROVED_STATUS || $request->status === CommonsCreditRequest::DENIED_STATUS) {
			$ia = elgg_set_ignore_access();
			$feedback = $request->getFeedback();
			$feedback_url = elgg_get_site_url() . "nihcp_credit_request_review/feedback/" . $request->getGUID();
			$row .= "<td class='ccreq-status'><span class='tooltip tooltipborder'><a href='$feedback_url'>$request->status</a><span class='tooltiptext feedback'><h4>"
				.elgg_echo('nihcp_commons_credit_request:ccreq:feedback').":</h4>$feedback->comments</span></span></td>";
			elgg_set_ignore_access($ia);
		} else {
			$status = $request->status === CommonsCreditRequest::COMPLETED_STATUS ? CommonsCreditRequest::SUBMITTED_STATUS : $request->status;
			$row .= "<td class='ccreq-status'>$status</td>";
		}
		$row .= "<td>$credit_amount</td>";
		if ($full_view) {
			if ($request->owner_guid === elgg_get_logged_in_user_guid()) {
				$row .= "<td>$action_button</td>";

				$row .= "<td>$delegate_button</td>";
			} else {// user is a delegate
				if ($request->status === CommonsCreditRequest::APPROVED_STATUS) { //allow allocation button
					$row .= "<td>$action_button</td>";
				} else {
					$row .= "<td></td>";
				}

				$row .= "<td></td>";
			}

		}
		$row .= "
			</tr>
		";
		$content .= $row;
		elgg_set_ignore_access($ia);
	}//foreach request
    $content .= "</tbody>";
}//if request
echo $content;
