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
 


use \Nihcp\Entity\CommonsCreditCycle;
use \Nihcp\Entity\CommonsCreditRequest;

$ia = elgg_set_ignore_access();

$cycles = CommonsCreditCycle::getCycles($omit_future = true);

$session = elgg_get_session();
$selected_cycle_guid = $session->get('ccr_prev_selected_cycle', CommonsCreditCycle::getActiveCycleGUID());

$content = elgg_view('commons_credit_request/overview', array('cycles' => $cycles, 'selected_cycle_guid' => $selected_cycle_guid, 'full_view' => false));

$has_approved_requests = !empty(CommonsCreditRequest::getByUser(\Nihcp\Entity\CommonsCreditRequest::APPROVED_STATUS));

elgg_set_ignore_access($ia);

if ($has_approved_requests) {
    $link_text = elgg_echo("nihcp_commons_credit_request:ccreq:more_and_allocate");
} else {
    $link_text = elgg_echo("nihcp_commons_credit_request:ccreq:more");
}

$content .= "<div class=\"ptm\"><a href=\"".elgg_get_site_url()."nihcp_commons_credit_request/overview\">".$link_text."</a></div>";

echo $content;
