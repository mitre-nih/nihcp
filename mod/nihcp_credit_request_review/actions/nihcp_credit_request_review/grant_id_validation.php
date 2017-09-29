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
 


if (nihcp_triage_coordinator_gatekeeper()) {
    $action = get_input('action', '', false);

    $rb_guid = htmlspecialchars(get_input('rb_guid', '', false), ENT_QUOTES, 'UTF-8');
    $request_guid = htmlspecialchars(get_input('request_guid', '', false), ENT_QUOTES, 'UTF-8');
    $is_active = get_input('is_active','',false);
    $is_active_comment = htmlspecialchars(get_input('is_active_comment', '', false), ENT_QUOTES, 'UTF-8');

    // Because we create the RiskBenefitScore entity when a domain expert is assigned,
    // this entity should already exist by this point.
    $ia = elgg_set_ignore_access();
    $req = get_entity($request_guid);
    elgg_set_ignore_access($ia);

    switch ($action) {
        case 'Save':

            $ia2 = elgg_set_ignore_access();
            $req->is_active = $is_active;
            $req->is_active_comment = $is_active_comment;
            elgg_set_ignore_access($ia2);
            break;
        default:
            // do nothing
            break;
    }

    forward(elgg_get_site_url() . "nihcp_credit_request_review/overview");

}
