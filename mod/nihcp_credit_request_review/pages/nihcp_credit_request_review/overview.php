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
			'direction' => 'ASC'
		),
		'limit' => 0,
	]);

	$session = elgg_get_session();
	$selected_cycle_guid = $session->get('crr_prev_selected_cycle', CommonsCreditCycle::getActiveCycleGUID());

	$params = array(
		'title' => elgg_echo("nihcp_credit_request_review"),
		'content' => elgg_view('nihcp_credit_request_review/overview', array('cycles' => $cycles, 'selected_cycle_guid' => $selected_cycle_guid)),
		'filter' => '',
		'class' => 'crr-overview-layout'
	);

	$body = elgg_view_layout('one_column', $params);

	echo elgg_view_page("nihcp_credit_request_review", $body, 'default');

});
