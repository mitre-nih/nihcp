<?php

if (nihcp_triage_coordinator_gatekeeper()) {

    $action = get_input('action', '', false);

    $request_guid = htmlspecialchars(get_input('request_guid', '', false), ENT_QUOTES, 'UTF-8');
    $alignment_commons_credits_objectives_guid = htmlspecialchars(get_input('alignment_commons_credits_objectives_guid', '', false), ENT_QUOTES, 'UTF-8');

	$ia = elgg_set_ignore_access(true);
    $alignment_commons_credits_objectives = get_entity($alignment_commons_credits_objectives_guid);
    elgg_set_ignore_access($ia);


    switch ($action) {
        case 'Save':

            // get the object related to this ccreq
            // see if there is existing saved answers entity
			// if so, update it and save
            // else, create a new one and save it
            if (empty($alignment_commons_credits_objectives)) {
                $alignment_commons_credits_objectives = new \Nihcp\Entity\AlignmentCommonsCreditsObjectives();
                $alignment_commons_credits_objectives->status = \Nihcp\Entity\AlignmentCommonsCreditsObjectives::REVIEWED_STATUS;
                $alignment_commons_credits_objectives->save();
                add_entity_relationship($request_guid, \Nihcp\Entity\AlignmentCommonsCreditsObjectives::RELATIONSHIP_CCREQ_TO_ALIGNMENT_COMMONS_CREDITS_OBJECTIVES, $alignment_commons_credits_objectives->getGUID());
            }
            $alignment_commons_credits_objectives->question1 = htmlspecialchars(get_input('question1', '', false), ENT_QUOTES, 'UTF-8');

            $alignment_commons_credits_objectives->save();
            break;
        default:
            // do nothing
            break;

    }

    forward(elgg_get_site_url() . "nihcp_credit_request_review/overview");

}