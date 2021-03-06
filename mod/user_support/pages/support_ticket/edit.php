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

$guid = (int) get_input("guid");
elgg_entity_gatekeeper($guid, "object", UserSupportTicket::SUBTYPE);

$entity = get_entity($guid);
if (!$entity->canEdit()) {
	register_error(elgg_echo("limited_access"));
	forward(REFERER);
}

$owner = $entity->getOwnerEntity();

elgg_set_page_owner_guid($owner->getGUID());

$title_text = $entity->title;

// breadcrumb
if ($owner->getGUID() == elgg_get_logged_in_user_guid()) {
	elgg_push_breadcrumb(elgg_echo("user_support:tickets:mine:title"), "user_support/support_ticket/owner/" . $owner->username);
} else {
	elgg_push_breadcrumb(elgg_echo("user_support:tickets:owner:title", array($owner->name)), "user_support/support_ticket/owner/" . $owner->username);
}

elgg_push_breadcrumb($title_text, $entity->getURL());
elgg_push_breadcrumb(elgg_echo("edit"));

// build page
$page_data = elgg_view_layout("content", array(
	"title" => $title_text,
	"content" => elgg_view_form("user_support/support_ticket/edit", array(), array("entity" => $entity)),
	"filter" => ""
));

echo elgg_view_page($title_text, $page_data);
