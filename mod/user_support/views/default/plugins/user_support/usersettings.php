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
 


$page_owner = elgg_get_page_owner_entity();

if ($page_owner->getGUID() != elgg_get_logged_in_user_guid()) {
	return;
}

if (!user_support_staff_gatekeeper(false, $page_owner->getGUID())) {
	return;
}

$plugin = elgg_extract("entity", $vars);

$noyes_options = array(
	"no" => elgg_echo("option:no"),
	"yes" => elgg_echo("option:yes")
);

$body = "<div>";
$body .= elgg_echo("user_support:usersettings:admin_notify") . "<br />";
$body .= elgg_view("input/dropdown", array("name" => "params[admin_notify]", "options_values" => $noyes_options, "value" => $plugin->getUserSetting("admin_notify", $page_owner->getGUID())));
$body .= "</div>";

echo $body;
