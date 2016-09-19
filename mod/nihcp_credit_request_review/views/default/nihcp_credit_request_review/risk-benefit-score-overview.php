<?php


use Nihcp\Entity\RiskBenefitScore;
use Nihcp\Manager\RoleManager;

echo "<h3>Benefit and Risk Scores</h3>";
echo "<br>";

pseudo_atomic_set_ignore_access(function() {


    $request_guid = get_input("request_guid");
    $request = get_entity($request_guid);
    $project_title = $request->project_title;

    $reviews = nihcp_domain_expert_gatekeeper(false) && !elgg_is_admin_logged_in() ?
		RiskBenefitScore::getEntitiesForRequestAndDomainExpert($request_guid, elgg_get_logged_in_user_guid()) :
		RiskBenefitScore::getRiskBenefitScoreEntitiesForRequest($request_guid);


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
        if (nihcp_role_gatekeeper(array(RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR), false) || elgg_get_logged_in_user_entity() == $de) {
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
});