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
 
// time spent should be stored in minutes

$ticket = elgg_extract("entity", $vars);
$user_guid = elgg_get_logged_in_user_guid();

if (!empty($ticket)) {

    $time_spent_entity = UserSupportTimeSpent::get_time_spent_for_ticket_by_user($ticket->getGUID(), $user_guid);

	$hours = $time_spent_entity ? (int)($time_spent_entity->getTimeSpent() / 60) : 0;
	$minutes = $time_spent_entity ? (int)($time_spent_entity->getTimeSpent() % 60) : 0;

	$total_time = UserSupportTimeSpent::sum_time_spent_for_ticket($ticket->getGUID());

	$total_hours = (int)($total_time / 60);
	$total_minutes = (int)($total_time % 60);

	$form_body = elgg_view("input/hidden", array("name" => "guid", "value" => $ticket->getGUID()));

	$form_body .= "<div class='time-spent'>";

	$form_body .= "<p>";
	$form_body .= elgg_echo('user_support:ticket:time_spent');
	$form_body .= "</p>";

	$form_body .= "<div class='elgg-grid'>";

	//hours
	$form_body .= "<div class='elgg-col elgg-col-1of6'>";
	$form_body .= "<label>" . elgg_echo("user_support:ticket:time_spent:hours") . "</label>";
	$form_body .= elgg_view('input/number', array('name' => 'hours', 'value' => $hours, 'min' => 0));
	$form_body .= "</div>";

	//minutes
	$form_body .= "<div class=' elgg-col elgg-col-1of6'>";
	$form_body .= "<label>" . elgg_echo("user_support:ticket:time_spent:minutes") . "</label>";
	$form_body .= elgg_view('input/number', array('name' => 'minutes', 'value' => $minutes, 'min' => 0));
	$form_body .= "</div>";

	$form_body .= "</div>";

	$form_body .= "<div class='elgg-grid'>";

	//total time
	$form_body .= "<div class='ptm elgg-col elgg-col-1of5'>";
	$form_body .= "<label>" . elgg_echo("user_support:ticket:total_time_spent") . "</label>";
	$form_body .= "<div class='solid border plm pts'>$total_hours h $total_minutes m</div>";
	$form_body .= "</div>";

	$form_body .= "</div>";

	//save
	$form_body .= "<div class='ptm'>";
	$form_body .= elgg_view('input/submit', array('name' => 'action', 'value' => "Update"));
	$form_body .= "</div>";

	$form_body .= "</div>";

    echo $form_body;
}