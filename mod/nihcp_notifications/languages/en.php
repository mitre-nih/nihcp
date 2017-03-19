<?php

return [

	// email content for CCREQ submission notification
	'nihcp_notifications:notify:submit_request:subject' => "Next Steps to Facilitate Your NIH Commons Application",
	'nihcp_notifications:notify:submit_request:body' => "Thank you for submitting a research proposal to the NIH Commons Cloud Pilot (https://datascience.nih.gov/commons/). We look forward to reviewing your application. You will be able to check the status of your application(s) in the portal at any time.

While review is underway, there are concrete steps you can preemptively take to speed the usability of cloud credits, if approved.  We propose the following steps, which may help accelerate your onboarding process if credits are provided.

	1. Consider your vendor options:  while you may have assembled your proposal with a particular vendor in mind, it may be worth considering if an alternative may be the most optimal choice, for price, service, or other reasons. Please see the providers and services menu on the portal.

	2. Identify your secondary surety:  the pilot requires that you provide a secondary surety to cover any overages (expenses in excess of credits provided). Both credit cards and purchase orders (POs) will work for this purpose.

	3. Review agreement needs:  if you are planning on using human subject data which you received from a covered entity (i.e., one with which you have a Business Associates Agreement  BAA), you may want to explore which vendor(s) already have an equivalent BAA with your home institute.  Negotiating a new BAA can be very lengthy.  In addition, you may want to review the standard account agreements of one or more providers to see if you and your home institution are comfortable with the terms and conditions.  Finally, if you require any form of Data Use Agreement with those who share your data, please take the time to create, update, or revise this document with the assistance of your institute’s General Counsel’s Office.

	4. Make a plan:  Credits expire 12 months from the data of distribution, so plan accordingly.  All unused credits will be forfeited.

After review is complete and if your credit request(s) are approved, you will be promptly to select the necessary credit allocation(s) from the cloud provider(s) of your choice in the portal. The cloud provider(s) you select will contact you directly to assist with the onboarding process.

At the end of the one-year pilot, simply index any pledged Digital Objects (e.g. datasets, tools, etc.) in bioCADDIE (https://biocaddie.org/), and submit your feedback on the new funding model via the Post Implementation Report.

If you have questions during the process, please feel free to contact us at commons_credits@mitre.org or submit a help ticket through the portal.

Sincerely,

Commons Credits Pilot Staff

CAMH FFRDC, operated by the MITRE Corporation",


	// email content for allocation submssion notification
	// email content for CCREQ submission notification
	'nihcp_notifications:notify:submit_allocations:subject' => "Commons Credits Vendor Allocation",
	'nihcp_notifications:notify:submit_allocations:body' => "Your Commons Credits have been allocated to the following:",

	'nihcp_notifications:notify:assign_de:subject' => 'You Have Been Assigned as a Domain Expert',
	'nihcp_notifications:notify:assign_de:body' => "Hi %s,\r\nYou have been assigned as a domain expert for %s.",
	'nihcp_notifications:notify:assign_de:summary' => "%s Assigned as DE for %s",
	'nihcp_notifications:notify:risk_benefit_score_complete:subject' => 'Risk/Benefit Scoring Completed',
	'nihcp_notifications:notify:risk_benefit_score_complete:body' => "The risk/benefit scoring for %s has been completed.",
	'nihcp_notifications:notify:risk_benefit_score_complete:summary' => 'Risk/Benefit Scoring Completed for %s',
	'nihcp_notifications:notify:decide:subject' => 'Your Commons Credits Request Has Been %s',
	'nihcp_notifications:notify:decide:body' => "Your Commons Credits Request %s, titled \"%s,\" has been %s.",
	'nihcp_notifications:notify:decide:summary' => '%s Has Been %s',
	'nihcp_notifications:notify:allocations_updated:subject' => 'Commons Credits Allocations Updated',
	'nihcp_notifications:notify:allocations_updated:body' => 'Your remaining Commons Credits have been updated to the following:',
	'nihcp_notifications:notify:allocations_updated:summary' => 'Allocations Updated for %s',
	'nihcp_notifications:notify:withdraw_decision:subject' => 'Notice: CCREQ Review Decision Withdrawn',
	'nihcp_notifications:notify:withdraw_decision:body' => "Title: %s \r\n ID: %s \r\n Previous Decision: %s \r\n Current Status: %s \r\n Reason: %s",
	'nihcp_notifications:notify:withdraw_decision:summary' => "Review Decision Withdrawn for %s",

    //for daily stats emails
    'nihcp_notifications:notify:ccreq_stats_subj' => 'Daily CCREQ Statistics',
    'nihcp_notifications:notify:ccreq_stats_daily' => 'There have been %d CCREQ(s) submitted in the past 24 hours.',
    'nihcp_notifications:notify:ccreq_stats_weekly' =>  'There have been %d CCREQ(s) submitted in the last week that have not been reviewed.',
    'nihcp_notifications:notify:ccreq_stats_overall' => 'There are %d total CCREQ(s) that have not been reviewed.',
    'nihcp_notifications:notify:ccreq_stats_draft' => 'There are %d CCREQ drafts.',

    'nihcp_notifications:notify:helpdesk_stats_subj' => 'Daily Helpdesk Statistics',
    'nihcp_notifications:notify:helpdesk_stats_daily' => '%d helpdesk ticket(s) have been submitted in the last day and remain open.',
    'nihcp_notifications:notify:helpdesk_stats_weekly' => '%d helpdesk ticket(s) have been submitted in the last week and remain open.',
    'nihcp_notifications:notify:helpdesk_stats_overall' => 'There are %d total helpdesk ticket(s) open',

	// email change
	'nihcp_notifications:email_change:subject' => 'Email Change Attempted',
	'nihcp_notifications:email_change:body' => 'You or someone has requested to change your email address on the NIH Commons Credits Pilot Portal. If you did not perform this action or it was done in error, please contact the portal administrator.',

    'nihcp_notifications:profile:weekly_digest_title' => 'Weekly Digest Settings',
    'nihcp_notifications:profile:weekly_digest_opt_out' => 'I would like to opt out of the weekly email digest',

    'nihcp_notifications:weekly_digest:title' => 'Last week on...the NIH Commons Credit Portal',
    'nihcp_notifications:weekly_digest:email_subj' => 'NIH Commons Credit Portal Weekly Digest',

];
