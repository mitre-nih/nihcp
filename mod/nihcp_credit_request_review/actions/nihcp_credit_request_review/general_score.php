<?php
if (nihcp_triage_coordinator_gatekeeper()) {
    $action = get_input('action', '', false);

    $request_guid = htmlspecialchars(get_input('request_guid', '', false), ENT_QUOTES, 'UTF-8');
    $review_class = htmlspecialchars(get_input('review_class', '', false), ENT_QUOTES, 'UTF-8');
    $gs_guid = htmlspecialchars(get_input('gs_guid', '', false), ENT_QUOTES, 'UTF-8');

	$ia = elgg_set_ignore_access(true);
    $gs_entity = get_entity($gs_guid);



    switch ($action) {
        case 'Save':

            // get the object related to this ccreq
            // see if there is existing saved answers entity
			// if so, update it and save
            // else, create a new one and save it
            if (empty($gs_entity)) {
                $gs_entity = new \Nihcp\Entity\GeneralScore();
                $gs_entity->status = \Nihcp\Entity\GeneralScore::REVIEWED_STATUS;
                $gs_entity->digital_object_class = $review_class;
                $gs_entity->save();

                switch($review_class) {
                    case 'datasets':
                        add_entity_relationship($request_guid, \Nihcp\Entity\GeneralScore::RELATIONSHIP_CCREQ_TO_GENERAL_SCORE_DATASETS, $gs_entity->getGUID());

                        break;
                    case 'applications_tools':
                        add_entity_relationship($request_guid, \Nihcp\Entity\GeneralScore::RELATIONSHIP_CCREQ_TO_GENERAL_SCORE_APPSTOOLS, $gs_entity->getGUID());

                        break;
                    case 'workflows':
                        add_entity_relationship($request_guid, \Nihcp\Entity\GeneralScore::RELATIONSHIP_CCREQ_TO_GENERAL_SCORE_WORKFLOWS, $gs_entity->getGUID());

                        break;
                    default:
                        break;
                }
            }
            $gs_entity->num_digital_objects = htmlspecialchars(get_input('num_digital_objects', '', false), ENT_QUOTES, 'UTF-8');
            $gs_entity->general_score = htmlspecialchars(get_input('general_score', '', false), ENT_QUOTES, 'UTF-8');
            $gs_entity->general_score_comments = htmlspecialchars(get_input('general_score_comments', '', false), ENT_QUOTES, 'UTF-8');

            $gs_entity->completed_date = date('n/j/Y');

            $gs_entity->save();
            break;
        default:
            // do nothing
            break;

    }

    elgg_set_ignore_access($ia);

    forward(elgg_get_site_url() . "nihcp_credit_request_review/general-score-overview/$request_guid");

}