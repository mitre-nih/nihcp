<?php

$url = elgg_get_site_url();
$logo_url = elgg_normalize_url("mod/nihcp_theme/graphics/nihcp_logo.png");
echo "<a href=$url><img alt='NIH Logo Image' src=$logo_url></a>";
//Science Icons,Network,Science Icons graphics by <a href="http://www.freepik.com/">Freepik</a> from <a href="http://www.flaticon.com/">Flaticon</a> are licensed under <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0">CC BY 3.0</a>. Made with <a href="http://logomakr.com" title="Logo Maker">Logo Maker</a>
include elgg_get_plugins_path() . '/nihcp_theme/views/default/nihcp_theme/page/elements/browser_warning.php';