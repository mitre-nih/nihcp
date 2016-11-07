<?php

namespace Nihcp\Entity;

class Feedback extends \ElggObject {
	const SUBTYPE = 'feedback';

	const RELATIONSHIP_CCREQ_TO_FEEDBACK = "ccreq_to_feedback";

	const RELATIONSHIP_FEEDBACK_TO_FEEDBACK = "feedback_changed_to";

	const COMMENT_CHAR_LIMIT = 500;

	public function initializeAttributes() {
		parent::initializeAttributes();

		$class = get_class($this);
		$this->attributes['subtype'] = $class::SUBTYPE;
	}

	public static function getFeedback($request_guid) {
		$entities = elgg_get_entities_from_relationship(array(
				'relationship' => self::RELATIONSHIP_CCREQ_TO_FEEDBACK,
				'relationship_guid' => $request_guid,
				'type' => 'object',
				'subtype' => self::SUBTYPE,
				'order_by' => 'time_created desc',
				'limit' => 1
		));

		$result = empty($entities) ? false : $entities[0];

		return $result;
	}

	public static function getFeedbackHistory($request_guid) {
		$entities = elgg_get_entities_from_relationship(array(
			'relationship' => self::RELATIONSHIP_CCREQ_TO_FEEDBACK,
			'relationship_guid' => $request_guid,
			'type' => 'object',
			'subtype' => self::SUBTYPE,
			'order_by' => 'time_created desc',
			'limit' => 0
		));

		return $entities;
	}

	public function getStatusChange() {
		$entities = elgg_get_entities_from_relationship(array(
			'relationship' => CommonsCreditStatusChange::RELATIONSHIP_FEEDBACK_TO_STATUS_CHANGE,
			'relationship_guid' => $this->guid,
			'type' => 'object',
			'subtype' => CommonsCreditStatusChange::SUBTYPE,
		));

		$result = empty($entities) ? false : $entities[0];

		return $result;
	}

	public function getPrevFeedback() {
		$entities = elgg_get_entities_from_relationship(array(
			'relationship' => $this::RELATIONSHIP_FEEDBACK_TO_FEEDBACK,
			'relationship_guid' => $this->guid,
			'inverse_relationship' => true,
			'type' => 'object',
			'subtype' => $this::SUBTYPE,
		));

		$result = empty($entities) ? false : $entities[0];

		return $result;
	}

	public function getNextFeedback() {
		$entities = elgg_get_entities_from_relationship(array(
			'relationship' => $this::RELATIONSHIP_FEEDBACK_TO_FEEDBACK,
			'relationship_guid' => $this->guid,
			'type' => 'object',
			'subtype' => $this::SUBTYPE,
		));

		$result = empty($entities) ? false : $entities[0];

		return $result;
	}
}