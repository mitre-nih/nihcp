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
 


$types_values = array(
	"question" => elgg_echo("user_support:support_type:question"),
	"bug" => elgg_echo("user_support:support_type:bug"),
	"request" => elgg_echo("user_support:support_type:request"),
);

$entity = elgg_extract("entity", $vars);

if (!empty($entity)) {
	$title = $entity->description;
	$tags = $entity->tags;
	$help_url = $entity->help_url;
	$support_type = $entity->support_type;
	
	$form_body = elgg_view("input/hidden", array("name" => "guid", "value" => $entity->getGUID()));
	$form_body .= elgg_view("input/hidden", array("name" => "help_context", "value" => $entity->help_context));
} else {
	$title = "";
	$tags = array();
	$help_url = elgg_extract("help_url", $vars);
	$support_type = "";
	$help_context = elgg_extract("help_context", $vars);
	
	if (!empty($help_context)) {
		$form_body = elgg_view("input/hidden", array("name" => "help_context", "value" => $help_context));
	} else {
		$form_body = elgg_view("input/hidden", array("name" => "help_context", "value" => user_support_get_help_context()));
	}
}

$form_body .= "<div>";
$form_body .= "<label for=\"question_title\">" . elgg_echo("user_support:question") . "</label>";
$form_body .= elgg_view("input/plaintext", array("id" => "question_title", "name" => "title", "value" => $title));
$form_body .= "</div>";


$form_body .= "<div>";
$form_body .= "<label for=\"tags\">" . elgg_echo("tags") . "</label>";
$form_body .= elgg_view("input/tags", array("id" => "tags", "name" => "tags", "value" => $tags));
$form_body .= "</div>";

if ($help_url) {
	$form_body .= "<div>";
	$form_body .= "<label for=\"help_url\">" . elgg_echo("user_support:url") . "</label>";
	$form_body .= elgg_view("input/url", array("id" => "help_url", "name" => "help_url", "value" => $help_url));
	$form_body .= "</div>";
}

$form_body .= "<div class='elgg-foot'>";
$form_body .= elgg_view("input/hidden", array("name" => "elgg_xhr", "value" => elgg_is_xhr()));
$form_body .= elgg_view("input/submit", array("value" => elgg_echo("submit")));
$form_body .= "</div>";

echo $form_body;
