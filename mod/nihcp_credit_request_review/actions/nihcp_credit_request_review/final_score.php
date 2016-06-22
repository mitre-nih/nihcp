<?php
nihcp_triage_coordinator_gatekeeper();


$action = get_input('action', '', false);

$request_guid = htmlspecialchars(get_input('request_guid', '', false), ENT_QUOTES, 'UTF-8');
$review_class = htmlspecialchars(get_input('review_class', '', false), ENT_QUOTES, 'UTF-8');
$fs_guid = htmlspecialchars(get_input('fs_guid', '', false), ENT_QUOTES, 'UTF-8');

$ia = elgg_set_ignore_access(true);
$fs_entity = get_entity($fs_guid);



switch ($action) {
    case 'Save':

        // get the object related to this ccreq
        // see if there is existing saved answers entity
        // if so, update it and save
        // else, create a new one and save it
        if (empty($fs_entity)) {
            $fs_entity = new \Nihcp\Entity\FinalScore();
            $fs_entity->status = "reviewed";
            $fs_entity->class = $review_class;
            $fs_entity->save();

            add_entity_relationship($request_guid, \Nihcp\Entity\FinalScore::RELATIONSHIP_CCREQ_TO_FINAL_SCORE, $fs_entity->getGUID());

        }
        $fs_entity->sbr = htmlspecialchars(get_input('mean_class_sbr', '', false), ENT_QUOTES, 'UTF-8');
        $fs_entity->sv = htmlspecialchars(get_input('scientific_value', '', false), ENT_QUOTES, 'UTF-8');
        $fs_entity->cf = htmlspecialchars(get_input('cf', '', false), ENT_QUOTES, 'UTF-8');

        $fs_entity->save();
        break;
    default:
        // do nothing
        break;
}

elgg_set_ignore_access($ia);

forward(elgg_get_site_url() . "nihcp_credit_request_review/final-score-overview/$request_guid");
