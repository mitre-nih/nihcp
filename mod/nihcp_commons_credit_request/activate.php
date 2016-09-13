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