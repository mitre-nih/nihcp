<?php

namespace Nihcp\Manager;

class RoleManager {

	const INVESTIGATOR = 'Investigator';
	const NIH_APPROVER = 'NIH Approver';
	const DOMAIN_EXPERT = 'Domain Expert';
	const TRIAGE_COORDINATOR = 'Triage Coordinator';
	const VENDOR_ADMIN = 'Vendor Administrator';
	const HELP_ADMIN = 'Help Administrator';


	public static function getConfig() {
		return [0 => self::INVESTIGATOR,
				1 => self::NIH_APPROVER,
				2 => self::DOMAIN_EXPERT,
				3 => self::TRIAGE_COORDINATOR,
				4 => self::VENDOR_ADMIN,
                5 => self::HELP_ADMIN];
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
		$roles = pseudo_atomic_set_ignore_access('elgg_get_entities_from_metadata', $options);
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
}