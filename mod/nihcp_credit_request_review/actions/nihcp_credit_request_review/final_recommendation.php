<?php


if (nihcp_triage_coordinator_gatekeeper()) {

	$action = get_input('action', '', false);

	$request_guid = htmlspecialchars(get_input('request_guid', '', false), ENT_QUOTES, 'UTF-8');

	$ia = elgg_set_ignore_access(true);
	$final_recommendation_obj = get_entity(\Nihcp\Entity\FinalRecommendation::getFinalRecommendation($request_guid));
	$request_obj = get_entity($request_guid);
	elgg_set_ignore_access($ia);

	switch ($action) {
		case 'Save':

			// get the object related to this ccreq
			// see if there is existing saved answers entity
			// if so, update it and save
			// else, create a new one and save it
			if (empty($final_recommendation_obj)) {
				$final_recommendation_obj = new \Nihcp\Entity\FinalRecommendation();
				$final_recommendation_obj->final_recommendation = "";

				$final_recommendation_obj->save();
				add_entity_relationship($request_guid, \Nihcp\Entity\FinalRecommendation::RELATIONSHIP_CCREQ_TO_FINAL_RECOMMENDATION, $final_recommendation_obj->getGUID());
			}
			$final_recommendation = htmlspecialchars(get_input('final_recommendation', '', false), ENT_QUOTES, 'UTF-8');
			$final_recommendation_obj->final_recommendation_comments = htmlspecialchars(get_input('final_recommendation_comments', '', false), ENT_QUOTES, 'UTF-8');
			if($final_recommendation) {
				$final_recommendation_obj->final_recommendation = $final_recommendation;
				$request_obj->status = 'Completed';
				$final_recommendation_obj->status='Completed';
			} else {
				$request_obj->status = 'Submitted';
				$final_recommendation_obj->status="";
			}
			$final_recommendation_obj->save();
			break;
		default:
			// do nothing
			break;

	}

	forward(elgg_get_site_url() . "nihcp_credit_request_review/overview");
}