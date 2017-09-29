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
 


use \Nihcp\Manager\RoleManager;
use \Nihcp\Entity\CommonsCreditRequest;
use \Nihcp\Entity\CommonsCreditRequestDelegation;

nihcp_role_gatekeeper(array(RoleManager::INVESTIGATOR, RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR, RoleManager::DOMAIN_EXPERT));

$guid = get_input('request_guid');

$ia = elgg_get_ignore_access();

$delegate = CommonsCreditRequestDelegation::getDelegateForCCREQ($guid);

// check to see if reviewer or delegate
if (nihcp_role_gatekeeper(array(RoleManager::TRIAGE_COORDINATOR, RoleManager::NIH_APPROVER, RoleManager::DOMAIN_EXPERT), false)
	|| (!empty($delegate) && ($delegate->guid === elgg_get_logged_in_user_guid()))) {
    $ia = elgg_set_ignore_access(true);
}

if($guid) {
	$request = get_entity($guid); // this should only return a value if ignore access succeeded above, or user is the owner
	if (!($request instanceof CommonsCreditRequest)) {
		register_error(elgg_echo('error:404:content'));
		forward('/nihcp_commons_credit_request/overview');
	}
}

$form_vars = array(
	'enctype' => 'multipart/form-data',
	'id' => 'ccreq-form'
);

$body_vars = array(
	'current_request' => $request
);

// allow form editing only if new request or in draft state.
// allow only delegate to edit if delegation is in "delegated" state
// allow only PI to edit if delegation is in
if ((empty($request)
		|| (($request->status === "Draft" || !$request->status) && $request->isDraftEditable()))
		&& nihcp_investigator_gatekeeper(false)) {
    $content = elgg_view_form('request', $form_vars, $body_vars);
} else {
    $content = elgg_view('commons_credit_request/submitted_request', $body_vars);
}

elgg_set_ignore_access($ia);
$params = array(
    'title' => elgg_echo("nihcp_commons_credit_request"),
    'content' => $content,
    'filter' => '',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page(elgg_echo("nihcp_commons_credit_request"), $body);


