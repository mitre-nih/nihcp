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
	'item:object:commonscreditallocation' => 'Credit Allocation',
	'nihcp_credit_allocation' => 'Credit Allocation',
	'nihcp_credit_allocation:widget:description' => 'Plugin for displaying Commons Credit allocation in the Commons Portal.',
	'nihcp_credit_allocation:menu' => 'Credit Allocation',
	'nihcp_credit_allocation:request:select_vendor' => 'Select Vendor',
	'nihcp_credit_allocation:request:requested_allocation' => 'Requested Allocation',
	'nihcp_credit_allocation:request:unallocated_credit' => 'Unallocated Credit',
	'nihcp_credit_allocation:file:none' => 'No Credit Allocation files found.',
	'nihcp_credit_allocation:file:not_csv' => 'Upload must be a CSV file.',
	'nihcp_credit_allocation:file:problem_opening' => 'Problem opening Credit Allocation file.',

	'nihcp_credit_allocation:validate:ingest_failed' => 'Ingest failed. Please check the file and try again after fixing the issues.',
	'nihcp_credit_allocation:validate:bad_format' => 'Line %s of CSV file has wrong number of fields.',
	'nihcp_credit_allocation:allocations:none' => 'No allocations found',
	'nihcp_credit_allocation:validate:nan' => 'Line %s of CSV file has non-numeric values for credits',
	'nihcp_credit_allocation:validate:no_ccreq' => 'CCREQ ID in line %s of CSV file does not exist in the system.',
	'nihcp_credit_allocation:validate:too_much_remaining' => 'Remaining credits in line %s of CSV file is greater than its initial credits.',
	'nihcp_credit_allocation:validate:new_allocation' => 'Cloud Account in line %s of CSV file did not previously exist in the system.',
	'nihcp_credit_allocation:validate:remaining_credits_increased' => 'Remaining credits increased from previous value in line %s of CSV file.',
	'nihcp_credit_allocation:validate:initial_changed' => 'Initial credit amount changed from previous value in line %s of CSV file.',
	'nihcp_credit_allocation:validate:no_vendor' => 'Unable to find vendor with ID on line %s of CSV file.',
	'nihcp_credit_allocation:submit:areyousure' => 'Are you sure you want to submit your allocations? This is a binding action and cannot be reversed once confirmed.',
	'nihcp_credit_allocation:notify:subject' => 'NIH Commons Credits Allocations Updated',
	'nihcp_credit_allocation:notify:body' => '%s,
Your remaining NIH Commons Credits have been updated to the following:',
	'nihcp_credit_allocation:action:allocate' => 'Allocate',
	'nihcp_credit_allocation:action:view_allocation' => 'View Allocation',
];