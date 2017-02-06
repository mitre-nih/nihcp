<?php
use Nihcp\Entity\CommonsCreditAllocation;
use Nihcp\Entity\CommonsCreditRequest;

$request_guid = htmlspecialchars(get_input('request_guid', '', false), ENT_QUOTES, 'UTF-8');
$vendor_guid = htmlspecialchars(get_input('vendor_guid', '', false), ENT_QUOTES, 'UTF-8');

// do a check to see if user should have access to this request in order to delete allocations for it
$ia = elgg_get_ignore_access();
if(CommonsCreditRequest::hasAccess($request_guid)) {
	$ia = elgg_set_ignore_access();
}

$guid = CommonsCreditAllocation::getAllocationGUID($request_guid, $vendor_guid);

elgg_set_ignore_access($ia);

if($guid) {

	$ia = elgg_get_ignore_access();
	if(CommonsCreditRequest::hasAccess($request_guid)) {
		$ia = elgg_set_ignore_access();
	}

	$allocation = get_entity($guid);


	if($allocation->status === CommonsCreditAllocation::STAGED_STATUS || elgg_is_admin_logged_in()) {
		$result = $allocation->delete();
		elgg_set_ignore_access($ia);;
		return $result;
	}
	elgg_set_ignore_access($ia);
}

elgg_set_ignore_access($ia);

return 'error';