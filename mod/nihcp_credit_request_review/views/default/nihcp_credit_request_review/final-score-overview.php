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

echo "<h3>". elgg_echo("nihcp_credit_request_review:crr:final_score") . "</h3>";
echo "<br>";

pseudo_atomic_set_ignore_access(function() {

    $request_guid = get_input("request_guid");
    $request = get_entity($request_guid);
    $project_title = $request->project_title;

    $has_datasets = !empty($request->datasets);
    $has_applications_tools = !empty($request->applications_tools);
    $has_workflows = !empty($request->workflows);

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

    $scientific_roi = round(FinalScore::calculateROI($request_guid));

    if (!empty($scientific_roi)) {
        echo elgg_echo("nihcp_credit_request_review:crr:final_score") . ": " . $scientific_roi;
    } else {
        echo elgg_echo("nihcp_credit_request_review:crr:final_score") . ": 0";
    }


    $content = "
	<table class=\"elgg-table\">
        <tr>
            <th>Class</th>
            <th>" . elgg_echo("nihcp_credit_request_review:crr:final_score:sbr") . "</th>
            <th>" . elgg_echo("nihcp_credit_request_review:crr:final_score:sv") . "</th>
            <th>" . elgg_echo("nihcp_credit_request_review:crr:final_score:cf") . "</th>
        </tr>
        ";

    if ($has_datasets) {
        $fs_datasets = get_entity(FinalScore::getFinalScoreDatasetsFromRequestGuid($request_guid));

        $content .=
            "<tr>";

        if (empty($fs_datasets)) {
            $content .= "
            <td>Datasets</td>
            <td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/final-score/" . $request_guid . "/datasets'>Open</a></td>
            <td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/final-score/" . $request_guid . "/datasets'>Open</a></td>
            <td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/final-score/" . $request_guid . "/datasets'>Open</a></td>
   ";
        } else {
            $content .= "
            <td>Datasets</td>
            <td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/final-score/" . $request_guid . "/datasets'>" . round($fs_datasets->sbr) . "</a></td>
            <td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/final-score/" . $request_guid . "/datasets'>" . round($fs_datasets->sv) . "</a></td>
            <td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/final-score/" . $request_guid . "/datasets'>" . round($fs_datasets->cf) . "</a></td>
   ";
        }
        $content .= "
        </tr>
    ";
    }

    if ($has_applications_tools) {
        $fs_appstools = get_entity(FinalScore::getFinalScoreAppsToolsFromRequestGuid($request_guid));
        $content .=
            "<tr>";

        if (empty($fs_appstools)) {
            $content .= "
            <td>Applications/Tools</td>
            <td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/final-score/" . $request_guid . "/applications_tools'>Open</a></td>
            <td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/final-score/" . $request_guid . "/applications_tools'>Open</a></td>
            <td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/final-score/" . $request_guid . "/applications_tools'>Open</a></td>
   ";
        } else {
            $content .= "
            <td>Applications/Tools</td>
            <td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/final-score/" . $request_guid . "/applications_tools'>" . round($fs_appstools->sbr) . "</a></td>
            <td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/final-score/" . $request_guid . "/applications_tools'>" . round($fs_appstools->sv) . "</a></td>
            <td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/final-score/" . $request_guid . "/applications_tools'>" . round($fs_appstools->cf) . "</a></td>
   ";
        }
        $content .= "
        </tr>
    ";
    }

    if ($has_workflows) {
        $fs_workflows = get_entity(FinalScore::getFinalScoreWorkflowsFromRequestGuid($request_guid));
        $content .=
            "<tr>";

        if (empty($fs_workflows)) {
            $content .= "
            <td>Workflows</td>
            <td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/final-score/" . $request_guid . "/workflows'>Open</a></td>
            <td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/final-score/" . $request_guid . "/workflows'>Open</a></td>
            <td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/final-score/" . $request_guid . "/workflows'>Open</a></td>
   ";
        } else {
            $content .= "
            <td>Workflows</td>
            <td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/final-score/" . $request_guid . "/workflows'>" . round($fs_workflows->sbr) . "</a></td>
            <td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/final-score/" . $request_guid . "/workflows'>" . round($fs_workflows->sv) . "</a></td>
            <td><a href='" . elgg_get_site_url() . "nihcp_credit_request_review/final-score/" . $request_guid . "/workflows'>" . round($fs_workflows->cf) . "</a></td>
   ";
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