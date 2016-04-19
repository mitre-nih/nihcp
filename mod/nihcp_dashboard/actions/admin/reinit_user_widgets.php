<?php
pseudo_atomic_set_ignore_access(function() {
	$widgets = elgg_get_entities(array('type' => 'object', 'subtype' => 'widget'));
	foreach ($widgets as $widget) {
		if ($widget->handler != "reinit_user_widgets") {
			$widget->delete();
		}
	}
});

\Nihcp\Manager\WidgetManager::createWidgets();

$users = elgg_get_entities(['type' => 'user', 'limit' => 0]);
foreach($users as $user) {
	$groups = $user->getGroups(array());
	foreach($groups as $group) {
		$group->leave($user);
		$group->join($user);
	}
}