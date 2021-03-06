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

$review_mode = elgg_extract('review_mode', $vars, false);

$request = elgg_extract('request', $vars);

$full_view = elgg_extract('full_view', $vars, true);

$content = elgg_view('nihcp_credit_allocation/components/allocations', ['full_view' => $full_view, 'review_mode' => $review_mode, 'requests' => [$request]]);

$show_actions = false;
if(CommonsCreditRequest::hasAccess($request->getGUID())) {
	$show_actions = !CommonsCreditAllocation::isAllocated($request->guid);
}

if($show_actions) {
	$ia = elgg_set_ignore_access();
	$unallocated = CommonsCreditAllocation::getUnallocatedCredit($request->guid);
	$action_params = array(
		'value' => 'Allocate',
		'id' => 'cca-allocate-button',
		'class' => 'elgg-button-submit',
		'onclick' => "location.href='" . elgg_get_site_url() . "nihcp_credit_allocation/allocate/$request->guid';"
	);
	if($unallocated == 0 || !CommonsCreditVendor::getActiveUnallocatedVendors($request->guid)) {
		$action_params = array_merge($action_params, ['class' => 'disabled', 'disabled' => 1]);
	}
	$action_button = elgg_view('input/button', $action_params);
	$content = "<div class='mbm'><label class='mrm'>Unallocated Credit</label><span id='cca-unallocated-amount'>$unallocated</span></div><div class=\"mbm\">$action_button</div>".$content;
	$content .= "<div class=\"ptm\">";
	$content .= elgg_view('input/hidden', array('name' => 'request_guid', 'id'=>'request_guid', 'value'=>$request->guid));
	$submit_params = array('name' => 'action', 'value' => 'Submit', 'id' => 'cca-allocations-submit-button');
	if($unallocated > 0) {
		$submit_params = array_merge($submit_params, ['class' => 'disabled', 'disabled' => 1]);
	}
	$content .= elgg_view('input/submit', $submit_params);
	$content .= "</div>";
	elgg_set_ignore_access($ia);
}

echo $content;
