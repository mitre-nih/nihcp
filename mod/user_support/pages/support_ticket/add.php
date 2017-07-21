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

elgg_set_page_owner_guid($user->getGUID());

$title_text = elgg_echo("user_support:help_center:ask");

// breadcrumb
elgg_push_breadcrumb(elgg_echo("user_support:tickets:mine:title"), "user_support/support_ticket/owner/" . $user->username);
elgg_push_breadcrumb($title_text);

// page elements
$form = elgg_view_form("user_support/support_ticket/edit");

// build page
$page_data = elgg_view_layout("content", array(
	"title" => $title_text,
	"content" => $form,
	"filter" => ""
));

// draw page
echo elgg_view_page($title_text, $page_data);