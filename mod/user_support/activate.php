<?php
// register subtype handlers
if (get_subtype_id('object', UserSupportFAQ::SUBTYPE)) {
	update_subtype('object', UserSupportFAQ::SUBTYPE, "UserSupportFAQ");
} else {
	add_subtype('object', UserSupportFAQ::SUBTYPE, "UserSupportFAQ");
}

if (get_subtype_id('object', UserSupportHelp::SUBTYPE)) {
	update_subtype('object', UserSupportHelp::SUBTYPE, "UserSupportHelp");
} else {
	add_subtype('object', UserSupportHelp::SUBTYPE, "UserSupportHelp");
}

if (get_subtype_id('object', UserSupportTicket::SUBTYPE)) {
	update_subtype('object', UserSupportTicket::SUBTYPE, "UserSupportTicket");
} else {
	add_subtype('object', UserSupportTicket::SUBTYPE, "UserSupportTicket");
}

if (get_subtype_id('object', UserSupportTimeSpent::SUBTYPE)) {
	update_subtype('object', UserSupportTimeSpent::SUBTYPE, "UserSupportTimeSpent");
} else {
	add_subtype('object', UserSupportTimeSpent::SUBTYPE, "UserSupportTimeSpent");
}