<?php
global $CATALOG_TYPES;
foreach($CATALOG_TYPES as $type) {

	$type_name = elgg_echo("item:object:$type");
	$url = elgg_get_site_url() . "catalog/$type";
	$text = elgg_echo("nihcp_catalog:widget:text", [$type_name]);

	echo "<a href=\"$url\">$text</a><br>";
}