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

$guid = (int) get_input("guid");
$desc = get_input("description");
$help_context = get_input("help_context");
$tags = string_to_tag_array(get_input("tags"));

if (!empty($desc) && !empty($help_context)) {
	if (!empty($guid)) {
		if ($help = get_entity($guid)) {
			if (!elgg_instanceof($help, "object", UserSupportHelp::SUBTYPE, "UserSupportHelp")) {
				register_error("InvalidClassException:NotValidElggStar", array($guid, "UserSupportHelp"));
				unset($help);
			}
		}
	} else {
		$help = new UserSupportHelp();
		
		if (!$help->save()) {
			register_error(elgg_echo("IOException:UnableToSaveNew", array("UserSupportHelp")));
			unset($help);
		}
	}
	
	if (!empty($help)) {
		$help->description = $desc;
		$help->tags = $tags;
		$help->help_context = $help_context;
		
		if ($help->save()) {
			system_message(elgg_echo("user_support:action:help:edit:success"));
		} else {
			register_error(elgg_echo("user_support:action:help:edit:error:save"));
		}
	}
} else {
	register_error(elgg_echo("user_support:action:help:edit:error:input"));
}

forward(REFERER);
