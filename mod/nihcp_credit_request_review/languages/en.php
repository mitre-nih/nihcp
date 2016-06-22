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
	'nihcp_credit_request_review:crr:align_cc_obj:question2' => 'Is there an alignment with NIH Commons Goals?',
	'nihcp_credit_request_review:crr:align_cc_obj:question3' => 'Agreement to comply with NIH Policies governing Digital Objects?',
	'nihcp_credit_request_review:crr:align_cc_obj:question4' => 'Has there been an appropriate selection of Commons resources?',
	'nihcp_credit_request_review:crr:align_cc_obj:tooltip1' => 'Review the rationale for the proposed research, including the supporting documentation provided by the submitter. Do you agree that the targeted research communities are likely to reuse the data objects resulting from this research?',
	'nihcp_credit_request_review:crr:align_cc_obj:tooltip2' => 'Reusable biomedical Digital Objects are valuable artifacts resulting from NIH-funded research that should be preserved and leveraged for future work.  To facilitate reuse, a Digital Object must be FAIR compliant.  In addition, requesters are expected to make optimal use of the dynamic and elastic capabilities of Cloud services. Does the credit request show evidence of these two NIH aspirational goals?',
	'nihcp_credit_request_review:crr:align_cc_obj:tooltip3' => 'Does the requester agree to abide with the NIH Policies governing Digital Objects?  These are outlined in section 12 of the NIH Commons Credit Decision Framework and Process, and cover data sharing, implementation, intellectual property rights and access.',
	'nihcp_credit_request_review:crr:align_cc_obj:tooltip4' => 'Does the requester propose a selection of Commons resources commensurate with the research proposed in the request?  Guidance to estimating requisite storage, compute and data transfer rates is available in the credit market place pages of the Commons Credits Portal; additional information is described in sections 9 and 10 of the NIH Commons Credit Decision Framework and Process. Compare the estimates provided by the requester against justifiable resource levels.',

	'nihcp_credit_request_review:crr:general_score' => 'General Score',
	'nihcp_credit_request_review:crr:general_score:number_of_dos' => 'Number of Digital Objects',
	'nihcp_credit_request_review:crr:general_score:mean_score' => 'Mean Score (Enter number from 0.00 to 20.00)',

	'nihcp_credit_request_review:crr:benefit_risk_score' => 'Benefit and Risk Scores',
	'nihcp_credit_request_review:crr:risk_score' => 'Assign Mean Risk Score',
	'nihcp_credit_request_review:crr:benefit_score' => 'Assign Mean Benefit Score',

	'nihcp_credit_request_review:crr:final_score' => 'Scientific ROI',
	'nihcp_credit_request_review:crr:final_score:sbr' => 'Mean Scientific Benefit Ratio',
	'nihcp_credit_request_review:crr:final_score:sv' => 'Scientific Value',
	'nihcp_credit_request_review:crr:final_score:cf' => 'Computational Factor',

	'nihcp_credit_request_review:crr:final_recommendation' => 'Final Recommendation',

	'nihcp_credit_request_review:crr:decision:approve' => 'Approved',
	'nihcp_credit_request_review:crr:decision:deny' => 'Denied',

	'nihcp_credit_request_review:no_access' => 'Review not available.',
];