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
 
use Nihcp\Manager\RoleManager;
use Nihcp\Entity\RiskBenefitScore;

$request_guid = get_input('request_guid');
$file_guid = get_input('file_guid');

set_input("guid", $file_guid);

$ia = elgg_set_ignore_access();

$request = get_entity($request_guid);

$is_valid = $request->pricing_upload_guid == $file_guid || $request->supplementary_materials_upload_guid == $file_guid;

$is_submitted = $request->status !== \Nihcp\Entity\CommonsCreditRequest::DRAFT_STATUS;

elgg_set_ignore_access($ia);


// Allow access to:
// NIH Approvers, or Triage Coordinators, or Domain Experts have have assigned requests
if( ( nihcp_role_gatekeeper([RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR], false) || (nihcp_domain_expert_gatekeeper(false) && RiskBenefitScore::isDomainExpertAssignedToRequest(elgg_get_logged_in_user_entity(), $request_guid)) )
		&& $is_valid
		&& $is_submitted) {
	$ia = elgg_set_ignore_access();
}

include elgg_get_plugins_path().'/file/pages/file/download.php';

elgg_set_ignore_access($ia);