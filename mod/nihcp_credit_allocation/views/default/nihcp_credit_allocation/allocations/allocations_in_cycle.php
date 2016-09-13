<?php
namespace Nihcp\Entity;
use Nihcp\Manager\RoleManager;

$review_mode = elgg_extract('review_mode', $vars, nihcp_role_gatekeeper([RoleManager::TRIAGE_COORDINATOR, RoleManager::NIH_APPROVER, RoleManager::CREDIT_ADMIN], false));

$cycle_guid = elgg_extract('cycle_guid', $vars);

$ia = elgg_get_ignore_access();

$requests = [];
if($review_mode) {
	$ia = elgg_set_ignore_access();
	$cycle = get_entity($cycle_guid);
	$requests = CommonsCreditRequest::sort($cycle->getRequests());
} else {
	$requests = CommonsCreditRequest::getByUserAndCycle('all', 0, $cycle_guid);
}
elgg_set_ignore_access($ia);
$full_view = elgg_extract('full_view', $vars, true);
$content = elgg_view('nihcp_credit_allocation/components/allocations', ['full_view' => $full_view, 'review_mode' => $review_mode, 'requests' => $requests]);

echo $content;