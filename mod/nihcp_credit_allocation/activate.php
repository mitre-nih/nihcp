<?php
if (get_subtype_id('object', 'commonscreditallocation')) {
	update_subtype('object', 'commonscreditallocation', 'Nihcp\Entity\CommonsCreditAllocation');
} else {
	add_subtype('object', 'commonscreditallocation', 'Nihcp\Entity\CommonsCreditAllocation');
}
if (get_subtype_id('object', 'commonscreditallocationfile')) {
	update_subtype('object', 'commonscreditallocationfile', 'Nihcp\Entity\CommonsCreditAllocationFile');
} else {
	add_subtype('object', 'commonscreditallocationfile', 'Nihcp\Entity\CommonsCreditAllocationFile');
}
if (get_subtype_id('object', 'commonscreditvendor')) {
	update_subtype('object', 'commonscreditvendor', 'Nihcp\Entity\CommonsCreditVendor');
} else {
	add_subtype('object', 'commonscreditvendor', 'Nihcp\Entity\CommonsCreditVendor');
}