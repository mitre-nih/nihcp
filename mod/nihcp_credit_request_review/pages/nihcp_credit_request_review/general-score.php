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

use Nihcp\Manager\RoleManager;

nihcp_role_gatekeeper(array(RoleManager::DOMAIN_EXPERT, RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR));



$ia = elgg_set_ignore_access(true);
// guid of ccreq under review
$request_guid = get_input('request_guid');
$request_entity = get_entity($request_guid);

$review_class = get_input('review_class');
$review_class_text = '';
$gs_guid = null;
switch($review_class) {
    case 'datasets':
        $gs_guid = \Nihcp\Entity\GeneralScore::getGeneralScoreDatasetsFromRequestGuid($request_guid);
        break;
    case 'applications_tools':
        $gs_guid = \Nihcp\Entity\GeneralScore::getGeneralScoreAppsToolsFromRequestGuid($request_guid);
        break;
    case 'workflows':
        $gs_guid = \Nihcp\Entity\GeneralScore::getGeneralScoreWorkflowsFromRequestGuid($request_guid);
        break;
    default:
        break;
}

$gs_entity = get_entity($gs_guid);


// editable view only for Triage Coordinators and available only while a review is not fully completed.
if (nihcp_triage_coordinator_gatekeeper(false) && $request_entity->status != CommonsCreditRequest::COMPLETED_STATUS) {

    $content = elgg_view_form('general-score', null, array(
        'request_guid' => $request_guid,));

// if there is an entity, show the non-editable object view
// Domain Experts should see reviews for requests they have been assigned
// Triage Coordinators and NIH Approvers should see reviews for completed reviews
} else if (!empty($gs_entity)
    && ( (nihcp_domain_expert_gatekeeper(false) && in_array(elgg_get_logged_in_user_entity(), RiskBenefitScore::getAssignedDomainExperts($request_guid)))
        || (nihcp_role_gatekeeper(array(RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR), false) && $request_entity->isComplete()) )
) {

    $content = elgg_view_entity($gs_entity);
} else {

    $content = elgg_echo('nihcp_credit_request_review:no_access');
}

$params = array(
    'title' => elgg_echo("nihcp_credit_request_review"),
    'content' => $content,
    'filter' => '',
);

$body = elgg_view_layout('one_column', $params);

elgg_set_ignore_access($ia);
echo elgg_view_page("nihcp_credit_request_review", $body);
