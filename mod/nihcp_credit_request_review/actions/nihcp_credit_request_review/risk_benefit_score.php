<?php
if (nihcp_domain_expert_gatekeeper()) {
    $action = get_input('action', '', false);

    $rb_guid = htmlspecialchars(get_input('rb_guid', '', false), ENT_QUOTES, 'UTF-8');
    $request_guid = htmlspecialchars(get_input('request_guid', '', false), ENT_QUOTES, 'UTF-8');

    // Because we create the RiskBenefitScore entity when a domain expert is assigned,
    // this entity should already exist by this point.
	$ia = elgg_set_ignore_access();
    $rb_entity = get_entity($rb_guid);
    elgg_set_ignore_access($ia);


    switch ($action) {
        case 'Save':

            $rb_entity->benefit_score = htmlspecialchars(get_input('benefit_score', '', false), ENT_QUOTES, 'UTF-8');
            $rb_entity->risk_score = htmlspecialchars(get_input('risk_score', '', false), ENT_QUOTES, 'UTF-8');
            $rb_entity->comments = htmlspecialchars(get_input('score_comments', '', false), ENT_QUOTES, 'UTF-8');

            $rb_entity->completed_date = date('n/j/Y');
            $rb_entity->status = \Nihcp\Entity\RiskBenefitScore::COMPLETED_STATUS;

            $rb_entity->save();
			$ia = elgg_set_ignore_access();
			if(\Nihcp\Entity\RiskBenefitScore::isCompleted($request_guid)) {
				$request = get_entity($request_guid);
				elgg_trigger_event('risk_benefit_score_complete', 'object:'.\Nihcp\Entity\CommonsCreditRequest::SUBTYPE, $request);
			}
			elgg_set_ignore_access($ia);
            break;
        default:
            // do nothing
            break;

    }

    forward(elgg_get_site_url() . "nihcp_credit_request_review/risk-benefit-score-overview/$request_guid");

}