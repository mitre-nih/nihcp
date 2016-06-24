<?php
	$request = elgg_extract('entity', $vars);
	$pricing_upload_file = get_entity($request->pricing_upload_guid);
	$supplementary_materials_upload_file = get_entity($request->supplementary_materials_upload_guid);



echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:project_title") . "</b>";
echo "<div>";
echo $request->project_title;
echo "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<b>Submitter</b>";
echo "<div>";
echo get_entity($request->owner_guid)->getDisplayName();
echo "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<b>Submission Date</b>";
echo "<div>";
echo $request->submission_date;
echo "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:grant_linkage") . "</b>";
echo "<div>";
echo $request->grant_linkage;
echo "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:proposed_research") . "</b>";
echo "<div>";
echo $request->proposed_research;
echo "</div>";
echo "</div>";

if (!empty($request->productivity_gain_explanation)) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:productivity_gain") . "</b>";
    echo "<div>";
    echo $request->productivity_gain;
    echo "</div>";
    echo "<div>";
    echo $request->productivity_gain_explanation;
    echo "</div>";
    echo "</div>";
}

if (!empty($request->unique_resource_access_explanation)) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:unique_resource_access") . "</b>";
    echo "<div>";
    echo $request->unique_resource_access;
    echo "</div>";
    echo "<div>";
    echo $request->unique_resource_access_explanation;
    echo "</div>";
    echo "</div>";
}

if (!empty($request->past_experience_explanation)) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:past_experience") . "</b>";
    echo "<div>";
    echo $request->past_experience;
    echo "</div>";
    echo "<div>";
    echo $request->past_experience_explanation;
    echo "</div>";
    echo "</div>";
}

if (!empty($request->datasets)) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:datasets") . "</b>";
    echo "<div>";
    echo $request->datasets;
    echo "</div>";
    echo "<div>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions") . "</b>";
    echo "</div>";
    echo "<div>";
    if ($request->datasets_has_access_restrictions === 'on') {
        echo 'Yes';
    } else {
        echo 'No';
    }
    echo "</div>";
    echo "</div>";
}

if (!empty($request->applications_tools)) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:applications_tools") . "</b>";
    echo "<div>";
    echo $request->applications_tools;
    echo "</div>";
    echo "<div>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions") . "</b>";
    echo "</div>";
    echo "<div>";
    if ($request->applications_tools_has_access_restrictions === 'on') {
        echo 'Yes';
    } else {
        echo 'No';
    }
    echo "</div>";
    echo "</div>";
}

if (!empty($request->workflows)) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:workflows") . "</b>";
    echo "<div>";
    echo $request->workflows;
    echo "</div>";
    echo "<div>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions") . "</b>";
    echo "</div>";
    echo "<div>";
    if ($request->workflows_has_access_restrictions === 'on') {
        echo 'Yes';
    } else {
        echo 'No';
    }
    echo "</div>";
    echo "</div>";
}

if (!empty($request->digital_object_retention_plan)) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:digital_object_retention_plan") . "</b>";
    echo "<div>";
    echo $request->digital_object_retention_plan;
    echo "</div>";
    echo "</div>";
}

echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:server_compute") . "</b>";
echo "<div>";
echo $request->server_compute_expected_cost;
echo "</div>";
echo "</div>";


echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:storage") . "</b>";
echo "<div>";
echo $request->storage_expected_cost;
echo "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:network_services") . "</b>";
echo "<div>";
echo $request->network_services_expected_cost;
echo "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:webservers") . "</b>";
echo "<div>";
echo $request->web_servers_expected_cost;
echo "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:databases") . "</b>";
echo "<div>";
echo $request->databases_expected_cost;
echo "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:other") . "</b>";
echo "<div>";
echo $request->other_expected_cost;
echo "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:total_cost") . "</b>";
echo "<div>";
echo $request->getExpectedCostTotal();
echo "</div>";
echo "</div>";

if($pricing_upload_file) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:pricing") . "</b>";
    echo "<div>";

    echo elgg_view("output/url", ['text' => $pricing_upload_file->title, 'href' => "/nihcp_commons_credit_request/attachment/$request->guid?file_guid=$pricing_upload_file->guid"]);

    echo "</div>";
    echo "</div>";
}

if($supplementary_materials_upload_file) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:supplementary_materials") . "</b>";
    echo "<div>";

    echo elgg_view("output/url", ['text' => $supplementary_materials_upload_file->title, 'href' => "/nihcp_commons_credit_request/attachment/$request->guid?file_guid=$supplementary_materials_upload_file->guid"]);

    echo "</div>";
    echo "</div>";
}

