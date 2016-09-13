<?php
use \Nihcp\Entity\CommonsCreditAllocation;

nihcp_investigator_gatekeeper();
$request_guid = htmlspecialchars(get_input('request_guid', '', false), ENT_QUOTES, 'UTF-8');
if($request_guid) {
	// which button was pressed
	$action = htmlspecialchars(get_input('action', '', false), ENT_QUOTES, 'UTF-8');
	if($action == "Submit") {
		$vendor_guid = htmlspecialchars(get_input('vendor_guid', '', false), ENT_QUOTES, 'UTF-8');
		$allocation_guid = CommonsCreditAllocation::getAllocationGUID($request_guid, $vendor_guid);
		$allocation = $allocation_guid ? get_entity($allocation_guid) : new CommonsCreditAllocation();
		if (!$allocation->status || $allocation->status === CommonsCreditAllocation::STAGED_STATUS) {
			$credit_allocated = htmlspecialchars(get_input('credit_allocated', '', false), ENT_QUOTES, 'UTF-8');
			if($credit_allocated > 0) {
				if ($allocation_guid = CommonsCreditAllocation::saveAllocationFromForm($allocation)) {
					$allocation->addToRequest($request_guid);
					system_message('Success');
				} else {
					register_error('Fail');
				}
			} else {
				$allocation->delete();
				system_message('Removed allocation');
			}
		} else {
			register_error('Unable to save allocation with the status: ' . $allocation->status);
		}
	}
	forward("/nihcp_credit_allocation/allocations/$request_guid");
}

forward('/nihcp_credit_allocation/allocations');