<?php

namespace Nihcp\Entity;


class FinalScore extends \ElggObject {
    const SUBTYPE = 'final_score';

    const RELATIONSHIP_CCREQ_TO_FINAL_SCORE = 'ccreq_to_fs';

    public function initializeAttributes() {
        parent::initializeAttributes();

        $class = get_class($this);
        $this->attributes['subtype'] = $class::SUBTYPE;
    }


    public static function calculateROI($request_guid) {
        $request = get_entity($request_guid);

        if ($request->getExpectedCostTotal() == 0) {
            return 0;
        }

        $entities = elgg_get_entities_from_relationship(array(
            'relationship' => FinalScore::RELATIONSHIP_CCREQ_TO_FINAL_SCORE,
            'relationship_guid' => $request_guid,
            'type' => 'object',
            'subtype' => FinalScore::SUBTYPE
        ));

        $scientific_roi = 0;
        foreach ($entities as $entity) {
            $scientific_roi += ($entity->sv * $entity->cf);
        }

        $scientific_roi = ($scientific_roi / $request->getExpectedCostTotal() * 1000);
        return number_format((float)$scientific_roi, 2, '.', '');

    }

    public static function isFinalScoreCompleted($request_guid) {
        return (empty(get_entity($request_guid)->datasets) || self::getFinalScoreDatasetsFromRequestGuid($request_guid))
        && (empty(get_entity($request_guid)->applications_tools) || self::getFinalScoreAppsToolsFromRequestGuid($request_guid))
        && (empty(get_entity($request_guid)->workflows) || self::getFinalScoreWorkflowsFromRequestGuid($request_guid));
    }

    public static function getFinalScoreGuidFromRequestGuid($request_guid, $digital_object_class) {
        $entities = elgg_get_entities_from_relationship(array(
            'relationship' => FinalScore::RELATIONSHIP_CCREQ_TO_FINAL_SCORE,
            'relationship_guid' => $request_guid,
            'type' => 'object',
            'subtype' => FinalScore::SUBTYPE
        ));

        foreach ($entities as $entity) {
            if ($entity->class == $digital_object_class) {
                return $entity->getGUID();
            }
        }
        return null;
    }

    public static function getFinalScoreDatasetsFromRequestGuid($request_guid) {
        return self::getFinalScoreGuidFromRequestGuid($request_guid, CommonsCreditRequest::DATASETS);
    }

    public static function getFinalScoreAppsToolsFromRequestGuid($request_guid) {
        return self::getFinalScoreGuidFromRequestGuid($request_guid, CommonsCreditRequest::APPLICATIONS_TOOLS);
    }

    public static function getFinalScoreWorkflowsFromRequestGuid($request_guid) {
        return self::getFinalScoreGuidFromRequestGuid($request_guid, CommonsCreditRequest::WORKFLOWS);
    }
}