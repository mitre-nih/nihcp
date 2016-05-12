<?php
/**
 * NIHCP theme plugin
 *
 * @package nihcp_theme
 */
require_once(dirname(__FILE__) . "/lib/functions.php");

elgg_register_event_handler('init','system','nihcp_theme_init');

function nihcp_theme_init() {
	elgg_register_plugin_hook_handler('registeruser:validate:password', 'all', 'nihcp_password_verify');
    elgg_register_plugin_hook_handler('registeruser:validate:email', 'all', 'nihcp_email_verify');
	elgg_register_plugin_hook_handler('comments', 'object', 'nihcp_hide_page_comments');

	elgg_register_page_handler('', 'forward_to_dashboard');

	elgg_unregister_plugin_hook_handler('prepare', 'menu:site', '_elgg_site_menu_setup');

	elgg_register_event_handler('pagesetup', 'system', 'nihcp_theme_pagesetup', 1001);

	elgg_register_plugin_hook_handler('forward', 'all', 'nihcp_xss_forward_check');

	// write permission plugin hooks
	elgg_unregister_plugin_hook_handler('permissions_check', 'object', 'pages_write_permission_check');
	elgg_unregister_plugin_hook_handler('container_permissions_check', 'object', 'pages_container_permission_check');
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'nihcp_pages_write_permission_check');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'nihcp_pages_container_permission_check');

	elgg_register_plugin_hook_handler('prepare', 'menu:title', 'nihcp_pages_title_menu_setup');

	elgg_register_plugin_hook_handler('prepare', 'menu:site', 'nihcp_theme_site_menu_setup');

	elgg_extend_view('css/elements/layout', 'nihcp_theme/css/elements/layout');

	elgg_extend_view('page/elements/header_logo', 'nihcp_theme/page/elements/header_logo');
}

function forward_to_dashboard($hook, $type, $return = null, $params = null) {
	forward("dashboard");
}

/**
 * Rearrange menu items
 */
function nihcp_theme_pagesetup() {

	if (elgg_is_logged_in()) {

		elgg_unregister_menu_item('topbar', 'profile');
		elgg_unregister_menu_item('topbar', 'friends');
		elgg_unregister_menu_item('topbar', 'messages');

		$name = elgg_get_logged_in_user_entity()->getDisplayName();
		elgg_register_menu_item('topbar', array(
			'name' => 'logged-in-as',
			'text' => "You are logged in as $name",
			'section' => 'alt',
			'href' => 'settings',
		));

		elgg_register_menu_item('site', array(
			'name' => 'help_center',
			'text' => 'Help',
			'href' => 'user_support/help_center',
		));

		$item = elgg_get_menu_item('site', 'pages');
		if($item) {
			$item->setText('Knowledgebase');
		}
	}
}

function nihcp_pages_title_menu_setup($hook, $type, $value, $params) {
	/* @var $item \ElggMenuItem */

	$entity = $params['entity'];

	$should_remove = !elgg_is_admin_logged_in() && elgg_get_context() === "pages";

	foreach ($value as $section => $menu) {
		foreach ($menu as $i => $item) {
			if ($should_remove) {
				unset($value[$section][$i]);
			}
		}
	}

	return $value;
}

/**
 * Set up the site menu
 *
 * Handles default, featured, and custom menu items
 *
 * @access private
 */
function nihcp_theme_site_menu_setup($hook, $type, $return, $params) {

	$allowed_item_names = ['dashboard', 'pages', 'faq', 'help_center'];

	foreach($return['default'] as $i => $item) {
		$j = array_search($item->getName(), $allowed_item_names);
		if($j === False) {
			unset($return['default'][$i]);
		} else {
			$item->setPriority($j+1); //+1 since priority is 1-indexed
		}
	}

	usort($return['default'], 'compare_priority');

	return $return;
}

function compare_priority($a, $b) {
	$pa = $a->getPriority();
	$pb = $b->getPriority();

	if ($pa == $pb) {
		return 0;
	}
	return ($pa < $pb) ? -1 : 1;
}



/*
 * Function to hook into elgg's password checking and enforce password complexity:
 * - At least 8 characters
 * - At least 1 lowercase letter
 * - At least 1 uppercase letter
 * - At least 1 number
 * - At least 1 special character
 *
 * @param
 * @param  $type
 * @param  $return_value
 * @param  $params
 * @return
 */
function nihcp_password_verify($hook, $type, $return_value, $params) {
	$password = $params['password'];
	// Run password through cracklib-check
	exec('echo ' . escapeshellarg($password) . ' | cracklib-check 2>/dev/null', $output, $return_var);
	// Check it ran properly

	if (0 == $return_var) {
		if (preg_match('/^.*\: ([^:]+)$/', $output[0], $matches)) {
			// Check response

			if (strtoupper($matches[1]) != 'OK') {
				$msg = $matches[1];
				throw new \RegistrationException('Password Unacceptable: ' . $msg);
			}
		} else {
			// Badly formatted response from cracklib-check.
			throw new Exception("Didn't understand cracklib-check response.");
		}
	} else {
		// Some sort of execution error
		throw new Exception('Failed to run cracklib-check.');
	}

	if (!preg_match('#.*^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@\#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]).*$#', $password)) {
		throw new \RegistrationException('Your password is not strong enough');
	}

	return true;
}

function nihcp_xss_forward_check($hook, $type, $reason, $params) {
	$forward_url = $params['forward_url'];
	if(startsWith($forward_url, rtrim(elgg_get_site_url(), '/'))) {
		return $forward_url;
	}
	return false;
}

/**
 * Extend permissions checking to extend can-edit for write users.
 *
 * @param string $hook
 * @param string $entity_type
 * @param bool   $returnvalue
 * @param array  $params
 *
 * @return bool
 */
function nihcp_pages_write_permission_check($hook, $entity_type, $returnvalue, $params) {
	/* @var ElggObject $entity */
	$entity = $params['entity'];

	if (!pages_is_page($entity)) {
		return null;
	}

	return elgg_is_admin_logged_in();
}

/**
 * Extend container permissions checking to extend can_write_to_container for write users.
 *
 * @param string $hook
 * @param string $entity_type
 * @param bool   $returnvalue
 * @param array  $params
 *
 * @return bool
 */
function nihcp_pages_container_permission_check($hook, $entity_type, $returnvalue, $params) {
	if (elgg_get_context() != "pages") {
		return null;

	}
	if (elgg_get_page_owner_guid()
		&& can_write_to_container(elgg_get_logged_in_user_guid(), elgg_get_page_owner_guid())) {
		return true;
	}
	if ($page_guid = get_input('page_guid', 0)) {
		$entity = get_entity($page_guid);
	} elseif ($parent_guid = get_input('parent_guid', 0)) {
		$entity = get_entity($parent_guid);
	}
	if (isset($entity) && pages_is_page($entity)) {
		if (elgg_is_admin_logged_in()) {
			return true;
		}
	}
}
/*
 * Only allow email addresses from a whitelist of domains.
 */
function nihcp_email_verify($hook, $type, $return_value, $params) {

    // if it already failed some previous email check, then don't bother checking the whitelist
    if (!$return_value) {
        return false;
    }

    // TODO we should probably get this whitelist from config file instead of hardcoding
    $whitelisted_domains = array(
        "@mitre.org",
        "@nih.gov",
        ".edu"
    );

    $email = $params['email'];

    foreach ($whitelisted_domains as $whitelisted_domain) {
        if (preg_match("/$whitelisted_domain$/", $email)) {
            return true;
        }

    }

    // else no matches against whitelist so fail
    return false;

}

function nihcp_hide_page_comments($hook, $type, $return_value, $params) {
	$entity = $params['entity'];
	if(!$entity) {
		return null;
	}
	if(pages_is_page($entity)) {
		return "<span class=\"empty\"></span>";
	} else {
		return $return_value;
	}
}