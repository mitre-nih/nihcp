<?php
namespace Nihcp\Entity;

$review_mode = elgg_extract('review_mode', $vars, false);

$request = elgg_extract('request', $vars);

$full_view = elgg_extract('full_view', $vars, true);

$content = elgg_view('nihcp_credit_allocation/components/allocations', ['full_view' => $full_view, 'review_mode' => $review_mode, 'requests' => [$request]]);

$show_actions = false;
if($request->getOwnerGUID() === elgg_get_logged_in_user_guid()) {
	$show_actions = true;
	$allocations = CommonsCreditAllocation::getAllocations($request->guid);
	foreach ($allocations as $allocation) {
		if ($allocation->status !== CommonsCreditAllocation::STAGED_STATUS) {
			$show_actions = false;
		}
	}
}

if($show_actions) {
	$action_button = elgg_view('input/button', array(
		'value' => 'Allocate',
		'id' => 'cca-allocate-button',
		'class' => 'elgg-button-submit mbm',
		'onclick' => "location.href='" . elgg_get_site_url() . "nihcp_credit_allocation/allocate/$request->guid';"
	));
	$content = "<div>$action_button</div>".$content;
	$content .= "<div class=\"ptm\">";
	$content .= elgg_view('input/hidden', array('name' => 'request_guid', 'id'=>'request_guid', 'value'=>$request->guid));
	$content .= elgg_view('input/submit', array('name' => 'action', 'value' => 'Submit', 'id' => 'cca-allocations-submit-button'));
	$content .= "</div>";
}

echo $content;