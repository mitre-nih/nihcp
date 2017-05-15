<?php
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