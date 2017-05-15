<?php

require_once(elgg_get_plugins_path() . "nihcp_credit_request_review/lib/functions.php");

use Nihcp\Manager\RoleManager;


nihcp_role_gatekeeper(array(RoleManager::TRIAGE_COORDINATOR, RoleManager::NIH_APPROVER));

$cycle_guid = get_input('nihcp-ccreq-cycle-select');
$report_type = get_input('nihcp-ccreq-report-select');

switch ($report_type) {
	case 'Triage Report':
		triage_report_export($cycle_guid);
		break;
	case 'Pledged Digital Objects':
		pledged_digital_objects_export($cycle_guid);
		break;
	case 'Tracking Sheet':
		tracking_sheet_export($cycle_guid);
		break;
	case 'Domain Expert List':
		domain_expert_list_export($cycle_guid);
		break;
	case 'How Did You Learn About The Portal':
		how_did_you_hear_export();
		break;
	case 'CCREQ Summaries':
		ccreq_summaries_export($cycle_guid);
		break;
	default:
		break;
}