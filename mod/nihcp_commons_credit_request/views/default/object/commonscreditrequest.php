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
 

	$request = elgg_extract('entity', $vars);
	$pricing_upload_file = get_entity($request->pricing_upload_guid);
	$supplementary_materials_upload_file = get_entity($request->supplementary_materials_upload_guid);


echo "<div class='pvs'>";
    echo "<b>" . "CCREQ ID" . "</b>";
    echo "<div class='pvl phs'>";
    echo $request->getRequestId();
    echo "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:project_title") . "</b>";
    echo "<div class='pvl phs'>";
    echo $request->project_title;
    echo "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<b>Submitter</b>";
echo "<div class='pvl phs'>";
    $user = get_entity($request->owner_guid);
    echo "<div>";
    echo $user->getDisplayName();
    echo "</div>";

    if (nihcp_triage_coordinator_gatekeeper(false)) {
        echo "<div>";
        echo $user->email;
        echo "</div>";
    }
echo "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<b>Submission Date</b>";
echo "<div class='pvl phs'>";
echo $request->submission_date;
echo "</div>";
echo "</div>";

if ($request->grant_linkage) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:grant_linkage") . "</b>";
    echo "<div class='pvl phs'>";
    echo nl2br($request->grant_linkage);
    echo "</div>";
    echo "</div>";
}

echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:grant_id") . " </b>";
echo "<i>" . elgg_echo("nihcp_commons_credit_request:ccreq:grant_id:desc") . "</i>";
echo "<div class='pvl phs'>";
echo $request->grant_id;
echo "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:nih_program_officer_name") . " </b>";
echo "<i>" . elgg_echo("nihcp_commons_credit_request:ccreq:nih_program_officer_name:desc") . "</i>";
echo "<div class='pvl phs'>";
echo $request->nih_program_officer_name;
echo "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:nih_program_officer_email") . " </b>";
echo "<i>" . elgg_echo("nihcp_commons_credit_request:ccreq:nih_program_officer_email:desc") . "</i>";
echo "<div class='pvl phs'>";
echo $request->nih_program_officer_email;
echo "</div>";
echo "</div>";

if ($request->alt_grant_verification_contact) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:alt_grant_verification_contact") . " </b>";
    echo "<i>" . elgg_echo("nihcp_commons_credit_request:ccreq:alt_grant_verification_contact:desc") . "</i>";
    echo "<div class='pvl phs'>";
    echo $request->alt_grant_verification_contact;
    echo "</div>";
    echo "</div>";
}

if ($request->alt_grant_verification_contact_title) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:alt_grant_verification_contact_title") . " </b>";
    echo "<i>" . elgg_echo("nihcp_commons_credit_request:ccreq:alt_grant_verification_contact_title:desc") . "</i>";
    echo "<div class='pvl phs'>";
    echo $request->alt_grant_verification_contact_title;
    echo "</div>";
    echo "</div>";
}

if ($request->alt_grant_verification_contact_email) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:alt_grant_verification_contact_email") . " </b>";
    echo "<i>" . elgg_echo("nihcp_commons_credit_request:ccreq:alt_grant_verification_contact_email:desc") . "</i>";
    echo "<div class='pvl phs'>";
    echo $request->alt_grant_verification_contact_email;
    echo "</div>";
    echo "</div>";
}

echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:proposed_research") . " </b>";
echo "<i>" . elgg_echo("nihcp_commons_credit_request:ccreq:proposed_research:desc") . "</i>";
echo "<div class='pvl phs'>";
echo nl2br($request->proposed_research);
echo "</div>";
echo "</div>";

if($supplementary_materials_upload_file) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:supplementary_materials") . " </b>";
    echo "<div class='pvl phs'>";

        echo elgg_view("output/url", ['text' => $supplementary_materials_upload_file->title, 'href' => "/nihcp_commons_credit_request/attachment/$request->guid?file_guid=$supplementary_materials_upload_file->guid"]);

        echo "</div>";
    echo "</div>";
}


if (!empty($request->productivity_gain) || !empty($request->productivity_gain_explanation)) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:productivity_gain") . " </b>";
    echo "<i>"
        . elgg_echo("nihcp_commons_credit_request:ccreq:answer_yes") ." "
        . elgg_echo("nihcp_commons_credit_request:ccreq:productivity_gain:desc")
        . "</i>";
        echo "<div class='pvl phs'>";
            echo "<div>";
            echo $request->productivity_gain;
            echo "</div>";
            echo "<div>";
            echo nl2br($request->productivity_gain_explanation);
            echo "</div>";
        echo "</div>";
    echo "</div>";
}

if (!empty($request->unique_resource_access) || !empty($request->unique_resource_access_explanation)) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:unique_resource_access") . " </b>";
    echo "<i>"
        . elgg_echo("nihcp_commons_credit_request:ccreq:answer_yes") . " "
        . elgg_echo("nihcp_commons_credit_request:ccreq:unique_resource_access:desc")
        . "</i>";
        echo "<div class='pvl phs'>";
            echo "<div>";
            echo $request->unique_resource_access;
            echo "</div>";
            echo "<div>";
            echo nl2br($request->unique_resource_access_explanation);
            echo "</div>";
        echo "</div>";
    echo "</div>";
}

if (!empty($request->past_experience) || !empty($request->past_experience_explanation)) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:past_experience") . " </b>";
    echo "<i>"
        . elgg_echo("nihcp_commons_credit_request:ccreq:answer_yes") ." "
        . elgg_echo("nihcp_commons_credit_request:ccreq:past_experience:desc")
        . "</i>";
        echo "<div class='pvl phs'>";
            echo "<div>";
            echo $request->past_experience;
            echo "</div>";
            echo "<div>";
            echo nl2br($request->past_experience_explanation);
            echo "</div>";
        echo "</div>";
    echo "</div>";
}


echo "<label>" . elgg_echo("nihcp_commons_credit_request:ccreq:digital_objects:desc") . "</i>" . "</label>";



if (!empty($request->datasets)) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:datasets") . " </b>";
    echo "<i>" . elgg_echo("nihcp_commons_credit_request:ccreq:datasets:desc") . "</i>";
    echo "<div class='pvl phs'>";
    echo nl2br($request->datasets);
    echo "</div>";
    echo "<div>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions") . " </b>";
    echo "</div>";
    echo "<div class='pvl phs'>";
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
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:applications_tools") . " </b>";
    echo "<i>" . elgg_echo("nihcp_commons_credit_request:ccreq:applications_tools:desc") . "</i>";
    echo "<div class='pvl phs'>";
    echo nl2br($request->applications_tools);
    echo "</div>";
    echo "<div>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions") . " </b>";
    echo "</div>";
    echo "<div class='pvl phs'>";
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
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:workflows") . " </b>";
    echo "<i>" . elgg_echo("nihcp_commons_credit_request:ccreq:workflows:desc") . "</i>";
    echo "<div class='pvl phs'>";
    echo nl2br($request->workflows);
    echo "</div>";
    echo "<div>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:access_restrictions") . " </b>";
    echo "</div>";
    echo "<div class='pvl phs'>";
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
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:digital_object_retention_plan") . " </b>";
    echo "<i>" . elgg_echo("nihcp_commons_credit_request:ccreq:digital_object_retention_plan:desc") . "</i>";
    echo "<div class='pvl phs'>";
    echo nl2br($request->digital_object_retention_plan);
    echo "</div>";
    echo "</div>";
}

echo "<div class='pvm'>";
echo "<label>" . elgg_echo("nihcp_commons_credit_request:ccreq:cloud_service_request") . " </label>";
echo "<i>" . elgg_echo("nihcp_commons_credit_request:ccreq:cloud_service_request:desc") . "</i>";
echo "</div>";

if($pricing_upload_file) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:pricing") . " </b>";
    echo "<div class='pvl phs'>";

    echo elgg_view("output/url", ['text' => $pricing_upload_file->title, 'href' => "/nihcp_commons_credit_request/attachment/$request->guid?file_guid=$pricing_upload_file->guid"]);

    echo "</div>";
    echo "</div>";
}

echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:server_compute") . " </b>";
echo "<div class='pvl phs'>";
echo $request->server_compute_expected_cost;
echo "</div>";
echo "</div>";


echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:storage") . " </b>";
echo "<div class='pvl phs'>";
echo $request->storage_expected_cost;
echo "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:network_services") . " </b>";
echo "<div class='pvl phs'>";
echo $request->network_services_expected_cost;
echo "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:webservers") . " </b>";
echo "<div class='pvl phs'>";
echo $request->web_servers_expected_cost;
echo "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:databases") . " </b>";
echo "<div class='pvl phs'>";
echo $request->databases_expected_cost;
echo "</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:other") . " </b>";
echo "<div class='pvl phs'>";
echo $request->other_expected_cost;
echo "</div>";
echo "</div>";

if ($request->other_expected_cost_explanation) {
    echo "<div class='pvs'>";
    echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:other_explanation") . " </b>";
    echo "<div class='pvl phs'>";
    echo nl2br($request->other_expected_cost_explanation);
    echo "</div>";
    echo "</div>";
}

echo "<div class='pvs'>";
echo "<b>" . elgg_echo("nihcp_commons_credit_request:ccreq:total_cost") . " </b>";
echo "<div class='pvl phs'>";
echo $request->getExpectedCostTotal();
echo "</div>";
echo "</div>";




