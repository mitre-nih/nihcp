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
 


namespace Nihcp\Manager;

class RoleManager {

	const INVESTIGATOR = 'Investigator';
	const NIH_APPROVER = 'NIH Approver';
	const DOMAIN_EXPERT = 'Domain Expert';
	const TRIAGE_COORDINATOR = 'Triage Coordinator';
	const VENDOR_ADMIN = 'Vendor Administrator';
	const HELP_ADMIN = 'Help Administrator';
	const CREDIT_ADMIN = 'Credit Administrator';


	public static function getConfig() {
		return [0 => self::INVESTIGATOR,
				1 => self::NIH_APPROVER,
				2 => self::DOMAIN_EXPERT,
				3 => self::TRIAGE_COORDINATOR,
				4 => self::VENDOR_ADMIN,
                5 => self::HELP_ADMIN,
				6 => self::CREDIT_ADMIN];
	}

	public static function createRoles() {
		$role_names = RoleManager::getConfig();
		foreach($role_names as $idx => $name) {
			if(RoleManager::getRoleByRoleId($idx)) {
				error_log("Not creating role `$name` because it already exists.");
				continue;
			}
			$group = new \ElggGroup();
			$group->membership = ACCESS_PRIVATE;
			$group->name = $name;
			$group->role_id = $idx;
			if ($group->save()) {
				error_log("Created role $name");
			} else {
				error_log("Unable to create role $name");
			}
		}
	}

	public static function getRoleByRoleId($role_id) {
		$options = [
			'type' => 'group',
			'limit' => 1,
			'metadata_name_value_pairs' => array(
				'name' => 'role_id',
				'value' => $role_id,
			),
		];

		$ia = elgg_set_ignore_access();
		$roles = elgg_get_entities_from_metadata($options);
		elgg_set_ignore_access($ia);

		return $roles ? $roles[0] : false;
	}

	public static function getRoleByName($role_name) {
		$config = RoleManager::getConfig();
		$role_id = null;
		foreach ($config as $_id => $_name) {
			if ($_name === $role_name) {
				$role_id = $_id;
				break;
			}
		}
		if (isset($role_id)) {
			return RoleManager::getRoleByRoleId($role_id);
		}
		return false;
	}

	public static function getRolesByUser($user_guid = 0) {
		if(!$user_guid) {
			$user_guid = elgg_get_logged_in_user_guid();
		}
		$user = get_entity($user_guid);
		if(!elgg_instanceof($user, 'user')) {
			return false;
		}
		$ia = elgg_set_ignore_access();
		$groups = $user->getGroups([], $limit = 0);
		elgg_set_ignore_access($ia);
		return $groups;
	}
}