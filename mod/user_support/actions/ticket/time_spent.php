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
 
$ticket_guid = (int) get_input("guid");
$user_guid = elgg_get_logged_in_user_guid();
$hours = (int) get_input("hours");
$minutes = (int) get_input("minutes");

//round minutes up to the nearest configured interval
$minutes = ceil($minutes / UserSupportTimeSpent::TIME_INTERVAL) * UserSupportTimeSpent::TIME_INTERVAL;

// get old time spent value for this helpdesk admin if it exists
$time_spent_entity = UserSupportTimeSpent::get_time_spent_for_ticket_by_user($ticket_guid, $user_guid);

// make a new one if it didnt exist yet
if (!isset($time_spent_entity)) {
    $time_spent_entity = new UserSupportTimeSpent();
    $time_spent_entity->save();
    add_entity_relationship($ticket_guid, UserSupportTimeSpent::RELATIONSHIP_TICKET_TO_TIME_SPENT, $time_spent_entity->getGUID());
    add_entity_relationship($user_guid, UserSupportTimeSpent::RELATIONSHIP_USER_TO_TIME_SPENT, $time_spent_entity->getGUID());
}

$time_spent_entity->setTimeSpent($hours * 60 + $minutes);