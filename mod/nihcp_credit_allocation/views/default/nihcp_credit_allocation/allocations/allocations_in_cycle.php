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

$review_mode = elgg_extract('review_mode', $vars, nihcp_role_gatekeeper([RoleManager::TRIAGE_COORDINATOR, RoleManager::NIH_APPROVER, RoleManager::CREDIT_ADMIN], false));

$cycle_guid = elgg_extract('cycle_guid', $vars);

$ia = elgg_get_ignore_access();

$requests = [];
if($review_mode) {
	$ia = elgg_set_ignore_access();
	$cycle = get_entity($cycle_guid);
	$requests = CommonsCreditRequest::sort($cycle->getRequests());
} else {
	$requests = CommonsCreditRequest::getByUserAndCycle('all', 0, $cycle_guid);
}
elgg_set_ignore_access($ia);
$full_view = elgg_extract('full_view', $vars, true);
$content = elgg_view('nihcp_credit_allocation/components/allocations', ['full_view' => $full_view, 'review_mode' => $review_mode, 'requests' => $requests]);

echo $content;
