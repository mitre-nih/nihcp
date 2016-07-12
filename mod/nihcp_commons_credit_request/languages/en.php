<?php

use \Nihcp\Entity\CommonsCreditRequest;

return [
	'nihcp_commons_credit_request' => 'Commons Credit Request',
	'nihcp_commons_credit_request:description' => 'Request Commons Credits here',
	'nihcp_commons_credit_request:widget:description' => 'Widget for requesting Commons Credits through the Commons Portal',
	'nihcp_commons_credit_request:menu' => 'Commons Credit Request',

	'item:object:commonscreditrequest' => 'Commons Credit Request',

	'nihcp_commons_credit_request:save:failed' => 'Unabled to save',
	'nihcp_commons_credit_request:ccreq:deletefiletooltip' => 'Delete file',

	// NIH Policies
	'nihcp_commons_credit_request:nih_policies:data_sharing' => 'NIH Data Sharing Policy and Implementation Guidance',
	'nihcp_commons_credit_request:nih_policies:data_sharing:website' => 'NIH Data Sharing Policy Website (i.e., FAQs, Data Sharing Workbook, related Guide Notices, etc.)',
	'nihcp_commons_credit_request:nih_policies:grants_policy:availability_information' => 'NIH Grants Policy Statement (Rev. 10/10) – Availability of Information (i.e. FOIA, Privacy Act, Access to Research Data)',
	'nihcp_commons_credit_request:nih_policies:grants_policy:availability_research_results' => 'NIH Grants Policy Statement (Rev. 10/10) – Availability of Research Results: Publications, Intellectual Property Rights, and Sharing Research Resources',
	'nihcp_commons_credit_request:nih_policies:amendment_a110' => 'NIH Notice of Amendment to A-110: (Shelby Amendment – Applicability, Definitions, Overview, FAQs)',
	'nihcp_commons_credit_request:nih_policies:grants_policy:access_research_data' => 'NIH Grants Policy Statement (Rev. 10/10) - Access to Research Data',

	'nihcp_commons_credit_request:ccreq:new' => 'New Request',
	'nihcp_commons_credit_request:ccreq:more' => 'More requests',
	'nihcp_commons_credit_request:ccreq:none' => 'No requests found',

	// Commons Credit Request form fields
	'nihcp_commons_credit_request:ccreq:required' => 'Required field',
    'nihcp_commons_credit_request:ccreq:answer_yes' => 'If Yes:',
    'nihcp_commons_credit_request:ccreq:access_restrictions' => 'Has access/usage restrictions',

    'nihcp_commons_credit_request:ccreq:project_title' => 'Project Title',
    'nihcp_commons_credit_request:ccreq:project_title:desc' => '(Limit '. CommonsCreditRequest::PROJECT_TITLE_MAX_LENGTH .' characters)',
    'nihcp_commons_credit_request:ccreq:grant_linkage' => 'Grant Linkage',
    'nihcp_commons_credit_request:ccreq:grant_linkage:desc' => 'Provide the active NIH Grant Title and NIH Grant ID. Add Institute Center (IC) information if this is an internal grant ' . '(Limit ' . CommonsCreditRequest::GRANT_LINKAGE_MAX_LENGTH . ' characters)',
    'nihcp_commons_credit_request:ccreq:proposed_research' => 'Proposed Research and Impact',
    'nihcp_commons_credit_request:ccreq:proposed_research:desc' => 'Describe and provide rationale for proposed research. Identify research communities impacted and likely to reuse the results of your research. Attach external document (s), if this field space is insufficient (Limit ' . CommonsCreditRequest::PROPOSED_RESEARCH_MAX_LENGTH . ' characters). Attachments should be uploaded as one zipped archive labelled "supporting documents"',
    'nihcp_commons_credit_request:ccreq:productivity_gain' => 'Productivity gain through the use of Commons',
    'nihcp_commons_credit_request:ccreq:productivity_gain:desc' => 'Provide your assessment of how much more research you can accomplish for the same credit award using Commons credit versus other approaches you would normally adopt ' . '(Limit ' . CommonsCreditRequest::PRODUCTIVITY_GAIN_MAX_LENGTH . ' characters)',
    'nihcp_commons_credit_request:ccreq:unique_resource_access' => 'Unique Resource Access',
    'nihcp_commons_credit_request:ccreq:unique_resource_access:desc' => 'Explain how the credit obtained will provide computational or data resources not currently available at your institution datacenter or laboratory ' . '(Limit ' . CommonsCreditRequest::UNIQUE_RESOURCE_ACCESS_MAX_LENGTH . ' characters)',
    'nihcp_commons_credit_request:ccreq:past_experience' => 'Past experience with Commons conformant objects',
    'nihcp_commons_credit_request:ccreq:past_experience:desc' => 'Describe research projects in which you have already used Commons objects ' . '(Limit ' . CommonsCreditRequest::PAST_EXPERIENCE__MAX_LENGTH . ' characters)',
    'nihcp_commons_credit_request:ccreq:digital_objects:desc' => 'Digital Object Classes such as Datasets, Applications/Tools, or Workflows that may be contributed to the Commons, should the credit be approved and research completed. For each of the following, please leave blank if not applicable.',
    'nihcp_commons_credit_request:ccreq:datasets' => 'Datasets',
    'nihcp_commons_credit_request:ccreq:datasets:desc' => 'List each Dataset with descriptions and potential value to the Commons. If possible, include information on the metadata, vocabularies, formats, models, dictionaries, normalization, accuracy and precision, data query and access mechanisms, size, and whether any of whether these Datasets is currently indexed ' . '(Limit ' . CommonsCreditRequest::DATASETS_MAX_LENGTH . ' characters)',
    'nihcp_commons_credit_request:ccreq:applications_tools' => 'Applications/Tools',
    'nihcp_commons_credit_request:ccreq:applications_tools:desc' => 'List all Application/Tool functionalities and potential value to the Commons. If possible, include information on compatible operating systems, input/output data vocabularies and formats, size footprint, existence of user and installation manuals, scalability, licensing issues, available source code, and whether any of these Apps./Tools are currently indexed ' . '(Limit ' . CommonsCreditRequest::APPLICATIONS_TOOLS_MAX_LENGTH . ' characters)',
    'nihcp_commons_credit_request:ccreq:workflows' => 'Workflows',
    'nihcp_commons_credit_request:ccreq:workflows:desc' => 'List all Workflows descriptions and potential value to the Commons. If possible, include information on compatible operating systems, input/output data vocabularies and formats, size footprint, existence of user and installation manuals, scalability, licensing issues, available source code, and whether any of these Workflows have been currently indexed ' . '(Limit ' . CommonsCreditRequest::WORKFLOWS_MAX_LENGTH . ' characters)',
    'nihcp_commons_credit_request:ccreq:digital_object_retention_plan' => 'Long-term digital objects retention plan',
    'nihcp_commons_credit_request:ccreq:digital_object_retention_plan:desc' => 'If digital objects are expected to be created in this plan, indicate plans for persistence ' . '(Limit ' . CommonsCreditRequest::DIGITAL_OBJECT_RETENTION_PLAN_MAX_LENGTH . ' characters)',
    'nihcp_commons_credit_request:ccreq:cloud_service_request' => 'Cloud Service Request',
    'nihcp_commons_credit_request:ccreq:cloud_service_request:desc' => 'Indicate the quantity and price for each service indicated below. You must append vendor calculator snapshots, documentation, or a filled <a href="' . elgg_get_site_url() . 'nihcp_commons_credit_request/service-request-worksheet">Service Worksheet</a> to substantiate credit requests. However, you are not limited to select vendor(s) used in making these resource estimates. Attachments for service estimation should be uploaded as one zipped archive labelled "service estimates"',
    'nihcp_commons_credit_request:ccreq:server_compute' => 'Server + Compute ($)',
    'nihcp_commons_credit_request:ccreq:storage' => 'Storage ($)',
    'nihcp_commons_credit_request:ccreq:network_services' => 'Network Services ($)',
    'nihcp_commons_credit_request:ccreq:webservers' => 'Webservers ($)',
    'nihcp_commons_credit_request:ccreq:databases' => 'Databases ($)',
    'nihcp_commons_credit_request:ccreq:other' => 'Other ($)',
	'nihcp_commons_credit_request:ccreq:other_explanation' => 'If other expected costs, please explain:',
	'nihcp_commons_credit_request:ccreq:other_explanation:desc' => '(Limit '. CommonsCreditRequest::OTHER_EXPECTED_COSTS_EXPLANATION .' characters)',
    'nihcp_commons_credit_request:ccreq:total_cost' => 'Total Cost ($)',
    'nihcp_commons_credit_request:ccreq:pricing' => 'Pricing Estimate Upload',
    'nihcp_commons_credit_request:ccreq:supplementary_materials' => 'Supplementary Materials Upload',

	// confirm screen
	'nihcp_commons_credit_request:ccreq:agreement_terms_and_conditions' => 'I agree to <a target="_blank" href="' . elgg_get_site_url() . 'nihcp_commons_credit_request/terms-and-conditions">Terms and Conditions</a>',
	'nihcp_commons_credit_request:ccreq:agreement_nih_policies_digital_objects' => 'I agree to abide with NIH policies governing a Digital Objects (DO). Please refer to the sidebar for details.',
	'nihcp_commons_credit_request:ccreq:agreement_biocaddie' => 'I agree to Index Digital Objects in <a target="_blank" href="https://biocaddie.org/">BioCADDIE</a>',

	// terms and conditions
	'nihcp_commons_credit_request:ccreq:terms_and_conditions' =>
			"This set of Terms and Conditions (“T&C”) documents requirements incumbent upon applicants
for Commons Credits that must be accepted as part of participation in the Commons Credits
Pilot.
The following conditions apply to all investigators applying for Commons Credits awards as part
of the pilot (“applicants”), whether or not they obtain awards:<br />
1. Applicant agrees to use the Commons Credits Web Portal to generate any request for
Commons Credits.<br />
2. Applicant acknowledges that only holders of active NIH grants on the first day of any
award cycle are eligible for submitting requests for Commons Credits.<br />
3. Applicant acknowledges that Commons Credits may only be used to obtain services
consistent with pilot objectives. Such services include, but are not limited to, cloud
storage, computations, virtual machines, as well as technical support consistent with the
use of such services.<br />
4. Applicant acknowledges that only cloud vendors admitted to the pilot by MITRE and NIH,
and who have previously both passed the list of Conformance Requirements and signed
the required business agreement with MITRE, may be utilized when using Commons
Credits. No other vendors may be used for the pilot.<br />
5. Applicant acknowledges that utilization of Commons Credits in the pilot must be reported
to MITRE and NIH on a periodic basis in accordance with detailed metrics and measures
as specified in the award and that this information will be aggregated with other data to
assess the feasibility of the Commons Credits approach.<br />
6. Applicant acknowledges that NIH, not MITRE Corporation, makes the final decisions
about awards and amounts of Credits. MITRE provides a triage process for the
applications, but does not provide final adjudication.<br />
7. Applicant accepts that the processes for determining awards may differ from
conventional NIH grant processes, and that this process is acceptable.<br />
8. Applicant agrees to ensure that credit requests are not made for work that directly
overlaps with work already covered by existing grant funding.<br />
9. Applicant accepts that there is no recourse to award decisions, and that there is no
process for protesting or disputing outcomes of this process.<br />
10. Applicant waives privacy of the award application, in that their name, project title,
amount of Commons Credits requested, or other relevant information may be made
public in final or interim project reports.<br />
11. Applicant accepts that pledges of data, tools, and workflows to be shared made during
the Commons Credits application process are expected to be honored if an award is
made, unless these artifacts are not usable owing to research failures. This sharing is
expected within 12 months of the date of award, or September 1, 2018, whichever
comes first.<br />
12. Applicant agrees to each credit request generating a separate and distinct funding
stream based on the work proposed in that request, and that funds between these
accounts cannot be comingled without NIH and MITRE consent.<br />
13. Applicant acknowledges that the Commons is not a Federal Data System.In addition to the conditions included above, the following additional conditions apply to
applicants who have been awarded Commons Credits for the purpose of participating in the
Pilot (hereafter called “awardees”):<br />
1. Awardee agrees to establish an account agreement all Cloud Vendors utilized by the
awardee during the pilot, and to be bound by the terms and conditions of that agreement<br />
2. Awardee agrees that the award amount constitutes the full amount of resources that will
be provided in the pilot for any individual application submission, and that any
expenditure in excess of this amount will be the responsibility of the Awardee. Awardee
may, however, supplement the Commons Credits award with their own funds (e.g.,
existing NIH grant award funds) at any time, though this will not be considered part of
the pilot process.<br />
3. Awardees agree to provide a “secondary surety” to all Cloud Vendors utilized by them in
the pilot (e.g., credit card) to cover all resources expended by them in excess of the
Commons Credits awarded.<br />
4. Awardee agrees to expend the Commons Credits awards provided to them within 12
months of the date of award, or September 1, 2018, whichever comes first.<br />
5. Awardee agrees to provide a post-implementation report of project outcomes within 12
months of the date of award, or September 1, 2018, whichever comes first.<br />
6. Awardee accepts responsibility for protecting personally identifiable information (PII) that
may reside within the data sets which they provide, use, or share during the pilot<br />
7. Awardee accepts responsibility for protecting intellectual property (IP) consistent with
their home institution’s rules and regulations, and with US law.<br />
8. Awardee acknowledges that while the Commons Credit Web Portal will provide monthly
updates to the amount of Credits remaining for any award, this information is not
provided on the same timescale in which charges are accrued. As such, awardees are
strongly encouraged to engage in more proactive monitoring of resources consumed,
including by, but not limited to, use of a cloud-vendor provided account dashboard.<br />
9. Awardee accepts to be bound by dispute resolution processes provided by participating
cloud vendors.<br />
10. An awardee may be suspended from the pilot, including loss of credits balance, upon
concurrence from MITRE and NIH for violating these terms and conditions, state or
federal law, or any other justifiable reason. Written notice will be provided. The
occurrence of this will result in the suspension of all Credits. This decision may not be
contested.<br />",

	'nihcp_commons_credit_request:ccreq:feedback' => "Feedback",

	'item:object:commonscreditcycle' => 'Submission Cycle',
	'nihcp_commons_credit_request:cycles' => 'Submission Cycles',
	'nihcp_commons_credit_request:cycles:view' => 'View Submission Cycles',
	'nihcp_commons_credit_request:cycles:none' => 'No cycles found',
	'nihcp_commons_credit_request:cycle:start' => 'Application Start',
	'nihcp_commons_credit_request:cycle:finish' => 'Application Finish',
	'nihcp_commons_credit_request:cycle:threshold' => 'Stratification Threshold',
	'nihcp_commons_credit_request:cycle:active' => 'Active',
	'nihcp_commons_credit_request:cycle:action' => 'Action',
	'nihcp_commons_credit_request:cycle:add' => 'Add',
	'nihcp_commons_credit_request:cycle:save:failed' => 'Unable to save cycle',
	'nihcp_commons_credit_request:cycle:save:success' => 'Saved cycle',
	'nihcp_commons_credit_request:cycle:noaccess' => 'You do not have permission to access this cycle',
	'nihcp_commons_credit_request:cycle:noactive' => 'No current available cycle. Please try again later.',
];