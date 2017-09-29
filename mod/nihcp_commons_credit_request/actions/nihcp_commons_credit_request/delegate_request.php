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

$delegation_guid = get_input('delegation_guid');

// check that logged in user is actually the intended delegate
$ia = elgg_set_ignore_access();
$delegation = get_entity($delegation_guid);
$delegate = get_user_by_email($delegation->getDelegateEmail())[0];
$is_delegate = !empty($delegate)
    && (elgg_get_logged_in_user_guid() === $delegate->getGUID());
$is_pending = !empty($delegation && ($delegation->getStatus() === CommonsCreditRequestDelegation::DELEGATION_PENDING_STATUS));
elgg_set_ignore_access($ia);

if (!$is_delegate || !$is_pending) {
    return false;
}

$action = get_input('action');
$ia = elgg_set_ignore_access();
switch ($action) {
    case  elgg_echo("nihcp_commons_credit_request:delegate:request:accept"):
        $ia = elgg_set_ignore_access();
        $ccreq = CommonsCreditRequestDelegation::getCCREQForDelegation($delegation->getGUID());
        if ($ccreq->status === CommonsCreditRequest::DRAFT_STATUS) {
            $delegation->setStatus(CommonsCreditRequestDelegation::DELEGATION_DELEGATED_STATUS);
        } else {
            $delegation->setStatus(CommonsCreditRequestDelegation::DELEGATION_SUBMITTED_STATUS);
        }
        add_entity_relationship($delegation->getGUID(), CommonsCreditRequestDelegation::RELATIONSHIP_DELEGATION_DELEGATED_TO, $delegate->getGUID());
        add_entity_relationship($ccreq->getGUID(), CommonsCreditRequestDelegation::RELATIONSHIP_CCREQ_TO_DELEGATE, $delegate->getGUID());
        elgg_log("Delegate with email " . $delegate->email . " has accepted delegation.");
        elgg_add_subscription(elgg_get_logged_in_user_guid(), 'email', $ccreq->getGUID());
        system_message("You have accepted delegation.");
        elgg_set_ignore_access($ia);
        break;
    case  elgg_echo("nihcp_commons_credit_request:delegate:request:decline"):
        $delegation->setStatus(CommonsCreditRequestDelegation::DELEGATION_DECLINED_STATUS);
        elgg_log("Delegate with email " . $delegate->email . " has declined delegation.");
        system_message("You have declined delegation.");
        break;
    default:
        break;
}
elgg_set_ignore_access($ia);
forward(elgg_get_site_url());
