<?php

use Nihcp\Entity\CommonsCreditRequest;
use Nihcp\Entity\FinalScore;
use Nihcp\Entity\FinalRecommendation;
use Nihcp\Entity\CommonsCreditVendor;
use Nihcp\Entity\CommonsCreditAllocation;
use Nihcp\Entity\GeneralScore;
use Nihcp\Entity\RiskBenefitScore;
use Nihcp\Entity\Feedback;
use Nihcp\Entity\AlignmentCommonsCreditsObjectives;

function triage_report_export($cycle_guid) {


    $ia = elgg_set_ignore_access();


    if (empty($cycle_guid)) {
        $ccreqs = CommonsCreditRequest::getAll();
        $cycle_view = "All_Cycles";
        $cycle = 1;
    } else {
        $ccreqs = CommonsCreditRequest::getByCycle($cycle_guid);
        $cycle = get_entity($cycle_guid);
        $cycle_view = "Cycle" . $cycle->start . "-" . $cycle->finish;
    }

    if (empty($ccreqs) || empty($cycle)) {
        register_error(elgg_echo('nihcp_report_export:no_data'));
        return false;
    }

    $vendors = CommonsCreditVendor::getAllVendors();
    $vendor_ids = array();
    foreach ($vendors as $vendor) {
        $vendor_ids[] = $vendor->vendor_id;
    }
    asort($vendor_ids);


    $ccreqs = CommonsCreditRequest::sort($ccreqs);
    $ccreq_rows = [];

    // Project Name, CCREQ ID, Credit ($), Investigator, Email, MITRE Triage Score, MITRE Triage Recommendation, NIH Decision, VendorName1 Credit ($), VendorName2 Credit ($), ...
    foreach ($ccreqs as $ccreq) {

        if ($ccreq->status != 'Draft') {
            $investigator = $ccreq->getOwnerEntity();

            if ($ccreq->status === CommonsCreditRequest::WITHDRAWN_STATUS) {
                $roi_score = CommonsCreditRequest::WITHDRAWN_STATUS;
                $triage_recommendation = CommonsCreditRequest::WITHDRAWN_STATUS;
                $nih_decision = CommonsCreditRequest::WITHDRAWN_STATUS;
            } else {

                if (empty($ccreq->datasets) && empty($ccreq->applications_tools) && empty($ccreq->workflows)) {
                    $roi_score = "N/A";
                } else {
                    if (!FinalRecommendation::isReviewCompleted($ccreq->getGUID()) && FinalScore::isFinalScoreCompleted($ccreq->getGUID())) {
                        $roi_score = FinalScore::calculateROI($ccreq->guid);
                    } else {
                        $roi_score = "";
                    }
                    if (!FinalScore::hasFinalScores($ccreq->guid) && $ccreq->isComplete()) {
                        $roi_score = "No Review";
                    }

                }

                $final_recommendation = get_entity(FinalRecommendation::getFinalRecommendation($ccreq->guid));
                if (FinalRecommendation::isReviewCompleted($ccreq->getGUID()) && $ccreq->isComplete()) {
                    $triage_recommendation = $final_recommendation->final_recommendation;
                } else {
                    $triage_recommendation = "";
                }

                $nih_decision = "";
                if ($ccreq->status === CommonsCreditRequest::APPROVED_STATUS || $ccreq->status === CommonsCreditRequest::DENIED_STATUS) {
                    $nih_decision = $ccreq->status;
                }
            }


            // get the vendor allocations
            $vendor_allocations = array();
            foreach ($vendor_ids as $vendor_id) {
                $vendor_allocations[$vendor_id] = 0;
            }
            $ccreq_allocations = CommonsCreditAllocation::getAllocations($ccreq->guid);
            foreach ($ccreq_allocations as $ccreq_allocation) {
                $vendor_allocations[$ccreq_allocation->vendor] = $ccreq_allocation->credit_allocated;
            }

            $ccreq_row = [$ccreq->project_title, $ccreq->getRequestId(), $ccreq->getExpectedCostTotal(), $investigator->getDisplayName(), $investigator->email, $roi_score, $triage_recommendation, $nih_decision];

            foreach ($vendor_allocations as $vendor_allocation) {
                $ccreq_row[] = $vendor_allocation;
            }

            $ccreq_rows[] = $ccreq_row;
        }

    }

    elgg_set_ignore_access($ia);

    $date = date('Ymd');

    $fo = new ElggFile();

    $title = "TriageReport-" . $cycle_view . "_-_$date";
    $fo->setFilename("$title.csv");
    $fo->originalfilename = $fo->getFilename();
    $fo->title = $title;
    $fo->setMimeType("text/csv");

    $fo->save();

    $fh = $fo->open('write');

    $newline = "\n";

    $header_fields = ['Project Name', 'CCREQ ID', 'Credit ($)', 'Investigator', 'Email', 'MITRE Triage Score', 'MITRE Triage Recommendation', 'NIH Decision'];
    foreach ($vendor_ids as $vendor_id) {
        $header_fields[] = $vendor_id;
    }

    fputcsv_eol($fh, $header_fields, $newline);

    $fh = $fo->open('append');
    foreach ($ccreq_rows as $row) {
        fputcsv_eol($fh, $row, $newline);
    }

    $fo->close();

    forward(str_replace('view', 'download', $fo->getURL()));
}

function pledged_digital_objects_export($cycle_guid) {
    $ia = elgg_set_ignore_access();


    if (empty($cycle_guid)) {
        $ccreqs = CommonsCreditRequest::getAll();
        $cycle_view = "All_Cycles";
        $cycle = 1;
    } else {
        $ccreqs = CommonsCreditRequest::getByCycle($cycle_guid);
        $cycle = get_entity($cycle_guid);
        $cycle_view = "Cycle" . $cycle->start . "-" . $cycle->finish;
    }

    if (empty($ccreqs) || empty($cycle)) {
        register_error(elgg_echo('nihcp_report_export:no_data'));
        return false;
    }


    $ccreqs = CommonsCreditRequest::sort($ccreqs);
    $date = date('Ymd');

    $fo = new ElggFile();

    $title = "PledgedDigitalObjects-" . $cycle_view . "_-_$date";
    $fo->setFilename("$title.txt");
    $fo->originalfilename = $fo->getFilename();
    $fo->title = $title;
    $fo->setMimeType("text/plain");

    $fo->save();

    $fh = $fo->open('write');

    $newline = "\r\n";

    $fh = $fo->open('append');
    foreach ($ccreqs as $ccreq) {
        if ($ccreq->status != 'Draft') {
            fput_eol($fh, "CCREQ ID", $newline);
            fput_eol($fh, "\t" . $ccreq->getRequestId(), $newline);
            fput_eol($fh, "Project Title", $newline);
            fput_eol($fh, "\t" . $ccreq->project_title, $newline);
            fput_eol($fh, "Submitter", $newline);
            fput_eol($fh, "\t" . get_entity($ccreq->owner_guid)->getDisplayName(), $newline);
            fput_eol($fh, "\t" . get_entity($ccreq->owner_guid)->email, $newline);

            if (!empty($ccreq->datasets)) {
                fput_eol($fh, "Datasets", $newline);
                fput_eol($fh, "\t" . $ccreq->datasets, $newline);
                fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions"), $newline);
                if ($ccreq->datasets_has_access_restrictions === 'on') {
                    $har = 'Yes';
                } else {
                    $har = 'No';
                }
                fput_eol($fh, "\t" . $har, $newline);
            }

            if (!empty($ccreq->applications_tools)) {
                fput_eol($fh, "Applications/Tools", $newline);
                fput_eol($fh, "\t" . $ccreq->applications_tools, $newline);
                fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions"), $newline);
                if ($ccreq->applications_tools_has_access_restrictions === 'on') {
                    $har = 'Yes';
                } else {
                    $har = 'No';
                }
                fput_eol($fh, "\t" . $har, $newline);
            }

            if (!empty($ccreq->workflows)) {
                fput_eol($fh, "Workflows", $newline);
                fput_eol($fh, "\t" . $ccreq->workflows, $newline);
                fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions"), $newline);
                if ($ccreq->workflows_has_access_restrictions === 'on') {
                    $har = 'Yes';
                } else {
                    $har = 'No';
                }
                fput_eol($fh, "\t" . $har, $newline);
            }

            fput_eol($fh, "\r\n", $newline);
            fput_eol($fh, "\r\n", $newline);
        }
    }

    elgg_set_ignore_access($ia);

    $fo->close();
    forward(str_replace('view', 'download', $fo->getURL()));
}

function tracking_sheet_export($cycle_guid) {

    $ia = elgg_set_ignore_access();


    if (empty($cycle_guid)) {
        $ccreqs = CommonsCreditRequest::getAll();
        $cycle_view = "All_Cycles";
        $cycle = 1;
    } else {
        $ccreqs = CommonsCreditRequest::getByCycle($cycle_guid);
        $cycle = get_entity($cycle_guid);
        $cycle_view = "Cycle" . $cycle->start . "-" . $cycle->finish;
    }

    if (empty($ccreqs) || empty($cycle)) {
        register_error(elgg_echo('nihcp_report_export:no_data'));
        return false;
    }


    $ccreqs = CommonsCreditRequest::sort($ccreqs);
    $ccreq_rows = [];

    foreach ($ccreqs as $ccreq) {
        if ($ccreq->status != 'Draft') { // filter out draft ccreqs
            $investigator = $ccreq->getOwnerEntity();


            if ($ccreq->status === CommonsCreditRequest::WITHDRAWN_STATUS) {
                $nih_program_officer_name = CommonsCreditRequest::WITHDRAWN_STATUS;
                $nih_program_officer_email = CommonsCreditRequest::WITHDRAWN_STATUS;
                $roi_score = CommonsCreditRequest::WITHDRAWN_STATUS;
                $triage_recommendation = CommonsCreditRequest::WITHDRAWN_STATUS;
                $triage_feedback = CommonsCreditRequest::WITHDRAWN_STATUS;
                $nih_decision = CommonsCreditRequest::WITHDRAWN_STATUS;
                $is_gs_complete = CommonsCreditRequest::WITHDRAWN_STATUS;
                $is_br_complete = CommonsCreditRequest::WITHDRAWN_STATUS;
                $has_assigned_domain_experts = CommonsCreditRequest::WITHDRAWN_STATUS;
                $nih_approver = CommonsCreditRequest::WITHDRAWN_STATUS;
                $feedback_date = CommonsCreditRequest::WITHDRAWN_STATUS;

            } else {
                $nih_program_officer_name = $ccreq->nih_program_officer_name;
                $nih_program_officer_email = $ccreq->nih_program_officer_email;

                if (empty($ccreq->datasets) && empty($ccreq->applications_tools) && empty($ccreq->workflows)) {
                    $roi_score = "N/A";
                } else if (!FinalScore::hasFinalScores($ccreq->guid) && $ccreq->isComplete()) {
                    $roi_score = "No Review";
                } else if (FinalScore::isFinalScoreCompleted($ccreq->guid)) {
                    $roi_score = FinalScore::calculateROI($ccreq->guid);
                } else {
                    $roi_score = "";
                }



                if (empty($ccreq->datasets) && empty($ccreq->applications_tools) && empty($ccreq->workflows)) {
                    $has_assigned_domain_experts = "N/A";
                } else if (empty(RiskBenefitScore::getRiskBenefitScoreEntitiesForRequest($ccreq->guid)) && $ccreq->isComplete()) {
                    $has_assigned_domain_experts = "No Review";
                } else {
                    $has_assigned_domain_experts = RiskBenefitScore::hasAssignedRiskBenefitScores($ccreq->guid) ? "yes" : "no";
                }

                $nih_decision = "";
                if ($ccreq->status === CommonsCreditRequest::APPROVED_STATUS || $ccreq->status === CommonsCreditRequest::DENIED_STATUS) {
                    $nih_decision = $ccreq->status;
                }

                if (empty($ccreq->datasets) && empty($ccreq->applications_tools) && empty($ccreq->workflows)) {
                    $is_gs_complete = "N/A";
                } else if (!GeneralScore::hasAnyReviews($ccreq->guid) && $ccreq->isComplete()) {
                    $is_gs_complete = "No Review";
                } else {
                    $is_gs_complete = GeneralScore::isReviewCompleted($ccreq->guid) ? "Yes" : "Incomplete";
                }

                if (empty($ccreq->datasets) && empty($ccreq->applications_tools) && empty($ccreq->workflows)) {
                    $is_br_complete = "N/A";
                } else if (empty(RiskBenefitScore::getRiskBenefitScoreEntitiesForRequest($ccreq->guid)) && $ccreq->isComplete()) {
                    $is_br_complete = "No Review";
                } else if (empty(RiskBenefitScore::getAssignedDomainExperts($ccreq->guid))) {
                    $is_br_complete = "Unassigned";
                } else {
                    $is_br_complete = RiskBenefitScore::isCompleted($ccreq->guid) ? "Yes" : "Incomplete";
                }

                // only show certain fields if review is completed state
                // this is required now since review status can be changed by an administrator, but previous review data still exists
                if (FinalRecommendation::isReviewCompleted($ccreq->guid) && $ccreq->isComplete()) {
                    $final_recommendation = get_entity(FinalRecommendation::getFinalRecommendation($ccreq->guid));
                    if (empty($final_recommendation)) {
                        $triage_recommendation = "";
                        $triage_feedback = "";
                    } else {
                        $triage_feedback = $final_recommendation->final_recommendation_comments;
                        $triage_recommendation = $final_recommendation->final_recommendation;
                    }

                    $final_decision_feedback = Feedback::getFeedback($ccreq->guid);
                    $nih_approver = empty($final_decision_feedback) ? "" : $final_decision_feedback->getOwnerEntity()->getDisplayName();
                    $feedback_date = empty($final_decision_feedback) ? "" : date('Y-m-d', $final_decision_feedback->time_created);
                } else { // review isn't completed so set everything blank
                    $triage_recommendation = "";
                    $triage_feedback = "";
                    $nih_approver = "";
                    $feedback_date = "";
                }
            }


            $has_cost_sheet = empty($ccreq->pricing_upload_guid) ? "no" : "yes";
            $is_less_than_50k = $ccreq->getExpectedCostTotal() < 50000 ? "yes" : "no";
            $is_grant_id_valid = ($ccreq->grant_id_verification === "1" || $ccreq->is_active === "yes") ? "yes" : "no";
            // the grant verified status set by the reviewer or admin takes precedence
            if ($ccreq->is_active === "no") {
                $is_grant_id_valid = "no";
            }


            $ccreq_row = [$ccreq->getRequestId(), $ccreq->project_title, $investigator->getDisplayName(), $investigator->email, $nih_program_officer_name, $nih_program_officer_email,
                $is_gs_complete, $is_br_complete, $has_cost_sheet, $is_less_than_50k, $is_grant_id_valid, $roi_score, $triage_feedback, $triage_recommendation,
                $ccreq->grant_id, $ccreq->server_compute_expected_cost, $ccreq->storage_expected_cost, $ccreq->network_services_expected_cost, $ccreq->web_servers_expected_cost, $ccreq->databases_expected_cost, $ccreq->other_expected_cost, $ccreq->getExpectedCostTotal(),
                $has_assigned_domain_experts, $nih_decision, $nih_approver, $feedback_date];


            $ccreq_rows[] = $ccreq_row;
        }

    }

    elgg_set_ignore_access($ia);

    $date = date('Ymd');

    $fo = new ElggFile();

    $title = "TrackingSheet-" . $cycle_view . "_-_$date";
    $fo->setFilename("$title.csv");
    $fo->originalfilename = $fo->getFilename();
    $fo->title = $title;
    $fo->setMimeType("text/csv");

    $fo->save();

    $fh = $fo->open('write');

    $newline = "\n";

    $header_fields = ['CCREQ ID', 'Project Title', 'Submitter', 'Submitter Email', 'Program Officer', 'Program Officer Email',
        'GS Complete?', 'B+R Complete?', 'Cost Sheet?', 'Under $50k?', 'Grant Valid?', 'Scientific ROI', 'Comments for Portal', 'Final Recommendation',
        'Submitter Grant #', 'Server+Compute', 'Storage', 'Network Services', 'Webservers', 'Databases', 'Other', 'Total Monetary Request',
        'Domain Expert Assigned', 'Decision', 'NIH Approver', 'Decision Date'];

    fputcsv_eol($fh, $header_fields, $newline);

    $fh = $fo->open('append');
    foreach ($ccreq_rows as $row) {
        fputcsv_eol($fh, $row, $newline);
    }

    $fo->close();

    forward(str_replace('view', 'download', $fo->getURL()));

}

function domain_expert_list_export($cycle_guid) {

    $ia = elgg_set_ignore_access();


    if (empty($cycle_guid)) {
        $ccreqs = CommonsCreditRequest::getAll();
        $cycle_view = "All_Cycles";
        $cycle = 1;
    } else {
        $ccreqs = CommonsCreditRequest::getByCycle($cycle_guid);
        $cycle = get_entity($cycle_guid);
        $cycle_view = "Cycle" . $cycle->start . "-" . $cycle->finish;
    }

    if (empty($ccreqs) || empty($cycle)) {
        register_error(elgg_echo('nihcp_report_export:no_data'));
        return false;
    }


    $ccreqs = CommonsCreditRequest::sort($ccreqs);
    $domain_expert_rows =[];

    foreach($ccreqs as $ccreq) {
        if ($ccreq->status != 'Draft' && $ccreq->status != CommonsCreditRequest::WITHDRAWN_STATUS) {
            $domain_experts = RiskBenefitScore::getAssignedDomainExperts($ccreq->guid);

            // ccreq ID, Domain Expert name, date assigned, date received
            foreach ($domain_experts as $domain_expert) {
                $domain_expert_row = array();
                $domain_expert_row[] = $ccreq->getRequestId();
                $domain_expert_row[] = $domain_expert->getDisplayName();

                $rb_entities = RiskBenefitScore::getEntitiesForRequestAndDomainExpert($ccreq->guid, $domain_expert->guid);
                $first_rb_entity = $rb_entities[0];
                $domain_expert_row[] = date('Y-m-d', $first_rb_entity->time_created);

                $last_updated = 0;
                if (RiskBenefitScore::isAllAssignedCompleted($ccreq->guid, $domain_expert->guid)) {
                    foreach ($rb_entities as $rb_entity) {
                        $time_updated = strtotime($rb_entity->completed_date);
                        if ($time_updated > $last_updated) {
                            $last_updated = $time_updated;
                        }
                    }
                    $domain_expert_row[] = date('Y-m-d', $last_updated);
                }
                $domain_expert_rows[] = $domain_expert_row;
            }
        }
    }

    if (empty($domain_expert_rows)) {
        elgg_set_ignore_access($ia);
        register_error(elgg_echo('nihcp_report_export:no_domain_experts'));
        return false;
    }


    elgg_set_ignore_access($ia);


    $date = date('Ymd');

    $fo = new ElggFile();

    $title = "DomainExpertList-" . $cycle_view . "_-_$date";
    $fo->setFilename("$title.csv");
    $fo->originalfilename = $fo->getFilename();
    $fo->title = $title;
    $fo->setMimeType("text/csv");

    $fo->save();

    $fh = $fo->open('write');

    $newline = "\n";

    $header_fields = ['CCREQ ID', 'Domain Expert', 'Date Assigned', 'Date Received'];

    fputcsv_eol($fh, $header_fields, $newline);

    $fh = $fo->open('append');
    foreach ($domain_expert_rows as $row) {
        fputcsv_eol($fh, $row, $newline);
    }

    $fo->close();

    forward(str_replace('view', 'download', $fo->getURL()));
}

function how_did_you_hear_export() {

    $ia = elgg_set_ignore_access();

    $users = elgg_get_entities(array(
        'type' => 'user',
        'limit' => 0
    ));

    $options = get_how_did_you_hear_options();

    $rows = [];

    // initialize all the counts to 0
    foreach($options as $option) {
        $rows[$option] = 0;
    }
    $rows['unspecified'] = 0;

    // first column is name of categories
    // second column is counts
    foreach ($users as $user) {

        $category = $user->how_did_you_hear_about_us;

        if (empty($category)) {
            $rows['unspecified'] = $rows['unspecified'] + 1;
        } else {
            $rows[$category] = $rows[$category] + 1;
        }


    }

    elgg_set_ignore_access($ia);

    $date = date('Ymd');

    $fo = new ElggFile();

    $title = "HowDidYouHearAboutThePortal-" . "_-_$date";
    $fo->setFilename("$title.csv");
    $fo->originalfilename = $fo->getFilename();
    $fo->title = $title;
    $fo->setMimeType("text/csv");

    $fo->save();

    $fh = $fo->open('write');

    $newline = "\n";

    $header_fields = ['How did you first learn about the NIH commons credits pilot?', 'Total Count'];

    fputcsv_eol($fh, $header_fields, $newline);

    $fh = $fo->open('append');
    foreach ($rows as $category=>$count) {
        fputcsv_eol($fh, [elgg_echo("nihcp_theme:how_did_you_hear_about_us:option:$category"), $count], $newline);
    }

    $fo->close();

    forward(str_replace('view', 'download', $fo->getURL()));

}

function ccreq_summaries_export($cycle_guid) {

    $ia = elgg_set_ignore_access();


    if (empty($cycle_guid)) {
        $ccreqs = CommonsCreditRequest::getAll();
        $cycle_view = "All_Cycles";
        $cycle = 1;
    } else {
        $ccreqs = CommonsCreditRequest::getByCycle($cycle_guid);
        $cycle = get_entity($cycle_guid);
        $cycle_view = "Cycle" . $cycle->start . "-" . $cycle->finish;
    }

    if (empty($ccreqs) || empty($cycle)) {
        register_error(elgg_echo('nihcp_report_export:no_data'));
        return false;
    }

    $ccreqs = CommonsCreditRequest::sort($ccreqs);

    $date = date('Ymd');

    $fo = new ElggFile();

    $title = "CCREQSummaries-" . $cycle_view . "_-_$date";
    $fo->setFilename("$title.txt");
    $fo->originalfilename = $fo->getFilename();
    $fo->title = $title;
    $fo->setMimeType("text/plain");

    $fo->save();

    $fh = $fo->open('write');

    $fh = $fo->open('append');
    foreach ($ccreqs as $ccreq) {
        if ($ccreq->status != 'Draft') {
            fput_eol($fh, "CCREQ ID");
            fput_eol($fh, "\t" . $ccreq->getRequestId());
            fput_eol($fh, "Project Title");
            fput_eol($fh, "\t" . $ccreq->project_title);
            fput_eol($fh, "Submitter");
            fput_eol($fh, "\t" . get_entity($ccreq->owner_guid)->getDisplayName());
            fput_eol($fh, "\t" . get_entity($ccreq->owner_guid)->email);
            fput_eol($fh, "Submission Date");
            fput_eol($fh, "\t" . $ccreq->submission_date);
            if (!empty($ccreq->grant_linkage)) {
                fput_eol($fh, "Grant Linkage");
                fput_eol($fh, "\t" . $ccreq->grant_linkage);
            }
            if (!empty($ccreq->grant_id)) {
                fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:grant_id") . ". " . elgg_echo("nihcp_commons_credit_request:ccreq:grant_id:desc:plaintext"));
                fput_eol($fh, "\t" . $ccreq->grant_id);
            }
            fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:nih_program_officer_name") . ". " . elgg_echo("nihcp_commons_credit_request:ccreq:nih_program_officer_name:desc"));
            fput_eol($fh, "\t" . $ccreq->nih_program_officer_name);
            fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:nih_program_officer_email") . ". " . elgg_echo("nihcp_commons_credit_request:ccreq:nih_program_officer_email:desc"));
            fput_eol($fh, "\t" . $ccreq->nih_program_officer_email);
            fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:proposed_research") . ". " . elgg_echo("nihcp_commons_credit_request:ccreq:proposed_research:desc"));
            fput_eol($fh, "\t" . $ccreq->proposed_research);

            if (!empty($ccreq->productivity_gain) || !empty($ccreq->productivity_gain_explanation)) {
                fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:productivity_gain") . ". " . elgg_echo("nihcp_commons_credit_request:ccreq:productivity_gain:desc"));
                fput_eol($fh, "\t" . $ccreq->productivity_gain);
                fput_eol($fh, "\t" . $ccreq->productivity_gain_explanation);
            }
            if (!empty($ccreq->unique_resource_access) || !empty($ccreq->unique_resource_access_explanation)) {
                fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:unique_resource_access") . ". " . elgg_echo("nihcp_commons_credit_request:ccreq:unique_resource_access:desc"));
                fput_eol($fh, "\t" . $ccreq->unique_resource_access);
                fput_eol($fh, "\t" . $ccreq->unique_resource_access_explanation);
            }
            if (!empty($ccreq->past_experience) || !empty($ccreq->past_experience_explanation)) {
                fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:past_experience") . ". " . elgg_echo("nihcp_commons_credit_request:ccreq:past_experience:desc"));
                fput_eol($fh, "\t" . $ccreq->past_experience);
                fput_eol($fh, "\t" . $ccreq->past_experience_explanation);
            }

            if (!empty($ccreq->datasets) && !empty($ccreq->applications_tools) && !empty($ccreq->workflows)) {
                fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:digital_objects:desc"));
            }
            if (!empty($ccreq->datasets)) {
                fput_eol($fh, "Datasets");
                fput_eol($fh, "\t" . $ccreq->datasets);
                fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions"));
                if ($ccreq->datasets_has_access_restrictions === 'on') {
                    $har = 'Yes';
                } else {
                    $har = 'No';
                }
                fput_eol($fh, "\t" . $har);
            }

            if (!empty($ccreq->applications_tools)) {
                fput_eol($fh, "Applications/Tools");
                fput_eol($fh, "\t" . $ccreq->applications_tools);
                fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions"));
                if ($ccreq->applications_tools_has_access_restrictions === 'on') {
                    $har = 'Yes';
                } else {
                    $har = 'No';
                }
                fput_eol($fh, "\t" . $har);
            }

            if (!empty($ccreq->workflows)) {
                fput_eol($fh, "Workflows");
                fput_eol($fh, "\t" . $ccreq->workflows);
                fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions"));
                if ($ccreq->workflows_has_access_restrictions === 'on') {
                    $har = 'Yes';
                } else {
                    $har = 'No';
                }
                fput_eol($fh, "\t" . $har);
            }

            if (!empty($ccreq->digital_object_retention_plan)) {
                fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:digital_object_retention_plan") . ". " . elgg_echo("nihcp_commons_credit_request:ccreq:digital_object_retention_plan:desc"));
                fput_eol($fh, "\t" . $ccreq->digital_object_retention_plan);
            }

            fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:cloud_service_request") . ". " . elgg_echo("nihcp_commons_credit_request:ccreq:cloud_service_request:desc:plaintext"));

            fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:server_compute"));
            fput_eol($fh, "\t" . $ccreq->server_compute_expected_cost);
            fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:storage"));
            fput_eol($fh, "\t" . $ccreq->storage_expected_cost);

            fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:network_services"));
            fput_eol($fh, "\t" . $ccreq->network_services_expected_cost);
            fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:webservers"));
            fput_eol($fh, "\t" . $ccreq->web_servers_expected_cost);
            fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:databases"));
            fput_eol($fh, "\t" . $ccreq->databases_expected_cost);
            fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:other"));
            fput_eol($fh, "\t" . $ccreq->other_expected_cost);
            if ($ccreq->other_expected_cost_explanation) {
                fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:other_explanation"));
                fput_eol($fh, "\t" . $ccreq->other_expected_cost_explanation);
            }
            fput_eol($fh, elgg_echo("nihcp_commons_credit_request:ccreq:total_cost"));
            fput_eol($fh, "\t" . $ccreq->getExpectedCostTotal());

            $request_guid = $ccreq->guid;

            // Review Summary
            $acco = get_entity(AlignmentCommonsCreditsObjectives::getFromRequestGuid($request_guid));
            $gs_ds_guid = GeneralScore::getGeneralScoreDatasetsFromRequestGuid($request_guid);
            $gs_at_guid = GeneralScore::getGeneralScoreAppsToolsFromRequestGuid($request_guid);
            $gs_wf_guid = GeneralScore::getGeneralScoreWorkflowsFromRequestGuid($request_guid);

            $benefit_risk_scores = RiskBenefitScore::getRiskBenefitScoreEntitiesForRequest($request_guid);


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

            // if not everything is empty, show a review.
            if (!(empty($benefit_risk_scores) && empty($acco) && empty($gs_ds_guid) && empty($gs_at_guid) && empty($gs_wf_guid)
                && empty($fs_ds_guid) && empty($fs_at_guid) && empty($fs_wf_guid) && empty($final_recommendation) && $rbscore_empty
                && empty($feedback) && empty($feedback_history))
            ) {

                fput_eol($fh, "Review Summary");

                if ($acco) {

                    $acco_text = elgg_echo('nihcp_credit_request_review:crr:align_cc_obj') . " - ";
                    if ($acco->pass()) {
                        $acco_text .= "Pass";
                    } else {
                        $acco_text .= "Fail";
                    }

                    fput_eol($fh, $acco_text);
                }

                if ($gs_at_guid || $gs_ds_guid || $gs_wf_guid) {

                    if (!empty($gs_ds_guid)) {
                        generalScoreToFile($gs_ds_guid, $fh);
                    }

                    if (!empty($gs_at_guid)) {
                        generalScoreToFile($gs_at_guid, $fh);
                    }

                    if (!empty($gs_wf_guid)) {
                        generalScoreToFile($gs_wf_guid, $fh);
                    }
                }

                if (!empty($benefit_risk_scores) && !$rbscore_empty) {
                    fput_eol($fh, elgg_echo("nihcp_credit_request_review:crr:benefit_risk_score"));

                    foreach ($benefit_risk_scores as $br) {
                        if ($br->status === RiskBenefitScore::COMPLETED_STATUS) {
                            riskBenefitScoreToFile($br->guid, $fh);
                        }
                    }
                }

                fput_eol($fh, elgg_echo("nihcp_credit_request_review:crr:final_score"));
                fput_eol($fh, "\t" . round(FinalScore::calculateROI($request_guid)));

                if (!empty($fs_ds_guid)) {
                    finalScoreToFile($fs_ds_guid, $fh);
                }

                if (!empty($fs_at_guid)) {
                    finalScoreToFile($fs_at_guid, $fh);

                }

                if (!empty($fs_wf_guid)) {
                    finalScoreToFile($fs_wf_guid, $fh);

                }

                if ($final_recommendation) {
                    fput_eol($fh, "Final Review");

                    fput_eol($fh, elgg_echo('nihcp_credit_request_review:crr:final_recommendation'));
                    fput_eol($fh, "\t" . $final_recommendation->final_recommendation);


                    fput_eol($fh, "Comments");
                    if (empty($final_recommendation->final_recommendation_comments)) {
                        fput_eol($fh, "\tN/A");
                    } else {
                        fput_eol($fh, "\t" . $final_recommendation->final_recommendation_comments);
                    }
                }

                if ($feedback && in_array($ccreq->status, [CommonsCreditRequest::DENIED_STATUS, CommonsCreditRequest::APPROVED_STATUS])) {
                    fput_eol($fh, elgg_echo('nihcp_commons_credit_request:ccreq:feedback'));

                    fput_eol($fh, "Decision");
                    fput_eol($fh, "\t" . $feedback->decision);

                    fput_eol($fh, "Comments");
                    if (empty($feedback->comments)) {
                        fput_eol($fh, "\tN/A");
                    } else {
                        fput_eol($fh, "\t" . $feedback->comments);
                    }
                }

                if ($feedback_history && count($feedback_history) > 1) {
                    fput_eol($fh, "Decision Changelog");
                    foreach ($feedback_history as $prior_feedback) {

                        $status_change = $prior_feedback->getStatusChange();

                        if ($status_change) {

                            fput_eol($fh, "Changed from " . $status_change->from_status . " to " . $status_change->to_status);

                            fput_eol($fh, "Actor");
                            fput_eol($fh, "\t" . $status_change->getOwnerEntity()->getDisplayName());
                            fput_eol($fh, "Date");
                            $dt = date('D, d M Y H:i:s', $status_change->getTimeCreated());
                            fput_eol($fh, "\t" . $dt);
                            fput_eol($fh, "Reason");
                            fput_eol($fh, "\t" . $status_change->reason);
                            $next_feedback = $prior_feedback->getNextFeedback();

                            if ($next_feedback) {

                                fput_eol($fh, "New decision");

                                fput_eol($fh, "\t" . $next_feedback->decision);

                            }

                        }
                    }
                }


            }

            fput_eol($fh, "\r\n");
            fput_eol($fh, "\r\n");
        }
    }

    elgg_set_ignore_access($ia);

    $fo->close();
    forward(str_replace('view', 'download', $fo->getURL()));

}

function fputcsv_eol($fp, $array, $eol)
{
    fputcsv($fp, $array);
    if ("\n" != $eol && 0 === fseek($fp, -1, SEEK_CUR)) {
        fwrite($fp, $eol);
    }
}

function fput_eol($fp, $text)
{
    $newline = "\r\n";
    fputs($fp, $text);
    if ("\n" != $newline && 0 === fseek($fp, -1, SEEK_CUR)) {
        fwrite($fp, $newline);
    }
}

function generalScoreToFile($gs_guid, $fh) {
    $entity = get_entity($gs_guid);
    $class= $entity->digital_object_class;

    fput_eol($fh, elgg_echo('nihcp_credit_request_review:crr:general_score') . " for " . elgg_echo("nihcp_commons_credit_request:ccreq:$class"));

    fput_eol($fh, elgg_echo("nihcp_credit_request_review:crr:general_score:number_of_dos"));
    fput_eol($fh, "\t" . $entity->num_digital_objects);

    fput_eol($fh, elgg_echo("nihcp_credit_request_review:crr:mean_general_score"));
    fput_eol($fh, "\t" . round($entity->general_score));

    fput_eol($fh, "Comments");
    if (empty($entity->general_score_comments)) {
        fput_eol($fh, "\tN/A" );
    } else {
        fput_eol($fh, "\t" . $entity->general_score_comments);
    }
}

function riskBenefitScoreToFile($rb_guid, $fh) {
    $entity = get_entity($rb_guid);
    $do_class = $entity->class;

    fput_eol($fh, "Benefit and Risk Score for " . elgg_echo("nihcp_commons_credit_request:ccreq:$do_class"));

    fput_eol($fh, "Reviewer");
    fput_eol($fh, "\t" . RiskBenefitScore::getDomainExpertForRiskBenefitScore($entity->guid)->getDisplayName());

    fput_eol($fh, "Mean Benefit Score");
    fput_eol($fh, "\t" . $entity->benefit_score);

    fput_eol($fh, "Mean Risk Score");
    fput_eol($fh, "\t" . $entity->risk_score);

    fput_eol($fh, "Comments");
    if (empty($entity->comments)) {
        fput_eol($fh, "\tN/A" );
    } else {
        fput_eol($fh, "\t" . $entity->comments);
    }
}

function finalScoreToFile($fs_guid, $fh) {
    $entity = get_entity($fs_guid);

    $class_text = elgg_echo("nihcp_commons_credit_request:ccreq:" . $entity->class);

    fput_eol($fh, elgg_echo('nihcp_credit_request_review:crr:final_score:sv') . " for " . $class_text);

    fput_eol($fh, elgg_echo("nihcp_credit_request_review:crr:final_score:sbr"));
    fput_eol($fh, "\t" . round($entity->sbr));

    fput_eol($fh, elgg_echo("nihcp_credit_request_review:crr:final_score:sv"));
    fput_eol($fh, "\t" . round($entity->sv));

    fput_eol($fh, elgg_echo("nihcp_credit_request_review:crr:final_score:cf"));
    fput_eol($fh, "\t" . round($entity->cf));

}