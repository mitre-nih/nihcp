<?php
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