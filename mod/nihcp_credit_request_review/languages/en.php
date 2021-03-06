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
 

/**
 * The core language file is in /languages/en.php and each plugin has its
 * language files in a languages directory. To change a string, copy the
 * mapping into this file.
 *
 * For example, to change the blog Tools menu item
 * from "Blog" to "Rantings", copy this pair:
 * 			'blog' => "Blog",
 * into the $mapping array so that it looks like:
 * 			'blog' => "Rantings",
 *
 * Follow this pattern for any other string you want to change. Make sure this
 * plugin is lower in the plugin list than any plugin that it is modifying.
 *
 * If you want to add languages other than English, name the file according to
 * the language's ISO 639-1 code: http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
 */

return [
	'nihcp_credit_request_review' => 'Credit Request Review',
	'nihcp_credit_request_review:widget:description' => 'Plugin for reviewing Commons Credit Requests in the Commons Portal.',
	'nihcp_credit_request_review:menu' => 'Credit Request Review ',
	'nihcp_credit_request_review:crr:more' => 'View Requests',

	'nihcp_credit_request_review:crr:align_cc_obj' => 'Alignment with Commons Credit Objectives',
	'nihcp_credit_request_review:crr:align_cc_obj:question1' => 'Are the anticipated accomplishments valuable?',
	'nihcp_credit_request_review:crr:align_cc_obj:tooltip1' => 'Review the rationale for the proposed research, including the supporting documentation provided by the submitter. Do you agree that the targeted research communities are likely to reuse the data objects resulting from this research?',

	'nihcp_credit_request_review:crr:general_score' => 'General Score',
	'nihcp_credit_request_review:crr:mean_general_score' => 'Mean General Score',
	'nihcp_credit_request_review:crr:general_score:number_of_dos' => 'Number of Digital Objects',
	'nihcp_credit_request_review:crr:general_score:range' => '(Enter number from 0.00 to 20.00)',

	'nihcp_credit_request_review:crr:benefit_risk_score' => 'Benefit and Risk Scores',
	'nihcp_credit_request_review:crr:risk_score' => 'Assign Mean Risk Score',
	'nihcp_credit_request_review:crr:benefit_score' => 'Assign Mean Benefit Score',

	'nihcp_credit_request_review:crr:final_score' => 'Scientific ROI',
	'nihcp_credit_request_review:crr:final_score:sbr' => 'Mean Scientific Benefit Ratio',
	'nihcp_credit_request_review:crr:final_score:sv' => 'Total Scientific Value',
	'nihcp_credit_request_review:crr:final_score:cf' => 'Computational Factor',

	'nihcp_credit_request_review:crr:final_recommendation' => 'Final Recommendation',
	'nihcp_credit_request_review:crr:final_recommendation:save' => 'Complete Triage',

	'nihcp_credit_request_review:crr:decision:approve' => 'Approved',
	'nihcp_credit_request_review:crr:decision:deny' => 'Denied',
	'nihcp_credit_request_review:crr:decision:save' => 'Complete Adjudication',

    'nihcp_credit_request_verify' => 'Grant ID Validation',
    'nihcp_credit_request_review:crr:validate:is_active' => 'Is the Grant ID listed in this CCREQ active?',
    'nihcp_credit_request_review:crr:validate:is_active_comment' => 'Comment',

	'nihcp_credit_request_review:no_access' => 'Review not available.',
	'nihcp_credit_request_review:no_review' => 'No review was made.',

	'nihcp_export_email_addr' => 'Export Email Addresses',
	'nihcp_export_email_addr:widget:description' => 'Widget used to export portal users\' email addresses.',

	'nihcp_report_export' => 'Export Reports',
	'nihcp_report_export:widget:description' => 'Widget used to export various usage reports.',
	'nihcp_report_export:type' => 'Report Type',
	'nihcp_report_export:no_data' => 'No report data in the selected cycle.',
	'nihcp_report_export:no_domain_experts' => 'No Domain Expert assigned in this cycle.',

    'nihcp_credit_request_review:crr:table_summary' => 'This table allows you to review the credit requests.',
    'nihcp_credit_request_review:crr:search_label:search' => 'Showing results for: ',
    'nihcp_credit_request_review:crr:search_label:cycle' => 'Showing results for selected cycle.',


];
