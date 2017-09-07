<?php

namespace Nihcp\Entity;

class PostImplementationReport extends \ElggObject {

    const SUBTYPE = "post_implementation_report";

    const RELATIONSHIP_CCREQ_TO_PIR = "ccreq_to_pir";


    public function initializeAttributes() {
        parent::initializeAttributes();

        $class = get_class($this);
        $this->attributes['subtype'] = $class::SUBTYPE;

    }

    public static function getPirDigitalObjectReportFormFields() {
        return [
            "title" => new PirFormField("title", "textarea", 200),
            "class" => new PirFormField("class", "select", 0, ["Data", "Application/Tool", "Workflow"]),
            "description" => new PirFormField("description", "textarea", 1000),
            "indexing_system" => new PirFormField("indexing_system", "select", 0, ["bioCADDIE", "Other"]),
            "index_id" => new PirFormField("index_id", "textarea", 200, [], "use N/A only if bioCADDIE"),
            "reason" => new PirFormField("reason", "textarea", 200, [], "use N/A only if bioCADDIE"),
            "controlled_access" => new PirFormField("controlled_access", "select", 0, ["Yes", "No"]),
            "access_information" => new PirFormField("access_information", "textarea", 200, [], "use N/A only if bioCADDIE"),
            "use_agreements_file" => new PirFormField("use_agreements_file", "file"),
            "use_agreements_weblink" => new PirFormField("use_agreements_weblink", "text", [], "use N/A if necessary"),
            "do_size" => new PirFormField("do_size", "number"),
            "do_size_unit" => new PirFormField("do_size_unit", "select", 0, ["KB", "MB", "TB", "PB"]),
            "do_dependencies" => new PirFormField("do_dependencies", "textarea", 200, [], "use N/A if necessary"),
            "estimated_production_cost" => new PirFormField("estimated_production_cost", "cost"),
            "comment_for_cost" => new PirFormField("comment_for_cost", "textarea", 200),
            "do_retention_plan" => new PirFormField("do_retention_plan", "textarea", 200),
            "related_publications" => new PirFormField("related_publications", "textarea", 200, [], "use N/A only if bioCADDIE"),
            "known_community_issues" => new PirFormField("known_community_issues", "textarea", 200, [], "use N/A if necessary"),
            "construction_issues" => new PirFormField("construction_issues", "textarea", 200, [], "use N/A if necessary"),

        ];
    }

    // returns the PIR associated with the given CCREQ
    public static function getPirGuidFromCcreqGuid($ccreq_guid) {

        $entities = elgg_get_entities_from_relationship(array(
            'relationship' => PostImplementationReport::RELATIONSHIP_CCREQ_TO_PIR,
            'relationship_guid' => $ccreq_guid,
            'type' => 'object',
            'subtype' => PostImplementationReport::SUBTYPE
        ));

        $pir_guid = empty($entities) ? null : $entities[0]->getGUID();

        return $pir_guid;
    }

    public static function getCcreqGuidFromPirGuid($pir_guid) {
        $entities = elgg_get_entities_from_relationship(array(
            'relationship' => PostImplementationReport::RELATIONSHIP_CCREQ_TO_PIR,
            'relationship_guid' => $pir_guid,
            'inverse_relationship' => true,
            'type' => 'object',
            'subtype' => CommonsCreditRequest::SUBTYPE,
        ));

        $ccreq_guid = empty($entities) ? null : $entities[0]->getGUID();
        return $ccreq_guid;
    }

}