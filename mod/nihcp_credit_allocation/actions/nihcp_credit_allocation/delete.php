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
 

use Nihcp\Entity\CommonsCreditAllocation;
use Nihcp\Entity\CommonsCreditRequest;

$request_guid = htmlspecialchars(get_input('request_guid', '', false), ENT_QUOTES, 'UTF-8');
$vendor_guid = htmlspecialchars(get_input('vendor_guid', '', false), ENT_QUOTES, 'UTF-8');

// do a check to see if user should have access to this request in order to delete allocations for it
$ia = elgg_get_ignore_access();
if(CommonsCreditRequest::hasAccess($request_guid)) {
	$ia = elgg_set_ignore_access();
}

$guid = CommonsCreditAllocation::getAllocationGUID($request_guid, $vendor_guid);

elgg_set_ignore_access($ia);

if($guid) {

	$ia = elgg_get_ignore_access();
	if(CommonsCreditRequest::hasAccess($request_guid)) {
		$ia = elgg_set_ignore_access();
	}

	$allocation = get_entity($guid);


	if($allocation->status === CommonsCreditAllocation::STAGED_STATUS || elgg_is_admin_logged_in()) {
		$result = $allocation->delete();
		elgg_set_ignore_access($ia);;
		return $result;
	}
	elgg_set_ignore_access($ia);
}

elgg_set_ignore_access($ia);

return 'error';
