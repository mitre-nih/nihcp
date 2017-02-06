<?php
use Nihcp\Manager\RoleManager;
use Nihcp\Entity\RiskBenefitScore;

nihcp_role_gatekeeper(array(RoleManager::INVESTIGATOR, RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR, RoleManager::DOMAIN_EXPERT));

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