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

		if(elgg_is_active_plugin('nihcp_notifications')) {
			$de = get_entity($assign_de_guid);
			$subject = elgg_echo('nihcp_notifications:notify:assign_de:subject');
			$message = elgg_echo('nihcp_notifications:notify:assign_de:body', [$de->getDisplayName(), $request->getRequestId()]);
			$summary = elgg_echo('nihcp_notifications:notify:assign_de:summary', [$de->getDisplayName(), $request->getRequestId()]);
			notify_user($assign_de_guid, elgg_get_site_entity()->getGUID(), $subject, $message,
				$params = ['object' => $request, 'action' => 'assign_de', 'summary' => $summary],
				$methods_override = 'email');
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