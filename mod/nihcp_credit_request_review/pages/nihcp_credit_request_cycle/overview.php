<?php
use \Nihcp\Entity\CommonsCreditCycle;

nihcp_triage_coordinator_gatekeeper();

$ia = elgg_set_ignore_access();

$cycles = CommonsCreditCycle::getCycles();

$params = array(
	'title' => elgg_echo("nihcp_credit_request_review"),
	'content' => elgg_view('nihcp_credit_request_cycle/overview', array('cycles' => $cycles)),
	'filter' => '',
);

elgg_set_ignore_access($ia);

$body = elgg_view_layout('content', $params);


echo elgg_view_page("nihcp_commons_credit_request", $body);
