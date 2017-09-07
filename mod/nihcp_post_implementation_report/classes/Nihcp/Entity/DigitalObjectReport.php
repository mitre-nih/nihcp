<?php

namespace Nihcp\Entity;


class DigitalObjectReport extends \ElggObject {
    const SUBTYPE = "digital_object_report";

    const RELATIONSHIP_PIR_TO_DOR = "pir_to_dor";

    public function initializeAttributes() {
        parent::initializeAttributes();

        $class = get_class($this);
        $this->attributes['subtype'] = $class::SUBTYPE;
    }

    public static function getDigitalObjectReportsFromPirGuid($pir_guid) {

        $entities = elgg_get_entities_from_relationship(array(
            'relationship' => DigitalObjectReport::RELATIONSHIP_PIR_TO_DOR,
            'relationship_guid' => $pir_guid,
            'type' => 'object',
            'subtype' => DigitalObjectReport::SUBTYPE,
            'limit' => 0
        ));

        return $entities;
    }
}