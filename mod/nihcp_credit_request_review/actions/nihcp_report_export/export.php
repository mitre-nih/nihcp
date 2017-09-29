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
	case 'PIR Summaries (text)':
		pir_summaries_text_export($cycle_guid);
		break;
	case 'PIR Summaries (csv)':
		pir_summaries_csv_export($cycle_guid);
	default:
		break;
}
