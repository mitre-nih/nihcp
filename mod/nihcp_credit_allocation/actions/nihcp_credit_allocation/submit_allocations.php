<?php
namespace Nihcp\Entity;

$request_guid = get_input('request_guid');

if($request_guid) {
	$request = get_entity($request_guid);
	if (!$request instanceof CommonsCreditRequest || $request->getOwnerGUID() !== elgg_get_logged_in_user_guid()) {
		return 'error';
	}

	$unallocated = $request->getExpectedCostTotal();

	$allocations = CommonsCreditAllocation::getAllocations($request->guid);
	foreach ($allocations as $allocation) {
		if($allocation->status !== CommonsCreditAllocation::STAGED_STATUS) {
			register_error('Allocation already submitted');
			return 'error';
		}
		$_allocated = $allocation->credit_allocated ? $allocation->credit_allocated : 0;
		$unallocated -= $_allocated;
	}

	if($unallocated != 0) {
		register_error('All credits must be allocated before submitting');
		return 'error';
	}

	foreach($allocations as $allocation) {
		$allocation->status = CommonsCreditAllocation::SUBMITTED_STATUS;
	}
	return true;
}
return 'error';