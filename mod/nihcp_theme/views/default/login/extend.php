<?php
$site_url = elgg_get_site_url();
$urls = [];
foreach(['rob', 'privacy', 'terms'] as $page) {
	$urls[] = $site_url.$page;
}
echo "<p id=\"rob-acknowledgement\">".elgg_echo('nihcp_theme:rob_acknowledgement', $urls)."</p>";