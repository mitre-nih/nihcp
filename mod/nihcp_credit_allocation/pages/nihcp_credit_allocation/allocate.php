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
 

namespace Nihcp\Entity;

$request_guid = get_input("request_guid");
$vendor_guid = get_input("vendor_guid");

$ia = elgg_get_ignore_access();

if (CommonsCreditRequest::hasAccess($request_guid)) {
	$ia = elgg_set_ignore_access();
}

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

elgg_set_ignore_access($ia);
