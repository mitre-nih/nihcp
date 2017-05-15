<?php
//include elgg_get_root_path() . '/views/default/forms/login.php';
include elgg_get_plugins_path() . '/nihcp_theme/views/default/nihcp_theme/page/elements/browser_warning.php';
?>

<div>
	<label for="loginusername"><?php echo elgg_echo('loginusername'); ?></label>
	<?php echo elgg_view('input/text', array(
		'id' => 'loginusername',
		'name' => 'username',
		'autofocus' => true,
		));
	?>
</div>
<div>
	<label for="password"><?php echo elgg_echo('password'); ?></label>
	<?php echo elgg_view('input/password', array(
		'id' => 'password', 
		'name' => 'password')); 
	?>
</div>

<?php echo elgg_view('login/extend', $vars); ?>

<div class="elgg-foot">
	<label class="mtm float-alt">
		<input type="checkbox" name="persistent" value="true" />
		<?php echo elgg_echo('user:persistent'); ?>
	</label>
	
	<?php echo elgg_view('input/submit', array('value' => elgg_echo('login'))); ?>
	
	<?php 
	if (isset($vars['returntoreferer'])) {
		echo elgg_view('input/hidden', array('name' => 'returntoreferer', 'value' => 'true'));
	}
	?>

	<?php
	echo elgg_view_menu('login', array(
		'sort_by' => 'priority',
		'class' => 'elgg-menu-general elgg-menu-hz mtm',
	));
	?>
</div>
