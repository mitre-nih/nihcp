<?php
/**
 * Display an icon from the elgg icons sprite.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['class'] Class of elgg-icon
 */

//we're overriding this view to add alt text for 508 compliance.
$class = (array) elgg_extract("class", $vars);
$class[] = "elgg-icon";

$vars["class"] = $class;

$attributes = elgg_format_attributes($vars);
$alt = substr($class[0],10);
if($alt){
    $alt = "Icon of " . $alt;
}
echo "<span alt='$alt' $attributes></span>";
