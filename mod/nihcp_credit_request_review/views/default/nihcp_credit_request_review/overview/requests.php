<?php
namespace Nihcp\Entity;
use Nihcp\Manager\RoleManager;

nihcp_role_gatekeeper([RoleManager::TRIAGE_COORDINATOR, RoleManager::NIH_APPROVER, RoleManager::DOMAIN_EXPERT]);

$cycle_guid = sanitize_string(elgg_extract('cycle_guid', $vars));
$session = elgg_get_session();
$session->set('crr_prev_selected_cycle', $cycle_guid);
$ia = elgg_set_ignore_access();
$cycle = get_entity($cycle_guid);

$offset = elgg_extract('offset',$vars,0);
$limit = elgg_extract('limit',$vars,10);
$search_term = sanitise_string(elgg_extract('search_term',$vars));

//this is a bit ugly, fastest way to implement tho
if($search_term){
    $requests = CommonsCreditRequest::searchByTitle($search_term);
    if (nihcp_nih_approver_gatekeeper(false) && !elgg_is_admin_logged_in()) {
        $requests = CommonsCreditRequest::sortForNIHApprover($requests);
    } else {
        //$toSort = $cycle->getRequests($limit, $offset);
        $requests = CommonsCreditRequest::sort($requests);
    }
}else {

    // sort the ccreqs in different ways depending on the role of the user
    if (nihcp_nih_approver_gatekeeper(false) && !elgg_is_admin_logged_in()) {
        $requests = CommonsCreditRequest::sortForNIHApprover($cycle->getRequests());
    } else {
        $toSort = $cycle->getRequests();
        $requests = CommonsCreditRequest::sort($toSort);
    }
}
$isDomainExpert = nihcp_domain_expert_gatekeeper(false) && !elgg_is_admin_logged_in();

if ($isDomainExpert) {
    $assigned_requests = [];
    foreach ($requests as $cycle_request) {
        foreach (RiskBenefitScore::getRequestsAssignedToDomainExpert(elgg_get_logged_in_user_guid()) as $assigned) {
            if ($cycle_request->guid == $assigned->guid) {
                $assigned_requests[] = $cycle_request;
            }
        }
    }
    $requests = $assigned_requests;
}

$full_view = elgg_extract('full_view', $vars, true);

$content = '';

$incomplete_icon = "<div class='crr-overview-incomplete-icon' title=\"Item is incomplete\">&#x26AA;</div>";

$content .= "<div>";
if($search_term){
    $content .= elgg_echo('nihcp_credit_request_review:crr:search_label:search') . $search_term;
}else{
    $content .= elgg_echo('nihcp_credit_request_review:crr:search_label:cycle');
}
$content .= "</div>";

// TCs, DEs, and NAs all have different sets of columns of this table
if($requests) {
    $content .= "<table summary=\"".elgg_echo("nihcp_credit_request_review:crr:table_summary")."\" class=\"elgg-table crr-overview-table\">";
    $content .=     "<thead><tr>";
    $content .= "<th scope='col' class='project-name'><b>Project Name</b></th>";
    if ($full_view) {
        $content .= "<th scope='col'><b>CCREQ ID</b></th>";
        if (!$isDomainExpert) {
            $content .= "<th scope='col'><b>Investigator</b></th>";
        }
        $content .= "<th scope='col'><b>Submission Date</b></th>";
    }
    $content .= "<th scope='col'><b>Status</b></th>";
    $content .= "<th scope='col'><b>Active Grant</b></th>";
    if ($full_view) {
        $content .= "<th scope='col'><b>Alignment</b></th>";
        $content .= "<th scope='col'><b>General Score*</b></th>";
        $content .= "<th scope='col'><b>Benefit and Risk Scores</b></th>";
        if (!$isDomainExpert) {
            $content .= "<th scope='col'><b>" . elgg_echo("nihcp_credit_request_review:crr:final_score") . "</b></th>";
            $content .= "<th scope='col'><b>Final Review</b></th>";
        }
    }
    $content .= "<th scope='col'><b>Credit ($)</b></th>";

    if ($full_view && !$isDomainExpert) {
        $content .= "<th scope='col'><b>Completed</b></th>";
    }

    if (nihcp_nih_approver_gatekeeper(false)) {
        $content .= "<th scope='col'><b>Final Decision</b></th>";
    }

    $content .= "</tr></thead><tbody>";



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
            $row .= "<td class='ccreq-status'>";
            if(elgg_is_admin_logged_in()) {
                $statuses = [CommonsCreditRequest::SUBMITTED_STATUS, CommonsCreditRequest::COMPLETED_STATUS];
                if(!in_array($request->status, $statuses)) {
                    $statuses[] = $request->status;
                }
                $row .= elgg_view('input/select', ['options' => $statuses, 'value' => $request->status, 'x-status' => $request->status, 'class' => 'crr-status-select']);
            } else {
                $row .= $request->status;
            }
            $row .= "</td>";
            //grant ID verification
            $row .="<td class='ccreq-verification'>";
            if($request->grant_id_verification === "1"){
                $row .= "RePORTER Verified";
                $row .= "</td>";
            }else{
                $t_url = elgg_get_site_url() . "nihcp_credit_request_review/verify/$request->guid";
                $status = $request->is_active;
                if($status == "yes"){
                    $row .= "<a href=\"$t_url\">Valid</a></td>";
                }else if($status == "no"){
                    $row .= "<a href=\"$t_url\">Invalid</a></td>";
                }else{
                    $row .= "<a href=\"$t_url\">RePORTER Unverified</a></td>";
                }
                //$row .= "RePORTER Unverified";
            }
            //end grant ID verification
            if ($full_view) {

                if ($request->status === 'Withdrawn') { // no review needs to take place
                    if (!$isDomainExpert) {
                        $row .= "<td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td>";
                    } else {
                        $row .= "<td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td>";
                    }
                } else {

                    $align_cc_obj_guid = AlignmentCommonsCreditsObjectives::getFromRequestGuid($request->getGUID());

                    if (empty($align_cc_obj_guid) || $is_nih_approver_and_review_not_complete) {
                        $align_cc_obj_link = $incomplete_icon;
                    } else {

                        $align_cc_obj_link = get_entity($align_cc_obj_guid)->pass() ? "Pass" : "Fail";
                    }
                    $align_cc_obj_form_url = elgg_get_site_url() . "nihcp_credit_request_review/align-cc-obj/" . $request->getGUID();
                    $td = "<td><a href='" . $align_cc_obj_form_url . "'>" . $align_cc_obj_link . "</a></td>";

                    // check if theres nothing to show
                    if (empty($align_cc_obj_guid) && $request->isComplete()) {
                        $td = "<td>No Review</td>";
                    }
                    $row .= $td;


                    // check if there is anything to score
                    if (empty($request->datasets) && empty($request->applications_tools) && empty($request->workflows) ) {
                        $row .="<td>N/A</td>";
                    } else {


                        if (GeneralScore::isReviewCompleted($request->getGUID()) && !$is_nih_approver_and_review_not_complete) {

                            $general_score_link = GeneralScore::getTableCellView($request->getGUID());
                        } else {
                            $general_score_link = $incomplete_icon;
                        }
                        $general_score_url = elgg_get_site_url() . "nihcp_credit_request_review/general-score-overview/" . $request->getGUID();
                        $td = "<td><a href='" . $general_score_url . "'>" . $general_score_link . "</a></td>";

                        if (!GeneralScore::hasAnyReviews($request->guid) && $request->isComplete()) {
                            $td = $td = "<td>No Review</td>";
                        }
                        $row .= $td;
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
                            $risk_benefit_score_link = $incomplete_icon;
                        }
                        $td = "<td><a href='" . $risk_benefit_score_url . "'>" . $risk_benefit_score_link . "</a></td>";

                        if (empty(RiskBenefitScore::getRiskBenefitScoreEntitiesForRequest($request->guid)) && $request->isComplete()) {
                            $td = "<td>No Review</td>";
                        }

                        $row .= $td;
                    }

                    if (!$isDomainExpert) {

                        // check if there is anything to score
                        if (empty($request->datasets) && empty($request->applications_tools) && empty($request->workflows)) {
                            $row .= "<td>N/A</td>";
                        } else {


                            if (FinalScore::isFinalScoreCompleted($request->getGUID()) && !$is_nih_approver_and_review_not_complete) {
                                $final_score_link = round(FinalScore::calculateROI($request->getGUID()));
                            } else {
                                $final_score_link = $incomplete_icon;
                            }

                            $final_score_url = elgg_get_site_url() . "nihcp_credit_request_review/final-score-overview/" . $request->getGUID();
                            $td = "<td><a href='" . $final_score_url . "'>" . $final_score_link . "</a></td>";
                            if (!FinalScore::hasFinalScores($request->guid) && $request->isComplete()) {
                                $td = "<td>No Review</td>";
                            }

                            $row .= $td;
                        }

                        $final_recommendation_form_url = elgg_get_site_url() . "nihcp_credit_request_review/final-recommendation/" . $request->getGUID();

                        if (FinalRecommendation::isReviewCompleted($request->getGUID()) && $request->isComplete()) {
                            $row .=
                                "
                                        <td><a href='" .
                                $final_recommendation_form_url .
                                "'>" .
                                get_entity(FinalRecommendation::getFinalRecommendation($request->getGUID()))->final_recommendation .
                                "</a></td>";
                        } else {
                            $row .=
                                "
                                        <td><a href='" .
                                $final_recommendation_form_url .
                                "'>" .
                                $incomplete_icon .
                                "</a></td>";
                        }
                    }
                }
            }

            $row .= "
                                        <td>$credit_amount</td>";

            if ($full_view && !$isDomainExpert) {
                if ($request->status === 'Withdrawn') {
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
                    $feedback = $request->getFeedback();
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


    $content .= "</tbody></table>";

    // legend for table
    $content .= "<div class='ptl'>*General Score: Once completed, each cell shows the result of the mean general score multiplied by the number of digital objects for each of the classes of digital objects: (D)atasets, (A)pplications/(T)ools, and (W)orkflows.</div>";
} else {
    $content .= elgg_echo('nihcp_commons_credit_request:ccreq:none');
}
elgg_set_ignore_access($ia);

//$count = $cycle->getRequests(0,0,true);
/*$params = array(
    'base_url' => 'ajax/view/nihcp_credit_request_review/overview/requests',
    'offset' => $offset, //set at the top
    'count' => $count, //set at the top
    'limit' => $limit,
);*/
//$pagination = elgg_view('navigation/pagination',$params);
//echo $pagination . $content;

echo $content;
