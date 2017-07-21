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
 

if (nihcp_triage_coordinator_gatekeeper()) {

	$action = get_input('action', '', false);

	$request_guid = htmlspecialchars(get_input('request_guid', '', false), ENT_QUOTES, 'UTF-8');

	$ia = elgg_set_ignore_access(true);
	$final_recommendation_obj = get_entity(\Nihcp\Entity\FinalRecommendation::getFinalRecommendation($request_guid));
	$request_obj = get_entity($request_guid);
	elgg_set_ignore_access($ia);

	switch ($action) {
		case elgg_echo("nihcp_credit_request_review:crr:final_recommendation:save"):
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