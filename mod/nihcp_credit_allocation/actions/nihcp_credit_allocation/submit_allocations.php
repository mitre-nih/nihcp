<?php
namespace Nihcp\Entity;

$request_guid = get_input('request_guid');

if($request_guid) {
	$ia = elgg_set_ignore_access();
	$request = get_entity($request_guid);
	elgg_set_ignore_access($ia);
	if (!$request instanceof CommonsCreditRequest || !CommonsCreditRequest::hasAccess($request_guid)) {
		return 'error';
	}

	$ia = elgg_set_ignore_access();
	$unallocated = $request->getExpectedCostTotal();

	$allocations = CommonsCreditAllocation::getAllocations($request->guid);
	foreach ($allocations as $allocation) {
		if($allocation->status !== CommonsCreditAllocation::STAGED_STATUS) {
			register_error('Allocation already submitted');
			elgg_set_ignore_access($ia);
			return 'error';
		}
		$_allocated = $allocation->credit_allocated ? $allocation->credit_allocated : 0;
		$unallocated -= $_allocated;
	}


	if($unallocated != 0) {
		register_error('All credits must be allocated before submitting');
		elgg_set_ignore_access($ia);
		return 'error';
	}

	foreach($allocations as $allocation) {
		$allocation->status = CommonsCreditAllocation::SUBMITTED_STATUS;
	}

	// this event will send an array of CommonsCreditAllocation entities associated with this particular ccreq
	elgg_trigger_event('submit', 'object:'.CommonsCreditAllocation::SUBTYPE, $allocations);

	elgg_set_ignore_access($ia);
	return true;
}
return 'error';