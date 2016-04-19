<?php
$PORTAL_NAME = "Commons Credits Portal";
global $CONFIG;
$CONFIG->sitename = $PORTAL_NAME;
elgg_get_site_entity()->name = $PORTAL_NAME;
set_config('sitename', $PORTAL_NAME);