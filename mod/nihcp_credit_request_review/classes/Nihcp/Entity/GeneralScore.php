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