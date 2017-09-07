<?php
if (get_subtype_id('object', 'post_implementation_report')) {
	update_subtype('object', 'post_implementation_report', 'Nihcp\Entity\PostImplementationReport');
} else {
	add_subtype('object', 'post_implementation_report', 'Nihcp\Entity\PostImplementationReport');
}
if (get_subtype_id('object', 'digital_object_report')) {
	update_subtype('object', 'digital_object_report', 'Nihcp\Entity\DigitalObjectReport');
} else {
	add_subtype('object', 'digital_object_report', 'Nihcp\Entity\DigitalObjectReport');
}
