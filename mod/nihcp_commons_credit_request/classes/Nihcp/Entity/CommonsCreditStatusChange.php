<?php

namespace Nihcp\Entity;


class CommonsCreditStatusChange extends \ElggObject {

	const SUBTYPE = 'commonscreditstatuschange';
	const RELATIONSHIP_CCREQ_TO_STATUS_CHANGE = 'status_changed_because';
	const RELATIONSHIP_FEEDBACK_TO_STATUS_CHANGE = 'decision_changed_because';

	public function initializeAttributes() {
		parent::initializeAttributes();
		$class = get_class($this);
		$this->attributes['subtype'] = $class::SUBTYPE;
	}

	public function setReason($reason) {
		$this->reason = $reason;
	}

	public function getReason() {
		return $this->reason;
	}

	public static function getStatusChanges($request_guid) {
		$entities = elgg_get_entities_from_relationship([
			'type' => 'object',
			'subtype' => self::SUBTYPE,
			'limit' => 0,
			'order_by' => 'time_created desc',
			'relationship' => self::RELATIONSHIP_CCREQ_TO_STATUS_CHANGE,
			'relationship_guid' => $request_guid,
		]);
		return $entities;
	}



}