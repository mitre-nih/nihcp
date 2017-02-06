<?php

use \Nihcp\Manager\WidgetManager;

pseudo_atomic_set_ignore_access(function() {
	$widgets = elgg_get_entities(array('type' => 'object', 'subtype' => 'widget', 'limit' => 0));
	foreach ($widgets as $widget) {
		if ($widget->getContext() !== 'admin') {
			$widget->delete();
		}
	}
});

WidgetManager::createWidgets();

$users = elgg_get_entities(['type' => 'user', 'limit' => 0]);
foreach($users as $user) {
	$groups = $user->getGroups(array());
	foreach($groups as $group) {
		WidgetManager::createWidgetsForUserInGroup($user , $group);
	}
}