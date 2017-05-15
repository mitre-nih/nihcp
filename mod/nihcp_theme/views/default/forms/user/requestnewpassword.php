<?php
/**
 * Elgg forgotten password.
 *
 * @package Elgg
 * @subpackage Core
 */
?>

<div class="mtm">
	<?php echo elgg_echo('user:password:text'); ?>
</div>
<div>
	<label for="loginusername"><?php echo elgg_echo('loginusername'); ?></label><br />
	<?php echo elgg_view('input/text', array(
		'id' => 'loginusername',
		'name' => 'username',
		'autofocus' => true,
		));
	?>
</div>
<?php echo elgg_view('input/captcha'); ?>
<div class="elgg-foot">
	<?php echo elgg_view('input/submit', array('value' => elgg_echo('request'))); ?>
</div>
