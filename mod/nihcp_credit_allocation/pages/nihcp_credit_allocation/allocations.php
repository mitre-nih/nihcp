<?php
namespace Nihcp\Entity;
use Nihcp\Manager\RoleManager;

elgg_require_js('jquery');
elgg_require_js('cca');


$review_mode = nihcp_role_gatekeeper([RoleManager::TRIAGE_COORDINATOR, RoleManager::NIH_APPROVER, RoleManager::CREDIT_ADMIN], false);

$ia = elgg_set_ignore_access();
$cycles = elgg_get_entities_from_metadata([
	'type' => 'object',
	'subtype' => CommonsCreditCycle::SUBTYPE,
	'order_by_metadata' => array(
		'name' => 'start',
		'direction' => 'ASC'
	),
	'limit' => 0,
]);
elgg_set_ignore_access($ia);

$selected_cycle_guid = CommonsCreditCycle::getActiveCycleGUID();

$content = '';
$request_guid = get_input('request_guid');

if($request_guid) {
	$ia = elgg_get_ignore_access();
	if($review_mode || CommonsCreditRequest::hasAccess($request_guid)) {
		$ia = elgg_set_ignore_access();
	}
	$request = get_entity($request_guid);
	elgg_set_ignore_access($ia);
}

if($request) {
	$content = elgg_view('nihcp_credit_allocation/allocations/allocations_for_request', array(
		'request' => $request,
		'review_mode' => $review_mode
	));
} elseif ($review_mode) {

	$content = elgg_view('nihcp_credit_allocation/overview', array('cycles' => $cycles, 'selected_cycle_guid' => $selected_cycle_guid, 'review_mode' => $review_mode));
} else {
	register_error(elgg_echo("nihcp_groups:role:gatekeeper"));
	forward(REFERER);
}

$params = array(
	'title' => elgg_echo("nihcp_credit_allocation"),
	'content' => $content,
	'filter' => '',
	'class' => 'cca-overview-layout'
);

$body = elgg_view_layout('one_column', $params);

echo elgg_view_page("nihcp_credit_allocation", $body, 'default');

