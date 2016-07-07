<?php
use \Nihcp\Entity\CommonsCreditCycle;

nihcp_triage_coordinator_gatekeeper();
// which button was pressed
$action = htmlspecialchars(get_input('action', '', false), ENT_QUOTES, 'UTF-8');
if($action == "Save") {
	$guid = htmlspecialchars(get_input('cycle_guid', '', false), ENT_QUOTES, 'UTF-8');
	if ($guid) {
		$ia = elgg_set_ignore_access();
		$cycle = get_entity($guid);
		elgg_set_ignore_access($ia);
		if (!$cycle instanceof CommonsCreditCycle) {
			//error, redirect
			register_error(elgg_echo('nihcp_commons_credit_request:cycle:save:failed'));
			forward('/credit_request_cycle/overview');
		}
	} else {
		$cycle = new CommonsCreditCycle();
	}

	if ($guid = CommonsCreditCycle::saveCycleFromForm($cycle)) {
		system_message(elgg_echo('nihcp_commons_credit_request:cycle:save:success'));
	} else {
		register_error(elgg_echo('nihcp_commons_credit_request:cycle:save:failed'));
	}
}

forward('/credit_request_cycle/overview');