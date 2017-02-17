<?php
/**
 * Overriding Elgg core image_block view in order to only show image and menu to admins.
 */

$body = elgg_extract('body', $vars, '');

if (elgg_is_admin_logged_in()) {
	$image = elgg_extract('image', $vars, '');
	$alt_image = elgg_extract('image_alt', $vars, '');
}

$class = 'elgg-image-block';
$additional_class = elgg_extract('class', $vars, '');
if ($additional_class) {
	$class = "$class $additional_class";
}

$id = '';
if (isset($vars['id'])) {
	$id = "id=\"{$vars['id']}\"";
}


$body = "<div class=\"elgg-body\">$body</div>";

if ($image) {
	$image = "<div class=\"elgg-image\">$image</div>";
}

if ($alt_image) {
	$alt_image = "<div class=\"elgg-image-alt\">$alt_image</div>";
}

echo <<<HTML
<div class="$class clearfix" $id>
	$image$alt_image$body
</div>
HTML;
