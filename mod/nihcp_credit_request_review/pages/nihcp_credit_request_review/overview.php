<?php

elgg_require_js('jquery');
elgg_require_js('crr');

use \Nihcp\Entity\CommonsCreditRequest;
use \Nihcp\Entity\CommonsCreditCycle;
use Nihcp\Manager\RoleManager;

nihcp_role_gatekeeper(array(RoleManager::DOMAIN_EXPERT, RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR));

pseudo_atomic_set_ignore_access(function () {

	$cycles = elgg_get_entities_from_metadata([
		'type' => 'object',
		'subtype' => CommonsCreditCycle::SUBTYPE,
		'order_by_metadata' => array(
			'name' => 'start',
			'direction' => ASC
		),
		'limit' => 0,
	]);

	$selected_cycle_guid = CommonsCreditCycle::getActiveCycleGUID();

	$params = array(
		'title' => elgg_echo("nihcp_credit_request_review"),
		'content' => elgg_view('nihcp_credit_request_review/overview', array('cycles' => $cycles, 'selected_cycle_guid' => $selected_cycle_guid)),
		'filter' => '',
		'class' => 'crr-overview-layout'
	);

	$body = elgg_view_layout('one_column', $params);

	echo elgg_view_page("nihcp_credit_request_review", $body, 'default');

});
