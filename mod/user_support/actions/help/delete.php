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

$guid = (int) get_input("guid", 0);

if (!empty($guid) && ($entity = get_entity($guid))) {
	if (elgg_instanceof($entity, "object", UserSupportHelp::SUBTYPE, "UserSupportHelp")) {
		if ($entity->delete()) {
			system_message(elgg_echo("user_support:action:help:delete:success"));
		} else {
			register_error(elgg_echo("user_support:action:help:delete:error:delete"));
		}
	} else {
		register_error("InvalidClassException:NotValidElggStar", array($guid, "UserSupportHelp"));
	}
} else {
	register_error(elgg_echo("InvalidParameterException:MissingParameter"));
}

forward(REFERER);
