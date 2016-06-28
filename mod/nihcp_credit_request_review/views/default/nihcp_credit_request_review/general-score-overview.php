<?php

echo "<h3>General Score</h3>";
echo "<br>";

pseudo_atomic_set_ignore_access(function() {

    $request_guid = get_input("request_guid");
    $request = get_entity($request_guid);
    $project_title = $request->project_title;

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

    $has_datasets = !empty($request->datasets);
    $has_applications_tools = !empty($request->applications_tools);
    $has_workflows = !empty($request->workflows);


    $content = "
	<table class=\"elgg-table\">
        <tr>
            <th>Class</th>
            <th>" . elgg_echo("nihcp_credit_request_review:crr:mean_general_score") . "</th>
            <th>Completed Date</th>
        </tr>
        ";

    if ($has_datasets) {

        $gs_datasets = get_entity(\Nihcp\Entity\GeneralScore::getGeneralScoreDatasetsFromRequestGuid($request_guid));
        $content .=
            "<tr>";
        $content .= "<td>Datasets</td>";
        if (empty($gs_datasets)) {
            $content .= "<td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/general-score/" . $request_guid . "/datasets'>Open</a></td>
                        <td>N/A</td>";
        } else {
            $score = round($gs_datasets->general_score);
            $content .= "<td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/general-score/" . $request_guid . "/datasets'>$score</a></td>
                        <td>$gs_datasets->completed_date</td>";
        }
        $content .= "
        </tr>
        ";

    }


    if ($has_applications_tools) {

        $gs_appstools = get_entity(\Nihcp\Entity\GeneralScore::getGeneralScoreAppsToolsFromRequestGuid($request_guid));
        $content .=
            "<tr>";
        $content .= "<td>Applications/Tools</td>";
        if (empty($gs_appstools)) {
            $content .= "<td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/general-score/" . $request_guid . "/applications_tools'>Open</a></td>
                        <td>N/A</td>";
        } else {
            $score = round($gs_appstools->general_score);
            $content .= "<td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/general-score/" . $request_guid . "/applications_tools'>$score</a></td>
                        <td>$gs_appstools->completed_date</td>";
        }
        $content .= "
        </tr>
        ";
    }

    if ($has_workflows) {

        $gs_workflows = get_entity(\Nihcp\Entity\GeneralScore::getGeneralScoreWorkflowsFromRequestGuid($request_guid));
        $content .=
            "<tr>";
        $content .= "<td>Workflows</td>";
        if (empty($gs_workflows)) {
            $content .= "<td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/general-score/" . $request_guid . "/workflows'>Open</a></td>
                        <td>N/A</td>";
        } else {
            $score = round($gs_workflows->general_score);
            $content .= "<td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/general-score/" . $request_guid . "/workflows'>$score</a></td>
                        <td>$gs_workflows->completed_date</td>";
        }
        $content .= "
        </tr>
        ";
    }

    $content .= "
    </table>
        ";


    echo $content;
});