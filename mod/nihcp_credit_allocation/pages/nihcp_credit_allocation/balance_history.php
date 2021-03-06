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
 


namespace Nihcp\Entity;
use Nihcp\Manager\RoleManager;


$request_guid = get_input("request_guid");
$vendor_guid = get_input("vendor_guid");


if (!$request_guid || !$vendor_guid) {
    register_error(elgg_echo('error:404:content'));
    forward(REFERER);
}


// restrict access to only submitting investigator, delegate, credit admins, NIH approvers, triage coordinators
if (CommonsCreditRequest::hasAccess($request_guid) || nihcp_role_gatekeeper(array(RoleManager::NIH_APPROVER, RoleManager::TRIAGE_COORDINATOR, RoleManager::CREDIT_ADMIN), false)) {


    $content = elgg_view('nihcp_credit_allocation/balance_history', array("request_guid" => $request_guid, "vendor_guid" => $vendor_guid));

    $body = elgg_view_layout('one_sidebar', ['content' => $content, 'title' => elgg_echo("nihcp_credit_allocation")]);

    echo elgg_view_page(elgg_echo("nihcp_credit_allocation"), $body);
} else {
    register_error(elgg_echo("nihcp_groups:role:gatekeeper"));
    forward(REFERER);
}
