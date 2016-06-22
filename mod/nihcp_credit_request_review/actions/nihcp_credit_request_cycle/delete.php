<?php
nihcp_triage_coordinator_gatekeeper();

$guid = htmlspecialchars(get_input('cycle_guid', '', false), ENT_QUOTES, 'UTF-8');
if($guid) {
    $ia = elgg_set_ignore_access();
	$cycle = get_entity($guid);
	$r = false;
	if($cycle->canDelete()) {
		$r = $cycle->delete();
	}
	elgg_set_ignore_access($ia);
	return $r;
}

return false;