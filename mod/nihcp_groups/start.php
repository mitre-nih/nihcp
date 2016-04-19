<?php

require_once(dirname(__FILE__) . "/lib/functions.php");

elgg_register_event_handler('init', 'system', 'nihcp_groups_init', 1000);

function nihcp_groups_init() {

	// Register some actions
	$action_base = elgg_get_plugins_path() . 'nihcp_groups/actions/groups/membership';
	elgg_register_action("groups/invite", "$action_base/invite.php");
}