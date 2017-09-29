<?php
/*
Copyright 2017 The MITRE Corporation
 
This software was written for the NIH Commons Credit Portal. General questions 
can be forwarded to:

opensource@mitre.org

Technology Transfer Office
The MITRE Corporation
7515 Colshire Drive
McLean, VA 22102-7539

Permission is hereby granted, free of charge, to any person obtaining a copy 
of this software and associated documentation files (the "Software"), to deal 
in the Software without restriction, including without limitation the rights 
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
 


use \Nihcp\Manager\RoleManager;

require_once(dirname(__FILE__) . "/lib/functions.php");

elgg_register_event_handler('init', 'system', 'nihcp_groups_init', 1000);


function nihcp_groups_init() {

	// overriding Elgg group plugin menu setup
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'nihcp_groups_entity_menu_setup');

	// Register some actions
	$action_base = elgg_get_plugins_path() . 'nihcp_groups/actions/groups/membership';
	elgg_register_action("groups/invite", "$action_base/invite.php");

	// automatically join new users to the Investigator group
	elgg_register_plugin_hook_handler('register', 'user', 'nihcp_groups_autoadd_investigator');

	elgg_register_event_handler('join', 'group', 'triage_coordinator_join_sub');


}

// automatically join new users to the Investigator group
function nihcp_groups_autoadd_investigator($hook, $type, $return, $params) {
	$user = elgg_extract('user', $params);
	$group = RoleManager::getRoleByName(RoleManager::INVESTIGATOR);

	groups_join_group($group, $user);

	system_message(elgg_echo('nihcp_groups:register:email_verification'));
}


/**
 * Add links/info to entity menu particular to group entities
 *
 * Overriding Elgg group plugin menu setup.
 */
function nihcp_groups_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	/* @var ElggGroup $entity */
	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'groups') {
		return $return;
	}

	/* @var ElggMenuItem $item */
	foreach ($return as $index => $item) {
		if (in_array($item->getName(), array('access', 'likes', 'unlike', 'edit', 'delete', 'feature', 'unfeature'))) {
			unset($return[$index]);
		}
	}

	// membership type
	if ($entity->isPublicMembership()) {
		$mem = elgg_echo("groups:open");
	} else {
		$mem = elgg_echo("groups:closed");
	}

	$options = array(
		'name' => 'membership',
		'text' => $mem,
		'href' => false,
		'priority' => 100,
	);
	$return[] = ElggMenuItem::factory($options);

	// number of members
	$num_members = $entity->getMembers(array('limit' => 0, 'count' => true));
	$members_string = elgg_echo('groups:member');
	$options = array(
		'name' => 'members',
		'text' => $num_members . ' ' . $members_string,
		'href' => elgg_get_site_url() . "groups/members/" .$entity->getGUID(),
		'priority' => 200,
	);
	$return[] = ElggMenuItem::factory($options);

	return $return;
}

function triage_coordinator_join_sub($event, $type, $params) {
	$group = $params['group'];
	if(!$group->getDisplayName() === RoleManager::TRIAGE_COORDINATOR) {
		return true;
	}
	$user = $params['user'];
	elgg_add_subscription($user->getGUID(), 'email', $group->getGUID());
}
