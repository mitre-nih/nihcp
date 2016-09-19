<?php

namespace Nihcp\Entity;

class CommonsCreditAllocation extends \ElggObject {

	const SUBTYPE = 'commonscreditallocation';
	const RELATIONSHIP_CCREQ_TO_ALLOCATION = 'allocated_to';

	const ERROR_STATUS = 'Error';
	const FLAGGED_STATUS = 'Flagged';
	const FUNDED_STATUS = 'Funded';
	const STAGED_STATUS = 'Staged';
	const SUBMITTED_STATUS = 'Submitted';
	const UPDATED_STATUS = 'Updated';

	public function initializeAttributes() {
		parent::initializeAttributes();
		$class = get_class($this);
		$this->attributes['subtype'] = $class::SUBTYPE;
	}

	// Get the form values and save them
	public static function saveAllocationFromForm($new_allocation) {
		$request_guid = htmlspecialchars(get_input('request_guid', '', false), ENT_QUOTES, 'UTF-8');
		$vendor_guid = htmlspecialchars(get_input('vendor_guid', '', false), ENT_QUOTES, 'UTF-8');
		$credit_allocated = htmlspecialchars(get_input('credit_allocated', '', false), ENT_QUOTES, 'UTF-8');
		if($new_allocation->guid && $new_allocation->status !== CommonsCreditAllocation::STAGED_STATUS && !elgg_is_admin_logged_in()) {
			return false;
		}
		$saved = false;
		if ($new_allocation->validateNewAllocationAmount($credit_allocated, $request_guid)) {
			$new_allocation->credit_allocated = $credit_allocated;
			$new_allocation->credit_remaining = $credit_allocated;
			$saved = $new_allocation->save() ? true : false;
		} else {
			register_error('Unable to allocate that amount');
		}
		if(!$new_allocation->status) {
			$new_allocation->status = CommonsCreditAllocation::STAGED_STATUS;

			$ia = elgg_set_ignore_access();
			$vendor = get_entity($vendor_guid);
			$vendor_id = $vendor->getVendorId();
			elgg_set_ignore_access($ia);

			if (!elgg_instanceof($vendor, 'object', CommonsCreditVendor::SUBTYPE)) {
				register_error('Unable to find the selected vendor');
				return false;
			}
			$new_allocation->vendor = $vendor_id;
			add_entity_relationship($request_guid, CommonsCreditVendor::RELATIONSHIP_ALLOCATION_TO_VENDOR, $vendor_guid);
		}
		return $saved ? $new_allocation->getGUID() : false;
	}

	public function validateNewAllocationAmount($new_amount, $request_guid = 0) {
		$request_guid = $request_guid ? $request_guid : $this->getRequestGUID();
		$request = get_entity($request_guid);
		$unallocated = $request->getExpectedCostTotal();
		$allocations = CommonsCreditAllocation::getAllocations($request->guid);
		foreach($allocations as $allocation) {
			$_allocated = $allocation->credit_allocated && $allocation->getGUID() !== $this->getGUID() ? $allocation->credit_allocated : 0;
			$unallocated -= $_allocated;
		}
		$unallocated -= $new_amount;
		return $unallocated >= 0;
	}

	public function getRequestGUID() {
		$entities = elgg_get_entities_from_relationship([
			'type' => 'object',
			'subtype' => CommonsCreditRequest::SUBTYPE,
			'inverse_relationship' => true,
			'limit' => 0,
			'order_by' => 'time_created desc',
			'relationship' => CommonsCreditAllocation::RELATIONSHIP_CCREQ_TO_ALLOCATION,
			'relationship_guid' => $this->getGUID(),
		]);
		return empty($entities) ? false : $entities[0]->getGUID();
	}

	public function addToRequest($request_guid) {
		if(!($request_guid && elgg_instanceof(get_entity($request_guid), 'object', CommonsCreditRequest::SUBTYPE))) {
			return false;
		}
		return add_entity_relationship($request_guid, CommonsCreditAllocation::RELATIONSHIP_CCREQ_TO_ALLOCATION, $this->getGUID());
	}

	/**
	 * New entities are created each time CCAs are updated from file. This means that multiple CCA entities can
	 * exist for the same cloud account. However, this method will return only the newest, or most recently updated CCA
	 * entity per cloud account.
	 *
	 * @param $request_guid
	 * @return bool|\ElggEntity[]|mixed
	 */
	public static function getAllocations($request_guid) {
		if(!$request_guid) {
			return false;
		}
		$entities = elgg_get_entities_from_relationship([
			'type' => 'object',
			'subtype' => CommonsCreditAllocation::SUBTYPE,
			'limit' => 0,
			'relationship' => CommonsCreditAllocation::RELATIONSHIP_CCREQ_TO_ALLOCATION,
			'relationship_guid' => $request_guid
		]);

		// group by vendor first
		$allocations_by_vendor = array();
		foreach ($entities as $entity) {
			$allocations_by_vendor[$entity->vendor][] = $entity;
		}

		$result = array();
		// now iterate through the groups of redundant CCAs and pick the newest ones
		foreach($allocations_by_vendor as $allocations) {
			$newest_allocation = $allocations[0];
			foreach ($allocations as $allocation) {
				if ($allocation->getTimeCreated() > $newest_allocation->getTimeCreated()) {
					$newest_allocation = $allocation;
				}
			}
			$result[] = $newest_allocation;
		}

		return $result;
	}


	/**
	 * Returns only most recent Allocation GUID.
	 */
	//TO DO: store/retrieve vendor as entity-relationship
	public static function getAllocationGUID($request_guid, $vendor_guid) {
		$entities = self::getAllocationBalanceHistory($request_guid, $vendor_guid);
		return empty($entities) ? false : $entities[0]->getGUID();
	}

	/**
	 * Returns all of the Allocation entities associated to the given request and vendor.
	 * This should give a history of all of the entities for a given allocation.
	 * @param $request_guid
	 * @param $vendor_guid
	 * @return bool|\ElggEntity[]|mixed
	 */
	public static function getAllocationBalanceHistory($request_guid, $vendor_guid) {
		if (!$request_guid || !$vendor_guid) {
			return false;
		}

		$ia = elgg_set_ignore_access();
		$vendor = get_entity($vendor_guid);
		$vendor_id = $vendor->getVendorId();
		elgg_set_ignore_access($ia);

		if(!elgg_instanceof($vendor, 'object', CommonsCreditVendor::SUBTYPE)) {
			return false;
		}
		$entities = elgg_get_entities_from_relationship([
			'type' => 'object',
			'subtype' => CommonsCreditAllocation::SUBTYPE,
			'limit' => 0,
			'order_by' => 'time_created desc',
			'relationship' => CommonsCreditAllocation::RELATIONSHIP_CCREQ_TO_ALLOCATION,
			'relationship_guid' => $request_guid,
			'metadata_name_value_pairs' => [
				['name' => 'vendor', 'value' => $vendor_id]
			],
		]);
		return $entities;
	}

	public static function isAllocated($request_guid) {
		$is_allocated = false;
		$allocations = self::getAllocations($request_guid);
		if(!empty($allocations)) {
			$is_allocated = true;
		}
		foreach($allocations as $allocation) {
			if($allocation->status === CommonsCreditAllocation::STAGED_STATUS) {
				$is_allocated = false;
			}
		}
		return $is_allocated;
	}


	public function getAttachmentURL() {
		$file_guid = $this->file_guid;
	}

}