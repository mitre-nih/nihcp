<?php

use \Nihcp\Entity\CommonsCreditRequest;
use \Nihcp\Entity\CommonsCreditRequestDelegation;

$guid = get_input('request_guid');
$current_request = get_entity($guid);

// $current_request should be non-empty only when the owner of the ccreq comes here
if(!empty($current_request) && ($current_request instanceof CommonsCreditRequest)) {
    $action = get_input('action');

    switch ($action) {
        case 'Add':

            // make sure user isn't trying to delegate themselves
            $delegate_email = htmlspecialchars(get_input('delegate_email', '', false), ENT_QUOTES, 'UTF-8');
            if (elgg_get_logged_in_user_entity()->email === $delegate_email) {
                register_error(elgg_echo('nihcp_commons_credit_request:delegate:self_delegation_error'));
                break;
            }

            CommonsCreditRequestDelegation::createNewDelegation($current_request);
            break;
        case 'Cancel':
            forward('nihcp_commons_credit_request/overview');
            break;
        default:
            break;
    }

    forward("nihcp_commons_credit_request/delegate/$guid");
} else {
    register_error(elgg_echo('error:404:content'));
}