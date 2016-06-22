<?php

namespace Nihcp\Entity;

class Feedback extends \ElggObject {
	const SUBTYPE = 'feedback';

	const RELATIONSHIP_CCREQ_TO_FEEDBACK = "ccreq_to_feedback";

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
		));

		$result = empty($entities) ? null : $entities[0]->getGUID();

		return $result;
	}
}