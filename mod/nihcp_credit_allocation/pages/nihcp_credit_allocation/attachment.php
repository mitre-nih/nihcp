<?php
use Nihcp\Manager\RoleManager;
use Nihcp\Entity\RiskBenefitScore;

$file_guid = get_input('file_guid');

set_input("guid", $file_guid);

$ia = elgg_get_ignore_access();

// Allow access to:
// Credit Administrators
if(nihcp_credit_admin_gatekeeper(false)) {
	$ia = elgg_set_ignore_access();
} else {
	register_error(elgg_echo("file:downloadfailed"));
	forward();
}

include elgg_get_plugins_path().'/file/pages/file/download.php';

elgg_set_ignore_access($ia);