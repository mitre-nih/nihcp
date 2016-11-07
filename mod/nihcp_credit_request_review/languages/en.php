<?php
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

	'nihcp_credit_request_review:no_access' => 'Review not available.',
	'nihcp_credit_request_review:no_review' => 'No review was made.',
];