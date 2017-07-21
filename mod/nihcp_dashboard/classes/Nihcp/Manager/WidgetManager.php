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

class WidgetManager {

	public static function getConfig() {
		$config = [
			//Investigator
			0 => ['nihcp_catalog', 'nihcp_commons_credit_request', 'nihcp_credit_awards', 'blog'],
			//NIH Approver
			1 => ['nihcp_catalog', 'nihcp_credit_request_review', 'nihcp_portal_usage', 'nihcp_audit_log', 'nihcp_credit_allocation', 'blog'],
			//Domain Expert
			2 => ['nihcp_catalog', 'nihcp_credit_request_review', 'blog'],
			//Triage Coordinator
			3 => ['nihcp_catalog', 'nihcp_credit_request_review', 'nihcp_portal_usage', 'nihcp_audit_log', 'nihcp_credit_allocation', 'nihcp_export_email_addr', 'nihcp_report_export', 'blog'],
			//Vendor Administrator
			4 => ['nihcp_catalog', 'manage_vendors', 'blog'],
			//Helpdesk Administrator
			5 => ['support_staff', 'blog'],
			//Credit Administrator
			6 => ['nihcp_credit_allocation', 'blog']
		];
		return $config;
	}

	public static function createWidgets() {
		$widget_config = WidgetManager::getConfig();
		$site_id = elgg_get_site_entity()->guid;
		foreach($widget_config as $role_id => $widget_handlers) {
			foreach($widget_handlers as $idx => $handler) {
				if(!elgg_is_widget_type($handler)) {
					error_log("Unable to find widget type `$handler`");
					continue;
				}
				$role_context = RoleManager::getRoleByRoleId($role_id)->guid;
				$options = array(
					'type' => 'object',
					'subtype' => 'widget',
					'owner_guid' => $site_id,
					'limit' => 1,
					'private_setting_name_value_pairs' => [
						[
							'name' => 'handler',
							'value' => $handler,
						],
						[
							'name' => 'context',
							'value' => $role_context
						]
					]
				);
				$result = elgg_get_entities_from_private_settings($options);
				if($result) {
					error_log("Widget `$handler` already exists");
					continue;
				}
				if($new_widget_guid = elgg_create_widget($site_id, $handler, $role_context)) {
					$new_widget = get_entity($new_widget_guid);
					$col_idx = ($idx % 3) + 1;
					$new_widget->move($col_idx, 0);
					error_log("Created widget $handler");
				} else {
					error_log("Unable to create widget $handler");
				}
			}
		}
	}

	public static function createWidgetsForUserInGroup($entity, $group) {
		$default_widget_info = elgg_get_config('default_widget_info');

		if (!$default_widget_info || !$entity) {
			return;
		}

		$params = array('user' => $entity, 'group' => $group);

		$type = $group->getType();
		$subtype = $group->getSubtype();

		// event is already guaranteed by the hook registration.
		// need to check subtype and type.
		foreach ($default_widget_info as $info) {
			if ($info['entity_type'] == $type) {
				if ($info['entity_subtype'] == ELGG_ENTITIES_ANY_VALUE || $info['entity_subtype'] == $subtype) {
					if($info['widget_context'] == $group->guid) {
						elgg_push_context('create_default_widgets');

						pseudo_atomic_set_ignore_access(function($_params) {
							$_entity = $_params['user'];
							$_group = $_params['group'];

							// pull in by widget context with widget owners as the site
							// not using elgg_get_widgets() because it sorts by columns and we don't care right now.
							$options = array(
								'type' => 'object',
								'subtype' => 'widget',
								'owner_guid' => elgg_get_site_entity()->guid,
								'private_setting_name' => 'context',
								'private_setting_value' => $_group->guid, //$info['widget_context'],
								'limit' => 0
							);

							/* @var \ElggWidget[] $widgets */
							$widgets = elgg_get_entities_from_private_settings($options);

							$user_options = array(
								'type' => 'object',
								'subtype' => 'widget',
								'owner_guid' => $_entity->guid,
								'private_setting_name' => 'context',
								'private_setting_value' => 'dashboard',
								'limit' => 0
							);

							$user_widgets = elgg_get_entities_from_private_settings($user_options);

							$widget_cols = elgg_get_widgets($_entity->guid, 'dashboard');
							$col_szs = [];
							foreach ($widget_cols as $widget_col) {
								array_push($col_szs, count($widget_col));
							}

							$idx = $col_szs ? array_search(min($col_szs), $col_szs) : 0;

							foreach ($widgets as $widget) {
								foreach ($user_widgets as $user_widget) {
									if ($user_widget->getTitle() == $widget->getTitle()) {
										error_log("{$_entity->name} already has widget {$widget->getTitle()}");
										continue 2;
									}
								}
								// change the container and owner
								$new_widget = clone $widget;
								$new_widget->container_guid = $_entity->guid;
								$new_widget->owner_guid = $_entity->guid;

								// pull in settings
								$settings = get_all_private_settings($widget->guid);

								foreach ($settings as $name => $value) {
									$new_widget->$name = $value;
								}

								$new_widget->context = "dashboard";

								if ($new_widget->save()) {
									error_log("Created widget {$widget->getTitle()} for user {$_entity->name}");
								} else {
									error_log("Unable to create {$widget->getTitle()} for user {$_entity->name}");
								}

								$col_idx = ($idx % 3) + 1;
								$new_widget->move($col_idx, 0);
								$idx++;
							}

						}, $params);
						elgg_pop_context();
					}
				}
			}
		}
	}
}