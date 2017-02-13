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
	'nihcp_commons_credit_request:nih_policies' => 'NIH Policies',
	'nihcp_commons_credit_request:nih_policies:data_sharing' => 'NIH Data Sharing Policy and Implementation Guidance',
	'nihcp_commons_credit_request:nih_policies:data_sharing:website' => 'NIH Data Sharing Policy Website (i.e., FAQs, Data Sharing Workbook, related Guide Notices, etc.)',
	'nihcp_commons_credit_request:nih_policies:grants_policy:availability_information' => 'NIH Grants Policy Statement (Rev. 10/10) – Availability of Information (i.e. FOIA, Privacy Act, Access to Research Data)',
	'nihcp_commons_credit_request:nih_policies:grants_policy:availability_research_results' => 'NIH Grants Policy Statement (Rev. 10/10) – Availability of Research Results: Publications, Intellectual Property Rights, and Sharing Research Resources',
	'nihcp_commons_credit_request:nih_policies:amendment_a110' => 'NIH Notice of Amendment to A-110: (Shelby Amendment – Applicability, Definitions, Overview, FAQs)',
	'nihcp_commons_credit_request:nih_policies:grants_policy:access_research_data' => 'NIH Grants Policy Statement (Rev. 10/10) - Access to Research Data',

	'nihcp_commons_credit_request:ccreq:new' => 'New Request',
	'nihcp_commons_credit_request:ccreq:more' => 'More requests',
	'nihcp_commons_credit_request:ccreq:more_and_allocate' => 'View requests / Allocate credits',
	'nihcp_commons_credit_request:ccreq:none' => 'No requests found',

	// Commons Credit Request form fields
	'nihcp_commons_credit_request:ccreq:required' => 'Required field',
    'nihcp_commons_credit_request:ccreq:answer_yes' => 'If Yes:',
    'nihcp_commons_credit_request:ccreq:access_restrictions' => 'Has access/usage restrictions',

    'nihcp_commons_credit_request:ccreq:project_title' => 'Project Title',
    'nihcp_commons_credit_request:ccreq:project_title:desc' => '(Limit '. CommonsCreditRequest::PROJECT_TITLE_MAX_LENGTH .' characters)',

	// Grant Linkage Fields
	'nihcp_commons_credit_request:ccreq:grant_linkage' => 'Grant Linkage',
	'nihcp_commons_credit_request:ccreq:nih_program_officer_name' => 'NIH Program Officer Name',
	'nihcp_commons_credit_request:ccreq:nih_program_officer_email' => 'NIH Program Officer Email',
	'nihcp_commons_credit_request:ccreq:alt_grant_verification_contact' => 'Alternative Grant Verification Contact',
	'nihcp_commons_credit_request:ccreq:alt_grant_verification_contact_title' => 'Alternative Grant Verification Contact Title',
	'nihcp_commons_credit_request:ccreq:alt_grant_verification_contact_email' => 'Alternative Grant Verification Contact Email',
	'nihcp_commons_credit_request:ccreq:grant_id' => 'Grant ID',
    'nihcp_commons_credit_request:ccreq:nih_program_officer_name:desc' => '(First Middle Last) If there is no program officer for this grant, please put N/A and fill out the Alternative Grant Verification Contact fields ' . '(Limit ' . CommonsCreditRequest::GRANT_LINKAGE_MAX_LENGTH . ' characters)',
	'nihcp_commons_credit_request:ccreq:nih_program_officer_email:desc' => 'Provide active NIH-official email account. This is required for validation of your NIH-awarded grant ' . '(Limit ' . CommonsCreditRequest::GRANT_LINKAGE_MAX_LENGTH . ' characters)',
	'nihcp_commons_credit_request:ccreq:alt_grant_verification_contact:desc' => '(First Middle Last) This field is only required if a program officer is not declared. Please provide the full name of your NIH-sponsored contact that distributed official confirmation of your award ' . '(Limit ' . CommonsCreditRequest::GRANT_LINKAGE_MAX_LENGTH . ' characters)',
	'nihcp_commons_credit_request:ccreq:alt_grant_verification_contact_title:desc' => 'This field is only required if a program officer is not declared. Provide the current position title of your alternative grant verification contact ' . '(Limit ' . CommonsCreditRequest::GRANT_LINKAGE_MAX_LENGTH . ' characters)',
	'nihcp_commons_credit_request:ccreq:alt_grant_verification_contact_email:desc' => 'This field is only required if a program officer is not declared. Provide active institutional email account of your contact ' . '(Limit ' . CommonsCreditRequest::GRANT_LINKAGE_MAX_LENGTH . ' characters)',
	'nihcp_commons_credit_request:ccreq:grant_id:desc' => 'Check that the listed Grant ID can be validated in NIH RePORTER. The Grant ID in NIH RePORTER (<a href="https://projectreporter.nih.gov/reporter.cfm">https://projectreporter.nih.gov/reporter.cfm</a>) must populate active identified grant.' ,
    'nihcp_commons_credit_request:ccreq:grant_id:rationale' => 'The grant ID was not found in NIH RePORTER, please describe the rationale here. In some circumstances, validation of your grant may be required by your project officer '. '(Limit ' . CommonsCreditRequest::GRANT_LINKAGE_MAX_LENGTH . ' characters)',
    'nihcp_commons_credit_request:ccreq:grant_id:invalid' => 'The grant ID was not found in NIH RePORTER, please describe the rationale.',

	'nihcp_commons_credit_request:ccreq:proposed_research' => 'Proposed Research and Impact',
    'nihcp_commons_credit_request:ccreq:proposed_research:desc' => 'Describe and provide rationale for proposed research. Identify research communities impacted and likely to reuse the results of your research. Attach external document (s), if this field space is insufficient. Attach any Data Use Agreements (DUA) here if applicable. (Limit ' . CommonsCreditRequest::PROPOSED_RESEARCH_MAX_LENGTH . ' characters). Attachments should be uploaded as one zipped archive labeled "supporting documents"',
    'nihcp_commons_credit_request:ccreq:productivity_gain' => 'Productivity gain through the use of Commons',
    'nihcp_commons_credit_request:ccreq:productivity_gain:desc' => 'Provide your assessment of how much more research you can accomplish for the same credit award using Commons credits versus other approaches you would normally adopt ' . '(Limit ' . CommonsCreditRequest::PRODUCTIVITY_GAIN_MAX_LENGTH . ' characters)',
    'nihcp_commons_credit_request:ccreq:unique_resource_access' => 'Unique Resource Access',
    'nihcp_commons_credit_request:ccreq:unique_resource_access:desc' => 'Explain how the credit obtained will provide computational or data resources not currently available at your institution datacenter or laboratory ' . '(Limit ' . CommonsCreditRequest::UNIQUE_RESOURCE_ACCESS_MAX_LENGTH . ' characters)',
    'nihcp_commons_credit_request:ccreq:past_experience' => 'Past experience with Commons conformant objects',
    'nihcp_commons_credit_request:ccreq:past_experience:desc' => 'Describe research projects in which you have already used Commons objects ' . '(Limit ' . CommonsCreditRequest::PAST_EXPERIENCE__MAX_LENGTH . ' characters)',
    'nihcp_commons_credit_request:ccreq:digital_objects:desc' => 'Digital Objects generated from biomedical research such as Datasets, Applications/Tools, or Workflows that are pledged to the Commons, should the credit be approved and research completed. Investigators are not expected to share all Data Objects generated by this research, but rather the Data Objects that may be deemed valuable to the Commons community, which is at the discretion of the researcher. For each of the following, please leave blank if not applicable.',
    'nihcp_commons_credit_request:ccreq:datasets' => 'Datasets',
    'nihcp_commons_credit_request:ccreq:datasets:desc' => 'List each Dataset with descriptions and potential value to the Commons. If possible, include information on the metadata, vocabularies, formats, models, dictionaries, normalization, accuracy and precision, data query and access mechanisms, size, and whether any of these Datasets is currently indexed ' . '(Limit ' . CommonsCreditRequest::DATASETS_MAX_LENGTH . ' characters)',
    'nihcp_commons_credit_request:ccreq:applications_tools' => 'Applications/Tools',
    'nihcp_commons_credit_request:ccreq:applications_tools:desc' => 'List all Application/Tool functionalities and potential value to the Commons. If possible, include information on compatible operating systems, input/output data vocabularies and formats, size footprint, existence of user and installation manuals, scalability, licensing issues, available source code, and whether any of these Apps./Tools is currently indexed ' . '(Limit ' . CommonsCreditRequest::APPLICATIONS_TOOLS_MAX_LENGTH . ' characters)',
    'nihcp_commons_credit_request:ccreq:workflows' => 'Workflows',
    'nihcp_commons_credit_request:ccreq:workflows:desc' => 'List all Workflows descriptions and potential value to the Commons. If possible, include information on compatible operating systems, input/output data vocabularies and formats, size footprint, existence of user and installation manuals, scalability, licensing issues, available source code, and whether any of these Workflows has been currently indexed ' . '(Limit ' . CommonsCreditRequest::WORKFLOWS_MAX_LENGTH . ' characters)',
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
	'nihcp_commons_credit_request:ccreq:agreement_nih_policies_digital_objects' => 'I agree to abide with NIH policies governing Digital Objects (DO). Please refer to the sidebar for details.',
	'nihcp_commons_credit_request:ccreq:agreement_biocaddie' => 'I agree to Index Digital Objects in <a target="_blank" href="https://biocaddie.org/">bioCADDIE</a>',


	// Delegation
	'nihcp_commons_credit_request:delegate' => 'Delegation',
	'nihcp_commons_credit_request:delegate:email' => 'Delegate Email',
	'nihcp_commons_credit_request:delegate:message' => 'Message',
	'nihcp_commons_credit_request:delegate:pending' => 'Pending',
	'nihcp_commons_credit_request:delegate:invalid' => 'Pending', // intentionally the same as pending message for email privacy
	'nihcp_commons_credit_request:delegate:delegated' => 'Delegated',
	'nihcp_commons_credit_request:delegate:review' => 'PI Review',
	'nihcp_commons_credit_request:delegate:declined' => 'Declined',
	'nihcp_commons_credit_request:delegate:submitted' => 'Submitted',
	'nihcp_commons_credit_request:delegate:self_delegation_error' => 'You cannot delegate yourself.',
	'nihcp_commons_credit_request:delegate:instructions' => 'By delegating a CCREQ application, a PI is providing a designated delegate of their choice with “edit ownership” of that application.  The PI will no longer have edit privileges on that CCREQ, including the ability to submit the CCREQ, until the delegate returns ownership to them or they recall the delegation.
<br>
<br>
The designated delegate must be registered with an active account in the Commons Credits Pilot Portal at the time of delegation. The designated delegate will receive an email notifying them of their new access at the time of delegation, and must accept this role to proceed. If the designated delegate does not accept an invitation, the PI can recall the delegation and regain full control.
<br>
<br>
Please note:  with or without delegation, only PIs with active NIH grants at the time of credits distribution (the end of a cycle) can submit CCREQ applications.  Delegation does not change this requirement, nor does it change any deadline for submission.
<br>
<br>',
	'nihcp_commons_credit_request:delegate:request:subject' => "You have been assigned as a NIH Commons Credits Request delegate",
	'nihcp_commons_credit_request:delegate:revoke:subject' => "Your role as delegate on a NIH Commons Credits Request has been revoked",
	'nihcp_commons_credit_request:delegate:request:description' => '%s has assigned you as a delegate to this application for this CCREQ for the project titled "%s". Do you accept this delegation?',
	'nihcp_commons_credit_request:delegate:revoke:description' => '%s has removed you as a delegate to the application for the CCREQ for the project titled "%s"',
	'nihcp_commons_credit_request:delegate:request:accept' => 'Yes, I accept delegation.',
	'nihcp_commons_credit_request:delegate:request:decline' => 'No, I decline delegation.',

	// terms and conditions
	'nihcp_commons_credit_request:ccreq:terms_and_conditions' =>
			"


<div class=WordSection1>

<p class=MsoNormal align=center style='text-align:center'><b><span
style='font-size:12.0pt;line-height:107%;font-family:Arial'>INVESTIGATOR <span
style='text-transform:uppercase'>Program PARTICIPATiON Requirements for
participation in the Commons Credits Pilot</span></span></b></p>

<p class=MsoNormal align=center style='text-align:center'><b><span
style='font-size:12.0pt;line-height:107%;font-family:Arial'>&nbsp;</span></b></p>

<p class=MsoNormal><b><span style='font-family:Arial'>INTRODUCTION TO THE
COMMONS CREDITS PILOT</span></b></p>

<p class=MsoNormal><span style='font-family:Arial'>These Program Participation
Requirements for the Commons Credits Pilot (hereafter sometimes \"the Pilot\" or
\"Pilot\") sets out the obligations that applicants for funding in the form of
Commons Credits to enable participation in the Pilot must accept as a condition
of such funding and participation.&nbsp; </span></p>

<p class=MsoNormal><span style='font-family:Arial'>The following comments
briefly summarize the purpose and operation of the Pilot to facilitate
understanding of the Program Participation Requirements. </span></p>

<p class=MsoNormal><b><span style='font-family:Arial'>What is \"The Commons\"?</span></b></p>

<p class=MsoNormal><span style='font-family:Arial'>The NIH is developing The
Commons, a shared virtual space that will be scalable and exploit new computing
models, be more cost effective given digital growth, simplify sharing digital
research objects (data, metadata workflows, etc.) and will make these object
more FAIR: Findable, Accessible, Interoperable and Reusable. NIH hopes to instantiate
elements of the Commons in a mix of public and publicly accessible clouds and
high performance computing centers that meet NIH standards for business
relationships, capacity (compute and storage), interfaces, networking and
connectivity, information assurance and authentication/authorization; these
entities are referred to as Ôconformant vendors'. </span></p>

<p class=MsoNormal><span style='font-family:Arial'>The Commons is not a
traditional information technology system. Rather, <i>the Commons is the sum of
its infrastructure (e.g., public clouds), data, metadata and usable software</i>.
Thus, to create the Commons, the NIH needs to instantiate a self-sustaining
collection of data and software on generally accessible infrastructure that is
indexed in such a way that it can be found and used by other investigators. In
short, the NIH needs to assemble sufficient FAIR objects onto accessible
infrastructure such that scientists will utilize the Commons for their work and
enable its continued growth. NIH is using a variety of strategies to develop
and aggregate the capabilities described above, including conventional grants,
cooperative agreements and grant supplements. These strategies can be effective
for the collection of large data sets or the development of large scale
software, however, the vast majority of data and software (85% by some
estimates) are relatively small in scale, do not fit into conventional data
resources, are functionally inaccessible and are produced by investigators with
small levels of support.</span></p>

<p class=MsoNormal><b><span style='font-family:Arial'>What is the Commons
Credits Pilot's Purpose?</span></b></p>

<p class=MsoNormal><span style='font-family:Arial'>The purpose of the Commons
Credits Pilot is to test the funding and operation of the Commons Credit model
designed by MITRE and NIH to:</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>1.<span style='font:7.0pt \"Times New Roman\"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span
style='font-family:Arial'>Deal with the 85% of data identified above by making
useful but generally small scale digital objects available in the Commons</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>2.<span style='font:7.0pt \"Times New Roman\"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span
style='font-family:Arial'>Improve access to public clouds for biomedical
investigators</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>3.<span style='font:7.0pt \"Times New Roman\"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span
style='font-family:Arial'>Provide a new, and potentially more cost effective
strategy to provide computing resources to NIH investigators</span></p>

<p class=MsoNormal style='margin-left:.25in'><span style='font-family:Arial'>4.&nbsp;
Create a marketplace for biomedically useful cloud services that would use market
forces to deliver high value at the lowest possible cost.</span></p>

<p class=MsoNormal><span style='font-family:Arial'>Pursuant to the model to be
tested in the Commons Credits Pilot, eligible investigators would apply to
MITRE for dollar denominated sums called Commons Credits in the form of a prepaid
account for purchase of cloud computing services with the conformant vendor or vendors
identified in their respective applications, thereby providing investigators
the resources needed to perform their proposed tasks thus providing a test bed
to assess the viability of the Commons Credit model.</span></p>

<p class=MsoNormal><b><span style='font-family:Arial'>How Can Investigators
Participate in the Pilot?</span></b></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>1.<span style='font:7.0pt \"Times New Roman\"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span
style='font-family:Arial'>Eligible Principal Investigators may apply for to
MITRE Commons Credits in accordance with the application process summarized
below. Applications will incorporate a proposed scope of work, which will,
among other things, identify the digital objects the applicant proposes to
place on Commons infrastructure and the conformant cloud vendor or vendors as
to whom the Applicant proposes to use the Commons Credits to access
computational resources.</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>2.<span style='font:7.0pt \"Times New Roman\"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span
style='font-family:Arial'>Pursuant to the applications process designed and
operated by MITRE as approved by NIH, MITRE will receive, review and triage all
applications from eligible Principal Investigators and make recommendations to
NIH for award of Commons Credits in specific amounts to applicants whose
proposals meet the criteria for award. Applicants approved by NIH for award
will be identified as \"Funded Participants.\" </span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>3.<span style='font:7.0pt \"Times New Roman\"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span
style='font-family:Arial'>Upon approval by NIH of a given Funded Participant
MITRE will distribute Commons Credits in the form of prepaid accounts with the
respective conformant vendors identified by Funded Participants in their
proposals.</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>4.<span style='font:7.0pt \"Times New Roman\"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span
style='font-family:Arial'>Funded Participants may thereafter draw on the
Commons Credits up to the amount awarded to perform the scope of work proposed
in their respective applications and subject to Program Requirements.</span></p>

<p class=MsoNormal><b><span style='font-family:Arial'>PROGRAM PARTICIPATION REQUIREMENTS</span></b></p>

<p class=MsoNormal><span style='font-family:Arial'>The following requirements
apply to all investigators applying for award of Commons Credits (\"applicants\"),
whether or not they are awarded such Credits. </span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>1.<span style='font:7.0pt \"Times New Roman\"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span
style='font-family:Arial'>Applicant acknowledges that award of Commons Credits
is a condition of participation in the Commons Credits Pilot.</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>2.<span style='font:7.0pt \"Times New Roman\"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span
style='font-family:Arial'>Applicant acknowledges that only individual
scientists who are Principal Investigators or Co-Principal Investigators on an
active NIH grant on the date within an awards cycle that an application for
Common Credits is submitted and awarded and can demonstrate that the
institution holding the grant supports the application are eligible for
participation in the Pilot and that ineligible applicants will not receive
awards of Commons Credits.</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>3.<span style='font:7.0pt \"Times New Roman\"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span
style='font-family:Arial'>Applicant acknowledges that the sole purpose of the
restriction of eligibility for award of Commons Credits to such Principal
Investigators (\"PI\") or Co-Principal Investigators (\"Co-PI\") is identification
of qualified researchers as potential participants in the Commons Credit Pilot.
&nbsp;</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>4.<span style='font:7.0pt \"Times New Roman\"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span
style='font-family:Arial'>Applicant acknowledges that the Commons Credits Pilot
is not a part of the NIH grants process and that an award of Commons Credits to
a PI or Co-PI is not the award of or equivalent to the award of a grant but
results only in the recipient becoming a \"Funded Participant\" in the Pilot. </span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>5.<span style='font:7.0pt \"Times New Roman\"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span
style='font-family:Arial'>Applicant acknowledges that the processes for awarding
Commons Credits in the Pilot is separate and distinct from the NIH grant award
process, that the Pilot process is acceptable and that, as the sponsor of this
effort, NIH reserves the right to modify or adopt this process for either the
pilot or the post-pilot period.</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>6.<span style='font:7.0pt \"Times New Roman\"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span
style='font-family:Arial'>Applicant agrees use the Commons Credits Web Portal
to generate all communications relating in any way to the Commons Credits
Pilot. &nbsp;</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>7.<span style='font:7.0pt \"Times New Roman\"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span
style='font-family:Arial'>Applicant agrees that Commons Credits will not be
used in connection with any other federally funded research program and will be
used solely in connection with the Pilot to obtain services consistent with Pilot
objectives.&nbsp; Such services include cloud storage, computations, hosting, virtual
machines, as well as technical support consistent with the use of such
services. Questions as to the use of Commons Credits for services other than
those identified must be addressed to and will be promptly resolved by MITRE.</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>8.<span style='font:7.0pt \"Times New Roman\"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span
style='font-family:Arial'>Applicant acknowledges that only conformant vendors
admitted to the pilot by MITRE and NIH, and who have previously both passed the
list of Conformance Requirements and signed the required business agreement
with MITRE, may be utilized when using Commons Credits.&nbsp; No other vendors
may be used for the pilot.</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>9.<span style='font:7.0pt \"Times New Roman\"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span
style='font-family:Arial'>Applicant acknowledges that utilization of Commons
Credits in the pilot must be reported to MITRE and NIH on a periodic basis in
accordance with detailed metrics and measures as specified in the award and
that this information will be aggregated with other data to assess the
feasibility of the Commons Credits approach. </span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>10.<span style='font:7.0pt \"Times New Roman\"'>&nbsp; </span></span><span
style='font-family:Arial'>Applicant acknowledges that NIH, not MITRE
Corporation, makes the final decisions as to awards and amounts of
Credits.&nbsp; MITRE provides a triage process for the applications, but does
not provide final adjudication.</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>11.<span style='font:7.0pt \"Times New Roman\"'>&nbsp; </span></span><span
style='font-family:Arial'>Applicant accepts that there is no recourse to award
decisions, and that there is no process for protesting or disputing outcomes of
the Commons Credits Pilot selection process.</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>12.<span style='font:7.0pt \"Times New Roman\"'>&nbsp; </span></span><span
style='font-family:Arial'>Applicant hereby consents to disclosure of the
contents of any application for Commons Credits, in that specific information,
limited to Applicant's name, project title, and amount of Commons Credits requested,
may be made publicly available in interim or final project reports.&nbsp; Note
also that all information within any application for Commons Credits will be
available to the NIH upon request.</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>13.<span style='font:7.0pt \"Times New Roman\"'>&nbsp; </span></span><span
style='font-family:Arial'>Applicant understands and accepts that all pledges of
electronic artifacts to be shared with the community (e.g., data, tools, and
workflows) within the Commons made as part of an application for Commons
Credits shall be honored if the application results in an award and that sharing
of electronic artifacts within 12 months of the date of award, or September 1,
2018, whichever comes first, is mandatory for all participants in the Commons
Credits Pilot. </span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>14.<span style='font:7.0pt \"Times New Roman\"'>&nbsp; </span></span><span
style='font-family:Arial'>Applicant acknowledges that shared artifacts may have
access or usage restrictions.&nbsp; This is typically most relevant for data
artifacts.&nbsp; The two tier system of \"open access data\" and \"controlled
access data\" at dbGAP is a useful reference for consideration, but is not prescriptive
for the pilot (</span><a
href=\"http://www.ncbi.nlm.nih.gov/projects/gap/cgi-bin/about.html\"><span
style='font-family:Arial'>http://www.ncbi.nlm.nih.gov/projects/gap/cgi-bin/about.html</span></a><span
style='font-family:Arial'>).&nbsp;&nbsp; In this context, if the use of a data
use agreement (DUA) or related agreement is appropriate for granting access to
artifacts, the artifact vendor and artifact recipient will be sole parities to
that agreement.</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>15.<span style='font:7.0pt \"Times New Roman\"'>&nbsp; </span></span><span
style='font-family:Arial'>Applicant agrees that each application for Commons
Credits will propose a specific scope of work and, if Commons Credits are
awarded, can be used solely for the work proposed in the subject application &nbsp;and
that Commons Credits identified to that work can be commingled with Commons
Credits identified to other work proposed in other applications only upon a
MITRE recommendation to NIH that commingling is appropriate in a given instance
&nbsp;and NIH&nbsp; final approval of commingling in that instance. &nbsp;&nbsp;</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>16.<span style='font:7.0pt \"Times New Roman\"'>&nbsp; </span></span><span
style='font-family:Arial'>Applicant acknowledges that the Commons is not a Federal
Data System, and as such no attestation of specific security controls is made
beyond what is provided by individual cloud vendors.&nbsp; Applicant
acknowledges that cloud vendors typically do not provide security above the
hypervisor layer, and that the applicant must consider their security needs
carefully and plan accordingly before instantiating services or uploading data.</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>17.<span style='font:7.0pt \"Times New Roman\"'>&nbsp; </span></span><span
style='font-family:Arial'>Applicant acknowledges that all cloud vendors require
all persons accessing their cloud services to enter into agreements governing access
to and utilization of such services. </span></p>

<p class=MsoNormal><span style='font-family:Arial'>In addition to the
conditions included above, the following additional conditions apply to
applicants who have been awarded Commons Credits for the purpose of
participating in the Pilot (hereafter called \"funded participants\"):</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>18.<span style='font:7.0pt \"Times New Roman\"'>&nbsp; </span></span><span
style='font-family:Arial'>Funded Participant agrees enter into and be bound by
the terms and conditions of agreements for access and utilization of cloud
services as required by conformant vendors utilized by the Participant in
connection with the Pilot. </span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>19.<span style='font:7.0pt \"Times New Roman\"'>&nbsp; </span></span><span
style='font-family:Arial'>Funded Participant agrees that the award amount
constitutes the full amount of resources that will be provided in the pilot for
any individual application submission, and that any expenditure in excess of
this amount will be the responsibility of the Funded Participant.&nbsp; Funded
Participant may, however, supplement the Commons Credits award with its own
funds at any time, though work performed using such funds will not be
considered part of the pilot process.</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>20.<span style='font:7.0pt \"Times New Roman\"'>&nbsp; </span></span><span
style='font-family:Arial'>Funded Participants agree to provide a \"secondary
surety\" to all Cloud Vendors utilized by them in the pilot (e.g., credit card)
to cover all resources expended by them in excess of the Commons Credits
awarded.&nbsp; Funded Participant acknowledges that this mechanism will be used
by the vendors to ensure full payment of all utilization charges.</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>21.<span style='font:7.0pt \"Times New Roman\"'>&nbsp; </span></span><span
style='font-family:Arial'>Funded Participant agrees that all Commons Credits funds
provided through awards that are not expended within 12 months of the date of
award, or by September 1, 2018, whichever comes first, will be used by other
Funded Participants or for other purposes consistent with the objectives of the
Pilot as directed by MITRE. </span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>22.<span style='font:7.0pt \"Times New Roman\"'>&nbsp; </span></span><span
style='font-family:Arial'>Funded Participant agrees to provide a short post-implementation
report of project outcomes within 12 months of the date of award, or September
1, 2018, whichever comes first.</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>23.<span style='font:7.0pt \"Times New Roman\"'>&nbsp; </span></span><span
style='font-family:Arial'>Funded Participant acknowledges that the Commons is a
shared environment, which places considerable responsibility on the Funded
Participant for maintaining data and application security related to their own
activities.&nbsp; For example, Funded Participant accepts responsibility for
protecting personally identifiable information (PII) that may reside within the
data sets which they provide, use, or share during the pilot.&nbsp; </span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>24.<span style='font:7.0pt \"Times New Roman\"'>&nbsp; </span></span><span
style='font-family:Arial'>Funded Participant accepts responsibility for
protecting intellectual property (IP) consistent with their home institution's
rules and regulations, and with U.S. law.&nbsp; </span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>25.<span style='font:7.0pt \"Times New Roman\"'>&nbsp; </span></span><span
style='font-family:Arial'>Funded Participant acknowledges that the list of
example responsibilities related to security provided here is not comprehensive,
and that there may be legal ramifications for data breaches.</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>26.<span style='font:7.0pt \"Times New Roman\"'>&nbsp; </span></span><span
style='font-family:Arial'>Funded Participant acknowledges that while the Commons
Credit Web Portal will provide monthly updates to the amount of Credits
remaining for any award, this information is not provided on the same timescale
in which charges are accrued.&nbsp; As such, Funded Participants are required
to engage in more proactive monitoring of resources consumed, including by, but
not limited to, use of a cloud-vendor provided account dashboard or other tools
which can be used to accomplish this purpose. </span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>27.<span style='font:7.0pt \"Times New Roman\"'>&nbsp; </span></span><span
style='font-family:Arial'>Funded Participant agrees to be bound by dispute
resolution processes provided by participating cloud vendors.</span></p>

<p class=MsoListParagraph style='text-indent:-.25in'><span style='font-family:
Arial'>28.<span style='font:7.0pt \"Times New Roman\"'>&nbsp; </span></span><span
style='font-family:Arial'>Funded Participant may be suspended from the pilot,
including loss of credits balance, upon concurrence from MITRE and NIH for violating
these terms and conditions, state or federal law, or any other reason.&nbsp;
Written notice will be provided.&nbsp; The occurrence of this will result in
the suspension of all Credits to the Funded Participant in question and used
for other purposes consistent with the objectives of the Pilot as directed by
MITRE. &nbsp;&nbsp;This decision cannot be contested.</span></p>

</div>",

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

	'nihcp_commons_credit_request:id:assign_fail' => 'Unable to assign a CCREQ ID. Please contact the Support Staff for further assistance.',
    'nihcp_commons_credit_request:table:summary' => 'This table shows the requests for credit.',
];