<?php

use \Nihcp\Manager\RoleManager;

// The onus is on the developer to use this function responsibly since it provides minimal protection on its own
function pseudo_atomic_set_ignore_access(callable $callback, $parameter = null, $override_error_handler = false, $ignore = true) {
	if($override_error_handler) {
		set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
			// error was suppressed with the @-operator
			if (0 === error_reporting()) {
				return false;
			}

			throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
		});
	}

	$ia = elgg_set_ignore_access($ignore);
	try {
		$ret = call_user_func($callback, $parameter);
	} catch (Exception $e) {
		//error_log($e);
		$ret = false;
	} finally {
		elgg_set_ignore_access($ia);
		if($override_error_handler) {
			restore_error_handler();
		}
	}

	return $ret;
}

/**
 * Added gatekeeper function for privileged groups
 *
 * @param bool $forward   forward the user to REFERER
 * @param int  $user_guid the user to check (default: current user)
 *
 * @return bool
 */
function nihcp_role_gatekeeper($role_names, $forward = true, $user_guid = 0) {
	$result = false;

	if(!is_array($role_names)) {
		$role_names = [$role_names];
	}

	$user_guid = sanitise_int($user_guid, false);
	if (empty($user_guid)) {
		$user_guid = elgg_get_logged_in_user_guid();
	}

	if (!empty($user_guid)) {
			$member_cache = array();

			foreach($role_names as $role_name) {

				$role_group = RoleManager::getRoleByName($role_name);
				if (!$role_group) {
					error_log(elgg_echo("nihcp_groups:role:notfound", $role_name));
					continue;
				}

				$users = $role_group ? $role_group->getMembers() : null;

				if (!empty($users)) {
					foreach ($users as $user) {
						$member_cache[] = $user->getGUID();
					}
				}
			}

		if (in_array($user_guid, $member_cache)) {
			$result = true;
		} elseif (($user = get_user($user_guid)) && $user->isAdmin()) {
			$result = true;
		}
	}

	if (!$result && $forward) {
		register_error(elgg_echo("nihcp_groups:role:gatekeeper"));
		forward(REFERER);
	}

	return $result;
}

function nihcp_investigator_gatekeeper($forward = true, $user_guid = 0) {
	return nihcp_role_gatekeeper(RoleManager::INVESTIGATOR, $forward, $user_guid);
}

function nihcp_nih_approver_gatekeeper($forward = true, $user_guid = 0) {
	return nihcp_role_gatekeeper(RoleManager::NIH_APPROVER, $forward, $user_guid);
}

function nihcp_domain_expert_gatekeeper($forward = true, $user_guid = 0) {
	return nihcp_role_gatekeeper(RoleManager::DOMAIN_EXPERT, $forward, $user_guid);
}

function nihcp_triage_coordinator_gatekeeper($forward = true, $user_guid = 0) {
	return nihcp_role_gatekeeper(RoleManager::TRIAGE_COORDINATOR, $forward, $user_guid);
}

function nihcp_vendor_admin_gatekeeper($forward = true, $user_guid = 0) {
	return nihcp_role_gatekeeper(RoleManager::VENDOR_ADMIN, $forward, $user_guid);
}

function nihcp_help_admin_gatekeeper($forward = true, $user_guid = 0) {
	return nihcp_role_gatekeeper(RoleManager::HELP_ADMIN, $forward, $user_guid);
}