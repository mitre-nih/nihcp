<?php
use Nihcp\Manager\RoleManager;
use Nihcp\Entity\RiskBenefitScore;

nihcp_role_gatekeeper(array(RoleManager::INVESTIGATOR, RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR, RoleManager::DOMAIN_EXPERT));

$dor_guid = get_input('request_guid');
$file_guid = get_input('file_guid');

set_input("guid", $file_guid);

// Allow access to:
// NIH Approvers, or Triage Coordinators
if( nihcp_role_gatekeeper([RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR], false) ) {
	$ia = elgg_set_ignore_access();
}

include elgg_get_plugins_path().'/file/pages/file/download.php';

elgg_set_ignore_access($ia);