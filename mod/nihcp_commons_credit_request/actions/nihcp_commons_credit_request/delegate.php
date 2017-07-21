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