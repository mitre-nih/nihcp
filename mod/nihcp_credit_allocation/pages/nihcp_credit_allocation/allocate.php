<?php
namespace Nihcp\Entity;

$request_guid = get_input("request_guid");
$vendor_guid = get_input("vendor_guid");

$current_request = get_entity($request_guid);
if(!$current_request instanceof CommonsCreditRequest || $current_request->status !== CommonsCreditRequest::APPROVED_STATUS || CommonsCreditAllocation::isAllocated($request_guid)) {
	register_error(elgg_echo('error:404:content'));
	forward('/nihcp_commons_credit_request/overview');
}

$content = elgg_view_form('nihcp_credit_allocation/allocate', null, ['request_guid' => $request_guid, 'vendor_guid' => $vendor_guid]);

$params = array(
	'title' => elgg_echo("nihcp_credit_allocation"),
	'content' => $content,
	'filter' => '',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page("nihcp_credit_allocation", $body);