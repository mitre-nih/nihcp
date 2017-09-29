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
use \Nihcp\Entity\PostImplementationReport;

$cycle_guid = sanitize_string(elgg_extract('cycle_guid', $vars));
$session = elgg_get_session();
$session->set('crr_prev_selected_cycle', $cycle_guid);
$ia = elgg_set_ignore_access();
$cycle = get_entity($cycle_guid);

if (nihcp_triage_coordinator_gatekeeper(false) || nihcp_nih_approver_gatekeeper(false)) { //reviewer view
    $search_term = sanitise_string(elgg_extract('search_term',$vars));
    if ($search_term) {
        $ccreqs = CommonsCreditRequest::searchByTitle($search_term);
    } else {
        $ccreqs = CommonsCreditRequest::getByCycle($cycle_guid);
    }
} else { // investigator view
    $ccreqs = CommonsCreditRequest::getByUserAndCycle(CommonsCreditRequest::APPROVED_STATUS, 0, $cycle_guid);
}

$content = "<div>";


// reviewer view
if (nihcp_triage_coordinator_gatekeeper(false) || nihcp_nih_approver_gatekeeper(false)) {
    if (empty($ccreqs)) {
        $content .= "No approved applications for this cycle. Select a different cycle.";
    } else {

        $content .= "<div>";
        if($search_term){
            $content .= elgg_echo('nihcp_credit_request_review:crr:search_label:search') . $search_term;
        }else{
            $content .= elgg_echo('nihcp_credit_request_review:crr:search_label:cycle');
        }

        $content .= "</div>";

        $content .= "<table id=\"pir-overview-table\" class =\"elgg-table\" summary=\"List of PIRs for selected cycle.\">";
        $content .= "<thead><tr><th scope='col'><b>Project Name</b></th><th scope='col'><b>CCREQ ID</b></th><th scope='col'><b>Investigator</b></th><th scope='col'><b>Report</b></th></tr></thead>";
        $content .= "<tbody>";

        foreach ($ccreqs as $ccreq) {


            $pir_guid = PostImplementationReport::getPirGuidFromCcreqGuid($ccreq->guid);
            $pir_link = elgg_get_site_url() . "nihcp_post_implementation_report/pir/" . $ccreq->guid;
            if (empty($pir_guid) || get_entity($pir_guid)->status !== "Submitted") { // no pir to view yet
                $report_link = "N/A";
            } else { //PIR submitted
                $report_link = "<button class=\"pir-edit-button\" onclick='location.href=\"$pir_link\"'>View</button>";
            }

            $content .=  "<tr><td><a href=\""
                . elgg_get_site_url()
                . "nihcp_commons_credit_request/request/$ccreq->guid\">$ccreq->project_title</a></td>"
                . "<td>" . $ccreq->getRequestId() . "</td>"
                . "<td>" . $ccreq->getOwnerEntity()->getDisplayName() . "</td>"
                . "<td>$report_link</td></tr>";
        }

        $content .= "</tbody></table>";

    }
} else { // investigator view

    if (empty($ccreqs)) {
        $content .= "No approved applications for this cycle. Select a different cycle.";
    } else {

        $content .= "<table class =\"elgg-table\" summary=\"List of PIRs for selected cycle.\">";

        $content .= "<thead><tr><th>Project Name</th><th>CCREQ ID</th><th>Status</th></tr></thead>";
        $content .= "<tbody>";

        foreach ($ccreqs as $ccreq) {

            $status_action = "";
            $pir = get_entity(PostImplementationReport::getPirGuidFromCcreqGuid($ccreq->guid));
            // url path includes guid for ccreq
            $pir_link = elgg_get_site_url() . "nihcp_post_implementation_report/pir/" . $ccreq->guid;
            if (empty($pir)) {
                $status_action = "<button class=\"pir-edit-button\" onclick='location.href=\"$pir_link\"'>Add</button>";
            } else if ($pir->status === 'Draft') {
                $status_action = "<button class=\"pir-edit-button\" onclick='location.href=\"$pir_link\"'>Edit</button>";
            } else { // submitted PIR
                $status_action = "<a href='$pir_link'>Submitted</a>";
            }

            $content .= "<tr><td><a href=\""
                . elgg_get_site_url()
                . "nihcp_commons_credit_request/request/$ccreq->guid\">$ccreq->project_title</a></td>"
                . "<td>" . $ccreq->getRequestId() . "</td>"
                . "<td>$status_action</td></tr>";
        }

        $content .= "</tbody></table>";

    }
}
$content .= "</div>";

echo $content;

elgg_set_ignore_access($ia);