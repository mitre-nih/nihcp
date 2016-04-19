<?php

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