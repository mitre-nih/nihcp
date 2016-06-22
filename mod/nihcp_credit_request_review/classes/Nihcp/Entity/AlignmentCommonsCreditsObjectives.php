<?php

namespace Nihcp\Entity;


class AlignmentCommonsCreditsObjectives extends \ElggObject {
    const SUBTYPE = 'alignment_commons_credits_objectives';

    const RELATIONSHIP_CCREQ_TO_ALIGNMENT_COMMONS_CREDITS_OBJECTIVES = "ccreq_to_align_cc_obj";

    const REVIEWED_STATUS = 'reviewed';

    public function initializeAttributes() {
        parent::initializeAttributes();

        $class = get_class($this);
        $this->attributes['subtype'] = $class::SUBTYPE;
    }

    public function pass() {
        return $this->question1 && $this->question2 && $this->question3 && $this->question4;
    }

    // returns guid for the Alignment with CC Objectives entity that is associated with the ccreq guid given as input to this function.
    // returns null if one is not found.
    public static function getFromRequestGuid($request_guid) {
        $entities = elgg_get_entities_from_relationship(array(
            'relationship' => AlignmentCommonsCreditsObjectives::RELATIONSHIP_CCREQ_TO_ALIGNMENT_COMMONS_CREDITS_OBJECTIVES,
            'relationship_guid' => $request_guid,
            'type' => 'object',
            'subtype' => AlignmentCommonsCreditsObjectives::SUBTYPE
        ));

        $alignment_commons_credits_objectives_guid = empty($entities) ? null : $entities[0]->getGUID();

        return $alignment_commons_credits_objectives_guid;
    }

}