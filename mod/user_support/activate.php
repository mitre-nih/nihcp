<?php
/*
Copyright 2017 The MITRE Corporation

This software was written for the NIH Commons Credit Portal.
General questions can be forwarded to:
 
opensource@mitre.org

Technology Transfer Office
The MITRE Corporation
7515 Colshire Drive
McLean, VA 22102-7539

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
 

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