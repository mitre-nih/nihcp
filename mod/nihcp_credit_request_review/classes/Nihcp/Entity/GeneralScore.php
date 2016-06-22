<?php


namespace Nihcp\Entity;


class GeneralScore extends \ElggObject {
    const SUBTYPE = 'general_score';

    const RELATIONSHIP_CCREQ_TO_GENERAL_SCORE_DATASETS = 'ccreq_to_gs_datasets';
    const RELATIONSHIP_CCREQ_TO_GENERAL_SCORE_APPSTOOLS = 'ccreq_to_gs_appstools';
    const RELATIONSHIP_CCREQ_TO_GENERAL_SCORE_WORKFLOWS = 'ccreq_to_gs_workflows';

    const REVIEWED_STATUS = "reviewed";

    const COMMENT_CHAR_LIMIT = 500;

    public function initializeAttributes() {
        parent::initializeAttributes();

        $class = get_class($this);
        $this->attributes['subtype'] = $class::SUBTYPE;
    }

    public static function getTableCellView($request_guid) {
        $result = "<div>";
        $gs = get_entity(self::getGeneralScoreDatasetsFromRequestGuid($request_guid));
        $result .= "D: " . (empty($gs) ? "n/a" : round($gs->general_score * $gs->num_digital_objects));
        $result .= "</div>";
        $result .= "<div>";
        $gs = get_entity(self::getGeneralScoreAppsToolsFromRequestGuid($request_guid));
        $result .= "A/T: " . (empty($gs) ? "n/a" : round($gs->general_score * $gs->num_digital_objects));
        $result .= "</div>";
        $result .= "<div>";
        $gs = get_entity(self::getGeneralScoreWorkflowsFromRequestGuid($request_guid));
        $result .= "W: " . (empty($gs) ? "n/a" : round($gs->general_score * $gs->num_digital_objects));
        $result .= "</div>";
        return $result;
    }

    public static function isReviewCompleted($request_guid) {
        return (empty(get_entity($request_guid)->datasets) || self::getGeneralScoreDatasetsFromRequestGuid($request_guid))
            && (empty(get_entity($request_guid)->applications_tools) || self::getGeneralScoreAppsToolsFromRequestGuid($request_guid))
            && (empty(get_entity($request_guid)->workflows) || self::getGeneralScoreWorkflowsFromRequestGuid($request_guid));
    }

    public static function getGeneralScoreDatasetsFromRequestGuid($request_guid) {
        $entities = elgg_get_entities_from_relationship(array(
            'relationship' => GeneralScore::RELATIONSHIP_CCREQ_TO_GENERAL_SCORE_DATASETS,
            'relationship_guid' => $request_guid,
            'type' => 'object',
            'subtype' => GeneralScore::SUBTYPE
        ));

        $gs_datasets_guid = empty($entities) ? null : $entities[0]->getGUID();

        return $gs_datasets_guid;
    }

    public static function getGeneralScoreAppsToolsFromRequestGuid($request_guid) {
        $entities = elgg_get_entities_from_relationship(array(
            'relationship' => GeneralScore::RELATIONSHIP_CCREQ_TO_GENERAL_SCORE_APPSTOOLS,
            'relationship_guid' => $request_guid,
            'type' => 'object',
            'subtype' => GeneralScore::SUBTYPE
        ));

        $gs_appstools_guid = empty($entities) ? null : $entities[0]->getGUID();

        return $gs_appstools_guid;
    }

    public static function getGeneralScoreWorkflowsFromRequestGuid($request_guid) {
        $entities = elgg_get_entities_from_relationship(array(
            'relationship' => GeneralScore::RELATIONSHIP_CCREQ_TO_GENERAL_SCORE_WORKFLOWS,
            'relationship_guid' => $request_guid,
            'type' => 'object',
            'subtype' => GeneralScore::SUBTYPE
        ));

        $gs_workflows_guid = empty($entities) ? null : $entities[0]->getGUID();

        return $gs_workflows_guid;
    }

}