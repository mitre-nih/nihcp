<?php
$current = elgg_get_plugin_setting('portal_admin_email', 'nihcp_theme');
?>

<p>
	<?php echo elgg_echo('nihcp_theme:settings:portal_admin_email'); ?>

	<input name="params[portal_admin_email]" type="email" value=<?php echo $current ?>>
</p>