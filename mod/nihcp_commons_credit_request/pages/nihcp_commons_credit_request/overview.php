<?php

use \Nihcp\Entity\CommonsCreditCycle;

nihcp_investigator_gatekeeper();

$ia = elgg_set_ignore_access();

$cycles = CommonsCreditCycle::getCycles($omit_future = true);

$session = elgg_get_session();
$selected_cycle_guid = $session->get('ccr_prev_selected_cycle', CommonsCreditCycle::getActiveCycleGUID());

$params = array(
    'title' => elgg_echo("nihcp_commons_credit_request"),
    'content' => elgg_view('commons_credit_request/overview', array('cycles' => $cycles, 'selected_cycle_guid' => $selected_cycle_guid)),
    'filter' => '',
	'class' => 'ccr-overview-layout'
);

elgg_set_ignore_access($ia);

$body = elgg_view_layout('content', $params);

echo elgg_view_page(elgg_echo("nihcp_commons_credit_request"), $body);