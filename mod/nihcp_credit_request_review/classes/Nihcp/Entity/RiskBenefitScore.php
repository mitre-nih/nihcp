<?php

namespace Nihcp\Entity;


class RiskBenefitScore extends \ElggObject {

    const SUBTYPE = "risk_benefit_score";

    const COMPLETED_STATUS = 'Completed';

    const RELATIONSHIP_CCREQ_TO_DOMAIN_EXPERT = "ccreq_to_de";
    const RELATIONSHIP_CCREQ_TO_RB_SCORE = "ccreq_to_rbscore";
    const RELATIONSHIP_DE_TO_RB_SCORE = "de_to_rbscore";

    const COMMENT_CHAR_LIMIT = 500;

    public function initializeAttributes() {
        parent::initializeAttributes();

        $class = get_class($this);
        $this->attributes['subtype'] = $class::SUBTYPE;
    }

    public static function isDomainExpertAssignedToRequest($domain_expert_entity, $request_guid) {

        $de_entities = RiskBenefitScore::getAssignedDomainExperts($request_guid);
        $de_guids = array();

        foreach($de_entities as $assigned_de_entity) {
            $de_guids[] = $assigned_de_entity->getGUID();
        }

        return in_array($domain_expert_entity->getGUID(), $de_guids);
    }

    public static function getRequestForRiskBenefitScore($rb_score_guid) {
        $result = elgg_get_entities_from_relationship(array(
          'relationship' => self::RELATIONSHIP_CCREQ_TO_RB_SCORE,
            'inverse_relationship' => true,
            'relationship_guid' => $rb_score_guid
        ));

        return empty($result) ? null : $result[0];
    }



    public static function getDomainExpertForRiskBenefitScore($rb_score_guid) {
        $result = elgg_get_entities_from_relationship(array(
            'relationship' => self::RELATIONSHIP_DE_TO_RB_SCORE,
            'inverse_relationship' => true,
            'relationship_guid' => $rb_score_guid
        ));

        return empty($result) ? null : $result[0];
    }


    public static function getAssignedDomainExperts($request_guid) {
        return elgg_get_entities_from_relationship(array(
            'relationship' => self::RELATIONSHIP_CCREQ_TO_DOMAIN_EXPERT,
            'relationship_guid' => $request_guid,
            'type' => 'user',
            'limit' => 0
        ));
    }

    public static function getRequestsAssignedToDomainExpert($domain_expert_guid) {
        return elgg_get_entities_from_relationship(array(
            'relationship' => self::RELATIONSHIP_CCREQ_TO_DOMAIN_EXPERT,
            'inverse_relationship' => true,
            'relationship_guid' => $domain_expert_guid,
            'type' => 'object',
            'subtype' => CommonsCreditRequest::SUBTYPE,
            'limit' => 0
        ));
    }

    public static function getRiskBenefitScoreEntitiesForRequest($request_guid) {
        $ia = elgg_set_ignore_access();
        $entities = elgg_get_entities_from_relationship(array(
            'relationship' => self::RELATIONSHIP_CCREQ_TO_RB_SCORE,
            'relationship_guid' => $request_guid,
            'type' => 'object',
            'subtype' => self::SUBTYPE,
            'limit' => 0,
        ));
        elgg_set_ignore_access($ia);
        return $entities;
    }

    public static function hasAssignedRiskBenefitScores($request_guid) {
        return !empty(self::getRiskBenefitScoreEntitiesForRequest($request_guid));
    }

    // Checks to see if all the benefit/risk score review for a given ccreq and domain expert have been completed.
    // if no domain expert user is specified, this will check against the logged-in user
    public static function isAllAssignedCompleted($request_guid, $de_guid = 0) {
		if(!$de_guid) {
			$de_guid = elgg_get_logged_in_user_guid();
		}
        $assigned_reviews = self::getEntitiesForRequestAndDomainExpert($request_guid, $de_guid);
        if (empty($assigned_reviews)) {
            return false;
        }
        $result = true;
        foreach($assigned_reviews as $review) {
            if ($review->status != self::COMPLETED_STATUS) {
                $result = false;
            }
        }

        return $result;
    }

    // checks to see if all assigned RB scores for the given request have been completed.
    public static function isCompleted($request_guid) {
        if (self::hasAssignedRiskBenefitScores($request_guid)) {
            $scores = self::getRiskBenefitScoreEntitiesForRequest($request_guid);
            foreach($scores as $score) {
                if ($score->status != self::COMPLETED_STATUS) {
                    return false;
                }
            }
            return true;
        } else { // assume that no scores assigned means not complete yet
            return false;
        }
    }

    public static function getRiskBenefitScoreEntitiesForDomainExpert($domain_expert_guid) {
        return elgg_get_entities_from_relationship(array(
            'relationship' => self::RELATIONSHIP_DE_TO_RB_SCORE,
            'relationship_guid' => $domain_expert_guid,
            'type' => 'object',
            'subtype' => self::SUBTYPE,
            'limit' => 0
        ));
    }

    public static function getEntitiesForRequestAndDomainExpert($request_guid, $de_guid) {
        $rb_scores_request = self::getRiskBenefitScoreEntitiesForRequest($request_guid);
        $rb_scores_de = self::getRiskBenefitScoreEntitiesForDomainExpert($de_guid);

        $result = array();

        foreach ($rb_scores_request as $rsr) {
            foreach ($rb_scores_de as $rsd) {
                if ($rsr->guid == $rsd->guid) {
                    $result[] = $rsr;
                }
            }
        }

        return $result;
    }
}