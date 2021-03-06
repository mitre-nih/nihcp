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

$request_guid = get_input("request_guid");

$request = get_entity($request_guid);

echo "<h3>Credits Request Summary</h3>";

echo "<div class='pvm'>";

echo elgg_view_entity($request);

echo "</div>";

// TCs should always be able to see this content
// NAs should only see this content if the review is complete
// DEs see it if they are assigned this request for review
if ( nihcp_triage_coordinator_gatekeeper(false)
    || (nihcp_nih_approver_gatekeeper(false) && $request->isComplete())
    || (nihcp_domain_expert_gatekeeper(false) && RiskBenefitScore::isDomainExpertAssignedToRequest(elgg_get_logged_in_user_entity(), $request_guid)) ) {

    $ia = elgg_set_ignore_access(true);

    $acco = get_entity(AlignmentCommonsCreditsObjectives::getFromRequestGuid($request_guid));
    $gs_ds_guid = GeneralScore::getGeneralScoreDatasetsFromRequestGuid($request_guid);
    $gs_at_guid = GeneralScore::getGeneralScoreAppsToolsFromRequestGuid($request_guid);
    $gs_wf_guid = GeneralScore::getGeneralScoreWorkflowsFromRequestGuid($request_guid);

    $benefit_risk_scores = [];
	if(nihcp_domain_expert_gatekeeper(false) && !elgg_is_admin_logged_in()) {
		$benefit_risk_scores = RiskBenefitScore::getEntitiesForRequestAndDomainExpert($request_guid, elgg_get_logged_in_user_guid());
	} else {
		$benefit_risk_scores = RiskBenefitScore::getRiskBenefitScoreEntitiesForRequest($request_guid);
	}

    $fs_ds_guid = FinalScore::getFinalScoreDatasetsFromRequestGuid($request_guid);
    $fs_at_guid = FinalScore::getFinalScoreAppsToolsFromRequestGuid($request_guid);
    $fs_wf_guid = FinalScore::getFinalScoreWorkflowsFromRequestGuid($request_guid);

    $final_recommendation = get_entity(FinalRecommendation::getFinalRecommendation($request_guid));

    $rbscore_empty = true;
    foreach ($benefit_risk_scores as $rb) {
        if ($rb->status === RiskBenefitScore::COMPLETED_STATUS) {
            $rbscore_empty = false;
        }
    }

	$feedback = Feedback::getFeedback($request_guid);
	$feedback_history = Feedback::getFeedbackHistory($request_guid);

    elgg_set_ignore_access($ia);

    // if not everything is empty, show a review.
    if( !(empty($benefit_risk_scores) && empty($acco) && empty($gs_ds_guid) && empty($gs_at_guid) && empty($gs_wf_guid)
        && empty($fs_ds_guid) && empty($fs_at_guid) && empty($fs_wf_guid) && empty($final_recommendation) && $rbscore_empty
		&& empty($feedback) && empty($feedback_history)) ) {

        echo "<h3>Review Summary</h3>";

		if($acco) {
			echo "<div class='pvm'>";

			echo elgg_view_entity($acco);

			echo "</div>";
		}

		if($gs_at_guid || $gs_ds_guid || $gs_wf_guid) {
			echo "<div class='pvm'>";

			if (!empty($gs_ds_guid)) {
				echo elgg_view_entity(get_entity($gs_ds_guid));
			}

			if (!empty($gs_at_guid)) {
				echo elgg_view_entity(get_entity($gs_at_guid));
			}

			if (!empty($gs_wf_guid)) {
				echo elgg_view_entity(get_entity($gs_wf_guid));
			}

			echo "</div>";
		}

        if (!empty($benefit_risk_scores) && !$rbscore_empty) {
			echo "<div class='pvm'>";
            echo "<h3>" . elgg_echo("nihcp_credit_request_review:crr:benefit_risk_score") . "</h3>";

            foreach ($benefit_risk_scores as $br) {
                if ($br-> status === RiskBenefitScore::COMPLETED_STATUS) {
                    echo "<div class='pvm'>";
                    echo elgg_view_entity($br);
                    echo "</div>";
                }
            }
			echo "</div>";
        }

        if (!nihcp_domain_expert_gatekeeper(false) || elgg_is_admin_logged_in()) {
            echo "<div class='pvm'>";

            echo "<h3>" . elgg_echo("nihcp_credit_request_review:crr:final_score") . "</h3>";
            echo "<div>". round(FinalScore::calculateROI($request_guid)) . "</div>";

            if (!empty($fs_ds_guid)) {
                echo "<div class='pvm'>";
                echo elgg_view_entity(get_entity($fs_ds_guid));
                echo "</div>";

            }

            if (!empty($fs_at_guid)) {
                echo "<div class='pvm'>";
                echo elgg_view_entity(get_entity($fs_at_guid));
                echo "</div>";

            }

            if (!empty($fs_wf_guid)) {
                echo "<div class='pvm'>";
                echo elgg_view_entity(get_entity($fs_wf_guid));
                echo "</div>";

            }

            echo "</div>";

			if($final_recommendation) {
				echo "<div class='pvm'>";

				echo elgg_view_entity($final_recommendation);

				echo "</div>";
			}

			if($feedback && in_array($request->status, [CommonsCreditRequest::DENIED_STATUS, CommonsCreditRequest::APPROVED_STATUS])) {
				echo "<div class='pvm'>";

				echo elgg_view_entity($feedback);

				echo "</div>";
			}

			if($feedback_history && count($feedback_history) > 1) {
				echo "<div class='pvm'>";
				echo "<h3>Decision Changelog</h3>";
				foreach($feedback_history as $prior_feedback) {

					$status_change = $prior_feedback->getStatusChange();

					if($status_change) {
						echo "<div class='pvs'>";

						echo elgg_view_entity($status_change);

						$next_feedback = $prior_feedback->getNextFeedback();

						if($next_feedback) {
							echo "<div class='pvs'>";

							echo "<div><b>New decision</b></div>";

							echo "<div>$next_feedback->decision</div>";

							echo "</div>";
						}

						echo "</div>";
					}
				}
				echo "</div>";
			}

        }
    }
}
