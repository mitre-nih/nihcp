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
 
if ($user_guid = elgg_get_logged_in_user_guid()) {
	elgg_set_page_owner_guid($user_guid);
}

// make sure we have the correct entity
$guid = (int) get_input("guid");
elgg_entity_gatekeeper($guid, "object", UserSupportFAQ::SUBTYPE);

$entity = get_entity($guid);
$container = $entity->getContainerEntity();

// build page elements
$title_text = $entity->title;

// make breadcrumb
if (elgg_instanceof($container, "group")) {
	elgg_push_breadcrumb($container->name, "user_support/faq/group/" . $container->getGUID() . "/all");
	elgg_set_page_owner_guid($container->getGUID());
}
elgg_push_breadcrumb($title_text);

$body = elgg_view_entity($entity, array(
	"full_view" => true
));

$comments = "";
if ($entity->canComment()) {
	$comments = elgg_view_comments($entity);
}

// build page
$page_data = elgg_view_layout("content", array(
	"title" => elgg_echo("user_support:question") . ": " . $title_text,
	"content" => $body . $comments,
	"filter" => ""
));

// draw page
echo elgg_view_page($title_text, $page_data);
