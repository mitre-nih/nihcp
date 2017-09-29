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
            "digital_object" => new PirFormField("digital_object", "select", 0, ["Single", "Set"]),
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