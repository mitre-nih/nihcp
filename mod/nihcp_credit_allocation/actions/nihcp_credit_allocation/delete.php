<?php
use Nihcp\Entity\CommonsCreditAllocation;

$request_guid = htmlspecialchars(get_input('request_guid', '', false), ENT_QUOTES, 'UTF-8');
$vendor_guid = htmlspecialchars(get_input('vendor_guid', '', false), ENT_QUOTES, 'UTF-8');

$guid = CommonsCreditAllocation::getAllocationGUID($request_guid, $vendor_guid);

if($guid) {
	$allocation = get_entity($guid);
	if($allocation->status === CommonsCreditAllocation::STAGED_STATUS || elgg_is_admin_logged_in()) {
		return $allocation->delete();
	}
}

return 'error';