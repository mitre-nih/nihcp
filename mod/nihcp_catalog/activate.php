<?php

if (get_subtype_id('object', 'glossary')) {
	update_subtype('object', 'glossary', 'Nihcp\Entity\Glossary');
} else {
	add_subtype('object', 'glossary', 'Nihcp\Entity\Glossary');
}

if (get_subtype_id('object', 'services')) {
	update_subtype('object', 'services', 'Nihcp\Entity\Services');
} else {
	add_subtype('object', 'services', 'Nihcp\Entity\Services');
}


if (get_subtype_id('object', 'equivalency')) {
	update_subtype('object', 'equivalency', 'Nihcp\Entity\Equivalency');
} else {
	add_subtype('object', 'equivalency', 'Nihcp\Entity\Equivalency');
}