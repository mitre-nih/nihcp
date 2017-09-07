<?php
nihcp_triage_coordinator_gatekeeper();

$guid = get_input('cycle_guid');
$form_vars = array(
	'enctype' => 'multipart/form-data',
	'id' => 'ccr-cycle-form'
);

$body_vars = array(
	'cycle_guid' => $guid
);


$ia = elgg_set_ignore_access(true);

$content = elgg_view_form('credit_request_cycle/edit', $form_vars, $body_vars);

elgg_set_ignore_access($ia);

$params = array(
	'title' => elgg_echo("nihcp_credit_request_review"),
	'content' => $content,
	'filter' => '',
);

$body = elgg_view_layout('one_column', $params);

echo elgg_view_page(elgg_echo("nihcp_credit_request_review"), $body);
