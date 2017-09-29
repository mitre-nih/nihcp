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
 

if (get_subtype_id('object', 'commonscreditrequest')) {
	update_subtype('object', 'commonscreditrequest', 'Nihcp\Entity\CommonsCreditRequest');
} else {
	add_subtype('object', 'commonscreditrequest', 'Nihcp\Entity\CommonsCreditRequest');
}
if (get_subtype_id('object', 'commonscreditcycle')) {
	update_subtype('object', 'commonscreditcycle', 'Nihcp\Entity\CommonsCreditCycle');
} else {
	add_subtype('object', 'commonscreditcycle', 'Nihcp\Entity\CommonsCreditCycle');
}
if (get_subtype_id('object', 'commonscreditrequestid')) {
	update_subtype('object', 'commonscreditrequestid', 'Nihcp\Entity\CommonsCreditRequestId');
} else {
	add_subtype('object', 'commonscreditrequestid', 'Nihcp\Entity\CommonsCreditRequestId');
}
if (get_subtype_id('object', 'commonscreditstatuschange')) {
	update_subtype('object', 'commonscreditstatuschange', 'Nihcp\Entity\CommonsCreditStatusChange');
} else {
	add_subtype('object', 'commonscreditstatuschange', 'Nihcp\Entity\CommonsCreditStatusChange');
}
if (get_subtype_id('object', 'commonscreditrequestdelegation')) {
	update_subtype('object', 'commonscreditrequestdelegation', 'Nihcp\Entity\CommonsCreditRequestDelegation');
} else {
	add_subtype('object', 'commonscreditrequestdelegation', 'Nihcp\Entity\CommonsCreditRequestDelegation');
}

/* Fix for legacy installations: assign CCREQ IDs to submitted CCREQs */
/*
$entities = elgg_get_entities_from_metadata([
	'type' => 'object',
	'subtype' => \Nihcp\Entity\CommonsCreditRequest::SUBTYPE,
	'limit' => 0,
	'metadata_name_value_pairs' => [
		['name' => 'status', 'value' => \Nihcp\Entity\CommonsCreditRequest::DRAFT_STATUS, 'operand' => '!=']
	]
]);
foreach($entities as $entity) {
	$result = \Nihcp\Entity\CommonsCreditRequestId::assignToRequest($entity->getGUID());
	if($result) {
		elgg_log("Generated CCREQ ID for $result");
	}
}
*/
