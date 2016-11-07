<?php

nihcp_nih_approver_gatekeeper();

$ia = elgg_set_ignore_access(true);

$request_guid = get_input('request_guid');
$request_entity = get_entity($request_guid);
$decision = get_input('decision');

if ($request_entity->isComplete() && $decision) {
	$content = elgg_view_form('decide-request', null, array('request_guid' => $request_guid, 'decision' => $decision));
} else if ($request_entity->isComplete()) {
	$content = elgg_echo("nihcp_credit_request_review:no_review");
} else {
	$content = elgg_echo("nihcp_credit_request_review:no_access");
}

elgg_set_ignore_access($ia);

echo $content;