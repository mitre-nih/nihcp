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
 
namespace Nihcp\Entity;
use Nihcp\Manager\RoleManager;

nihcp_role_gatekeeper([RoleManager::TRIAGE_COORDINATOR, RoleManager::NIH_APPROVER, RoleManager::DOMAIN_EXPERT]);

$cycle_guid = elgg_extract('cycle_guid', $vars);
$ia = elgg_set_ignore_access();
$cycle = get_entity($cycle_guid);
$requests = CommonsCreditRequest::sort($cycle->getRequests());

$isDomainExpert = nihcp_domain_expert_gatekeeper(false) && !elgg_is_admin_logged_in();

if ($isDomainExpert) {
	$assigned_requests = [];
	foreach( $requests as $cycle_request ) {
		foreach ( RiskBenefitScore::getRequestsAssignedToDomainExpert(elgg_get_logged_in_user_guid()) as $assigned ) {
			if ($cycle_request->guid == $assigned->guid) {
				$assigned_requests[] = $cycle_request;
			}
		}
	}
	$requests = $assigned_requests;
}

$full_view = elgg_extract('full_view', $vars, true);

$content = '';

// TCs, DEs, and NAs all have different sets of columns of this table
if($requests) {
	$content .= "<table class=\"elgg-table crr-overview-table\">";
	$content .=	"<tr>";
	$content .= "<th class='project-name'><b>Project Name</b></th>";
	if ($full_view) {
		$content .= "<th><b>CCREQ ID</b></th>";
		if (!$isDomainExpert) {
			$content .= "<th><b>Investigator</b></th>";
		}
		$content .= "<th><b>Submission Date</b></th>";
	}
	$content .= "<th><b>Status</b></th>";
	if ($full_view) {
		$content .= "<th><b>Alignment</b></th>";
		$content .= "<th><b>General Score*</b></th>";
		$content .= "<th><b>Benefit and Risk Scores</b></th>";
		if (!$isDomainExpert) {
			$content .= "<th><b>" . elgg_echo("nihcp_credit_request_review:crr:final_score") . "</b></th>";
			$content .= "<th><b>Final Review</b></th>";
		}
	}
	$content .= "<th><b>Credit ($)</b></th>";

	if ($full_view && !$isDomainExpert) {
		$content .= "<th><b>Completed</b></th>";
	}

	if (nihcp_nih_approver_gatekeeper(false)) {
		$content .= "<th><b>Final Decision</b></th>";
	}

	$content .= "</tr>";



	foreach ($requests as $request) {

		$is_nih_approver_and_review_not_complete = nihcp_nih_approver_gatekeeper(false, 0, true)
			&& !FinalRecommendation::isReviewCompleted($request->getGUID())
			&& !elgg_is_admin_logged_in();

		if ($request->status != 'Draft') {
			$requester = get_entity($request->owner_guid)->getDisplayName();

			$credit_amount = $request->getExpectedCostTotal();


			$row = "<tr id=\"$request->guid\">";

			$project_url = elgg_get_site_url() . "nihcp_credit_request_review/review/$request->guid";
			$row .= "<td><a href=\"$project_url\">$request->project_title</a></td>";
			if ($full_view) {
				$row .= "<td>{$request->getRequestId()}</td>";
				if (!$isDomainExpert) {
					$row .= "<td>$requester</td>";
				}
				$row .= "<td>$request->submission_date</td>";
			}
			$row .= "<td class='ccreq-status'>$request->status</td>";
			if ($full_view) {

				if ($request->status == 'Withdrawn') { // no review needs to take place
					if (!$isDomainExpert) {
						$row .= "<td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td>";
					} else {
						$row .= "<td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td>";
					}
				} else {

					$align_cc_obj_guid = AlignmentCommonsCreditsObjectives::getFromRequestGuid($request->getGUID());

					if (empty($align_cc_obj_guid) || $is_nih_approver_and_review_not_complete) {
						$align_cc_obj_link = elgg_view_icon('attention-hover');
					} else {

						$align_cc_obj_link = get_entity($align_cc_obj_guid)->pass() ? "Pass" : "Fail";
					}
					$align_cc_obj_form_url = elgg_get_site_url() . "nihcp_credit_request_review/align-cc-obj/" . $request->getGUID();
					$row .= "<td><a href='" . $align_cc_obj_form_url . "'>" . $align_cc_obj_link . "</a></td>";
					// check if there is anything to score
					if (empty($request->datasets) && empty($request->applications_tools) && empty($request->workflows) ) {
						$row .="<td>N/A</td>";
					} else {


						if (GeneralScore::isReviewCompleted($request->getGUID()) && !$is_nih_approver_and_review_not_complete) {

							$general_score_link = GeneralScore::getTableCellView($request->getGUID());
						} else {
							$general_score_link = elgg_view_icon('attention-hover');
						}
						$general_score_url = elgg_get_site_url() . "nihcp_credit_request_review/general-score-overview/" . $request->getGUID();
						$row .= "<td><a href='" . $general_score_url . "'>" . $general_score_link . "</a></td>";
					}

					// check if there is anything to score
					if ( empty($request->datasets) && empty($request->applications_tools) && empty($request->workflows) ) {
						$row .= "<td>N/A</td>";
					} else {
						$risk_benefit_score_url = elgg_get_site_url() . "nihcp_credit_request_review/risk-benefit-score-overview/" . $request->getGUID();
						if ((RiskBenefitScore::isCompleted($request)
							|| (!elgg_is_admin_logged_in() && nihcp_domain_expert_gatekeeper(false, elgg_get_logged_in_user_guid(), true) && RiskBenefitScore::isAllAssignedCompleted($request)))
							&& !$is_nih_approver_and_review_not_complete) {
							$risk_benefit_score_link = "Completed";
						} else {
							$risk_benefit_score_link = elgg_view_icon('attention-hover');
						}
						$row .= "<td><a href='" . $risk_benefit_score_url . "'>" . $risk_benefit_score_link . "</a></td>";
					}

					if (!$isDomainExpert) {

						// check if there is anything to score
						if (empty($request->datasets) && empty($request->applications_tools) && empty($request->workflows)) {
							$row .= "<td>N/A</td>";
						} else {


							if (FinalScore::isFinalScoreCompleted($request->getGUID()) && !$is_nih_approver_and_review_not_complete) {
								$final_score_link = round(FinalScore::calculateROI($request->getGUID()));
							} else {
								$final_score_link = elgg_view_icon('attention-hover');
							}

							$final_score_url = elgg_get_site_url() . "nihcp_credit_request_review/final-score-overview/" . $request->getGUID();
							$row .= "<td><a href='" . $final_score_url . "'>" . $final_score_link . "</a></td>";
						}

						$final_recommendation_form_url = elgg_get_site_url() . "nihcp_credit_request_review/final-recommendation/" . $request->getGUID();

						if (FinalRecommendation::isReviewCompleted($request->getGUID())) {
							$row .=
								"
					<td><a href='" .
								$final_recommendation_form_url .
								"'>" .
								get_entity(\Nihcp\Entity\FinalRecommendation::getFinalRecommendation($request->getGUID()))->final_recommendation .
								"</a></td>";
						} else {
							$row .=
								"
					<td><a href='" .
								$final_recommendation_form_url .
								"'>" .
								elgg_view_icon('attention-hover') .
								"</a></td>";
						}
					}
				}
			}

			$row .= "
					<td>$credit_amount</td>";

			if ($full_view && !$isDomainExpert) {
				if ($request->status == 'Withdrawn') {
					$row .= "<td>N/A</td>";
				} else if ($request->isComplete()) {
					$row .= "
                    <td>" . elgg_view_icon('checkmark-hover') . "</td>";
				} else {
					$row .= "
                    <td>" . elgg_view_icon('delete-hover') . "</td>";
				}
			}

			if (nihcp_nih_approver_gatekeeper(false)) {
				$row .= "<td class=\"crr-decision\">";
				$decision = '';
				if ($request->status === CommonsCreditRequest::APPROVED_STATUS) {
					$decision = elgg_echo('nihcp_credit_request_review:crr:decision:approve');
				} elseif ($request->status === CommonsCreditRequest::DENIED_STATUS) {
					$decision = elgg_echo('nihcp_credit_request_review:crr:decision:deny');
				} elseif ($request->status === CommonsCreditRequest::WITHDRAWN_STATUS || $request->status !== CommonsCreditRequest::COMPLETED_STATUS) {
					$decision = "N/A";
				} else {
					$decision =
						elgg_view('input/button', array(
							'value' => 'Approve',
							'class' => 'elgg-button-submit crr-approver-button'
						))
						. elgg_view('input/button', array(
							'value' => 'Deny',
							'class' => 'elgg-button-cancel crr-approver-button'
						));
				}
				if($request->status === CommonsCreditRequest::APPROVED_STATUS || $request->status === CommonsCreditRequest::DENIED_STATUS) {
					$feedback = get_entity($request->getFeedback());
					$feedback_url = elgg_get_site_url() . "nihcp_credit_request_review/feedback/" . $request->getGUID();
					$row .= "<span class='tooltip tooltipborder'><a href='$feedback_url'>$decision</a><span class='tooltiptext feedback'><h4>"
						.elgg_echo('nihcp_commons_credit_request:ccreq:feedback').":</h4>$feedback->comments</span></span></td>";
				} else {
					$row .= $decision;
				}
				$row .= "</td>";
			}

			$row .= "
				</tr>
			";
			$content .= $row;
		}
	}


	$content .= "</table>";

	// legend for table
	$content .= "<div class='ptl'>*General Score: Once completed, each cell shows the result of the mean general score multiplied by the number of digital objects for each of the classes of digital objects: (D)atasets, (A)pplications/(T)ools, and (W)orkflows.</div>";
} else {
	$content .= elgg_echo('nihcp_commons_credit_request:ccreq:none');
}
elgg_set_ignore_access($ia);
echo $content;