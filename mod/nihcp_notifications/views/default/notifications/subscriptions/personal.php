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
/**
 * @uses $vars['user'] ElggUser
 */

/* @var ElggUser $user */
$user = $vars['user'];

$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();

?>
<div class="notification_personal">
<div class="elgg-module elgg-module-info">
	<div class="elgg-head">
		<h3>
			<?php echo elgg_echo('notifications:subscriptions:personal:title'); ?>
		</h3>
	</div>
</div>
<table id="notificationstable" cellspacing="0" cellpadding="4" width="100%">
	<tr>
		<td>&nbsp;</td>
<?php
$i = 0; 
foreach($NOTIFICATION_HANDLERS as $method => $foo) {
	if ($i > 0) {
		echo "<td class='spacercolumn'>&nbsp;</td>";
	}
?>
		<td class="<?php echo $method; ?>togglefield">
			<?php echo elgg_echo('notification:method:'.$method); ?>
		</td>
<?php
	$i++;
}
?>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="namefield">
			<p>
				<?php echo elgg_echo('notifications:subscriptions:personal:description') ?>
			</p>
		</td>

<?php

$fields = '';
$i = 0;
foreach($NOTIFICATION_HANDLERS as $method => $foo) {
	if ($notification_settings = get_user_notification_settings($user->guid)) {
		if (isset($notification_settings->$method) && $notification_settings->$method) {
			$personalchecked[$method] = 'checked="checked"';
		} else {
			$personalchecked[$method] = '';
		}
	}
	if ($i > 0) {
		$fields .= "<td class='spacercolumn'>&nbsp;</td>";
	}
	$fields .= <<< END
		<td class="{$method}togglefield">
		<a  border="0" id="{$method}personal" class="{$method}toggleOff" onclick="adjust{$method}_alt('{$method}personal');">
		<input aria-label="{$method}-personal" type="checkbox" name="{$method}personal" id="{$method}checkbox" onclick="adjust{$method}('{$method}personal');" value="1" {$personalchecked[$method]} /></a></td>
END;
	$i++;
}
echo $fields;

?>

		<td>&nbsp;</td>
	</tr>
</table>
</div>