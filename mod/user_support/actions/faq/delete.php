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

$forward_url = REFERER;

if (!empty($guid)) {
	if (($entity = get_entity($guid)) && $entity->canEdit()) {
		$container = $entity->getContainerEntity();
		
		if (elgg_instanceof($entity, "object", UserSupportFAQ::SUBTYPE, "UserSupportFAQ")) {
			if ($entity->delete()) {
				
				if (elgg_instanceof($container, "group")) {
					$forward_url = "user_support/faq/group/" . $container->getGUID() . "/all";
				} else {
					$forward_url = "user_support/faq";
				}
				
				system_message(elgg_echo("user_support:action:faq:delete:success"));
			} else {
				register_error(elgg_echo("user_support:action:faq:delete:error:delete"));
			}
		} else {
			register_error(elgg_echo("InvalidClassException:NotValidElggStar", array($guid, "UserSupportFAQ")));
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:NoEntityFound"));
	}
} else {
	register_error(elgg_echo("InvalidParameterException:MissingParameter"));
}

forward($forward_url);
