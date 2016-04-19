<?php
if(!elgg_is_admin_logged_in()) {
	$vars['show_add_widgets'] = false;
}
include elgg_get_root_path() . 'views/default/page/layouts/widgets.php';