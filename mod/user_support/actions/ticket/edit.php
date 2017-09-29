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
$title = get_input("title");
$help_url = get_input("help_url");
$help_context = get_input("help_context");
$tags = string_to_tag_array(get_input("tags"));
$support_type = get_input("support_type");
$elgg_xhr = get_input("elgg_xhr");

$forward_url = REFERER;

$loggedin_user = elgg_get_logged_in_user_entity();

if (!empty($title)) {
	if (!empty($guid)) {
		if ($ticket = get_entity($guid)) {
			if (!elgg_instanceof($ticket, "object", UserSupportTicket::SUBTYPE, "UserSupportTicket")) {
				register_error(elgg_echo("InvalidClassException:NotValidElggStar", array($guid, "UserSupportTicket")));
				unset($ticket);
			}
		}
	} else {
		$ticket = new UserSupportTicket();
		
		$ticket->title = elgg_get_excerpt($title, 50);
		$ticket->description = $title;
		
		if (!$ticket->save()) {
			register_error(elgg_echo("IOException:UnableToSaveNew", array("UserSupportTicket")));
			unset($ticket);
		}
	}
	
	if (!empty($ticket)) {
		$ticket->title = elgg_get_excerpt($title, 50);
		$ticket->description = $title;
		
		$ticket->help_url = $help_url;
		$ticket->help_context = $help_context;
		$ticket->tags = $tags;
		$ticket->support_type = $support_type;

		// XXX unset this once access controls are properly developed
		$ticket->access_id = ACCESS_LOGGED_IN;
		
		if ($ticket->save()) {
			if (!empty($guid)) {
				$forward_url = $ticket->getURL();
			} elseif (empty($elgg_xhr)) {
				$forward_url = "user_support/support_ticket/owner/" . $loggedin_user->username;
			}
			system_message(elgg_echo("user_support:action:ticket:edit:success"));
		} else {
			register_error(elgg_echo("user_support:action:ticket:edit:error:save"));
		}
	}
} else {
	register_error(elgg_echo("user_support:action:ticket:edit:error:input"));
}

forward($forward_url);
