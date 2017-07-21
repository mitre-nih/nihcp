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

$user = elgg_get_logged_in_user_entity();
$page_owner = elgg_get_page_owner_entity();

if (empty($page_owner) || !(elgg_instanceof($page_owner, "site") || elgg_instanceof($page_owner, "group"))) {
	register_error(elgg_echo("pageownerunavailable", array(elgg_get_page_owner_guid())));
	forward(REFERER);
}

if (elgg_instanceof($page_owner, "group") && !$page_owner->canEdit()) {
	register_error(elgg_echo("user_support:page_owner:cant_edit"));
	forward(REFERER);
} elseif (elgg_instanceof($page_owner, "site")) {
	elgg_admin_gatekeeper();
}

$annotation = false;
if (elgg_is_admin_logged_in()) {
	if ($annotation_id = (int) get_input("annotation")) {
		if ($temp_anno = elgg_get_annotation_from_id($annotation_id)) {
			if (($entity = $temp_anno->getEntity()) && elgg_instanceof($entity, "object", UserSupportTicket::SUBTYPE)) {
				$annotation = $temp_anno;
			}
		}
	}
}

elgg_push_context("faq");

// make breadcrumb
if (elgg_instanceof($page_owner, "group")) {
	elgg_push_breadcrumb($page_owner->name, "user_support/faq/group/" . $page_owner->getGUID() . "/all");
}
elgg_push_breadcrumb(elgg_echo("user_support:faq:create:title"));

// page elements
$title_text = elgg_echo("user_support:faq:create:title");

$body_vars = array(
	"help_context" => user_support_find_unique_help_context(),
	"annotation" => $annotation
);
$content = elgg_view_form("user_support/faq/edit", array(), $body_vars);

// build page
$page_data = elgg_view_layout("content", array(
	"title" => $title_text,
	"content" => $content,
	"filter" => ""
));

// draw page
echo elgg_view_page($title_text, $page_data);
