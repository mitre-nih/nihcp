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

class CommonsCreditRequestId extends \ElggObject {

	const SUBTYPE = 'commonscreditrequestid';
	const RELATIONSHIP_CCREQ_TO_ID = 'identified_by';
	const ID_PREFIX = 'CCREQ';
	const ID_DELIM = '-';
	const CYCLE_NUM_LEN = 2;
	const REQUEST_NUM_LEN = 5;

	public function initializeAttributes() {
		parent::initializeAttributes();

		$class = get_class($this);
		$this->attributes['subtype'] = $class::SUBTYPE;
	}

	public function getValue() {
		return $this->value;
	}

	private static function getYear($request_guid) {
		$d = \DateTime::createFromFormat(CommonsCreditRequest::DATE_FORMAT, get_entity($request_guid)->submission_date);
		return $d->format('Y');
	}

	private static function getCycleNumberWithinYear($request_guid) {
		$cycle = get_entity($request_guid)->getCycle();
		if(!$cycle) {
			return false;
		}
		return $cycle->getNumberWithinYear();
	}

	private static function getRequestSubmissionNumberWithinCycle($request_guid) {
		$request = get_entity($request_guid);
		return $request->getSubmissionNumberWithinCycle();
	}

	private static function generateForRequest($request_guid) {
		$year_str = CommonsCreditRequestId::getYear($request_guid);
		$cycle_num = CommonsCreditRequestId::getCycleNumberWithinYear($request_guid);
		$submission_num = CommonsCreditRequestId::getRequestSubmissionNumberWithinCycle($request_guid);
		if(!$year_str || !$cycle_num || !$submission_num) {
			return false;
		}
		$cycle_num_str = strval($cycle_num);
		while(strlen($cycle_num_str) < CommonsCreditRequestId::CYCLE_NUM_LEN) {
			$cycle_num_str = '0'.$cycle_num_str;
		}
		$submission_num_str = strval($submission_num);
		while(strlen($submission_num_str) < CommonsCreditRequestId::REQUEST_NUM_LEN) {
			$submission_num_str = '0'.$submission_num_str;
		}
		$parts = [CommonsCreditRequestId::ID_PREFIX, $year_str, $cycle_num_str, $submission_num_str];
		$value = join(CommonsCreditRequestId::ID_DELIM, $parts);
		$id_entity = new CommonsCreditRequestId();
		$id_entity->save();
		$id_entity->value = $value;
		return $id_entity;
	}

	public static function assignToRequest($request_guid) {
		if(!elgg_instanceof(get_entity($request_guid), 'object', CommonsCreditRequest::SUBTYPE)
			|| get_entity($request_guid)->getRequestIdEntity()
			|| get_entity($request_guid)->status === CommonsCreditRequest::DRAFT_STATUS) {
			return false;
		}
		$ccreq_id_entity = pseudo_atomic_set_ignore_access(function($_request_guid) {
			return CommonsCreditRequestId::generateForRequest($_request_guid);
		}, $request_guid);
		if(!$ccreq_id_entity) {
			return false;
		}
		add_entity_relationship($request_guid, CommonsCreditRequestId::RELATIONSHIP_CCREQ_TO_ID, $ccreq_id_entity->guid);
		return $ccreq_id_entity->getGUID();
	}

	// returns the GUID of the entity associated with the CCREQ ID name
	public static function getGUIDFromName($ccreq_id) {
		$entities = elgg_get_entities_from_metadata([
			'type' => 'object',
			'subtype' => CommonsCreditRequestId::SUBTYPE,
			'limit' => 0,
			'metadata_name_value_pairs' => [
				['name' => 'value', 'value' => $ccreq_id]
			],
		]);

		return empty($entities) ? false : $entities[0]->getGUID();
	}
}
