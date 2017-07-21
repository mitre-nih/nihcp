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
 
elgg_gatekeeper();

$user = elgg_get_page_owner_entity();

if (!$user->canEdit() && !user_support_staff_gatekeeper(false)) {
	register_error(elgg_echo("user_support:staff_gatekeeper"));
	forward(REFERER);
}

$status = get_input("status", UserSupportTicket::OPEN);
if (!in_array($status, array(UserSupportTicket::OPEN, UserSupportTicket::CLOSED))) {
	$status = UserSupportTicket::OPEN;
}

$q = get_input("q");

$options = array(
	"type" => "object",
	"subtype" => UserSupportTicket::SUBTYPE,
	"owner_guid" => $user->getGUID(),
	"full_view" => false,
	"metadata_name_value_pairs" => array("status" => $status),
	"order_by" => "e.time_updated desc",
	"no_results" => elgg_echo("notfound")
);

if (!empty($q)) {
	$options["joins"] = array("JOIN " . elgg_get_config("dbprefix") . "objects_entity oe ON e.guid = oe.guid");
	$options["wheres"] = array("oe.description LIKE '%" . sanitise_string($q) . "%'");
}

$search_action = "user_support/support_ticket/owner/" . $user->username;

elgg_push_context("support_ticket_title");

// build page elements
if ($status == UserSupportTicket::CLOSED) {
	$search_action .= "/archive";
	if ($user->getGUID() == elgg_get_logged_in_user_guid()) {
		$title_text = elgg_echo("user_support:tickets:mine:archive:title");
	} else {
		$title_text = elgg_echo("user_support:tickets:owner:archive:title", array($user->name));
	}
} else {
	if ($user->getGUID() == elgg_get_logged_in_user_guid()) {
		$title_text = elgg_echo("user_support:tickets:mine:title");
	} else {
		$title_text = elgg_echo("user_support:tickets:owner:title", array($user->name));
	}
}

$form_vars = array(
	"method" => "GET",
	"disable_security" => true,
	"action" => $search_action
);
$search = elgg_view_form("user_support/support_ticket/search", $form_vars);

$body = elgg_list_entities_from_metadata($options);

// build page
$page_data = elgg_view_layout("content", array(
	"title" => $title_text,
	"content" => $search . $body,
	"filter" => elgg_view_menu("user_support", array("class" => "elgg-tabs"))
));

elgg_pop_context();

// draw page
echo elgg_view_page($title_text, $page_data);
