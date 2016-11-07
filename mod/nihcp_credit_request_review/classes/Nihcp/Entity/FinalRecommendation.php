<?php

namespace Nihcp\Entity;

class FinalRecommendation extends \ElggObject {
	const SUBTYPE = 'final_recommendation';

	const RELATIONSHIP_CCREQ_TO_FINAL_RECOMMENDATION = "ccreq_to_final_recommendation";
	const RECOMMEND = 'Recommend';
	const DOWNSELECT = 'Down Select';

	const COMMENT_CHAR_LIMIT = 500;

	public function initializeAttributes() {
		parent::initializeAttributes();

		$class = get_class($this);
		$this->attributes['subtype'] = $class::SUBTYPE;
	}

	public static function isValidStatus($s) {
		$statuses = [self::RECOMMEND, self::DOWNSELECT];
		return in_array($s, $statuses);
	}

	public static function isReviewCompleted($request_guid) {
		return self::getFinalRecommendation($request_guid) && get_entity(self::getFinalRecommendation($request_guid))->status === 'Completed';
	}

	public static function getFinalRecommendation($request_guid) {
		$entities = elgg_get_entities_from_relationship(array(
				'relationship' => self::RELATIONSHIP_CCREQ_TO_FINAL_RECOMMENDATION,
				'relationship_guid' => $request_guid,
				'type' => 'object',
		));

		$result = empty($entities) ? null : $entities[0]->getGUID();

		return $result;
	}
}