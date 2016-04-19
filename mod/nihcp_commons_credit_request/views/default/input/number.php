<?php

if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-text {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-text";
}

$defaults = array(
	'value' => '',
	'disabled' => false,
);

$vars = array_merge($defaults, $vars);

?>
<input type="number" <?php echo elgg_format_attributes($vars); ?> />
