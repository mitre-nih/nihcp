<?php

namespace Nihcp\Entity;

class CommonsCreditVendor extends \ElggObject {

	const SUBTYPE = 'commonscreditvendor';
	const RELATIONSHIP_ALLOCATION_TO_VENDOR = 'allocated_to';

	public function initializeAttributes() {
		parent::initializeAttributes();
		$class = get_class($this);
		$this->attributes['subtype'] = $class::SUBTYPE;
		$this->owner_guid = 0;
		$this->active = true;
	}

	public function getVendorId() {
		return $this->vendor_id;
	}

	public function setVendorId($vendor_id) {
		$this->vendor_id = $vendor_id;
	}

	public static function getByName($vendor_name) {
		$ia = elgg_set_ignore_access();
		$dbprefix = elgg_get_config('dbprefix');
		$entities = elgg_get_entities_from_metadata([
			'type' => 'object',
			'subtype' => CommonsCreditVendor::SUBTYPE,
			'limit' => 1,
			'joins' => array("INNER JOIN {$dbprefix}objects_entity o ON (e.guid = o.guid)"),
			'wheres' => array("o.title = '{$vendor_name}'"),
		]);
		elgg_set_ignore_access($ia);
		return $entities && !empty($entities) ? $entities[0] : false;
	}

	public static function getByVendorId($vendor_id) {
		$ia = elgg_set_ignore_access();
		$entities = elgg_get_entities_from_metadata([
			'type' => 'object',
			'subtype' => CommonsCreditVendor::SUBTYPE,
			'limit' => 1,
			'metadata_name' => 'vendor_id',
			'metadata_value' => $vendor_id
		]);
		elgg_set_ignore_access($ia);
		return $entities && !empty($entities) ? $entities[0] : false;
	}

	public static function getAllVendors() {
		$ia = elgg_set_ignore_access();
		$entities = elgg_get_entities_from_metadata([
			'type' => 'object',
			'subtype' => CommonsCreditVendor::SUBTYPE,
			'limit' => 0,
		]);
		elgg_set_ignore_access($ia);
		return $entities;
	}

	public static function getActiveVendors() {
		$ia = elgg_set_ignore_access();
		$entities = elgg_get_entities_from_metadata([
			'type' => 'object',
			'subtype' => CommonsCreditVendor::SUBTYPE,
			'limit' => 0,
			'metadata_name' => 'active',
			'metadata_value' => true
		]);
		elgg_set_ignore_access($ia);
		return $entities;
	}

	public static function getActiveUnallocatedVendors($request_guid) {
		$vendors = self::getActiveVendors();
		$ia = elgg_set_ignore_access();
		foreach(CommonsCreditAllocation::getAllocations($request_guid) as $allocation) {
			$vendors = array_filter($vendors, function($vendor) use ($allocation) {return $vendor->vendor_id !== $allocation->vendor;});
		}
		elgg_set_ignore_access($ia);
		return $vendors;
	}
}
