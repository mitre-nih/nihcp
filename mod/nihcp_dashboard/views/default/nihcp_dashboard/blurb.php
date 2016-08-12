<div class="elgg-col elgg-col-2of3">
<?php

	$portal_admin_email = elgg_get_plugin_setting('portal_admin_email', 'nihcp_theme');
	echo elgg_view('output/longtext', array(
		'id' => 'dashboard-info',
		'class' => 'elgg-inner pam mhs mtn',
		'value' => elgg_echo("dashboard:nowidgets", [$portal_admin_email]),
	));
?>
</div>