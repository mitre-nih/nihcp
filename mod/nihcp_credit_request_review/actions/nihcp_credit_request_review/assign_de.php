<?php

namespace Nihcp\Entity;

// create R/B score entity and create relationship to ccreq and DE
// create relationship to DE and this ccreq
// enable access to this ccreq review for DE
if (nihcp_triage_coordinator_gatekeeper()) {

    $request_guid = htmlspecialchars(get_input('request_guid', '', false), ENT_QUOTES, 'UTF-8');
    $assign_de_guid = htmlspecialchars(get_input('assign', '', false), ENT_QUOTES, 'UTF-8');
    $unassign_de_guid = htmlspecialchars(get_input('unassign', '', false), ENT_QUOTES, 'UTF-8');

    $ia = elgg_set_ignore_access(true);
    $request = get_entity($request_guid);

    $digital_object_classes = array();
    $digital_object_classes[CommonsCreditRequest::DATASETS] = $request->hasDatasets();
    $digital_object_classes[CommonsCreditRequest::APPLICATIONS_TOOLS] = $request->hasApplicationsTools();
    $digital_object_classes[CommonsCreditRequest::WORKFLOWS] = $request->hasWorkflows();




    // when assigning a new DE, creates a relationship from the ccreq to the DE user
    // creates a new RiskBenefitScore entity
    // and creates a relationship to it from the ccreq
    // also creates a relationship from the DE user to the RBScore entity
    // unassigning a DE does the reverse
    if ($assign_de_guid) {
        foreach($digital_object_classes as $do=>$has) {
            if ($has) {
                add_entity_relationship($request_guid, RiskBenefitScore::RELATIONSHIP_CCREQ_TO_DOMAIN_EXPERT, $assign_de_guid);
                $rb_score_entity = new RiskBenefitScore();
                $rb_score_entity->class = $do;
                $rb_score_entity->request_guid = $request_guid;
                $rb_score_entity->save();
                add_entity_relationship($request_guid, RiskBenefitScore::RELATIONSHIP_CCREQ_TO_RB_SCORE, $rb_score_entity->getGUID());
                add_entity_relationship($assign_de_guid, RiskBenefitScore::RELATIONSHIP_DE_TO_RB_SCORE, $rb_score_entity->getGUID());

            }
        }


    } else if ($unassign_de_guid) {
        $rb_score_entities = RiskBenefitScore::getEntitiesForRequestAndDomainExpert($request_guid, $unassign_de_guid);



        foreach($rb_score_entities as $rb) {
            $rb->delete();
       }

        remove_entity_relationship($request_guid, RiskBenefitScore::RELATIONSHIP_CCREQ_TO_DOMAIN_EXPERT, $unassign_de_guid);

    }

    elgg_set_ignore_access($ia);
}