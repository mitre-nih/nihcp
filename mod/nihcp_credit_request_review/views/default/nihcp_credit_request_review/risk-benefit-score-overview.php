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
 


use Nihcp\Entity\CommonsCreditRequest;
use Nihcp\Entity\RiskBenefitScore;
use Nihcp\Manager\RoleManager;

echo "<h3>Benefit and Risk Scores</h3>";
echo "<br>";

$ia = elgg_set_ignore_access();


$request_guid = get_input("request_guid");
$request = get_entity($request_guid);
$project_title = $request->project_title;

// user is either a Domain Expert OR a Triage Coordinator/NIH Approver/Admin
$reviews = (nihcp_domain_expert_gatekeeper(false) && !elgg_is_admin_logged_in()) ?
    RiskBenefitScore::getEntitiesForRequestAndDomainExpert($request_guid, elgg_get_logged_in_user_guid()) :
    RiskBenefitScore::getRiskBenefitScoreEntitiesForRequest($request_guid);

// sort by DO class: Datasets, Apps/Tools, Workflows
// in groups as assigned to DEs
usort($reviews, function($a, $b) {


    if ($a === $b) {
        return 0;
    }

    $compare_de = strcasecmp(RiskBenefitScore::getDomainExpertForRiskBenefitScore($a->guid)->getDisplayName(), RiskBenefitScore::getDomainExpertForRiskBenefitScore($b->guid)->getDisplayName());


    if ($compare_de === 0) {

        if ($a->class === CommonsCreditRequest::DATASETS) { // always first
            return -1;
        } else if ($a->class === CommonsCreditRequest::WORKFLOWS) { // always last
            return 1;
        } else { // $a is a App/Tool, so depends on what $b is
            if ($b->class === CommonsCreditRequest::DATASETS) {
                return 1;
            } else if ($b->class === CommonsCreditRequest::WORKFLOWS) { // Workflows
                return -1;
            } else { // $b is also app/tool
                return 0;
            }
        }
    } else {
        return $compare_de;
    }

});

$content = "";

if (!empty($request_guid)) {
    echo "<div>";
    echo "<a class=\"elgg-button-submit elgg-button\" href=\" " . elgg_get_site_url() . "nihcp_credit_request_review/overview\">Back to Overview</a>";
    echo "</div>";
    echo "<br />";
    echo "<div>";
    $project_url = elgg_get_site_url() . "nihcp_credit_request_review/review/$request_guid";
    echo "Project : <a href=\"$project_url\">$project_title</a>";
    echo "</div>";
}

if (nihcp_triage_coordinator_gatekeeper(false) && $request->isEditable()) {
    echo "<a class=\"elgg-button-submit elgg-button\" href=\" " . elgg_get_site_url() . "nihcp_credit_request_review/assign-de/$request_guid\">Assign Domain Expert</a>";
}


$content .= "
<table class=\"elgg-table\">

<tr>
    <th>Class</th>
    <th>Reviewer</th>
    <th>Benefit Score / Risk Score</th>
    <th>Completed Date</th>
</tr>
";

foreach ($reviews as $review) {
    $de = RiskBenefitScore::getDomainExpertForRiskBenefitScore($review->getGUID());

    // DEs should only see reviews that they are assigned
    if (nihcp_role_gatekeeper(array(RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR), false) || elgg_get_logged_in_user_guid() == $de->getGUID()) {
        $content .= "<tr>";
        $content .= "<td>" . elgg_echo("nihcp_commons_credit_request:ccreq:$review->class") . "</td>";
        $content .= "<td>" . $de->getDisplayName() . "</td>";
        if ($review->status == RiskBenefitScore::COMPLETED_STATUS) {
            $rb_link_text = $review->benefit_score . " / " . $review->risk_score;
            $content .= "
        <td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/risk-benefit-score/" . $request->getGUID() . "/" . $review->getGUID() . "'>$rb_link_text</a></td>";
            $content .= "<td>" . $review->completed_date . "</td>";
        } else {
            if ($request->isComplete()) {
                $content .= "<td>Review over</td>";
            } else if (nihcp_domain_expert_gatekeeper(false)) {
                $content .= "<td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/risk-benefit-score/" . $request->getGUID() . "/" . $review->getGUID() . "'>Open</a></td>";
            } else {
                $content .= "<td>Not completed yet</td>";
            }
            $content .= "<td>N/A</td>";
        }
        $content .= "</tr>";
    }
}

$content .= "</table>";


echo $content;

elgg_set_ignore_access($ia);
