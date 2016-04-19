<?php
elgg_register_menu_item('user_widget_control_panel', array(
	'name' => 'reinit_user_widgets',
	'text' => elgg_echo("nihcp_dashboard:widgets:reinit_user_widgets:title"),
	'href' => 'action/nihcp_dashboard/reinit_user_widgets',
	'is_action' => true,
	'link_class' => 'elgg-button elgg-button-action',
	'confirm' => elgg_echo('nihcp_dashboard:widgets:reinit_user_widgets:confirm'),
));

echo elgg_view_menu('user_widget_control_panel', array(
	'class' => 'elgg-menu-hz',
	'item_class' => 'mrm',
));
