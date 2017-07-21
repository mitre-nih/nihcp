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
 

/**
 * All event handlers are bundled here
 */

/**
 * Listen to the creation of a comment
 *
 * @param string     $event  the name of the event
 * @param string     $type   the type of the event
 * @param ElggObject $object the supplied ElggObject
 *
 * @return void
 */
function user_support_create_comment_event($event, $type, $object) {
	
	// is it a comment
	if (empty($object) || !elgg_instanceof($object, "object", "comment")) {
		return;
	}
	
	// on a support ticket
	$entity = $object->getContainerEntity();
	if (empty($entity) || !elgg_instanceof($entity, "object", UserSupportTicket::SUBTYPE)) {
		return;
	}
	
	$comment_close = get_input("support_ticket_comment_close");
	
	if (!empty($comment_close)) {
		// comment and close the ticket
		$entity->setStatus(UserSupportTicket::CLOSED);
		$entity->closedDateTime = date_timestamp_get(new DateTime());
		
		$entity->save();
	} elseif ($entity->getOwnerGUID() == $object->getOwnerGUID()) {
		// if the ticket owner comments, re-open the ticket
		$entity->setStatus(UserSupportTicket::OPEN);
		
		$entity->save();
	}
}
