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
 


use \Nihcp\Entity\CommonsCreditRequest;
use \Nihcp\Entity\CommonsCreditRequestDelegation;
use Nihcp\Manager\RoleManager;

nihcp_role_gatekeeper(array(RoleManager::INVESTIGATOR, RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR, RoleManager::DOMAIN_EXPERT));
$guid = get_input('request_guid');
$current_request = get_entity($guid);

// $current_request should be empty if current user is not the owner or admin
if (empty($current_request) || !($current_request instanceof CommonsCreditRequest)) {
    register_error(elgg_echo("nihcp_groups:role:gatekeeper"));
    forward(REFERER);
} else {

    // if no delegation exists yet, show the form for adding one
    // else show the existing delegation
    $delegation = CommonsCreditRequestDelegation::getDelegationForCCREQ($guid);

    $body_vars = ['current_request' => $current_request, 'delegation' => $delegation];
    if (empty($delegation)) {
        $content = elgg_view_form('delegate', null, $body_vars);
    } else {
        $content = elgg_view('commons_credit_request/delegate', $body_vars);
    }

    $params = array(
        'title' => elgg_echo("nihcp_commons_credit_request:delegate"),
        'content' => $content,
        'filter' => '',
    );

    $body = elgg_view_layout('content', $params);

    echo elgg_view_page("nihcp_commons_credit_request", $body);

}


