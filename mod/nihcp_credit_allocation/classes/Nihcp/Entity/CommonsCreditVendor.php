<?php
/*
Copyright 2017 The MITRE Corporation
 
This software was written for the NIH Commons Credit Portal. General questions 
can be forwarded to:

opensource@mitre.org

Technology Transfer Office
The MITRE Corporation
7515 Colshire Drive
McLean, VA 22102-7539

Permission is hereby granted, free of charge, to any person obtaining a copy 
of this software and associated documentation files (the "Software"), to deal 
in the Software without restriction, including without limitation the rights 
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
 
namespace Nihcp\Entity;

class CommonsCreditVendor extends \ElggObject {

	const SUBTYPE = 'commonscreditvendor';
	const RELATIONSHIP_ALLOCATION_TO_VENDOR = 'allocated_to';

	public function initializeAttributes() {
		parent::initializeAttributes();
		$class = get_class($this);
		$this->attributes['subtype'] = $class::SUBTYPE;
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
		$entities = elgg_get_entities_from_metadata([
			'type' => 'object',
			'subtype' => CommonsCreditVendor::SUBTYPE,
			'limit' => 1,
			'metadata_name' => 'title',
			'metadata_value' => $vendor_name
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
}
